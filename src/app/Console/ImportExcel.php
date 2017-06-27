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
                \DataManager::importExcelRows($sheet, $languages, $node, $field_array, $field_sub_array, $sub_field_insert);
              }
            }
        });
        $this->info('100%: Se agregaron los datos del excel.');
    }
}