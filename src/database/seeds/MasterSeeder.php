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
        $node_node = \Solunes\Master\App\Node::create(['name'=>'node', 'type'=>'system']);
        $node_node_requests = \Solunes\Master\App\Node::create(['name'=>'node-request', 'table_name'=>'node_requests', 'type'=>'subchild', 'model'=>'\Solunes\Master\App\NodeRequest', 'parent_id'=>$node_node->id]);
        $node_node_extras = \Solunes\Master\App\Node::create(['name'=>'node-extra', 'table_name'=>'node_extras', 'type'=>'subchild', 'model'=>'\Solunes\Master\App\NodeExtra', 'parent_id'=>$node_node->id]);
        $node_field = \Solunes\Master\App\Node::create(['name'=>'field', 'type'=>'child', 'model'=>'\Solunes\Master\App\Field', 'parent_id'=>$node_node->id]);
        $node_field_extras = \Solunes\Master\App\Node::create(['name'=>'field-extra', 'table_name'=>'field_extras', 'type'=>'subchild', 'model'=>'\Solunes\Master\App\FieldExtra', 'parent_id'=>$node_field->id]);
        $node_field_conditional = \Solunes\Master\App\Node::create(['name'=>'field-conditional', 'table_name'=>'field_conditionals', 'type'=>'subchild', 'model'=>'\Solunes\Master\App\FieldConditional', 'parent_id'=>$node_field->id]);
        $node_site = \Solunes\Master\App\Node::create(['name'=>'site', 'type'=>'global']);
        $node_page = \Solunes\Master\App\Node::create(['name'=>'page', 'type'=>'global']);
        $node_menu = \Solunes\Master\App\Node::create(['name'=>'menu', 'type'=>'global']);
        $node_section = \Solunes\Master\App\Node::create(['name'=>'section', 'type'=>'global']);
        $node_permission = \Solunes\Master\App\Node::create(['name'=>'permission', 'type'=>'system']);
        $node_role = \Solunes\Master\App\Node::create(['name'=>'role', 'type'=>'system']);
        $node_permission_role = \Solunes\Master\App\Node::create(['name'=>'permission-role', 'table_name'=>'permission_role', 'type'=>'field', 'model'=>'\Solunes\Master\App\Permission', 'parent_id'=>$node_role->id]);
        $node_user = \Solunes\Master\App\Node::create(['name'=>'user', 'location'=>'app', 'type'=>'global', 'model'=>'\App\User']);
        \Solunes\Master\App\NodeExtra::create(['parent_id'=>$node_user->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'field', 'value_array'=>json_encode(['status'])]);
        $node_role_user = \Solunes\Master\App\Node::create(['name'=>'role-user', 'table_name'=>'role_user', 'type'=>'field', 'model'=>'\Solunes\Master\App\Role', 'parent_id'=>$node_user->id]);
        $node_activity = \Solunes\Master\App\Node::create(['name'=>'activity', 'table_name'=>'activities', 'type'=>'system']);
        $node_notification = \Solunes\Master\App\Node::create(['name'=>'notification', 'type'=>'system']);
        $node_variable = \Solunes\Master\App\Node::create(['name'=>'variable', 'type'=>'global']);
        $node_image_folder = \Solunes\Master\App\Node::create(['name'=>'image-folder', 'table_name'=>'image_folders', 'type'=>'system']);
        $node_image_size = \Solunes\Master\App\Node::create(['name'=>'image-size', 'table_name'=>'image_sizes', 'type'=>'subchild', 'model'=>'\Solunes\Master\App\ImageSize', 'parent_id'=>$node_image_folder->id]);
        $node_temp_file = \Solunes\Master\App\Node::create(['name'=>'temp-file', 'table_name'=>'temp_files', 'type'=>'system']);

        // Usuarios
        $superadmin = \Solunes\Master\App\Role::create(['name'=>'superadmin', 'display_name'=>'Super Admin']);
        $admin = \Solunes\Master\App\Role::create(['name'=>'admin', 'display_name'=>'Admin']);
        $member = \Solunes\Master\App\Role::create(['name'=>'member', 'display_name'=>'Miembro']);
        $system_perm = \Solunes\Master\App\Permission::create(['name'=>'system', 'display_name'=>'Sistema']);
        $global_perm = \Solunes\Master\App\Permission::create(['name'=>'global', 'display_name'=>'Global']);
        $admin_perm = \Solunes\Master\App\Permission::create(['name'=>'admin', 'display_name'=>'Admin']);
        $form_perm = \Solunes\Master\App\Permission::create(['name'=>'form', 'display_name'=>'Formulario']);
        $dashboard_perm = \Solunes\Master\App\Permission::create(['name'=>'dashboard', 'display_name'=>'Dashboard']);
        $superadmin->attachPermissions([$system_perm, $global_perm, $admin_perm, $form_perm, $dashboard_perm]);
        $admin->attachPermissions([$global_perm, $admin_perm, $form_perm, $dashboard_perm]);

    }
}