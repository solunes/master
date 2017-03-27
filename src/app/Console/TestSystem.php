<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class TestSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa el sistema.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        if(\App::environment('local')){
            $this->info('Comenzando la prueba.');
            $items = \Solunes\Master\App\NodeTranslation::where('singular', 'like', '%model.%')->groupBy('singular')->orderBy('singular')->get();
            if(count($items)>0){
                $this->info('Node Translation. Model:');
                foreach($items as $item){
                    $this->info($item->singular);
                }
            }
            $items = \Solunes\Master\App\FieldTranslation::where('label', 'like', '%fields.%')->groupBy('label')->orderBy('label')->get();
            if(count($items)>0){
                $this->info('Field Translation. Field:');
                foreach($items as $item){
                    $this->info($item->label);
                }
            }
            $items = \Solunes\Master\App\FieldOptionTranslation::where('label', 'like', '%admin.%')->groupBy('label')->orderBy('label')->get();
            if(count($items)>0){
                $this->info('Field Option Translation. Admin:');
                foreach($items as $item){
                    $this->info($item->label);
                }
            }
            $items = \Solunes\Master\App\MenuTranslation::where('name', 'like', '%admin.%')->groupBy('name')->orderBy('name')->get();
            if(count($items)>0){
                $this->info('Menu Translation. Admin:');
                foreach($items as $item){
                    $this->info(str_replace('master::admin.', '', $item->name));
                }
            }
            $this->info('Finalizaron las pruebas.');
        } else {
            $this->info('No autorizado.');
        }
    }
}
