<?php 

namespace Solunes\Master\App\Helpers;

class DataManager {

    public static function importExcelRows($sheet, $languages) {
        $count_rows = $sheet->count();
        $sheet_model = $sheet->getTitle();
        $strpos_sheet = strpos($sheet_model, '#');
        if($strpos_sheet !== false){
            $sheet_model = substr($sheet_model, 0, $strpos_sheet);
        }
        if($node = \Solunes\Master\App\Node::where('name', $sheet_model)->first()){
            $field_array = [];
            $field_sub_array = [];
            $sub_field_insert = [];
            foreach($languages as $language){
                foreach($node->fields()->whereNotIn('type', ['child','subchild','field'])->get() as $field){
                    if($language->id>1){
                        $field_array[$field->name.'_'.$language->code] = $field;
                    } else {
                        $field_array[$field->name] = $field;
                    }
                }
            }
            foreach($node->fields()->where('type', 'field')->get() as $field){
                $field_sub_array[$field->name] = $field;
            }
            $sheet->each(function($row) use ($node, $field_array, $field_sub_array, $sub_field_insert) {
                $new_item = false;
                foreach($row->all() as $column => $input){
                    if($column=='id'&&$input){
                        $model = \FuncNode::node_check_model($node);
                        if(!$item = $model->where('id', $row->id)->first()){
                            $item = $model;
                        }
                        $new_item = true;
                    }
                    if($new_item&&isset($field_array[$column])){
                        $field = $field_array[$column];
                        if($column==$field->name){
                            $language_code = config('solunes.main_lang');
                        } else {
                            $language_code = str_replace($field->name.'_','',$column);
                        }
                        if($field->relation&&$input&&!is_numeric($input)){
                            if($sub_model = \Solunes\Master\App\Node::where('name', $field->value)->first()){
                                $sub_model = $sub_model->model;
                                if($get_submodel = $sub_model::where('name', $input)->first()){
                                    $input = $get_submodel->id;
                                }
                            }
                        } else if(!$field->relation&&($field->type=='select'||$field->type=='radio')){
                            if($subanswer = $field->field_options()->whereTranslation('label', $input)->first()){
                                $input = $subanswer->name;
                            } else {
                                $input = NULL;
                            }
                        } else if(!$field->relation&&$field->type=='checkbox'){
                            $subinput = [];
                            foreach(explode(' | ', $input) as $subval){
                                if($subanswer = $field->field_options()->whereTranslation('label', $subval)->first()){
                                    $subinput[] = $subanswer->name;
                                }
                            }
                            if(count($subinput)>0){
                                $input = json_encode($subinput);
                            } else {
                                $input = NULL;
                            }
                        }
                        if($input||$input=='0'){
                            if($field->type=='image'||$field->type=='file'){
                                $action_name = 'upload_'.$field->type;
                                if($field->multiple){
                                    foreach(explode(' | ',$input) as $subinput){
                                        if(filter_var($subinput, FILTER_VALIDATE_URL)){
                                            $file_path = $subinput;
                                        } else {
                                            $file_path = public_path('seed/'.$node->name.'/'.$subinput);
                                        }
                                        $input_array[] = \Asset::$action_name($file_path, $node->name.'-'.$field->name, true);
                                    }
                                    $input = json_encode($input_array);
                                } else {
                                    if(filter_var($input, FILTER_VALIDATE_URL)){
                                        $file_path = $input;
                                    } else {
                                        $file_path = public_path('seed/'.$node->name.'/'.$input);
                                    }
                                    $input = \Asset::$action_name($file_path, $node->name.'-'.$field->name, true);
                                }
                            }
                            $item = \FuncNode::put_data_field($item, $field, $input, $language_code);
                        }
                    } else if($new_item&&isset($field_sub_array[$column])) {
                        $field = $field_sub_array[$column];
                        if($field->multiple){
                            $array_insert = [];
                            foreach(explode(';',$input) as $value){
                                if($value&&!is_numeric($value)){
                                    $sub_model = \Solunes\Master\App\Node::where('table_name', $column)->first()->model;
                                    array_push($array_insert, $sub_model::where('name', $value)->first()->id);
                                } else if($value) {
                                    array_push($array_insert, $value);
                                }
                            }
                        } else {
                            if($input&&!is_numeric($input)){
                                $sub_model = \Solunes\Master\App\Node::where('table_name', $column)->first()->model;
                                $array_insert = $sub_model::where('name', $input)->first()->id;
                            } else {
                                $array_insert = $input;
                            }
                            $array_insert = [$array_insert];
                        }
                        $sub_field_insert[$column] = $array_insert;
                    }
                }
                if($new_item){
                    $item->save();
                    foreach($sub_field_insert as $column => $input){
                        $item->$column()->sync($input);
                    }
                }
            });
        }
        return $count_rows;
    }

    public static function exportNodeExcel($excel, $alphabet, $node, $just_last = false) {
        $sheet_title = $node->name;
        $languages = \Solunes\Master\App\Language::where('code','!=',config('solunes.main_lang'))->lists('code');
        $array = \DataManager::generateExportArray($alphabet, $node, $languages);
        $col_array = $array['col_array'];
        $col_width = $array['col_width'];
        $fields_array = $array['fields_array'];
        $field_options_array = $array['field_options_array'];
        $trans_array = $array['trans_array'];
        if($just_last){
            $items = \FuncNode::node_check_model($node)->orderBy('id','DESC')->limit(1)->get();
        } else {
            $items = \FuncNode::node_check_model($node)->get();
        }
        return \DataManager::generateSheet($excel, $alphabet, $sheet_title, $col_array, $col_width, $fields_array, $field_options_array, $items, $trans_array);
    }

    public static function generateExportArray($alphabet, $node, $languages) {
        $trans_array = [];
        $col_array = [];
        $col_width = [];
        $fields_array = $node->fields()->whereNotIn('type', ['title','content','child','subchild'])->get();
        $field_options_array = [];
        foreach($fields_array as $key => $field){
            array_push($col_array, $field->name);
            if($field->translation){
                foreach($languages as $language){
                    $trans_array[$language][] = $field->name;
                }
            }
            if(count($field->field_options)>0){
                foreach($field->field_options as $option){
                    $field_options_array[$field->name][$option->name] = $option->label;
                }
            }
            $col_width = \DataManager::generateColWidth($alphabet, $field, $key, $col_width);
        }
        foreach($trans_array as $lang => $fields){
            foreach($fields as $field_name){
                array_push($col_array, $field_name.'_'.$lang);
            }
        }
        return ['col_array'=>$col_array, 'col_width'=>$col_width, 'fields_array'=>$fields_array, 'field_options_array'=>$field_options_array, 'trans_array'=>$trans_array];
    }

    public static function generateSheet($excel, $alphabet, $sheet_title, $col_array, $col_width, $fields_array, $field_options_array, $items, $trans_array, $child_table = NULL) {
        //$excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->sheet($sheet_title, function($sheet) use($alphabet, $col_array, $col_width, $fields_array, $field_options_array, $items, $trans_array, $child_table) {
            $sheet->row(1, $col_array);
            $sheet->row(1, function($row) {
              $row->setFontWeight('bold');
            });
            $sheet->freezeFirstRow();

            $fila = 2;
            foreach($items as $item_key => $item){
                if($child_table){
                    foreach($item->$child_table as $subitem_key => $subitem){
                        $sheet->row($fila, array_merge([$item->name, $subitem_key+1], AdminList::make_fields_values($subitem, $fields_array, $field_options_array, '','excel')));
                        $fila++;
                    }
                } else {
                    $row_array = \AdminList::make_fields_values($item, $fields_array, $field_options_array, '','excel');
                    foreach($trans_array as $lang => $fields){
                        \App::setLocale($lang);
                        foreach($fields as $trans_field){
                            $row_array[] = $item->$trans_field;
                        }
                        \App::setLocale(config('solunes.main_lang'));
                    }
                    $sheet->row($fila, $row_array);
                    $fila++;
                }

            }
            if(count($col_width)>0){
                $sheet->setWidth($col_width);
            }
        });
        return $excel;
    }

    public static function generateInstructionsSheet($excel) {
        $excel->sheet('instrucciones', function($sheet) {
            $sheet->row(1, ['#', 'Instrucción o Consejo']);
            $sheet->row(1, function($row) {
              $row->setFontWeight('bold');
            });
            $sheet->freezeFirstRow();

            $consejos['1'] = 'No borrar ninguna de las páginas de esta muestra.';
            $consejos['2'] = 'No cambiar el nombre de las hojas de excel.';
            $consejos['3'] = 'Borrar el contenido actual de la hoja, fijandose el último ID de la muestra y comenzando a introducir las nuevas lineas siguiendo este ID.';
            $consejos['4'] = 'Respetar el formato de las columnas que tengan formatos especiales. (Ej: Imágenes con URL completo, Múltiples opciones divididas por ";", etc. )';
            $consejos['5'] = 'Si hay más de una hoja, es porque el elemento tiene subtablas, que también deben ser llenadas en el mismo formato.';
            $fila = 2;
            foreach($consejos as $consejo_key => $consejo){
                $sheet->row($fila, [$consejo_key, $consejo]);
                $fila++;
            }
            $sheet->setWidth(['A'=>10,'B'=>200]);
        });
        return $excel;
    }

    public static function generateColWidth($alphabet, $field, $key, $col_width = []) {
        if($field->type=='text'){
            $col_width[$alphabet[$key]] = 100;
        } else {
            $col_width[$alphabet[$key]] = 20;
        }
        return $col_width;
    }

    public static function generateAlphabet($count = NULL) {
        $letters = [];
        $letter = 'A';
        if($count){
            foreach(range(1,$count) as $key){
                $letters[] = $letter++;
            }
        } else {
            while ($letter !== 'FZ') {
                $letters[] = $letter++;
            }
        }
        return $letters;
    }

    public static function translateLocalization($languages, $item, $field, $trans_code, $trans_choice = NULL) {
        $main_lang = config('solunes.main_lang');
        foreach($languages as $lang){
            \App::setLocale($lang->code);
            if(!config('solunes.translation')||$lang->code==$main_lang||\Lang::has($trans_code)){
              if(is_int($trans_choice)){
                $translation = trans_choice($trans_code, $trans_choice);
              } else {
                $translation = trans($trans_code);
              }
            } else {
              $translation = \DataManager::generateGoogleTranslation($main_lang, $lang->code, $item->translate($main_lang)->$field);
            }
            $item->translateOrNew($lang->code)->$field = $translation;
        }
        \App::setLocale($main_lang);
        return $item;
    }

    public static function generateGoogleTranslation($source, $target, $text, $format = 'html') {
        if($source==$target){
            return $text;
        }
        $translator = new \Google\Cloud\Translate\TranslateClient(['key'=>config('services.google_cloud_api.key')]);
        $result = $translator->translate($text, ['target'=>$target,'source'=>$source,'format'=>$format]);
        if($result){
            return $result['text'];
        } else {
            return NULL;
        }
    }

}