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
            if($node->parent_id){
                $model = \FuncNode::node_check_model($node->parent);
                if($last = $model->first()){
                    $count = 1;
                    $url .= '?parent_id='.$last->id;
                }
            }
            foreach($preset_fields as $preset){
                $subnode = \Solunes\Master\App\Node::where('name', $preset->value)->first();
                $model = \FuncNode::node_check_model($subnode);
                if($preset->name!='parent_id'&&$last = $model->orderBy('id', 'DESC')->first()){
                    if($count==0) {
                        $url .= '?';
                    } else {
                        $url .= '&';
                    }
                    $url .= $preset->name.'='.$last->id;
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
                        if($field->type=='select'||$field->type=='radio'||$field->type=='checkbox'||$field->type=='relation'||$field->type=='field'){
                            if($field->type=='relation'||$field->type=='field'){
                                $value = 1;
                            } else {
                                $value = $field->field_options()->first()->name;
                            }
                            if($field->type=='relation'&&$field->name=='parent_id'){
                                $value = NULL;
                            }
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
                $this->actingAs($user)->visit($url_0)->see('Descargar');
                $this->visit($url)->see('El item se eliminó correctamente.');
                if($node->soft_delete){
                    $last->forceDelete();
                }
            }
        }
    }

}