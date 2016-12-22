<?php 

namespace Solunes\Master\App\Helpers;

class Dynamic {

    public static function generate_node($node_name, $table_name = NULL) {
      if(!$node = \Solunes\Master\App\Node::where('name', $node_name)->first()){
        $node = new \Solunes\Master\App\Node;
        $node->name = $node_name;
        if($table_name){
          $node->table_name = $table_name;
        } else {
          $node->table_name = str_replace('-', '_', $node_name);
        }
      }
      return $node;
    }

    public static function edit_node($node, $array, $language = 'es') {
      foreach($array as $array_key => $array_val){
        if($array_key=='singular'||$array_key=='plural'){
          $node->translateOrNew($language)->$array_key = $array_val;
        } else {
          $node->$array_key = $array_val;
        }
      }
      $node->save();
      return $node;
    }

    public static function generate_node_table($table_name, $array = ['id'=>'increments']) {
      if(!\Schema::hasTable($table_name)){
        \Schema::create($table_name, function(\Illuminate\Database\Schema\Blueprint $table) use($array) {
          foreach($array as $array_key => $array_val){
            if($array_val == 'timestamps'){
              $table->timestamps();
            } else if($array_val=='increments') {
              $table->$array_val($array_key);
            } else {
              $table->$array_val($array_key)->nullable();
            }
          }
        });
        return true;
      }
      return false;
    }

    public static function check_node_exists($node_name, $count) {
      if($count==0){
        $final_node_name = $node_name;
      } else {
        $final_node_name = $node_name.'-'.$count;
      }
      if(\Solunes\Master\App\Node::where('name', $final_node_name)->first()){
        $count++;
        return \Dynamic::check_node_exists($node_name, $count);
      }
      return $final_node_name;
    }

    public static function generate_node_extra($node, $type, $value_array) {
      if(!$node_extra = $node->node_extras()->where('type', $type)->first()){
        $node_extra = new \Solunes\Master\App\NodeExtra;
        $node_extra->parent_id = $node->id;
        $node_extra->type = $type;
      }
      $node_extra->value_array = json_encode($value_array);
      $node_extra->save();
      return $node_extra;
    }

    public static function generate_field($node, $field_name, $field_type) {
      if(!$field = \Solunes\Master\App\Field::where('parent_id', $node->id)->where('name', $field_name)->first()){
        $field = new \Solunes\Master\App\Field;
        $field->parent_id = $node->id;
        $field->name = $field_name;
        $field->type = $field_type;
      }
      return $field;
    }

    public static function edit_field($field, $array, $language = 'es') {
      foreach($array as $array_key => $array_val){
        if($array_key=='label'){
          $field->translateOrNew($language)->$array_key = $array_val;
        } else {
          $field->$array_key = $array_val;
        }
      }
      $field->save();
      return $field;
    }

    public static function generate_field_table($node, $field_type, $field_name, $last_field) {
      if(!in_array($field_type, ['title','content','subchild','field'])&&!\Schema::hasColumn($node->table_name, $field_name)){
        $column_type = 'string';
        if($field_type=='text'||$field_type=='checkbox'||$field_type=='map'||$field_type=='file'||$field_type=='image'){
          $column_type = 'text';
        } else if($field_type=='date'){
          if($field_name=='created_at'||$field_name=='updated_at'||$field_name=='deleted_at'){
            $column_type = 'timestamp';
          } else {
            $column_type = 'date';
          }
        } else if($field_type=='relation'){
          $column_type = 'integer';
        }
        \Schema::table($node->table_name, function ($table) use($column_type, $field_name, $last_field){
          if($field_name=='id'){
            $table->increments();
          } else if($last_field) {
            $table->$column_type($field_name)->nullable()->after($last_field->name);
          } else {
            $table->$column_type($field_name)->nullable();
          }
        });
        return true;
      }
      return false;
    }

    public static function check_field_exists($node, $field_name) {
      if($node->fields()->where('name', $field_name)->first()){
        $field_name = $node->table_name.'_field_'.rand(1000,9999);
        return \Dynamic::check_field_exists($node, $field_name);
      }
      return $field_name;
    }

    public static function generate_field_options($options, $field, $language = 'es') {
      foreach($options as $key => $option){
        if(!$option['name']){
          $i_option = new \Solunes\Master\App\FieldOption;
          $i_option->parent_id = $field->id;
          $i_option->name = \Dynamic::check_field_option_exists($field->id, $field->name, count($field->field_options)+1);
        } else {
          if(!$i_option = $field->field_options()->where('name', $option['name'])->first()){
            $i_option = new \Solunes\Master\App\FieldOption;
            $i_option->parent_id = $field->id;
            $i_option->name = $option['name'];
          }
        }
        if($option['label']){
          $i_option->translateOrNew($language)->label = $option['label'];
        } else {
          $i_option->translateOrNew($language)->label = '-';
        }
        if(is_numeric($option['active'])){
          $i_option->active = $option['active'];
        }
        $i_option->save();
      }
      return true;
    }

    public static function check_field_option_exists($field_id, $field_name, $count) {
      if(\Solunes\Master\App\FieldOption::where('parent_id', $field_id)->where('name', $field_name.'_'.$count)->count()>0){
        $count++;
        return \Dynamic::check_field_option_exists($field_id, $field_name, $count);
      }
      return $field_name.'_'.$count;
    }

    public static function generate_field_extra($field, $type, $value) {
      if(!$field_extra = $field->field_extras()->where('type', $type)->first()){
        $field_extra = new \Solunes\Master\App\FieldExtra;
        $field_extra->parent_id = $field->id;
        $field_extra->type = $type;
      }
      $field_extra->value = $value;
      $field_extra->save();
      return $field_extra;
    }

    public static function generate_field_conditional($field, $trigger_field, $trigger_show, $trigger_value) {
      if(!$field_cond = $field->field_conditionals()->where('trigger_field', $trigger_field)->first()){
        $field_cond = new \Solunes\Master\App\FieldConditional;
        $field_cond->parent_id = $field->id;
        $field_cond->trigger_field = $trigger_field;
      }
      $field_cond->trigger_show = $trigger_show;
      $field_cond->trigger_value = $trigger_value;
      $field_cond->save();
      return $field_cond;
    }

    public static function generate_image_folder($field, $folder_name, $extension) {
      if(!$field_folder = \Solunes\Master\App\ImageFolder::where('name', $folder_name)->first()){
        $field_folder = new \Solunes\Master\App\ImageFolder;
        $field_folder->name = $folder_name;
      }
      $field_folder->extension = $extension;
      $field_folder->save();
      return $field_folder;
    }

    public static function generate_image_size($field_folder, $code, $type = 'resize', $width = 600, $height = NULL) {
      if(!$field_size = $field_folder->image_sizes()->where('code', $code)->first()){
        $field_size = new \Solunes\Master\App\ImageSize;
        $field_size->parent_id = $field_folder->id;
        $field_size->code = $code;
      }
      $field_size->type = $type;
      $field_size->width = $width;
      if($height){
        $field_size->height = $height;
      }
      $field_size->save();
      return $field_size;
    }

}