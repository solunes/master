<?php 

namespace Solunes\Master\App\Helpers;

class FuncNode {

    public static function node_field_creation($table_name, $node, $name, $translation, $count, $languages) {
        $count++;
        $model = $node->model;
        $type = 'string';
        $value = NULL;
        $trans_name = $name;
        $display_list = 'show';
        $display_item = 'show';
        $multiple = 0;
        $required = 0;
        $preset = 0;
        $relation = false;
        $new_row = false;
        $permission = NULL;
        $col_type = \DB::select(\DB::raw("SHOW FIELDS FROM ".$table_name." where Field  = '".$name."'"))[0]->Type;
        $extras = [];
        $field_options = [];
        if(strpos($name, '_id') !== false) {
            $type = 'select';
            $relation = true;
            $trans_name = str_replace('_id', '', $name);
            $value = $trans_name;
            if($name=='parent_id') {
              $preset = 1;
              if($node->parent){
                $value = $node->parent->name;
              } else {
                $value = $node->name;
              }
            }
        } else if($name=='color'||strpos($name, 'color') !== false){
          $type = 'color';
        } else if($name=='image'||$name=='file'||$name=='logo'||$name=='isotype'||strpos($name, 'image') !== false||strpos($name, 'file') !== false){
          if($name=='file'||strpos($name, 'file') !== false){
            $type = 'file';
          } else {
            $type = 'image';
          }
          if($col_type=='text'){
            $multiple = 1;
          }
          array_push($extras, ['type'=>'folder','value'=>$node->name.'-'.$name]);
        } else if(strpos($name, 'checkbox') !== false){
            $type = 'checkbox';
            array_push($extras, ['type'=>'cols','value'=>'12']);
        } else if($name=='map'){
            $type = 'map';
            array_push($extras, ['type'=>'cols','value'=>'12']);
        } else if($col_type=='text'){
            $type = 'text';
            $new_row = true;
            array_push($extras, ['type'=>'class','value'=>'simple-textarea']);
            array_push($extras, ['type'=>'cols','value'=>'12']);
            array_push($extras, ['type'=>'rows','value'=>'3']);
        } else if($name=='barcode'){
          $type = 'barcode';
        } else if($name=='password'){
            $type = 'password';
        } else if($col_type=='tinyint(1)'||substr_count($col_type, 'enum')>0){
            $type = 'select';
            if($name=='active'){
              array_push($extras, ['type'=>'default_value','value'=>'1']);
            }
            if(substr_count($col_type, 'enum')>0){
              $value_array = substr($col_type, 6, -2);
            } else if($col_type=='tinyint(1)') {
              $value_array = "0','1";
            }
            foreach(explode("','",$value_array) as $subvalue){
              array_push($field_options, ['name'=>$subvalue]);
            }
        } else if($col_type=='integer'||strpos($col_type, 'int(') === true){
            $type = 'integer';
        } else if($col_type=='decimal'||strpos($col_type, 'decimal') === true){
            $type = 'decimal';
        } else if($col_type=='timestamp'||$col_type=='date'||$col_type=='time'){
            $type = 'date';
            if($node->type=='subchild'){
              array_push($extras, ['type'=>'class','value'=>$col_type.'-control']);
            } else {
              array_push($extras, ['type'=>'class','value'=>$col_type.'picker']);
            }
            if($name=='created_at'){
              \Solunes\Master\App\Filter::create(['node_id'=>$node->id, 'type'=>'field', 'subtype'=>'date', 'parameter'=>'created_at']);
            } else if($name=='deleted_at'){
              $node->soft_delete = 1;
              $node->save();
            }
        } else if(strpos($name, 'array') !== false){
            $type = 'array';
            array_push($extras, ['type'=>'rows','value'=>'2']);
        }
        $hidden_names = ['id', 'site_id', 'slug', 'password', 'created_at', 'updated_at', 'deleted_at'];
        if(in_array($name, $hidden_names)){
            $display_list = 'excel';
            $display_item = 'none';
            if($name=='password'){
              $display_item = 'admin';
            }
        } else if($count>5){
            $display_list = 'excel';
        }
        if($node->multilevel&&($name=='parent_id'||$name=='level')){
          if($name=='level'){
            $type = 'string';
            $relation = false;
          }
          $preset = true;
        }
        if($node->type!='field'){
            $rules = \FuncNode::node_check_rules($node, 'create');
            if(array_key_exists($name, $rules)&&strpos($rules[$name], 'required') !== false){
                $required = 1;
            }
        }
        $field = new \Solunes\Master\App\Field;
        $field->parent_id = $node->id;
        $field->name = $name;
        $field->trans_name = $trans_name;
        $field->type = $type;
        $field->order = $count;
        $field->display_list = $display_list;
        $field->display_item = $display_item;
        $field->translation = $translation;
        $field->relation = $relation;
        $field->multiple = $multiple;
        $field->new_row = $new_row;
        $field->value = $value;
        $field->preset = $preset;
        $field->required = $required;
        $field->save();
        \FuncNode::field_generate_extras($field, $extras);
        \FuncNode::field_generate_options($node, $field, $field_options, $languages);
        return $count;
    }

    public static function field_generate_extras($field, $extras) {
        if(count($extras)>0){
          foreach($extras as $extra){
            $subfield = new \Solunes\Master\App\FieldExtra;
            $subfield->parent_id = $field->id;
            $subfield->type = $extra['type'];
            $subfield->value = $extra['value'];
            $subfield->save();
          }
        }
    }

    public static function field_generate_custom_options($node_name, $array, $languages) {
      if($node = \Solunes\Master\App\Node::where('name', $node_name)->with('fields')->first()){
        if(count($array)>0){
          foreach($array as $field_name => $options){
            if($field = $node->fields()->where('name', $field_name)->first()){
              $field_options = [];
              foreach(range(1, $options) as $subvalue){
                array_push($field_options, ['name'=>$field_name.'_'.$subvalue]);
              }
              \FuncNode::field_generate_options($node, $field, $field_options, $languages);
            }
          }
        }
      }
    }

    public static function field_generate_options($node, $field, $options, $languages) {
        if(count($options)>0){
          foreach($options as $option){
            $subfield = new \Solunes\Master\App\FieldOption;
            $subfield->parent_id = $field->id;
            $subfield->name = $option['name'];
            $subfield = \DataManager::translateLocalization($languages, $subfield, 'label', $node->lang_folder.'::admin.'.$option['name']);
            $subfield->save();
          }
        }
    }

    public static function get_node($node_name) {
      $node = \Solunes\Master\App\Node::where('name', $node_name)->first();
      return $node;
    }

    public static function node_check_model($node) {
        $model = new $node->model;
        if($node->dynamic&&$node->parent_id==NULL){
            $model = $model->fromTable($node->table_name);
        }
        return $model;
    }

    public static function node_check_rules($node, $action) {
        $rules = [];
        $model = FuncNode::node_check_model($node);
        if($node->dynamic){
          $rules = $model->rules($node->table_name);
          if($rules==NULL){
            $rules = [];
          }
        } else {
          if($action=='create'){
            $rules = $model::$rules_create;
          } else if($action=='edit') {
            $rules = $model::$rules_edit;
          }
        }
        return $rules;
    }

    public static function node_menu_creation($node, $languages) {
        $menu_array = \Solunes\Master\App\Menu::where('menu_type', 'admin')->where('level', 1)->lists('id');
        if($node->folder){
            if($menu_parent = \Solunes\Master\App\MenuTranslation::whereIn('menu_id', $menu_array)->where('name', trans($node->lang_folder.'::admin.'.$node->folder))->first()){
              $menu_parent = $menu_parent->menu;
            } else {
              $menu_parent = \Solunes\Master\App\Menu::create(['type'=>'blank', 'menu_type'=>'admin', 'permission'=>$node->folder, 'icon'=>'th-list']);
              $menu_parent = \DataManager::translateLocalization($languages, $menu_parent, 'name', $node->lang_folder.'::admin.'.$node->folder);
              $menu_parent->save();
            }
            $menu = \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>$node->permission, 'parent_id'=>$menu_parent->id, 'level'=>2, 'icon'=>'th-list']);
            foreach($languages as $language){
              \App::setLocale($language->code);
              $menu->translateOrNew($language->code)->name = $node->plural;
              $menu->translateOrNew($language->code)->link = 'admin/model-list/'.$node->name;
            }
            \App::setLocale(config('solunes.main_lang'));
            $menu->save();
        }
    }

    public static function node_menu_deletion($array) {
      foreach($array as $name){
        $url = 'admin/model-list/'.$name;
        $item = \Solunes\Master\App\Menu::where('type','normal')->where('menu_type','admin')->whereTranslation('link', $url)->first();
        $item->active = 0;
        $item->save();
      }
      return true;
    }

    public static function custom_menu_creation($name, $url, $menu_parent, $icon = 'th-list') {
      $languages = \Solunes\Master\App\Language::get();
      $menu_array = ['menu_type'=>'admin', 'permission'=>$menu_parent->permission, 'parent_id'=>$menu_parent->id, 'level'=>$menu_parent->level + 1, 'icon'=>$icon];
      $menu = \Solunes\Master\App\Menu::create($menu_array);
      foreach($languages as $language){
        \App::setLocale($language->code);
        $menu->translateOrNew($language->code)->name = $name;
        $menu->translateOrNew($language->code)->link = $url;
      }
      \App::setLocale(config('solunes.main_lang'));
      $menu->save();
      return true;
    }

    public static function generate_translations($menu) {
      $languages = \Solunes\Master\App\Language::get();
      foreach($languages as $language){
        \App::setLocale($language->code);
        $menu->translateOrNew($language->code)->name = $menu->name;
        $menu->translateOrNew($language->code)->link = $menu->link;
      }
      \App::setLocale(config('solunes.main_lang'));
      $menu->save();
      return $menu;
    }

    public static function load_nodes_excel($path, $return = '') {
        $languages = \Solunes\Master\App\Language::get();
        \Excel::load($path, function($reader) use($return, $languages) {
          foreach($reader->get() as $sheet){
            $sheet_name = $sheet->getTitle();
            $sheet->each(function($row) use ($sheet_name, $return, $languages) {
              $node = \Solunes\Master\App\Node::where('name', $row->node)->first();
              if($sheet_name=='create-fields'){
                if($node){
                  $field = new \Solunes\Master\App\Field;
                  $field->parent_id = $node->id;
                  $field->name = $row->name;
                  $field->trans_name = $row->trans_name;
                  $field = \DataManager::translateLocalization($languages, $field, 'label', $node->lang_folder.'::fields.'.$row->trans_name);
                  $field->type = $row->type;
                  $field->display_list = $row->display_list;
                  $field->display_item = $row->display_item;
                  $field->relation = $row->relation;
                  $field->multiple = $row->multiple;
                  $field->translation = $row->translation;
                  $field->required = $row->required;
                  $field->order = $row->order;
                  $field->new_row = $row->new_row;
                  $field->preset = $row->preset;
                  $field->message = $row->message;
                  $field->value = $row->value;
                  $field->save();
                } else {
                  $return .= 'ALERTA: No se encontró el nodo '.$row->node.'.\n';
                }
              } else {
                if($node&&$field = $node->fields()->where('name', $row->field)->first()){
                  if($sheet_name=='edit-fields'){
                      $column = $row->column;
                      $field->$column = $row->new_value;
                      $field->save();
                  } else if($sheet_name=='extras'){
                    if($extra = $field->field_extras()->where('type', $row->type)->first()){
                      $extra->value = $row->new_value;
                    } else {
                      $extra = new \Solunes\Master\App\FieldExtra;
                      $extra->parent_id = $field->id;
                      $extra->type = $row->type;
                      $extra->value = $row->new_value;
                    }
                    $extra->save();
                  } else if($sheet_name=='conditionals'){
                      $conditional = new \Solunes\Master\App\FieldConditional;
                      $conditional->parent_id = $field->id;
                      $conditional->trigger_field = $row->trigger_field;
                      $conditional->trigger_show = $row->trigger_show;
                      $conditional->trigger_value = $row->trigger_value;
                      $conditional->save();
                  }
                } else {
                  $return .= 'ALERTA: No se encontró el campo '.$row->field.' o nodo '.$row->node.'.\n';
                }
              }
            });
          }
        });
        return $return;
    }

    public static function put_data_field($item, $field, $input, $lang_code = NULL) {
      if(!$lang_code){
        $lang_code = \App::getLocale();
      }
      $field_name = $field->name;
      if(is_array($input)){
        $final_input = json_encode($input);
        if($field->type=='image'||$field->type=='file') {
          \Solunes\Master\App\TempFile::where('type', $field->type)->whereIn('file', $input)->delete();
        } 
      } else {
        if($input&&($field->type=='image'||$field->type=='file')) {
          \Solunes\Master\App\TempFile::where('type', $field->type)->where('file', $input)->delete();
        }
        $final_input = $input;
      }
      if(is_string($final_input) && trim($final_input) === ''){
        $final_input = NULL;
      }
      $item = \FuncNode::put_in_database($item, $field, $field_name, $final_input, $lang_code);
      return $item;
    }

    public static function put_in_database($item, $field, $field_name, $final_input, $lang_code = NULL) {
      if(!$lang_code){
        $lang_code = \App::getLocale();
      }
      if($field->translation==1){
        $item->translateOrNew($lang_code)->$field_name = $final_input;
      } else {
        $item->$field_name = $final_input;
      }
      return $item;
    }
        
    public static function make_activity($node_id, $item_id, $user_id, $username, $action, $message) {
        $activity = new \Solunes\Master\App\Activity;
        $activity->node_id = $node_id;
        $activity->item_id = $item_id;
        $activity->user_id = $user_id;
        $activity->username = $username;
        $activity->action = $action;
        $activity->message = $message;
        $activity->save();
        return true;
    }

    public static function make_dashboard_notitification($name, $user_ids, $url, $message) {
      // Añadir array si es que se manda solo un ID
      if(!is_array($user_ids)){
        $user_ids = [$user_ids];
      }
      foreach(\App\User::whereIn('id', $user_ids)->get() as $user){
        $notification = new \Solunes\Master\App\Notification;
        $notification->name = $name;
        $notification->user_id = $user->id;
        $notification->url = $url;
        $notification->save();
        $subnotification = new \Solunes\Master\App\NotificationMessage;
        $subnotification->parent_id = $notification->id;
        $subnotification->type = 'dashboard';
        $subnotification->is_sent = true;
        $subnotification->message = $message;
        $subnotification->save();
      }
      return true;
    }

    public static function make_notitification($name, $user_ids, $url, $message, $email_parameters = []) {
      // Añadir array si es que se manda solo un ID
      if(!is_array($user_ids)){
        $user_ids = [$user_ids];
      }
      $email_name = 'notifications.'.$name;
      foreach(\App\User::whereIn('id', $user_ids)->get() as $user){
        $notifications_array = ['dashboard'];
        if($user->email&&$user->notifications_email){
          // ENVIAR EMAIL
          array_push($notifications_array, 'email');
        }
        if(config('solunes.send_notification_sms')&&$user->cellphone&&$user->notifications_sms){
          // ENVIAR SMS
          array_push($notifications_array, 'sms');
        }
        if(config('solunes.send_notification_whatsapp')&&$user->cellphone&&$user->notifications_whatsapp){
          // ENVIAR SMS
          array_push($notifications_array, 'whatsapp');
        }
        if(config('solunes.send_notification_app')){
          // ENVIAR APP PUSH
          array_push($notifications_array, 'app');
        }
        $notification = new \Solunes\Master\App\Notification;
        $notification->name = $name;
        $notification->user_id = $user->id;
        $notification->url = $url;
        $notification->save();
        foreach($notifications_array as $type){
          $final_message = $message;
          $sent = false;
          if($type=='dashboard'){
            // Se genera bien
            $sent = true;
          } else if($type=='email'){
            // ENVIAR EMAIL
            $vars = [];
            $vars_if = [];
            $vars_foreach = [];
            if(isset($email_parameters['name'])){
              $email_name = $email_parameters['name'];
            }
            if(isset($email_parameters['vars'])){
              $vars = $email_parameters['vars'];
            }
            if(isset($email_parameters['vars_if'])){
              $vars_if = $email_parameters['vars_if'];
            }
            if(isset($email_parameters['vars_foreach'])){
              $vars_foreach = $email_parameters['vars_foreach'];
            }
            $sent = \FuncNode::make_email($email_name, [$user->email], $vars, $vars_if, $vars_foreach);
          } else if($type=='sms'){
            // ENVIAR SMS
            $result = \Notification::sendSms($user->cellphone, $final_message);
            if($result){
              $sent = true;
            }
          } else if($type=='whatsapp'){
            // ENVIAR Whatsapp
            $result = \Notification::sendWhatsappTwilo($user->cellphone, $final_message);
            if($response=='queued'||$result=='sent'){
              $sent = true;
            }
          } else if($type=='app'){
            // ENVIAR PUSH NOTIFICATION A APP
            $sent = \Notification::sendNotificationToUser($user->id, $final_message);
          }
          $subnotification = new \Solunes\Master\App\NotificationMessage;
          $subnotification->parent_id = $notification->id;
          $subnotification->type = $type;
          $subnotification->is_sent = $sent;
          $subnotification->message = $message;
          $subnotification->save();
        }
      }
      return true;
    }

    public static function check_var($name) {
        if($item = \Solunes\Master\App\Variable::where('name', $name)->first()){
            return $item->value;
        } else {
            return NULL;
        }
    }

    public static function update_indicator_values($indicator) {
        $node = $indicator->node;
        $node_model = \FuncNode::node_check_model($node);
        $first_date = $node_model->whereNotNull('created_at')->where('created_at', '!=', '0000-00-00 00:00:00')->orderBy('created_at', 'ASC')->first()->created_at->format('Y-m-d');
        $first_date = new \DateTime($first_date);
        $last_date = new \DateTime( date('Y-m-d') );
        $last_date->modify('+1 day');
        $period = new \DatePeriod($first_date, new \DateInterval('P1D'), $last_date);
        $array['filter_category_id'] = $indicator->id;
        foreach ($period as $date) {
            $node_model = \FuncNode::node_check_model($node);
            if($indicator->data=='count_total'){
              $items = $node_model::where('created_at','<=', $date->format("Y-m-d 23:59:59"));
            } else if($indicator->data=='count') {
              $items = $node_model::where('created_at','>=', $date->format("Y-m-d 00:00:00"))->where('created_at','<=', $date->format("Y-m-d 23:59:59"));
            } else {
              $items = $node_model::where('created_at','<=', $date->format("Y-m-d 23:59:59"));
            }
            $array = \AdminList::filter_node($array, $node, $node_model, $items, 'indicator');
            $items = $array['items'];
            $indicator_value = $items->count();
            if($today_indicator = $indicator->indicator_values()->where('date', $date->format("Y-m-d"))->first()) {
            } else {
                $today_indicator = new \Solunes\Master\App\IndicatorValue;
                $today_indicator->parent_id = $indicator->id;
                $today_indicator->date = $date->format("Y-m-d");
            }
            $today_indicator->value = $indicator_value;
            $today_indicator->save();
        }
        return true;
    }

    public static function make_email($email_name, $to_array, $vars = [], $vars_if = [], $vars_foreach = []) {
      // $vars = ['@search@'=>'Reemplazar con esto']
      $to_fixed_array = [];
      foreach($to_array as $email_account){
        if(filter_var($email_account, FILTER_VALIDATE_EMAIL)){
          $to_fixed_array[] = $email_account;
        }
      }
      $email = \Solunes\Master\App\Email::where('name', $email_name)->first();
      if($email&&count($to_fixed_array)>0){
        $msg = $email->content;
        if(count($vars_if)>0){
          foreach($vars_if as $var_name => $var_value){           
            $beginning = '@'.$var_name.'@';
            $end = '@end'.$var_name.'@';
            if($var_value===true){
              $msg = str_replace($beginning, '', $msg);
              $msg = str_replace($end, '', $msg);
            } else {
              $beginningPos = strpos($msg, $beginning);
              $endPos = strpos($msg, $end);
              $textToDelete = substr($msg, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
              $msg = str_replace($textToDelete, '', $msg);
            }
          }
        }
        if(count($vars_foreach)>0){
          
        }
        if(count($vars)>0){
          $msg = str_replace(array_keys($vars), array_values($vars), html_entity_decode($msg));
        }
        \Mail::send('master::emails.default', ['msg'=>$msg, 'email'=>$email], function ($m) use($email, $to_fixed_array, $msg) {
            if($email->reply_to){
              $reply_to = $email->reply_to;
            } else {
              $find = ['http://','https://','www.','/'];
              $replace = [''];
              $domain = str_replace($find, $replace, \Solunes\Master\App\Site::find(1)->domain);
              $reply_to = 'no-reply@'.$domain;
            }
            if($email->reply_to_name){
              $reply_to_name = $email->reply_to_name;
            } else {
              $reply_to_name = \Solunes\Master\App\Site::find(1)->name;
            }
            $m->to($to_fixed_array)->replyTo($reply_to, $reply_to_name)->subject(config('solunes.app_name').' | '.$email->title);
        });
        return true;
      } else {
        return false;
      }
    }

    public static function get_items_array($node, $node_val = 0) {
        $node_name = $node->name;
        if($node->folder=='form'){
            $model = $node->model;
            if(request()->has($node->table_name)){
                $action = 'edit';
                $id = request()->input($node->table_name);
            } else {
                $action = 'create';
                $id = NULL;
            }
            $subarray = \AdminItem::get_request_variables('process', $node, $model, $node->name, $action, $id, []);
        } else {
            $items = \FuncNode::node_check_model($node);
            if(!is_numeric($node_val)){
                $items = $items->where('code', $node_val);
            }
            $subarray['node'] = $node;
            $subarray['items'] = $items;
            if(config('solunes.custom_get_items')){
              $subarray = \CustomFunc::custom_get_items($subarray, $node, $node_val);
            }
            $subarray = \AdminList::filter_node($subarray, $node, $node->model, $subarray['items'], 'site');
            $subarray['items'] = $subarray['items']->get();
        }
        return $subarray;
    }

    public static function slugify($text) {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
      // trim
      $text = trim($text, '-');
      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);
      // lowercase
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      return $text;
    }

    public static function generateRawCode($digits) {
        $digits = $digits -1;
        $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $pass = ''; 

        while ($i <= $digits) { 
            $num = rand(1,31); 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 
        return $pass;
    }

    public static function putUniqueValue($key, $value) {
      $inserted = 0;
      \Log::info('test: '.$value);
      try { 
          $message = \DB::table('unique_checks')->insert(['key' => $key, 'value' => $value]);
          $inserted = 1;
      } catch(\Illuminate\Database\QueryException $ex){ 
          $inserted = 0;
      }
      return $inserted;
    }

    public static function generateUniqueCode($parameter, $digits) {
      $code = \FuncNode::generateRawCode($digits);
      $check_unique = \FuncNode::putUniqueValue($parameter, $code);
      \Log::info('generate_unique: '.$check_unique);
      if($check_unique===0){
        \Log::info('unique_failed');
        $code = \FuncNode::generateUniqueCode($parameter, $digits);
      }
      return $code;
    }

}