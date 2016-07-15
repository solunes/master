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
            $response .= Form::submit(trans('admin.save'), array('class'=>'btn btn-site'));
        } else {
            $response .= Form::submit(trans('admin.'.$action), array('class'=>'btn btn-site'));
        }
        $response .= '</div>';
        return $response;
    }

    public static function form_input($i, $data_type, $field, $extras, $array_parameters = []) {
        $name = $field['name'];
        $type = $field['type'];
        if($type=='relation'){
            $type = 'select';
        } else if($type=='field'&&$field['multiple']==false){
            $type = 'radio';
        } else if($type=='field'&&$field['multiple']==true){
            $type = 'checkbox';
        }
        $subinput = false;
        $fixed_name = str_replace('[]', '', $name);
        $required = $field['required'];
        $parameters = [];
        if($type=='select'||$type=='checkbox'||$type=='radio'){
            $parameters['options'] = $field['options'];
        }
 
        // CLASS
        $class = 'form-control ';
        if(array_key_exists('class', $extras)){
            $class .= $extras['class'];
        }

        // FIELD CLASS
        $field_class = 'flex-item ';
        if(array_key_exists('field_class', $extras)){
            $field_class .= $extras['field_class'];
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
            $label = trans('fields.'.str_replace('_id','',$fixed_name));
        }
        if($required==true){
            $label .= ' (*)';
        }
        if(isset($field['tooltip'])&&$field['tooltip']){
            if($data_type=='view'){
                //$label .= '<div class="tooltip-mini">'.trans('tooltips.'.$fixed_name).'</div>';
            } else {
                $label .= ' <a href="#" class="help" title="'.trans('tooltips.'.$fixed_name).'"><i class="fa fa-question-circle"></i></a>';
            }
        }

        // VALUE
        $value = NULL;
        if($i&&($i->$fixed_name||$i->$fixed_name==0)){
            $value = $i->$fixed_name;
        } else if (request()->has($fixed_name)){
            $value = request()->input($fixed_name);
        } 
        if($type=='password'){
            $value = NULL;
        } else if($type=='array'&&$value&&is_array($value)){
            $value = json_encode($value);
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
            $array['data-file'] = $name;
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
        if((isset($field['preset'])&&$field['preset']==true)||($extras&&array_key_exists('disabled', $extras))){
            $array['disabled'] = true;
        }

        // PLACEHOLDER
        if($extras&&isset($extras['placeholder'])){
            $array['placeholder'] = $extras['placeholder'];
        }

        // RESPONSE
        if($subinput=='multiple') {
            $response = Field::form_input_builder($name, $type, $parameters, $array, $value, $data_type);
            if(\Session::has('errors')&&\Session::get('errors')->default->first($name)){
                $response .= '<div class="error">'.\Session::get('errors')->default->first($name).'</div>';
            }
        } else if($type=='checkbox'||$type=='radio'||$type=='score'){
            $response = Field::form_checkbox_builder($name, $type, $parameters, $label, $col, $i, $value, $data_type);
        } else  if($type=='hidden') {
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

        $response .= '</div>';
        return $response;
    }

    public static function form_input_builder($name, $type, $parameters, $array, $value, $data_type) {
        $response = NULL;
        if($type=='file'||$type=='image'){
          $response .= Field::generate_image_field($name, $type, $parameters, $array, $value, $data_type);
        } else if($data_type=='view'){
            if($type=='select'&&isset($parameters['options'][$value])){
                $response .= '<div>'.$parameters['options'][$value].'</div>';
            } else {
                if($value==NULL||$value==''){
                    $value = '-';
                }
                $response .= '<div>'.$value.'</div>';
            }
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

    public static function form_checkbox_builder($name, $type, $parameters, $label, $col, $i, $value, $data_type) {
        $array = [];
        if($data_type=='view'){
            $array = ['disabled'=>true];
        }
        if($type=='score'||$type=='main_score'){
            $option_array = ['1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'];
        } else {
            $option_array = $parameters['options'];
            if(isset($option_array[0])){
                unset($option_array[0]);
            }
        }
        $response = '<div id="field_'.$name.'" class="col-sm-'.$col.' '.$parameters['field_class'].'">';
        $response .= '<label for="'.$name.'" class="control-label">'.$label.'</label><div class="row">';
        if($col==12){
            $subcol = 4;
        } else if($col>5) {
            $subcol = 6;
        } else {
            $subcol = 12;
        }
        foreach($option_array as $key => $option) {
            $response .= '<div class="col-sm-'.$subcol.' col-xs-12"><div class="checkbox">';
            if($type=='radio'||$type=='score'||$type=='main_score'){
                $response .= '<label class="checkbox">'.Form::radio($name, $key, AdminItem::make_radio_value($key, $value), $array).' '.$option.'</label>';
            } else if($type=='checkbox'){
                $response .= '<label class="checkbox">'.Form::checkbox($name.'[]', $key, AdminItem::make_checkbox_value($key, $value), $array).' '.$option.'</label>';
            }
            $response .= '</div></div>';
        }
        if(\Session::has('errors')&&\Session::get('errors')->default->first($name)){
            $response .= '<div class="error col-sm-12">'.\Session::get('errors')->default->first($name).'</div>';
        }
        $response .= '</div></div>';
        return $response;
    }

    public static function generate_image_field($name, $type, $parameters, $array, $value, $data_type) {
          $folder = $parameters['folder'];
          $array['data-count'] = 0;
          $response = '<div class="file_container">';
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
            if(is_array($array_value)&&count($array_value)>0){
              foreach($array_value as $key => $value){
                $response .= '<div class="upload_thumb '.$type.'_thumb">';
                if($type=='image'){
                  $response .= '<a class="lightbox" href="'.Asset::get_image_path($folder, 'normal', $value).'">'.Asset::get_image($folder, 'mini', $value).'</a>';
                } else {
                  $response .= '<a href="'.Asset::get_file($folder, $value).'" target="_blank">'.$value.'</a>';
                }
                if($data_type!='view'){
                    if($multiple){
                        $response .= '<input type="hidden" name="'.$name.'['.$key.']" value="'.$value.'" />';
                    } else {
                        $response .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
                    }
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

}