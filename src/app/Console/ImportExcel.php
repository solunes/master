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
                $node = \Solunes\Master\App\Node::where('name', $sheet_model)->first();
                $sheet->each(function($row) use ($languages, $node) {
                    if($row->id){
                        $model = \FuncNode::node_check_model($node);
                        if(!$item = $model->where('id', $row->id)->first()){
                            $item = $model;
                        }
                        foreach($languages as $language){
                            foreach($node->fields()->whereNotIn('type', ['child','subchild','field'])->get() as $field){
                                $field_name = $field->name;
                                if($language->id>1){
                                    $field_name = $field_name.'_'.$language->code;
                                }
                                if($language->id==1||$field->translation==1){
                                    $input = $row->$field_name;
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
                                    if($input){
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
                                        $item = \FuncNode::put_data_field($item, $field, $input, $language->code);
                                    }
                                }
                            }
                        }
                        $item->save();
                        foreach($node->fields()->where('type', 'field')->get() as $field){
                            $field_name = $field->name;
                            $input = $row->$field_name;
                            if($field->multiple){
                                foreach(explode(';',$input) as $value){
                                    $array_insert = [];
                                    if(!is_numeric($value)){
                                        $sub_model = \Solunes\Master\App\Node::where('table_name', $field_name)->first()->model;
                                        array_push($array_insert, $sub_model::where('name', $value)->first()->id);
                                    } else {
                                        array_push($array_insert, $value);
                                    }
                                }
                                $item->$field_name()->sync($array_insert);
                            } else {
                                if(!is_numeric($input)){
                                    $sub_model = \Solunes\Master\App\Node::where('table_name', $field_name)->first()->model;
                                    $array_insert = $sub_model::where('name', $input)->first()->id;
                                } else {
                                    $array_insert = $input;
                                }
                                $item->$field_name()->sync([$array_insert]);
                            }
                        }
                    }
                });
            }
        });
        $this->info('100%: Se agregaron los datos del excel.');
    }
}