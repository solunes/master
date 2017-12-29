<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset migration, migrate, seed and run other initial tasks.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        if(\App::environment('local')){
            $this->callSilent('down');
            $this->info('0%: Deploy iniciado. Modo Mantenimiento iniciado');
            if(config('solunes.before_migrate')){
                $this->info('10%: '.\CustomFunc::before_migrate_actions());
            }
            $this->callSilent('migrate:reset');
            $this->info('20%: Reset migrate ejecutado correctamente.');
            $this->callSilent('migrate', ['--path'=>'/'.config('solunes.vendor_path').'/src/database/migrations']);
            if(config('solunes.business')){
                $this->callSilent('migrate', ['--path'=>'/'.config('solunes.solunes_path').'/business/src/database/migrations']);
            }
            if(config('solunes.project')){
                $this->callSilent('migrate', ['--path'=>'/'.config('solunes.solunes_path').'/project/src/database/migrations']);
            }
            if(config('solunes.store')){
                $this->callSilent('migrate', ['--path'=>'/'.config('solunes.solunes_path').'/store/src/database/migrations']);
            }
            if(config('solunes.pagostt')){
                $this->callSilent('migrate', ['--path'=>'/'.config('solunes.solunes_path').'/pagostt/src/database/migrations']);
            }
            $this->callSilent('migrate', ['--path'=>'/database/migrations']);
            $this->info('60%: Migrate ejecutado correctamente.');
            if(config('solunes.after_migrate')){
                $this->info('70%: '.\CustomFunc::after_migrate_actions());
            }
            $this->callSilent('seed');
            $this->info('75%: Database seed ejecutado correctamente con nodos.');
            $this->info('100%: Deploy finalizado.');
            $this->callSilent('up');
        } else {
            $this->info('No autorizado.');
        }
    }
}