<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GeneralNodeTest extends TestCase {
    
    public function testNodesItem() {
        $user = \App\User::find(1);
        foreach(\Solunes\Master\App\Node::whereNotIn('type', ['subchild', 'field'])->with('fields','fields.field_options')->get() as $node){
            $url = '/admin/model/'.$node->name.'/create';
            if($node->name=='indicator'){
                $url .= '?node_id=40&type=normal&data=count';
            } else if($node->parent_id){
                $model = \FuncNode::node_check_model($node->parent);
                if($last = $model->first()){
                    $url .= '?parent_id='.$last->id;
                }
            }
            if($node->name!='field'&&$node->name!='node'){
                $this->actingAs($user)->visit($url);
                $hidden_array = ['admin','show'];
                foreach($node->fields()->where('type', '!=', 'child')->displayItem($hidden_array)->whereNull('child_table')->get() as $field){
                    if($field->required||($node->name=='user'&&$field->name=='username')){
                        if($field->type=='select'||$field->type=='radio'||$field->type=='checkbox'||$field->type=='relation'||$field->type=='field'){
                            if($field->type=='relation'||$field->type=='field'){
                                $value = 1;
                            } else {
                                $value = $field->field_options()->first()->name;
                            }
                            if($field->type=='relation'&&$field->name=='parent_id'){

                            } else if($field->type=='checkbox') {
                                $this->checkbox($field->name);
                            } else {
                                $this->select($value, $field->name);
                            }
                        } else {
                            if($field->name=='password'){
                                $this->type('asdasdasda', $field->name);
                            } else if($field->type=='file'||$field->type=='image') {
                                $this->attach(public_path('assets/img/logo.png'), 'uploader_'.$field->name);
                                $this->type('asd.docx', $field->name);
                            } else {
                                $this->type(1, $field->name);
                            }
                        } 
                    }
                }
                $this->press('Crear')->see('El item fue creado correctamente.');
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