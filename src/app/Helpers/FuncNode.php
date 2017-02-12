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
        $new_row = false;
        $permission = NULL;
        $col_type = \DB::select(\DB::raw("SHOW FIELDS FROM ".$table_name." where Field  = '".$name."'"))[0]->Type;
        $extras = [];
        $field_options = [];
        if(strpos($name, '_id') !== false) {
            $type = 'relation';
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
        } else if($col_type=='text'){
            $type = 'text';
            $new_row = true;
            array_push($extras, ['type'=>'class','value'=>'simple-textarea']);
            array_push($extras, ['type'=>'cols','value'=>'12']);
            array_push($extras, ['type'=>'rows','value'=>'3']);
        } else if($name=='password'){
            $type = 'password';
        } else if($name=='map'){
            $type = 'map';
            array_push($extras, ['type'=>'cols','value'=>'12']);
        } else if($col_type=='tinyint(1)'||substr_count($col_type, 'enum')>0){
            $type = 'select';
            if(substr_count($col_type, 'enum')>0){
              $value_array = substr($col_type, 6, -2);
            } else if($col_type=='tinyint(1)') {
              $value_array = "0','1";
            }
            foreach(explode("','",$value_array) as $subvalue){
              array_push($field_options, ['name'=>$subvalue]);
            }
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
            foreach($languages as $language){
              \App::setLocale($language->code);
              $translation = trans('master::admin.'.$option['name']);
              $subfield->translateOrNew($language->code)->label = $translation;
            }
            \App::setLocale('es');
            $subfield->save();
          }
        }
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
            if($menu_parent = \Solunes\Master\App\MenuTranslation::whereIn('menu_id', $menu_array)->where('name', trans('master::admin.'.$node->folder))->first()){
              $menu_parent = $menu_parent->menu;
            } else {
              $menu_parent = \Solunes\Master\App\Menu::create(['type'=>'blank', 'menu_type'=>'admin', 'permission'=>$node->folder, 'icon'=>'th-list']);
              foreach($languages as $language){
                \App::setLocale($language->code);
                $menu_parent->translateOrNew($language->code)->name = trans('master::admin.'.$node->folder);
              }
              \App::setLocale('es');
              $menu_parent->save();
            }
            $menu = \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>$node->permission, 'parent_id'=>$menu_parent->id, 'level'=>2, 'icon'=>'th-list']);
            foreach($languages as $language){
              \App::setLocale($language->code);
              $menu->translateOrNew($language->code)->name = $node->plural;
              $menu->translateOrNew($language->code)->link = 'admin/model-list/'.$node->name;
            }
            \App::setLocale('es');
            $menu->save();
        }
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
                  foreach($languages as $language){
                    \App::setLocale($language->code);
                    $field->translateOrNew($language->code)->label = trans('master::fields.'.$row->trans_name);
                  }
                  \App::setLocale('es');
                  $field->type = $row->type;
                  $field->display_list = $row->display_list;
                  $field->display_item = $row->display_item;
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

    public static function put_data_field($item, $field, $input, $lang_code = 'es') {
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

    public static function put_in_database($item, $field, $field_name, $final_input, $lang_code = 'es') {
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

    public static function make_notitification($user_id, $url, $message) {
      if($user = \App\User::find($user_id)){
        $email = false;
        $sms = false;
        if($user->email&&$user->notifications_email){
          // ENVIAR EMAIL
          $email = true;
        }
        if($user->cellphone&&$user->notifications_sms){
          // ENVIAR SMS
          $sms = true;
        }
        if($email&&$sms){
          $type = 'all';
        } else if($email){
          $type = 'email';
        } else if($sms){
          $type = 'sms';
        } else {
          $type = 'none';
        }
        $notification = new \Solunes\Master\App\Notification;
        $notification->type = $type;
        $notification->user_id = $user_id;
        $notification->url = $url;
        $notification->message = $message;
        $notification->save();
        return true;
      } else {
        return false;
      }
    }

    public static function check_var($name) {
        if($item = \Solunes\Master\App\Variable::where('name', $name)->first()){
            return $item->value;
        } else {
            return NULL;
        }
    }

    public static function make_email($email_name, $to_array, $vars = [], $vars_if = [], $vars_foreach = []) {
      // $vars = ['@search@'=>'Reemplazar con esto']
      if($email = \Solunes\Master\App\Email::where('name', $email_name)->first()){
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
        \Mail::send('master::emails.default', ['msg' => $msg], function ($m) use($email, $to_array, $msg) {
            $m->to($to_array)->subject($email->title);
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
            $items = $items->get();
            $subarray = ['items'=>$items, 'node'=>$node];
        }
        return $subarray;
    }

}