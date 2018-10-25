<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class BackupSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup-save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database and files backup.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        if(\App::environment('local')||config('solunes.enable_backup')){
            if(config('solunes.enable_solunes_defaults')){
                $slug_file = 'bu-'.\FuncNode::slugify(config('solunes.app_name'));
                config(['laravel-backup.backup.name' => $slug_file]);
                config(['laravel-backup.backup.source.files.include' => [base_path('public/storage'),]]);
                config(['laravel-backup.backup.source.files.exclude' => [base_path('public/storage/'.$slug_file),]]);
                config(['laravel-backup.backup.source.databases' => ['mysql']]);
                \Log::info(base_path('public/storage'));
                \Log::info(base_path('public/storage/'.$slug_file));
            }
            if(config('solunes.enable_backup_files')){
                $this->info('Iniciando backup de base de datos y archivos.');
                $this->call('backup:run');
            } else {
                $this->info('Iniciando backup de base de datos, sin archivos.');
                $this->call('backup:run', ['--only-db'=>true]);
            }
        } else {
            $this->info('No autorizado.');
        }
    }
}