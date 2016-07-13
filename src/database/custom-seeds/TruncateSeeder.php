<?php

use Illuminate\Database\Seeder;

class TruncateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Nodes\Contact::truncate();
        \App\Nodes\FormContact::truncate();
        \App\Nodes\Sponsor::truncate();
        \App\Nodes\Agenda::truncate();
        \App\Nodes\Banner::truncate();
        \App\Nodes\ContentTranslation::truncate();
        \App\Nodes\Content::truncate();
        \App\Nodes\TitleTranslation::truncate();
        \App\Nodes\Title::truncate();
        \App\Nodes\Deadline::truncate();
        \App\Nodes\PostulationB::truncate();
        \App\Nodes\PostulationA::truncate();
        \App\Nodes\RegistryB::truncate();
        \App\Nodes\RegistryA::truncate();
        \App\SocialNetwork::truncate();

    }
}