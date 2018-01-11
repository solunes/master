<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty storage and seed again.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        if(\App::environment('local')){
            $this->callSilent('down');
            $time_start = microtime(true); 
            $this->info('0%: Seed iniciado.');
            $this->callSilent('empty:storage');
            $this->info('20%: Storage limpiado correctamente.');
            $this->callSilent('db:seed', ['--class'=>'DatabaseTruncateSeeder']);
            if(config('solunes.pagostt')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Pagostt\Database\Seeds\DatabaseTruncateSeeder']);
            }
            if(config('solunes.store')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Store\Database\Seeds\DatabaseTruncateSeeder']);
            }
            if(config('solunes.inventory')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Inventory\Database\Seeds\DatabaseTruncateSeeder']);
            }
            if(config('solunes.sales')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Sales\Database\Seeds\DatabaseTruncateSeeder']);
            }
            if(config('solunes.project')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Project\Database\Seeds\DatabaseTruncateSeeder']);
            }
            if(config('solunes.business')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Business\Database\Seeds\DatabaseTruncateSeeder']);
            }
            $this->callSilent('db:seed', ['--class'=>'\Solunes\Master\Database\Seeds\DatabaseTruncateSeeder']);
            $this->callSilent('db:seed', ['--class'=>'\Solunes\Master\Database\Seeds\DatabaseMasterSeeder']);
            if(config('solunes.business')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Business\Database\Seeds\DatabaseMasterSeeder']);
            }
            if(config('solunes.project')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Project\Database\Seeds\DatabaseMasterSeeder']);
            }
            if(config('solunes.sales')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Sales\Database\Seeds\DatabaseMasterSeeder']);
            }
            if(config('solunes.inventory')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Inventory\Database\Seeds\DatabaseMasterSeeder']);
            }
            if(config('solunes.store')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Store\Database\Seeds\DatabaseMasterSeeder']);
            }
            if(config('solunes.pagostt')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Pagostt\Database\Seeds\DatabaseMasterSeeder']);
            }
            $this->callSilent('db:seed', ['--class'=>'DatabaseMasterSeeder']);
            $this->info('50%: Base de datos llenada correctamente.');
            $this->callSilent('generate-nodes');
            $this->info('70%: Campos de nodos creados correctamente.');
            if(config('solunes.before_seed')){
                $this->info('80%: '.\CustomFunc::before_seed_actions());
            }
            $this->callSilent('import-excel');
            $this->info('95%: Campos de nodos creados correctamente.');
            if(config('solunes.business')&&config('business.after_seed')){
                $this->info('95%: '.\CustomBusiness::after_seed_actions());
            }
            if(config('solunes.project')&&config('project.after_seed')){
                $this->info('96%: '.\CustomProject::after_seed_actions());
            }
            if(config('solunes.store')&&config('store.after_seed')){
                $this->info('97%: '.\CustomStore::after_seed_actions());
            }
            if(config('solunes.after_seed')){
                $this->info('98%: '.\CustomFunc::after_seed_actions());
            }
            $this->info('100%: Seed finalizado.');
            $this->info('Total execution time in seconds: ' . (microtime(true) - $time_start));
            $this->callSilent('up');
        } else {
            $this->info('No autorizado.');
        }
    }
}