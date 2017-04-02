<?php 

namespace Solunes\Master\App\Helpers;

use Form;

class Field {
            
    public static function form_submit($i, $model, $action) {
        $response = '<div>';
        $response .= Form::hidden('action', $action);
        $response .= Form::hidden('model_node', $model);
        $response .= Form::hidden('lang_code', \App::getLocale());
        if(request()->has('parameters')){
            $response .= Form::hidden('parameters', 'parameters='.request()->input('parameters'));
        }
        if($action=='edit'){
            $response .= Form::hidden('id', $i->id);
            $response .= Form::submit(trans('master::admin.save'), array('class'=>'btn btn-site'));
        } else {
            $response .= Form::submit(trans('master::admin.'.$action), array('class'=>'btn btn-site'));
        }
        $response .= '</div>';
        return $response;
    }

    public static function form_input($i, $data_type, $field, $extras, $array_parameters = []) {
        $name = $field['name'];
        $type = $field['type'];
        if($type=='date'){
            $type = 'string';
        } else if($type=='relation'){
            $type = 'select';
        } else if($type=='field'&&$field['multiple']==false){
            $type = 'radio';
        } else if($type=='field'&&$field['multiple']==true){
            $type = 'checkbox';
        }
        $subinput = false;
        $fixed_name = str_replace('[]', '', $name);
        $required = false;
        if(isset($field['required'])){
            $required = $field['required'];
        }

        $parameters = [];
        if($type=='select'||$type=='checkbox'||$type=='radio'){
            $parameters['options'] = $field['options'];
            if($type=='select'&&!$required){
                $parameters['options'] = [''=>' '] + $parameters['options'];
            }
        }
 
        // CLASS
        $class = 'form-control input-lg" ';
        if(array_key_exists('class', $extras)){
            $class .= $extras['class'];
        }

        // FIELD CLASS
        $field_class = 'flex-item ';
        if(array_key_exists('field_class', $extras)){
            $field_class .= $extras['field_class'];
        }
        if($type=='title'){
            $field_class .= ' title';
        }
        $parameters['field_class'] = $field_class;

        // COL
        $col = 6;
        if(array_key_exists('cols', $extras)){
            $col = $extras['cols'];
        }

        // PARAMETERS ARRAY
        $array = ['class'=>$class, 'id'=>$name];
        if($array_parameters){
            $array = $array+$array_parameters;
        }

        // LABEL
        if(array_key_exists('label', $extras)){
            $label = $extras['label'];
        } else if(isset($field['final_label'])) {
            $label = $field['final_label'];
        } else {
            $label = trans('master::fields.'.str_replace('_id','',$fixed_name));
        }
        if($required==true){
            $label .= ' (*)';
        }
        if(isset($field['tooltip'])&&$field['tooltip']&&$data_type!='view'){
            $label .= ' <a href="#" class="help" title="'.$field['tooltip'].'"><i class="fa fa-question-circle"></i></a>';
        }
        if(isset($field['filter'])){
            $label .= ' <a href="'.url('admin/delete-filter/'.$field['filter']).'" onclick="return confirm(\''.trans('master::admin.delete_confirmation').'\');">( X )</a>';
        }
        if(isset($field['message'])&&$field['message']){
            $label .= '<div class="field-message">'.$field['message'].'</div>';
        }
        
        // VALUE
        $value = NULL;
        if($i&&($i->$fixed_name||intval($i->$fixed_name)===0)){
            $value = $i->$fixed_name;
        } else if (request()->has($fixed_name)){
            $value = request()->input($fixed_name);
        } else if(array_key_exists('value', $extras)){
            $value = $extras['value'];
        } else if(!$i&&array_key_exists('default_value', $extras)) {
            $value = $extras['default_value'];
        }
        if($type=='password'){
            $value = NULL;
        } else if(($type=='array'||$type=='checkbox')&&$value&&is_string($value)){
            $value = json_decode($value, true);
        }

        // SUBINPUT
        if(array_key_exists('subtype', $extras)){
            $subinput = $extras['subtype'];
            $array['rel'] = $extras['subinput'].'_'.$name;
            $fixed_name = $extras['subinput'].'_'.$name;
            $name = $extras['subinput'].'_'.$name.'['.$extras['subcount'].']';
            if($type=='string'||$type=='text'){
                $array['class'] = $array['class']. ' text-control';
            } else if($type=='hidden'){
                $array['class'] = $array['class'].' hidden-control';
            }
        }

        // FILAS
        if(array_key_exists('rows', $extras)){
            $array['rows'] = $extras['rows'];
        }

        // IMAGENES
        if($type=='file'||$type=='image'){
            $array['class'] = $array['class'].' fileupload';
            $array['data-type'] = $type;
            $array['data-folder'] = $extras['folder'];
            $parameters['folder'] = $extras['folder'];
            $parameters['i'] = $i;
            if(!$value){
                $value = request()->old($fixed_name);
            }
            if($field['multiple']){
                $array['data-multiple'] = '1';
                $array['multiple'] = true;
            } else {
                $array['data-multiple'] = '0';
            }
        }

        // CAMPOS PREDEFINIDOS
        if((isset($field['preset'])&&$field['preset']==true)||$data_type=='view'||($extras&&array_key_exists('disabled', $extras))){
            $array['disabled'] = true;
        }

        // PLACEHOLDER
        if($extras&&isset($extras['placeholder'])){
            $array['placeholder'] = $extras['placeholder'];
        }

        // CUSTOM FIELD CORRECTIONS
        if(config('solunes.custom_field')){
            $array = \CustomFunc::custom_field($array, $parameters, $type);
        }

        // CHECK IF EDITOR AND IS HIDDEN
        $parameters['hidden_message'] = NULL;
        if($data_type=='editor'&&isset($field['display_item'])){
            $display_label = NULL;
            if($field['display_item']=='admin'){
                $display_label = 'SOLO PARA ADMIN';
            } else if($field['display_item']=='none') {
                $display_label = 'CAMPO OCULTO';
            }
            if($display_label){
                $parameters['hidden_message'] = $display_label;
                $label .= ' | '.$display_label;
            }
            //$label .= ' | '.$field['order'];
        }

        // RESPONSE
        if($subinput=='multiple') {
            if($type=='checkbox'||$type=='radio'||$type=='score'){
                $response = Field::form_checkbox_input($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type);
            } else {
                $response = Field::form_input_builder($name, $type, $parameters, $array, $value, $data_type);
            }
            if(\Session::has('errors')&&\Session::get('errors')->default->first($name)){
                $response .= '<div class="error">'.\Session::get('errors')->default->first($name).'</div>';
            }
        } else if($type=='checkbox'||$type=='radio'||$type=='score'){
            $response = Field::form_checkbox_builder($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type);
        } else if($type=='hidden') {
            $response = Field::form_input_builder($name, $type, $parameters, $array, $value, $data_type);
        } else {
            $response = Field::form_field_builder($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type);
        }
        return $response;
    }

    public static function form_field_builder($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type) {
        $response = '<div id="field_'.$name.'" class="col-sm-'.$col.' '.$parameters['field_class'].'">';
        $response .= '<label for="'.$name.'" class="control-label">'.$label.'</label>';
        $response .= Field::form_input_builder($name, $type, $parameters, $array, $value, $data_type);
        if($data_type!='view'&&isset($array['disabled'])){
            $response .= Field::form_input_builder($name, 'hidden', [], [], $value, $data_type);
        }
        if($data_type!='view'&&\Session::has('errors')&&\Session::get('errors')->default->first($name)){
            $response .= '<div class="error">'.\Session::get('errors')->default->first($name).'</div>';
        }
        if($data_type=='editor'){
            $response .= \Field::generate_editor_fields($name, $parameters['hidden_message']);
        }
        $response .= '</div>';
        return $response;
    }

    public static function form_input_builder($name, $type, $parameters, $array, $value, $data_type) {
        $response = NULL;
        if($type=='file'||$type=='image'){
            $response .= Field::generate_image_field($name, $type, $parameters, $array, $value, $data_type);
        } else if($type=='map'){
            $response .= Field::generate_map_field($name, $type, $parameters, $array, $value, $data_type);
        } else if($type=='string'){
            $response = Form::text($name, $value, $array);
        } else if($type=='hidden'){
            $response = Form::hidden($name, $value, $array);
        } else if($type=='select'){
            $response = Form::select($name, $parameters['options'], $value, $array);
        } else if($type=='text'||$type=='array'){
            $response = Form::textarea($name, $value, $array);
        } else if($type=='password'){
            $response = Form::password($name, $array);
        }
        return $response;
    }

    public static function form_checkbox_builder($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type) {
        $response = '<div id="field_'.$name.'" class="col-sm-'.$col.' '.$parameters['field_class'].'">';
        $response .= '<label for="'.$name.'" class="control-label">'.$label.'</label>';
        $response .= \Field::form_checkbox_input($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type);
        if(\Session::has('errors')&&\Session::get('errors')->default->first($name)){
            $response .= '<div class="error col-sm-12">'.\Session::get('errors')->default->first($name).'</div>';
        }
        if($data_type=='editor'){
            $response .= \Field::generate_editor_fields($name, $parameters['hidden_message']);
        }
        $response .= '</div>';
        return $response;
    }

    public static function form_checkbox_input($name, $type, $parameters, $array, $label, $col, $i, $value, $data_type) {
        $array['data-checkbox'] = 'true';
        if($data_type=='view'){
            $array = ['disabled'=>true];
            $name = rand(10000000,99999999).'_'.$name;
        }
        if($type=='score'||$type=='main_score'){
            $option_array = ['1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'];
        } else {
            $option_array = $parameters['options'];
        }
        $response = NULL;
        if($type=='checkbox'){
        $response .= '<div class="mt-checkbox-inline">';
        } else {
        $response .= '<div class="mt-radio-inline">';
        }
        foreach($option_array as $key => $option) {
            $array['class'] = 'field_'.$name.' option_'.$key;
            if($type=='radio'||$type=='score'||$type=='main_score'){
                $response .= '<label class="mt-radio">'.$option.' '.Form::radio($name, $key, AdminItem::make_radio_value($key, $value), $array);
            } else if($type=='checkbox'){
                $response .= '<label class="mt-checkbox">'.$option.' '.Form::checkbox($name.'[]', $key, AdminItem::make_checkbox_value($key, $value), $array);
            }
            $response .= '<span></span></label>';
        }
        $response .= '</div>';
        return $response;
    }

    public static function generate_map_field($name, $type, $parameters, $array, $value, $data_type) {
        if(isset($array['rel'])){
            if($value==NULL){
                $map_text = 'Introducir Mapa';
                $value = '-16.495369;-68.134289';
            } else {
                $map_text = 'Editar Mapa ('.$value.')';
            }
            if($data_type=='view'){
                $response = $value;
            } else {
                $response = '<a id="link-'.$name.'" class="lightbox" href="'.url('admin/modal-map/'.$name.'/'.$value.'?lightbox[width]=800&lightbox[height]=500').'" rel="'.$array['rel'].'" data-value="'.$value.'">'.$map_text.'</a>';
            }
        } else {
            $response = '<div id="map-'.$name.'" style="height: 500px;"></div>';
            $response .= '<input id="search-'.$name.'" class="map-search-box" type="text" placeholder="Buscar">';
            if($value==NULL){
                $value = '-16.495369;-68.134289';
            }
        }
        $response .= Form::hidden($name, $value, $array);
        return $response;
    }

    public static function generate_image_field($name, $type, $parameters, $array, $value, $data_type) {
          $folder = $parameters['folder'];
          $array['data-count'] = 0;
          $response = '<div class="file_container">';
          $response .= '<div class="file_limitations"><p>';
          if($type=='file'){
            $response .= trans('master::admin.file_limitations');
          } else if($type=='image') {
            $response .= trans('master::admin.image_limitations');
          }
          $response .= '</p></div>';
          if(isset($array['rel'])){
            $rel_attribute = ' rel="'.$array['rel'].'"';
            $array['rel'] = 'uploader_'.$array['rel'];
          } else {
            $rel_attribute = NULL;
          }
          if($folder&&$value){
            $i = $parameters['i'];
            $multiple = false;
            if(isset($array['multiple'])){
                $multiple = true;
            }
            if(!$i){
                $action = 'temp';
            } else {
                $action = 'saved';
            }
            if(!$i&&$multiple){
                $array_value = $value;
            } else if($multiple){
                $array_value = json_decode($value, true);
            } else {
                $array_value = [$value];
            }
            if($multiple){
                $final_name = $name.'[0]';
            } else {
                $final_name = $name;
            }
            if(is_array($array_value)&&count($array_value)>0){
              foreach($array_value as $key => $value){
                if($multiple){
                    $final_name = $name.'['.$key.']';
                } else {
                    $final_name = $name;
                }
                $response .= '<div class="upload_thumb '.$type.'_thumb">';
                if($type=='image'){
                  $response .= '<a class="lightbox" href="'.Asset::get_image_path($folder, 'normal', $value).'">'.Asset::get_image($folder, 'mini', $value).'</a>';
                } else {
                  $response .= '<a href="'.Asset::get_file($folder, $value).'" target="_blank">'.$value.'</a>';
                }
                if($data_type!='view'){
                    $response .= '<input type="hidden" name="'.$final_name.'" value="'.$value.'"'.$rel_attribute.' />';
                    $response .= '<a class="delete_temp" data-folder="'.$folder.'"  data-action="'.$action.'" data-file="'.$value.'" data-type="'.$type.'" href="#">X</a>';
                }
                $array['data-count'] = $key+1;
                $response .= '</div>';
              }
            }
          }
          if($data_type=='view'&&$array['data-count']==0){
            $response .= '<div>-</div>';
          }
          $response .= '</div>';
          if($data_type!='view'){
              $response .= '<div class="progress_bar"><div class="bar" style="width: 0%;"></div>';
              $response .= '<a class="cancel_upload_button" href="#">Cancelar</a></div>';
              $response .= '<div class="error_bar"></div>';
              $response .= Form::file('uploader_'.$name, $array);
          }
          return $response;
    }

    public static function generate_editor_fields($name, $hidden = false) {
        $return = '<div class="form_fields_actions"> <i class="fa fa-arrow-up"></i> ';
        if($hidden){
            $return .= $hidden.' | ';
        }
        $return .= '<a href="'.url('admin/form-field/edit/'.request()->segment('3').'/'.$name).'">Editar Campo</a> | ';
        $return .= '<a href="'.url('admin/form-field/create/'.request()->segment('3').'/'.$name).'">Agregar Campo</a> | ';
        $return .= '<a href="'.url('admin/form-field-order/'.request()->segment('3').'/'.$name.'/up').'">Subir</a> | ';
        $return .= '<a href="'.url('admin/form-field-order/'.request()->segment('3').'/'.$name.'/down').'">Bajar</a>';
        $return .= '</div>';
        return $return;
    }

}