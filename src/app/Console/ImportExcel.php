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
        \Excel::load(public_path('seed/import.xls'), function($reader) {
            foreach($reader->get() as $sheet){
                $sheet_model = $sheet->getTitle();
                $node = \Solunes\Master\App\Node::where('name', $sheet_model)->first();
                $model = $node->model;
                $sheet->each(function($row) use ($node, $model) {
                    if($row->id){
                        if(!$item = $model::where('id', $row->id)->first()){
                            $item = new $model;
                        }
                        foreach($node->fields()->whereNotIn('type', ['child','subchild','field'])->get() as $field){
                            $field_name = $field->name;
                            $input = $row->$field_name;
                            if($field->type=='image'||$field->type=='file'){
                                $action_name = 'upload_'.$field->type;
                                $input = \Asset::$action_name(public_path('seed/'.$node->name.'/'.$input), $node->name.'-'.$field->name, true);
                            }
                            $item = \FuncNode::put_data_field($item, $field, $input);
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
