<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class GenerateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera las traducciones para todos los nodos e idiomas.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        if(\App::environment('local')){
            $this->info('Comenzando la prueba.');
            $nodes = \Solunes\Master\App\Node::where('translation',1)->get();
            $main_language = \Solunes\Master\App\Language::first();
            $translation_languages = \Solunes\Master\App\Language::where('id','!=',$main_language->id)->get();
            $this->info('Se detectaron '.count($translation_languages).' lenguajes traducibles.');
            if(count($translation_languages)>0){
                foreach($nodes as $node){
                    $node_label = iconv('UTF-8','ASCII//TRANSLIT',$node->singular);
                    $this->info('Comenzando nodo: '.$node_label.'.');
                    $fields = $node->fields()->where('translation',1)->get();
                    $items = \FuncNode::node_check_model($node);
                    $items = $items->get();
                    $this->info('Comenzando traduccion para: '.count($items).' items y '.count($fields).' campos traducibles.');
                    foreach($items as $item){
                        foreach($translation_languages as $lang){
                            if($item->hasTranslation($lang->code)){
                                foreach($fields as $field){
                                    $field_name = $field->name;
                                    $item_val = $item->translate($main_language->code)->$field_name;
                                    if($item_val&&!$item->translate($lang->code)->$field_name){
                                        $this->info('Sin traduccion: '.$node_label.' - '.$field_name.' en '.$lang->name.'.');
                                        $request = \DataManager::generateGoogleTranslation($main_language->code,$lang->code,$item_val);
                                        $item->translateOrNew($lang->code)->$field_name = $request;
                                    }
                                }
                            } else {
                                foreach($fields as $field){
                                    $field_name = $field->name;
                                    $item_val = $item->translate($main_language->code)->$field_name;
                                    if($item_val){
                                        $this->info('Sin traduccion: '.$node_label.' - '.$field_name.' en '.$lang->name.'.');
                                        $request = \DataManager::generateGoogleTranslation($main_language->code,$lang->code,$item_val);
                                        $item->translateOrNew($lang->code)->$field_name = $request;
                                    }
                                }
                            }
                            $item->save();
                        }
                    }
                }
            } else {
                $this->info('No se tienen otros lenguajes para traducir.');
            }
            $this->info('Finalizando la traduccion.');
        } else {
            $this->info('No autorizado.');
        }
    }
}
