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
        $this->info('0%: Deploy iniciado.');
        $this->callSilent('migrate:reset');
        $this->info('20%: Reset migrate ejecutado correctamente.');
        $this->callSilent('migrate', ['--path'=>'/vendor/solunes/master/src/database/migrations']);
        $this->callSilent('migrate', ['--path'=>'/database/migrations']);
        $this->info('60%: Migrate ejecutado correctamente.');
        $this->callSilent('seed');
        $this->info('75%: Database seed ejecutado correctamente con nodos.');
        $this->info('100%: Deploy finalizado.');
    }
}
