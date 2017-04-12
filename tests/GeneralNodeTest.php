<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GeneralNodeTest extends TestCase {
    
    public function testNodesItem() {
        $user = \App\User::find(1);
        $hidden_array = ['admin','show'];
        foreach(\Solunes\Master\App\Node::whereNotIn('type', ['subchild', 'field'])->with('fields','fields.field_options')->get() as $node){
            $url = '/admin/model/'.$node->name.'/create';
            $preset_fields = $node->fields()->displayItem($hidden_array)->preset()->required()->get();
            $count = 0;
            $parent = false;
            if($node->parent_id){
                $model = \FuncNode::node_check_model($node->parent);
                if($last = $model->first()){
                    $count = 1;
                    $url .= '?parent_id='.$last->id;
                    $parent = true;
                }
            }
            foreach($preset_fields as $preset){
                $new_val = 1;
                if($preset->relation){
                    $subnode = \Solunes\Master\App\Node::where('name', $preset->value)->first();
                    $model = \FuncNode::node_check_model($subnode);
                    if($last = $model->orderBy('id', 'DESC')->first()){
                        $new_val = $last->id;
                    }
                }
                if($preset->name!='parent_id'&&$parent===false){
                    if($count==0) {
                        $url .= '?';
                    } else {
                        $url .= '&';
                    }
                    $url .= $preset->name.'='.$new_val;
                    $count++;
                }
            }
            if($node->name=='indicator'){
                $url .= '&type=normal&data=count';
            }
            if($node->name!='field'&&$node->name!='node'){
                $input = [];
                foreach($node->fields()->where('type', '!=', 'child')->displayItem($hidden_array)->whereNull('child_table')->get() as $field){
                    $value = NULL;
                    if($field->required||($node->name=='user'&&$field->name=='username')){
                        if($field->relation){
                            if($field->name=='parent_id'){
                                $value = NULL;
                            } else {
                                $value = 1;
                            }
                        } else if($field->type=='select'||$field->type=='radio'||$field->type=='checkbox'){
                            $value = $field->field_options()->first()->name;
                        } else {
                            if($field->name=='password'){
                                $value = 'asdasdasda';
                            } else if($field->type=='file'||$field->type=='image') {
                                $value = public_path('assets/img/logo.png');
                            } else {
                                $value = 1;
                            }
                        } 
                        if($value!==NULL){
                            if($field->type=='checkbox'||($field->type=='field'&&$field->multiple)){
                                $input[$field->name] = [$value];
                            } else {
                                $input[$field->name] = $value;
                            }
                        }
                    }
                }
                //\Log::info('Crear: '.$url);
                $this->actingAs($user)->visit($url)
                ->submitForm('Crear', $input)->see('El item fue creado correctamente.');
            }
        }
    }

    public function testNodesEditItems() {
        $user = \App\User::find(1);
        foreach(\Solunes\Master\App\Node::whereNotIn('type', ['subchild', 'field'])->with('fields','fields.field_options')->get() as $node){
            $model = \FuncNode::node_check_model($node);
            if($node->name!='field'&&$node->name!='node'&&$last = $model->orderBy('id', 'DESC')->first()){
                $url = '/admin/model/'.$node->name.'/edit/'.$last->id.'/es';
                //\Log::info('Editar: '.$url);
                $this->actingAs($user)->visit($url);
                $this->press('Guardar')->see('El item fue actualizado correctamente.');
            }
        }
    }

    public function testNodesDeleteItems() {
        $user = \App\User::find(1);
        foreach(\Solunes\Master\App\Node::whereNotIn('type', ['subchild', 'field'])->orderBy('id', 'DESC')->with('fields','fields.field_options')->get() as $node){
            $model = \FuncNode::node_check_model($node);
            if($node->name!='field'&&$node->name!='node'&&$last = $model->orderBy('id', 'DESC')->first()){
                $url_0 = '/admin/model-list/'.$node->name;
                $url = '/admin/model/'.$node->name.'/delete/'.$last->id;
                //\Log::info('Borrar: '.$url);
                $this->actingAs($user)->visit($url_0)->see('Descargar');
                $this->visit($url)->see('El item se eliminó correctamente.');
                if($node->soft_delete){
                    $last->forceDelete();
                }
            }
        }
    }

}