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
            $this->info('Node Translation. Model:');
            foreach(\Solunes\Master\App\NodeTranslation::where('singular', 'like', '%model.%')->groupBy('singular')->orderBy('singular')->get() as $item){
                $this->info($item->singular);
            }
            $this->info('Field Translation. Field:');
            foreach(\Solunes\Master\App\FieldTranslation::where('label', 'like', '%fields.%')->groupBy('label')->orderBy('label')->get() as $item){
                $this->info($item->label);
            }
            $this->info('Field Option Translation. Admin:');
            foreach(\Solunes\Master\App\FieldOptionTranslation::where('label', 'like', '%admin.%')->groupBy('label')->orderBy('label')->get() as $item){
                $this->info($item->label);
            }
            $this->info('Finalizaron las pruebas.');
        } else {
            $this->info('No autorizado.');
        }
    }
}
