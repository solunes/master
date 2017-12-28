<?php

namespace Solunes\Master\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use Validator;
use Asset;
use AdminList;
use AdminItem;
use PDF;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->prev = $url->previous();
	  $this->module = 'test';
	}

	public function getGeneralTest() {
        if(\App::environment('local')){
            $response = '<strong>Comenzando la prueba...</strong>';
            
            $items = \Solunes\Master\App\NodeTranslation::where('singular', 'like', '%model.%')->groupBy('singular')->orderBy('singular')->get();
            if(count($items)>0){
            	$response .= '<br><br><strong>Node Translation.</strong> Model:';
                foreach($items as $item){
                    $response .= $this->getTransResponse($item->singular, ":model.");
                }
            }
            
            $items = \Solunes\Master\App\FieldTranslation::where('label', 'like', '%fields.%')->groupBy('label')->orderBy('label')->get();
            if(count($items)>0){
                $response .= '<br><br><strong>Field Translation.</strong> Agregar fields.php:';
                foreach($items as $item){
                	$response .= $this->getTransResponse($item->label, "master::fields.");
                }
            }
            
            $items = \Solunes\Master\App\FieldOptionTranslation::where('label', 'like', '%admin.%')->groupBy('label')->orderBy('label')->get();
            if(count($items)>0){
                $response .= '<br><br><strong>Field Option Translation.</strong> Agregar a admin.php:';
                foreach($items as $item){
                    $response .= $this->getTransResponse($item->label, "master::admin.");
                }
            }

            $items = \Solunes\Master\App\MenuTranslation::where('name', 'like', '%admin.%')->groupBy('name')->orderBy('name')->get();
            if(count($items)>0){
                $response .= '<br><br><strong>Menu Translation.</strong> Agregar a admin.php:';
                foreach($items as $item){
                	$response .= $this->getTransResponse($item->name, "master::admin.");
                }
            }

            $nodes_array = \Solunes\Master\App\Node::where('location', 'app')->lists('id');
            $items = \Solunes\Master\App\Field::whereIn('parent_id', $nodes_array)->where('type', 'text')->get();
            if(count($items)>0){
                $response .= '<br><br><strong>Textos largos.</strong> Revisar si es necesario editar nodes.xls:';
                foreach($items as $item){
                    if(!$item->field_extras()->where('type', 'class')->where('value', 'textarea')->first()){
                		$strong = $this->checkIfStrong($item->name, ['content','description']);
                		$response .= $strong['begin'];
                		$response .= "<br>- ".$this->generateLink(url('admin/model/'.$item->parent->name.'/create'), $item->parent->singular);
                        $response .= ' ('.$item->parent->name.') - '.$item->label.' ('.$item->name.')';
                		$response .= $strong['end'];
                    }
                }
            }

            $nodes = \Solunes\Master\App\Node::where('location', 'app')->get();
            if(count($nodes)>0){
                $response .= '<br><br><strong>Listado de Nodos.</strong> Revisar si el listado de nodos es correcto:';
                foreach($nodes as $node){
                	$response .= "<br>- ".$this->generateLink(url('admin/model-list/'.$node->name), $node->name)." -> Nº (count)";
                	foreach($node->fields()->displayList('show')->where('type', '!=', 'field')->get() as $field){
                		$strong = $this->checkIfStrong($field->type, ['text','subchild']);
                		$response .= $strong['begin'];
                		$response .= ' - '.$field->label.' ('.$field->type.')';
                		$response .= $strong['end'];
                	}
                }
            }

            $nodes = \Solunes\Master\App\Node::where('location', 'app')->where('dynamic', 0)->get();
            if(count($nodes)>0){
                $response .= '<br><br><strong>Revisión general de permisos.</strong> Revisar si los permisos están bien:';
                foreach($nodes as $node){
                    $model = \FuncNode::node_check_model($node);
                    $rules_edit = $model::$rules_edit;
                    $rules_create = $model::$rules_create;
                    $fields_array = $node->fields()->where('type', '!=', 'child')->displayItem(['admin', 'show'])->whereNull('child_table')->lists('name')->toArray();
                    // REVISAR SI HAY REGLAS QUE SOBRAN
                    $required_fields = $this->checkRules($rules_create, $rules_edit, $fields_array);
                    if(count($required_fields)>0){
                        $response .= "<br>- Quitar de modelo (rules): ".$this->generateLink(url('admin/model/'.$node->name.'/create'), $node->name);
                        foreach($required_fields as $field_name => $field_rule){
                            $response .= ' - '.$field_name.' ('.$field_rule.')';
                        }
                    }
                    // REVISAR SI HAY REGLAS QUE FALTAN
                    $fields = $node->fields()->where('type', '!=', 'child')->displayItem(['admin', 'show'])->whereNull('child_table')->get();
                    $pending_fields = $this->checkPendingRules($fields, ($rules_edit + $rules_create));
                    if(count($pending_fields)>0){
                        $response .= "<br>- Agregar regla a (rules): ".$this->generateLink(url('admin/model/'.$node->name.'/create'), $node->name);
                        foreach($pending_fields as $field){
                            $response .= ' - '.$field;
                        }
                    }
                }
            }

            $response .= '<br><br><strong>Finalizaron las pruebas.</strong>';
        } else {
            $response = 'No autorizado.';
        }
        print_r($response);
	}

	public function getTransResponse($name, $delete){
		$new_name = str_replace($delete, "",$name);
        $new_name_2 = $new_name;
        if($delete==':model.'){
            $new_name_2 = 'Muestra|Muestras';
        }
        $response = "<br>'".$new_name."' => '".$new_name."',";
	    return $response;
	}

	public function checkIfStrong($item, $array){
		if(in_array($item, $array)){
			return ['begin'=>'<strong>', 'end'=>'</strong>'];
		} else {
			return ['begin'=>NULL, 'end'=>NULL];
		}
	}

    public function generateLink($url, $label){
        return "<a target='_blank' href='".$url."'>".$label."</a>";
    }

    public function checkRules($rules_create, $rules_edit, $fields_array){
        $required_fields = [];
        foreach($rules_create as $rule_key => $rule){
            if((in_array($rule_key, $fields_array)&&strpos($rule, 'required') !== false)||$rule_key=='id'){
            } else {
                $required_fields[$rule_key] = 'create';
            }
        }
        foreach($rules_edit as $rule_key => $rule){
            if((in_array($rule_key, $fields_array)&&strpos($rule, 'required') !== false)||$rule_key=='id'){
            } else {
                if(isset($required_fields[$rule_key])){
                    $required_fields[$rule_key] = 'create - edit';
                } else {
                    $required_fields[$rule_key] = 'edit';
                }
            }
        }
        return $required_fields;
    }

    public function checkPendingRules($fields, $rules){
        $pending_fields = [];
        foreach($fields as $field){
            if((array_key_exists($field->name, $rules)&&strpos($rules[$field->name], 'required') !== false)){
            } else {
                if(in_array($field->type, ['select','radio','checkbox'])){
                    $pending_fields[$field->name] = $field->name;
                }
            }
        }
        return $pending_fields;
    }

    public function previewEmail($msg){
        $array['msg'] = $msg;
        $array['email'] = 'edumejia30@gmail.com';
        $array['button_link'] = 'http://www.solunes.com';
        $array['button_title'] = 'Ver Sitio Web';
        return view('master::emails.default', $array);
    }

}