<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class EmptyStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'empty:storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty the storage of the site.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $directories = \Storage::directories();
        foreach($directories as $directory){
            \Storage::deleteDirectory($directory);
        }
        $this->info(count($directories).' directorios eliminados.');
    }
}
