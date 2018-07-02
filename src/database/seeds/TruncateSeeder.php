<?php

namespace Solunes\Master\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;

class TruncateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
        // Nodos Globales
        \Solunes\Master\App\ImageSize::truncate();
        \Solunes\Master\App\ImageFolder::truncate();
        \Solunes\Master\App\TempFile::truncate();
        \Solunes\Master\App\VariableTranslation::truncate();
        \Solunes\Master\App\Variable::truncate();
        \Solunes\Master\App\InboxUser::truncate();
        \Solunes\Master\App\InboxMessage::truncate();
        \Solunes\Master\App\Inbox::truncate();
        \Solunes\Master\App\NotificationMessage::truncate();
        \Solunes\Master\App\Notification::truncate();
        \Solunes\Master\App\Activity::truncate();
        \Solunes\Master\App\EmailTranslation::truncate();
        \Solunes\Master\App\Email::truncate();
        if(config('solunes.alerts')){
            \Solunes\Master\App\AlertUser::truncate();
            \Solunes\Master\App\AlertConditional::truncate();
            \Solunes\Master\App\AlertAction::truncate();
            \Solunes\Master\App\Alert::truncate();
        }
        // Usuarios
        DB::table('permission_role')->truncate();
        DB::table('role_user')->truncate();  
        \Solunes\Master\App\Role::truncate();
        \Solunes\Master\App\Permission::truncate();
        \App\User::truncate();
        if(config('solunes.indicators')){
            \Solunes\Master\App\IndicatorUser::truncate();
            \Solunes\Master\App\Indicator::truncate();
        }
        // Nodos de Sistema
        \Solunes\Master\App\FieldOptionTranslation::truncate();
        \Solunes\Master\App\FieldOption::truncate();
        \Solunes\Master\App\FieldConditional::truncate();
        \Solunes\Master\App\FieldExtra::truncate();
        \Solunes\Master\App\FieldRelation::truncate();
        \Solunes\Master\App\FieldTranslation::truncate();
        \Solunes\Master\App\Field::truncate();
        \Solunes\Master\App\Filter::truncate();
        \Solunes\Master\App\NodeExtra::truncate();
        \Solunes\Master\App\NodeTranslation::truncate();
        \Solunes\Master\App\Node::truncate();
        \Solunes\Master\App\MenuTranslation::truncate();
        \Solunes\Master\App\Menu::truncate();
        \Solunes\Master\App\PageTranslation::truncate();
        \Solunes\Master\App\Page::truncate();
        \Solunes\Master\App\SiteTranslation::truncate();
        \Solunes\Master\App\Site::truncate();
        \Solunes\Master\App\Language::truncate();
        \App\PasswordReminder::truncate();

    }
}