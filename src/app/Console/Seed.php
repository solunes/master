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
            $time_start = microtime(true); 
            $this->info('0%: Seed iniciado.');
            $this->callSilent('empty:storage');
            $this->info('20%: Storage limpiado correctamente.');
            $this->callSilent('db:seed', ['--class'=>'DatabaseTruncateSeeder']);
            if(config('solunes.store')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Store\Database\Seeds\DatabaseTruncateSeeder']);
            }
            $this->callSilent('db:seed', ['--class'=>'\Solunes\Master\Database\Seeds\DatabaseTruncateSeeder']);
            $this->callSilent('db:seed', ['--class'=>'\Solunes\Master\Database\Seeds\DatabaseMasterSeeder']);
            if(config('solunes.store')){
                $this->callSilent('db:seed', ['--class'=>'\Solunes\Store\Database\Seeds\DatabaseMasterSeeder']);
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
            if(config('solunes.store')&&config('store.after_seed')){
                $this->info('96%: '.\CustomStore::after_seed_actions());
            }
            if(config('solunes.after_seed')){
                $this->info('98%: '.\CustomFunc::after_seed_actions());
            }
            $this->info('100%: Seed finalizado.');
            $this->info('Total execution time in seconds: ' . (microtime(true) - $time_start));
        } else {
            $this->info('No autorizado.');
        }
    }
}