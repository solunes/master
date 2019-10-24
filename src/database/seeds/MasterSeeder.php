<?php

namespace Solunes\Master\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;

class MasterSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // General
        \Solunes\Master\App\Language::create([
            'code' => 'es',
            'name' => 'Español',
            'image' => 'es.png'
        ]);
        \Solunes\Master\App\Site::create([
            'name' => 'Plataforma',
            'domain' => 'http://master.test/',
            'root' => '/',
            'google_verification' => '',
            'analytics' => ''
        ]);

        // Master Nodes
        $node_node = \Solunes\Master\App\Node::create(['name'=>'node', 'folder'=>'system']);
        $node_node_extras = \Solunes\Master\App\Node::create(['name'=>'node-extra', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_node->id]);
        $node_filter = \Solunes\Master\App\Node::create(['name'=>'filter', 'folder'=>'system']);
        $node_field = \Solunes\Master\App\Node::create(['name'=>'field', 'type'=>'child', 'location'=>'package', 'parent_id'=>$node_node->id]);
        $node_field_extras = \Solunes\Master\App\Node::create(['name'=>'field-extra', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_field->id]);
        $node_field_relation = \Solunes\Master\App\Node::create(['name'=>'field-relation', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_field->id]);
        $node_field_conditional = \Solunes\Master\App\Node::create(['name'=>'field-conditional', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_field->id]);
        $node_field_option = \Solunes\Master\App\Node::create(['name'=>'field-option', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_field->id]);
        if(config('solunes.indicators')){
            $node_indicator = \Solunes\Master\App\Node::create(['name'=>'indicator', 'folder'=>'global']);
            \Solunes\Master\App\Node::create(['name'=>'indicator-user', 'type'=>'child', 'location'=>'package', 'parent_id'=>$node_indicator->id]);
        }
        $node_site = \Solunes\Master\App\Node::create(['name'=>'site', 'folder'=>'global']);
        $node_page = \Solunes\Master\App\Node::create(['name'=>'page', 'folder'=>'global']);
        $node_menu = \Solunes\Master\App\Node::create(['name'=>'menu', 'folder'=>'global', 'multilevel'=>true]);

        // Users
        $node_permission = \Solunes\Master\App\Node::create(['name'=>'permission', 'folder'=>'system']);
        $node_role = \Solunes\Master\App\Node::create(['name'=>'role', 'folder'=>'system']);
        $node_permission_role = \Solunes\Master\App\Node::create(['name'=>'permission-role', 'table_name'=>'permission_role', 'location'=>'package', 'type'=>'field', 'model'=>'\Solunes\Master\App\Permission', 'parent_id'=>$node_role->id]);
        $node_user = \Solunes\Master\App\Node::create(['name'=>'user', 'location'=>'app', 'folder'=>'user']);
        \Solunes\Master\App\Filter::create(['node_id'=>$node_user->id, 'parameter'=>'status']);
        $node_role_user = \Solunes\Master\App\Node::create(['name'=>'role-user', 'table_name'=>'role_user', 'location'=>'package', 'type'=>'field', 'model'=>'\Solunes\Master\App\Role', 'parent_id'=>$node_user->id]);

        // Normal Nodes
        $node_trigger = \Solunes\Master\App\Node::create(['name'=>'trigger', 'folder'=>'global']);
        if(config('solunes.alerts')){
            $node_alert = \Solunes\Master\App\Node::create(['name'=>'alert', 'folder'=>'global']);
            \Solunes\Master\App\Node::create(['name'=>'alert-action', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_alert->id]);
            \Solunes\Master\App\Node::create(['name'=>'alert-conditional', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_alert->id]);
            \Solunes\Master\App\Node::create(['name'=>'alert-user', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_alert->id]);
        }
        $node_email = \Solunes\Master\App\Node::create(['name'=>'email', 'folder'=>'global']);
        $node_activity = \Solunes\Master\App\Node::create(['name'=>'activity', 'table_name'=>'activities', 'folder'=>'system']);
        $node_notification = \Solunes\Master\App\Node::create(['name'=>'notification', 'folder'=>'system']);
        \Solunes\Master\App\Node::create(['name'=>'notification-message', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_notification->id]);
        $node_inbox = \Solunes\Master\App\Node::create(['name'=>'inbox', 'table_name'=>'inbox', 'folder'=>'system']);
        \Solunes\Master\App\Node::create(['name'=>'inbox-user', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_inbox->id]);
        \Solunes\Master\App\Node::create(['name'=>'inbox-message', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_inbox->id]);
        $node_variable = \Solunes\Master\App\Node::create(['name'=>'variable', 'folder'=>'global']);
        $node_image_folder = \Solunes\Master\App\Node::create(['name'=>'image-folder', 'folder'=>'system']);
        \Solunes\Master\App\Node::create(['name'=>'image-size', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_image_folder->id]);
        $node_temp_file = \Solunes\Master\App\Node::create(['name'=>'temp-file', 'folder'=>'system']);
        $node_unique_check = \Solunes\Master\App\Node::create(['name'=>'unique-check', 'folder'=>'system']);

        $image_folder = \Solunes\Master\App\ImageFolder::create(['site_id'=>1,'name'=>'upload','extension'=>'jpg']);
        \Solunes\Master\App\ImageSize::create(['parent_id'=>$image_folder->id,'code'=>'normal','type'=>'resize','width'=>1000,'height'=>NULL]);

        // Customer Dashboard
        if(config('solunes.customer')){
            \Solunes\Master\App\Menu::create(['menu_type'=>'customer','icon'=>'table','name'=>'Pagos Pendientes','link'=>'account/my-payments/1354351278','order'=>5]);
            \Solunes\Master\App\Menu::create(['menu_type'=>'customer','icon'=>'table','name'=>'Historial de Pagos','link'=>'account/my-history/1354351278','order'=>5]);
            if(config('customer.subscriptions')){
                \Solunes\Master\App\Menu::create(['menu_type'=>'customer','icon'=>'table','name'=>'Suscripciones','link'=>'account/subscriptions/0/1354351278','order'=>5]);
                \Solunes\Master\App\Menu::create(['menu_type'=>'customer','icon'=>'table','name'=>'Mis Suscripciones','link'=>'account/my-subscriptions/1354351278','order'=>5]);
            }
        }

        // Creación de Permisos y Rangos
        $admin = \Solunes\Master\App\Role::create(['name'=>'admin', 'display_name'=>'Admin']);
        $member = \Solunes\Master\App\Role::create(['name'=>'member', 'display_name'=>'Miembro']);
        $system_perm = \Solunes\Master\App\Permission::create(['name'=>'system', 'display_name'=>'Sistema']);
        $global_perm = \Solunes\Master\App\Permission::create(['name'=>'global', 'display_name'=>'Global']);
        $user_perm = \Solunes\Master\App\Permission::create(['name'=>'user', 'display_name'=>'Usuarios']);
        $site_perm = \Solunes\Master\App\Permission::create(['name'=>'site', 'display_name'=>'Sitio']);
        $form_perm = \Solunes\Master\App\Permission::create(['name'=>'form', 'display_name'=>'Formulario']);
        $dashboard_perm = \Solunes\Master\App\Permission::create(['name'=>'dashboard', 'display_name'=>'Dashboard']);
        $admin->permission_role()->sync([$global_perm->id, $site_perm->id, $user_perm->id, $form_perm->id, $dashboard_perm->id]);
        
        // Tamaños de archivos
        \Solunes\Master\App\Variable::create([
            'name' => 'image_size',
            'type' => 'string',
            config('solunes.main_lang') => ['value'=>'5'],
        ]);
        \Solunes\Master\App\Variable::create([
            'name' => 'file_size',
            'type' => 'string',
            config('solunes.main_lang') => ['value'=>'10'],
        ]);
        \Solunes\Master\App\Variable::create([
            'name' => 'image_extension',
            'type' => 'string',
            config('solunes.main_lang') => ['value'=>'jpg,jpeg,png,gif'],
        ]);
        \Solunes\Master\App\Variable::create([
            'name' => 'file_extension',
            'type' => 'string',
            config('solunes.main_lang') => ['value'=>'doc,docx,xls,xlsx,ppt,pptx,pdf,txt,jpg,jpeg,png,gif'],
        ]);

        if(config('solunes.indicators')&&$user_node = \Solunes\Master\App\Node::where('name', 'user')->first()){
            \Solunes\Master\App\Indicator::create([
                'node_id' => $user_node->id,
                'name' => 'Cantidad de Usuarios',
                'user_id' => config('solunes.master_admin_id'),
                'filter_query' => json_encode([]),
            ]);
        }
    }
}