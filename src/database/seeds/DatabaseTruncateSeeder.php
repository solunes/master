<?php

namespace Solunes\Master\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use DB;

class DatabaseTruncateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(TruncateSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::reguard();
	}
}
