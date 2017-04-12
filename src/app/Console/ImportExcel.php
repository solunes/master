<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class ImportExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import content from excel import file in seed folder.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->info('0%: Se comenzó a importar el excel.');
        $languages = \Solunes\Master\App\Language::get();
        \Excel::load(public_path('seed/import.xls'), function($reader) use($languages) {
            foreach($reader->get() as $sheet){
              $sheet_model = $sheet->getTitle();
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
                $sheet->each(function($row) use ($languages, $node, $field_array, $field_sub_array, $sub_field_insert) {
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
                                $language_code = 'es';
                            } else {
                                $language_code = str_replace($field->name.'_','',$column);
                            }
                            if($field->relation&&!is_numeric($input)){
                                $sub_model = \Solunes\Master\App\Node::where('table_name', $column)->first()->model;
                                if($get_submodel = $sub_model::where('name', $value)->first()){
                                    $input = $get_submodel->id;
                                }
                            } else 
                            if($field->type=='select'||$field->type=='radio'){
                                if($subanswer = $field->field_options()->whereTranslation('label', $input)->first()){
                                    $input = $subanswer->name;
                                } else {
                                    $input = NULL;
                                }
                            } else if($field->type=='checkbox'){
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
                                            $input_array[] = \Asset::$action_name(public_path('seed/'.$node->name.'/'.$subinput), $node->name.'-'.$field->name, true);
                                        }
                                        $input = json_encode($input_array);
                                    } else {
                                        $input = \Asset::$action_name(public_path('seed/'.$node->name.'/'.$input), $node->name.'-'.$field->name, true);
                                    }
                                }
                                $item = \FuncNode::put_data_field($item, $field, $input, $language_code);
                            }
                        } else if($new_item&&isset($field_sub_array[$column])) {
                            $field = $field_sub_array[$column];
                            if($field->multiple){
                                $array_insert = [];
                                foreach(explode(';',$input) as $value){
                                    if(!is_numeric($value)){
                                        $sub_model = \Solunes\Master\App\Node::where('table_name', $column)->first()->model;
                                        array_push($array_insert, $sub_model::where('name', $value)->first()->id);
                                    } else {
                                        array_push($array_insert, $value);
                                    }
                                }
                            } else {
                                if(!is_numeric($input)){
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
            }
        });
        $this->info('100%: Se agregaron los datos del excel.');
    }
}