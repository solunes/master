<?php

namespace Solunes\Master\App\Console;

use Illuminate\Console\Command;

class GenerateNodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-nodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate node fields and relations based on schema and models.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->info('0%: Generador de Nodos iniciado.');
        $this->info('20%: Las tablas fueron limpiadas.');
        $nodes = \Solunes\Master\App\Node::get();
        $total_count = 0;
        \App::setLocale('es');
        $languages = \Solunes\Master\App\Language::get();
        $menu_dashboard = \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'permission'=>'dashboard', 'icon'=>'dashboard']);
        foreach($languages as $language){
          \App::setLocale($language->code);
          $menu_dashboard->translateOrNew($language->code)->name = trans('master::admin.dashboard');
          $menu_dashboard->translateOrNew($language->code)->link = 'admin';
        }
        \App::setLocale('es');
        $menu_dashboard->save();
        foreach($nodes as $node){
          $node = \DataManager::translateLocalization($languages, $node, 'singular', $node->lang_folder.'::model.'.$node->name, 1);
          $node = \DataManager::translateLocalization($languages, $node, 'plural', $node->lang_folder.'::model.'.$node->name, 0);
          $node->save();
          $table_name = $node->table_name;
          $columns = \Schema::getColumnListing($table_name);
          if($node->multilevel){
            $node_extra = new \Solunes\Master\App\NodeExtra;
            $node_extra->parent_id = $node->id;
            $node_extra->display = 'all';
            $node_extra->type = 'action_field';
            $node_extra->value_array = json_encode(['create-child','edit','delete']);
            $node_extra->save();
          }
          if($node->type=='field'){
            $count = 0;
            foreach($columns as $col){
                $count = \FuncNode::node_field_creation($table_name, $node, $col, 0, $count, $languages);
            }
            $total_count += $count;
          } else {
            $model = $node->model;
            $initiated_model = new $model;
            // CREAR MENU
            if($node->type=='normal'){
              \FuncNode::node_menu_creation($node, $languages);
            }
            // MENU CREADO, CREAR COLUMNAS
            $count = 0;
            foreach($columns as $col){
                if($col!='site_id'){
                    $count = \FuncNode::node_field_creation($table_name, $node, $col, 0, $count, $languages);
                }
            }
            // REVISAR SI TIENE TRADUCCION Y SI SE DEBEN CREAR ESOS CAMPOS TAMBIEN
            if(property_exists($model, 'translatedAttributes')){
              $node->translation = 1;
              $node->save();
              foreach($initiated_model->translatedAttributes as $col){
                if($col!='site_id'){
                  $count = \FuncNode::node_field_creation(str_replace('-','_',$node->name).'_translation', $node, $col, 1, $count, $languages);
                }
              }
            }
            // AGREGAR PARENT A DONDE CORRESPONDE
            if(count($node->children)>0){
              foreach($node->children as $child){
                $count++;
                $multiple = false;
                if($child->type=='field'){
                    $child_value = str_replace($node->name.'-', '', $child->name);
                    $child_value = str_replace('-'.$node->name, '', $child_value);
                    $relation = true;
                } else {
                    $child_value = $child->name;
                    $relation = false;
                }
                if($child->type=='subchild'){
                  $multiple = true;
                }
                $field = new \Solunes\Master\App\Field;
                $field->parent_id = $node->id;
                $field->name = $child->table_name;
                $field->trans_name = str_replace($node->name.'-', '', $child->table_name);
                $field->type = $child->type;
                $field->order = $count;
                $field->relation = $relation;
                $field->multiple = $multiple;
                $field->value = $child_value;
                $field->save();
              }
            }
            $total_count += $count;
          }
          $node->load('fields');
          foreach($node->fields as $field) {
            $saved = false;
            if(!$field->label){
                $field = \DataManager::translateLocalization($languages, $field, 'label', $node->lang_folder.'::fields.'.$field->trans_name);
                $saved = true;
            }
            if($saved===true){
                $field->save();
            }
          }
        }
        // Crear opciones extra del menú
        $menu_parent = \Solunes\Master\App\Menu::where('level', 1)->whereTranslation('name','Sistema')->first();
        \FuncNode::custom_menu_creation('Descargar en Masa', 'admin/export-nodes', $menu_parent, 'cloud-download');
        $menu_parent = \Solunes\Master\App\Menu::where('level', 1)->whereTranslation('name','Global')->first();
        \FuncNode::custom_menu_creation('Subir en Masa', 'admin/import-nodes', $menu_parent, 'cloud-upload');
        // Importar parametros de nodos en Excel
        $this->info('95%: Se importara el excel de nodes para corregir los campos.');
        $this->info(\FuncNode::load_nodes_excel(base_path(config('solunes.vendor_path').'/src/nodes.xlsx')));
        if(config('solunes.business')){
          $this->info(\FuncNode::load_nodes_excel(base_path(config('solunes.solunes_path').'/business/src/nodes.xlsx')));
        }
        if(config('solunes.project')){
          $this->info(\FuncNode::load_nodes_excel(base_path(config('solunes.solunes_path').'/project/src/nodes.xlsx')));
        }
        if(config('solunes.store')){
          $this->info(\FuncNode::load_nodes_excel(base_path(config('solunes.solunes_path').'/store/src/nodes.xlsx')));
        }
        if(config('solunes.pagostt')){
          $this->info(\FuncNode::load_nodes_excel(base_path(config('solunes.solunes_path').'/pagostt/src/nodes.xlsx')));
        }
        $this->info(\FuncNode::load_nodes_excel(public_path('seed/nodes.xlsx')));
        $this->info('100%: Se crearon '.$total_count.' campos.');
    }
}