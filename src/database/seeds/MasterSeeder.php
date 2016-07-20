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

        // Nodos
        $node_node = \Solunes\Master\App\Node::create(['name'=>'node', 'folder'=>'system']);
        $node_node_requests = \Solunes\Master\App\Node::create(['name'=>'node-request', 'location'=>'package', 'type'=>'subchild', 'parent_id'=>$node_node->id]);
        $node_node_extras = \Solunes\Master\App\Node::create(['name'=>'node-extra', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_node->id]);
        $node_field = \Solunes\Master\App\Node::create(['name'=>'field', 'type'=>'child', 'location'=>'package', 'parent_id'=>$node_node->id]);
        $node_field_extras = \Solunes\Master\App\Node::create(['name'=>'field-extra', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_field->id]);
        $node_field_conditional = \Solunes\Master\App\Node::create(['name'=>'field-conditional', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_field->id]);
        $node_site = \Solunes\Master\App\Node::create(['name'=>'site', 'folder'=>'global']);
        $node_page = \Solunes\Master\App\Node::create(['name'=>'page', 'folder'=>'global']);
        $node_menu = \Solunes\Master\App\Node::create(['name'=>'menu', 'folder'=>'global']);
        $node_section = \Solunes\Master\App\Node::create(['name'=>'section', 'folder'=>'global']);
        $node_permission = \Solunes\Master\App\Node::create(['name'=>'permission', 'folder'=>'system']);
        $node_role = \Solunes\Master\App\Node::create(['name'=>'role', 'folder'=>'system']);
        $node_permission_role = \Solunes\Master\App\Node::create(['name'=>'permission-role', 'table_name'=>'permission_role', 'type'=>'field', 'model'=>'\Solunes\Master\App\Permission', 'parent_id'=>$node_role->id]);
        $node_user = \Solunes\Master\App\Node::create(['name'=>'user', 'location'=>'app', 'folder'=>'global']);
        \Solunes\Master\App\NodeExtra::create(['parent_id'=>$node_user->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'field', 'value_array'=>json_encode(['status'])]);
        $node_role_user = \Solunes\Master\App\Node::create(['name'=>'role-user', 'table_name'=>'role_user', 'type'=>'field', 'model'=>'\Solunes\Master\App\Role', 'parent_id'=>$node_user->id]);
        $node_email = \Solunes\Master\App\Node::create(['name'=>'email', 'folder'=>'global']);
        $node_activity = \Solunes\Master\App\Node::create(['name'=>'activity', 'table_name'=>'activities', 'folder'=>'system']);
        $node_notification = \Solunes\Master\App\Node::create(['name'=>'notification', 'folder'=>'system']);
        $node_variable = \Solunes\Master\App\Node::create(['name'=>'variable', 'folder'=>'global']);
        $node_image_folder = \Solunes\Master\App\Node::create(['name'=>'image-folder', 'folder'=>'system']);
        $node_image_size = \Solunes\Master\App\Node::create(['name'=>'image-size', 'type'=>'subchild', 'location'=>'package', 'parent_id'=>$node_image_folder->id]);
        $node_temp_file = \Solunes\Master\App\Node::create(['name'=>'temp-file', 'folder'=>'system']);

        // Usuarios
        $superadmin = \Solunes\Master\App\Role::create(['name'=>'superadmin', 'display_name'=>'Super Admin']);
        $admin = \Solunes\Master\App\Role::create(['name'=>'admin', 'display_name'=>'Admin']);
        $member = \Solunes\Master\App\Role::create(['name'=>'member', 'display_name'=>'Miembro']);
        $system_perm = \Solunes\Master\App\Permission::create(['name'=>'system', 'display_name'=>'Sistema']);
        $global_perm = \Solunes\Master\App\Permission::create(['name'=>'global', 'display_name'=>'Global']);
        $site_perm = \Solunes\Master\App\Permission::create(['name'=>'site', 'display_name'=>'Site']);
        $form_perm = \Solunes\Master\App\Permission::create(['name'=>'form', 'display_name'=>'Formulario']);
        $dashboard_perm = \Solunes\Master\App\Permission::create(['name'=>'dashboard', 'display_name'=>'Dashboard']);
        $admin->permission_role()->sync([$global_perm->id, $site_perm->id, $form_perm->id, $dashboard_perm->id]);

    }
}