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
                	$response .= "<br>".$item->singular;
                	//$response .= "<br>'".str_replace("master::fields.", "",$item->label)."' => 'barcode'";
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
                $response .= '';
                $this->info('<br><br><strong>Field Option Translation.</strong> Agregar a admin.php:');
                foreach($items as $item){
                	$response .= "<br>".$item->label;
                	//$response .= "<br>'".str_replace("master::fields.", "",$item->label)."' => 'barcode'";
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
                		$strong = $this->checkIfStrong($field->type, ['text','field']);
                		$response .= $strong['begin'];
                		$response .= ' - '.$field->label.' ('.$field->type.')';
                		$response .= $strong['end'];

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

}