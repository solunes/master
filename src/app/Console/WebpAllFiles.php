<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class WebpAllFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webp-all-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all files in storage to webp';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        if(config('solunes.storage_webp_enable')&&(config('solunes.storage_webp_mass_convert')||\App::environment('local'))){
            $files = \Storage::allFiles();
            $count = 0;
            foreach($files as $file){
                if(strpos($file, '.webp') === false){
                    $this->info('Convirtiendo a webp: '.$file);
                    $final_path = \Asset::get_webp_image_path($file);
                    $count++;
                }
            }
            $this->info('Finalizado. Archivos: '.$count);
        } else {
            $this->info('No autorizado.');
        }
    }
}