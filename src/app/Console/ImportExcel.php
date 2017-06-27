<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class ImportExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import content from excel import file in seed folder.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->info('0%: Se comenzó a importar el excel.');
        $languages = \Solunes\Master\App\Language::get();
        \Excel::load(public_path('seed/import.xls'), function($reader) use($languages) {
            foreach($reader->get() as $sheet){
                \DataManager::importExcelRows($sheet, $languages);
            }
        });
        $this->info('100%: Se agregaron los datos del excel.');
    }
}