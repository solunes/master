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
        \Solunes\Master\App\Menu::create(['menu_type'=>'admin', 'icon'=>'dashboard', 'es'=>['name'=>'Dashboard', 'link'=>'admin']]);
        foreach($nodes as $node){
          $table_name = $node->table_name;
          $columns = \Schema::getColumnListing($table_name);
          if($node->type=='field'){
            $count = 0;
            foreach($columns as $col){
                $count = \FuncNode::node_field_creation($table_name, $node, $col, 0, $count);
            }
            $total_count += $count;
          } else {
            $model = $node->model;
            $initiated_model = new $model;
            // CREAR MENU
            \FuncNode::node_menu_creation($node);
            // MENU CREADO, CREAR COLUMNAS
            $count = 0;
            foreach($columns as $col){
                if($col!='site_id'){
                    $count = \FuncNode::node_field_creation($table_name, $node, $col, 0, $count);
                }
            }
            // REVISAR SI TIENE TRADUCCION Y SI SE DEBEN CREAR ESOS CAMPOS TAMBIEN
            if(property_exists($model, 'translatedAttributes')){
              $node->translation = 1;
              $node->save();
              foreach($initiated_model->translatedAttributes as $col){
                if($col!='site_id'){
                  $count = \FuncNode::node_field_creation(str_replace('-','_',$node->name).'_translation', $node, $col, 1, $count);
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
                } else {
                    $child_value = $child->name;
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
                if($node->location=='package'){
                    $lang_folder = 'master::fields.';
                } else {
                    $lang_folder = 'fields.';
                }
                $field->label = trans($lang_folder.$field->trans_name);
                $saved = true;
            }
            if($saved===true){
                $field->save();
            }
          }
        }
        $this->info('95%: Se importara el excel de nodes para corregir los campos.');
        $this->info(\FuncNode::load_nodes_excel('master::fields.', base_path('vendor/solunes/master/src/nodes.xlsx')));
        $this->info(\FuncNode::load_nodes_excel('fields.', public_path('seed/nodes.xlsx')));
        $this->info('100%: Se crearon '.$total_count.' campos.');
    }
}
