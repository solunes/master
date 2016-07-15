<?php 

namespace Solunes\Master\App\Helpers;

class FuncNode {

    public static function node_field_creation($table_name, $node, $name, $translation, $count) {
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
        $col_type = \DB::select(\DB::raw("SHOW FIELDS FROM ".$table_name." where Field  = '".$name."'"))[0]->Type;
        $extras = [];
        $requests = [];
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
        } else if($col_type=='text'){
            $type = 'text';
            $new_row = true;
            array_push($extras, ['type'=>'class','value'=>'simple-textarea']);
            array_push($extras, ['type'=>'cols','value'=>'12']);
            array_push($extras, ['type'=>'rows','value'=>'3']);
        } else if($name=='password'){
            $type = 'password';
        } else if($col_type=='tinyint(1)'||substr_count($col_type, 'enum')>0){
            $type = 'select';
            if(substr_count($col_type, 'enum')>0){
              $value = substr($col_type, 5, -1);
            } else if($col_type=='tinyint(1)') {
              $value = "'0','1'";
            }
            $required = true;
        } else if($col_type=='timestamp'||$col_type=='date'||$col_type=='time'){
            if($node->type=='subchild'){
              array_push($extras, ['type'=>'class','value'=>$col_type.'-control']);
            } else {
              array_push($extras, ['type'=>'class','value'=>$col_type.'picker']);
            }
            if($name=='created_at'){
              \Solunes\Master\App\NodeExtra::create(['parent_id'=>$node->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'dates', 'value_array'=>json_encode(['created_at'])]);
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
        } else if($name=='section_id') {
            $display_item = 'admin';
            array_push($requests, ['action'=>'where','col'=>$name,'value_type'=>'relation','value'=>'node_pivot_id']);
        } else if($count>6){
            $display_list = 'excel';
        }
        if($node->type!='field'){
            $rules = $model::$rules_create;
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
        \FuncNode::node_generate_request($node, $requests);
        \FuncNode::field_generate_extras($field, $extras);
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

    public static function node_generate_request($node, $requests) {
        if(count($requests)>0){
          foreach($requests as $req){
            $subfield = new \Solunes\Master\App\NodeRequest;
            $subfield->parent_id = $node->id;
            $subfield->action = $req['action'];
            $subfield->col = $req['col'];
            if(isset($req['value_type'])){
                $subfield->value_type = $req['value_type'];
            }
            $subfield->value = $req['value'];
            $subfield->save();
          }
        }
    }

    public static function node_generate_extra($node, $requests) {
        if(count($requests)>0){
          foreach($requests as $req){
            $subfield = new \Solunes\Master\App\NodeRequest;
            $subfield->parent_id = $node->id;
            $subfield->action = $req['action'];
            $subfield->col = $req['col'];
            if(isset($req['value_type'])){
                $subfield->value_type = $req['value_type'];
            }
            $subfield->value = $req['value'];
            $subfield->save();
          }
        }
    }

    public static function node_menu_creation($node) {
        $menu_array = \Solunes\Master\App\Menu::where('menu_type', 'admin')->where('level', 1)->lists('id');
        if($node->type=='site'&&!$node->parent_id){
            if(!$menu_site = \Solunes\Master\App\MenuTranslation::whereIn('menu_id', $menu_array)->where('name', 'Secciones')->first()){
                $menu_site = \Solunes\Master\App\Menu::create(['type'=>'blank', 'menu_type'=>'admin', 'permission'=>'dashboard', 'icon'=>'th-list', 'es'=>['name'=>'Secciones']]);
            }
            \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>$node->permission, 'parent_id'=>$menu_site->id, 'level'=>2, 'icon'=>'th-list', 'es'=>['name'=>$node->plural, 'link'=>'admin/model-list/'.$node->name]]);
        } else if($node->type=='system'&&!$node->parent_id) {
            if(!$menu_system = \Solunes\Master\App\MenuTranslation::whereIn('menu_id', $menu_array)->where('name', 'Sistema')->first()){
                $menu_system = \Solunes\Master\App\Menu::create(['type'=>'blank', 'menu_type'=>'admin', 'permission'=>'system', 'icon'=>'th-list', 'es'=>['name'=>'Sistema']]);
            }
            \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>$node->permission, 'parent_id'=>$menu_system->id, 'level'=>2, 'icon'=>'th-list', 'es'=>['name'=>$node->plural, 'link'=>'admin/model-list/'.$node->name]]);
        } else if($node->type=='global'&&!$node->parent_id) {
            if(!$menu_global = \Solunes\Master\App\MenuTranslation::whereIn('menu_id', $menu_array)->where('name', 'Global')->first()){
                $menu_global = \Solunes\Master\App\Menu::create(['type'=>'blank', 'menu_type'=>'admin', 'permission'=>'global', 'icon'=>'th-list', 'es'=>['name'=>'Global']]);
            }
            \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>$node->permission, 'parent_id'=>$menu_global->id, 'level'=>2, 'icon'=>'th-list', 'es'=>['name'=>$node->plural, 'link'=>'admin/model-list/'.$node->name]]);
        } else if($node->type=='form'&&!$node->parent_id) {
            if(!$menu_form = \Solunes\Master\App\MenuTranslation::whereIn('menu_id', $menu_array)->where('name', 'Forms')->first()){
                $menu_form = \Solunes\Master\App\Menu::create(['type'=>'blank', 'menu_type'=>'admin', 'permission'=>'form', 'icon'=>'th-list', 'es'=>['name'=>'Forms']]);
            }
            \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>$node->permission, 'parent_id'=>$menu_form->id, 'level'=>2, 'icon'=>'th-list', 'es'=>['name'=>$node->plural, 'link'=>'admin/model-list/'.$node->name]]);
        }
    }

    public static function load_nodes_excel($path, $return = '') {
        \Excel::load($path, function($reader) use($return) {
          foreach($reader->get() as $sheet){
            $sheet_name = $sheet->getTitle();
            $sheet->each(function($row) use ($sheet_name, $return) {
              $node = \Solunes\Master\App\Node::where('name', $row->node)->first();
              if($sheet_name=='create-fields'){
                if($node){
                  if($node->location=='package'){
                      $lang_folder = 'master::fields.';
                  } else {
                      $lang_folder = 'fields.';
                  }
                  $field = new \Solunes\Master\App\Field;
                  $field->parent_id = $node->id;
                  $field->name = $row->name;
                  $field->trans_name = $row->trans_name;
                  $field->label = $lang_folder.$row->trans_name;
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
                    if($conditional = $field->field_conditionals()->where('trigger_field', $row->trigger_field)->where('trigger_show', $row->trigger_show)->first()){
                      $conditional->trigger_value = $row->trigger_value;
                    } else {
                      $conditional = new \Solunes\Master\App\FieldConditional;
                      $conditional->parent_id = $field->id;
                      $conditional->trigger_field = $row->trigger_field;
                      $conditional->trigger_show = $row->trigger_show;
                      $conditional->trigger_value = $row->trigger_value;
                      $conditional->save();
                    }
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
        if($field->translation){
            $item->translateOrNew($lang_code)->$field_name = $input;
        } else {
            if(is_array($input)){
                $item->$field_name = json_encode($input);
                if($field->type=='image'||$field->type=='file') {
                    \Solunes\Master\App\TempFile::where('type', $field->type)->whereIn('file', $input)->delete();
                } 
            } else if(is_string($input)&&$input==''&&$input!=0){
                $item->$field_name = NULL;
            } else if($input) {
                if($field->type=='image'||$field->type=='file') {
                    \Solunes\Master\App\TempFile::where('type', $field->type)->where('file', $input)->delete();
                }
                $item->$field_name = $input;
            }
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

    public static function make_notitification($user_id, $message) {
        $notification = new \Solunes\Master\App\Notification;
        $notification->user_id = $user_id;
        $notification->message = $message;
        $notification->save();
        return true;
    }

}