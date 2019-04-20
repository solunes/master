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

class FilterController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->prev = $url->previous();
	  $this->module = 'filter';
	}

	public function getStandardFilter($parent_node_name, $relation_name, $parent_value) {
		$parent_node = \FuncNode::get_node($parent_node_name);
		$parent_model = \FuncNode::node_check_model($parent_node);
		$parent_model = $parent_model->find($parent_value);
	    $options = $parent_model->$relation_name()->get();
	    $array['options'] = $options;
	    return $array;
	}

}