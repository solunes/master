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

class ProcessController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->prev = $url->previous();
	  $this->module = 'dashboard';
	}

	public function getModel($single_model, $action, $custom_type = NULL, $id = NULL) {
		$lang = NULL;
		if($lang){
			\App::setLocale($lang);
		}
		$dashadmin_nodes = config('solunes.dashadmin_nodes');
		if(!isset($dashadmin_nodes[$single_model])){
			return redirect('');
		}
		$dashadmin_nodes = $dashadmin_nodes[$single_model];
		if(!isset($dashadmin_nodes[$custom_type])){
			return redirect('');
		}
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        $options = [];
	    $additional_vars = [];

	    if($check_item_permission = \AdminItem::check_item_permission($this->module, $node, $action, $id)){
	    	return $check_item_permission;
	    }

	    if($action!='create'&&!$model::find($id)){
	    	return redirect('admin/model-list/'.$single_model)->with('message_error', 'No se encontró el item que intentó buscar.');
	    }

        if($action=='delete'||$action=='restore'){
            return \AdminItem::delete_restore_item($this->module, $this->prev, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        }
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars, $custom_type);
        if(config('solunes.item_get_after_vars')&&in_array($single_model, config('solunes.item_get_after_vars'))){
        	$variables = $model->item_get_after_vars($this->module, $node, $single_model, $id, $variables);
        }
        if(config('solunes.store')&&config('store.item_get_after_vars')&&in_array($single_model, config('store.item_get_after_vars'))){
        	$variables = $model->item_get_after_vars($this->module, $node, $single_model, $id, $variables);
        }
        $variables['custom_type'] = $custom_type;
        return view('master::item.custom-model', $variables);
	}

	public function postModel(Request $request) {
      $model = $request->input('model_node');
      $action = $request->input('action_form');
      $lang_code = $request->input('lang_code');
      $custom_type = $request->input('custom_type');
      if(!$custom_type){
      	$custom_type = 'default';
      }
	  if($lang_code){
		\App::setLocale($lang_code);
	  }
      $response = AdminItem::post_request($this->module, $model, $action, $request, NULL, $custom_type);
      $item = $response[1];
      $node_model = $response[2];
	  if($response[0]->passes()) {
	  	$item = AdminItem::post_request_success($this->module, $request, $model, $item, 'process', $custom_type);
        /*if(config('solunes.item_post_redirect_success')&&in_array($model, config('solunes.item_post_redirect_success'))){
        	if($custom_redirect = $node_model->item_post_redirect_success($this->module, $model, $item->id, $action)){
        		return $custom_redirect;
        	}
        }*/
        if(isset(config('solunes.dashadmin_custom_redirect')[$model])){
	  		$redirect = config('solunes.dashadmin_custom_redirect')[$model];
	  		$redirect = str_replace('{id}', $item->id, $redirect);
        } else {
	  		$redirect = $this->module.'/custom-form/'.$model.'/edit/'.$custom_type.'/'.$item->id;
        }
        return AdminItem::post_success($action, $redirect);
	  } else {
        /*if(config('solunes.item_post_redirect_fail')&&in_array($model, config('solunes.item_post_redirect_fail'))){
        	if($custom_redirect = $node_model->item_post_redirect_fail($this->module, $model, $action)){
        		return $custom_redirect;
        	}
        }*/
	  	$redirect = $this->prev;
		return AdminItem::post_fail($action, $redirect, $response[0]);
	  }
	}

}