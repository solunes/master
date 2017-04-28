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

class AdminController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard')->only('getIndex');
	  $this->prev = $url->previous();
	  $this->module = 'admin';
	}

	public function getIndex() {
		if(request()->has('start_date')){
			$array['start_date'] = request()->input('start_date');
		} else {
			$array['start_date'] = date('Y-m-d', strtotime("-29 days"));
		}
		if(request()->has('end_date')){
			$array['end_date'] = request()->input('end_date');
		} else {
			$array['end_date'] = date('Y-m-d');
		}
        $user_id = auth()->user()->id;
		$array['block_alerts'] = \Solunes\Master\App\IndicatorGraph::whereHas('indicator_graph_users', function ($query) use($user_id) {
            $query->where('user_id', $user_id);
        })->where('graph','number')->has('indicator')->with('indicator')->get();
		$array['graph_alerts'] = \Solunes\Master\App\IndicatorGraph::whereHas('indicator_graph_users', function ($query) use($user_id) {
            $query->where('user_id', $user_id);
        })->where('graph','!=','number')->has('indicator')->with('indicator')->get();
      	return view('master::list.dashboard', $array);
	}

    public function getGenerateManual($role_name = NULL) {
	    $permission_array = \Login::get_role_permissions($role_name);
	    $array = ['role_name'=>$role_name];
	    $array['title'] = 'Manual de Administrador';
	    $array['header_title'] = 'Manual de Administrador';    
	    $array['site'] = \Solunes\Master\App\Site::find(1);
	    $array['nodes'] = \Solunes\Master\App\Node::whereNull('parent_id')->whereIn('permission', $permission_array)->with('fields', 'children.fields', 'children.children.fields')->get();
	    $pdf = \PDF::loadView('master::pdf.manual', $array);
	    $header = \View::make('pdf.header', $array);
	    return $pdf->setPaper('letter')->setOption('header-html', $header->render())->stream(trans('master::admin.manual').'_'.date('Y-m-d').'.pdf');
	}

	public function getModelList($model) {
		$array = AdminList::get_list($this, $model);
        if(request()->has('download-excel')){
            return AdminList::generate_query_excel($array);
        } else if(config('solunes.list_extra_actions')&&$extra_actions = \CustomFunc::list_extra_actions($array)){
            return $extra_actions;
        } else {
            if($array['node']->multilevel){
                return view('master::list.multilevel-list', $array);
            }
            return view('master::list.general-list', $array);
        }
	}

	public function getModel($single_model, $action, $id = NULL) {
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        $options = [];
	    $additional_vars = [];

	    \AdminItem::check_item_permission($this->module, $node, $action, $id);

        if($action=='delete'||$action=='restore'){
            return \AdminItem::delete_restore_item($this->module, $this->prev, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        }
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);

        return \AdminItem::get_item_view($node, $single_model, $variables);
	}

	public function getChildModel($single_model, $action, $id = NULL) {
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        $options = ['child'=>true];
	    $additional_vars = [];

	    \AdminItem::check_item_permission($this->module, $node, $action, $id);

        if($action=='delete'||$action=='restore'){
            return \AdminItem::delete_restore_item($this->module, $this->prev, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        }
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);

        return \AdminItem::get_item_view($node, $single_model, $variables);
	}

	public function getModelIndicator($action, $id = NULL) {
	    $single_model = 'indicator';
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        $array = [];
	    if($action=='edit'){
		  	$indicator = \Solunes\Master\App\Indicator::find($id);
	        $indicator_model = \FuncNode::node_check_model($indicator->node);
		  	$array['node_name'] = $indicator->node->plural;
			$array['filter_category'] = 'indicator';
			$array['filter_node'] = $indicator->node->name;
			$array['filter_type'] = 'field';
			$array['filter_category_id'] = $id;
			$filled_items = $indicator_model;
			$array = \AdminList::filter_node($array, $indicator->node, $indicator_model, $filled_items, 'indicator');
			if(config('solunes.custom_indicator')){
				$array = \CustomFunc::custom_indicator($indicator->node, $indicator, $array);
			}
			if(request()->has('search')&&isset($array['filters'])&&is_array($array['filters'])){
			  foreach($array['filters'] as $field_name => $field){
			  	$filter = \Solunes\Master\App\Filter::find($field['id']);
			  	$action_value = [];
			  	if($field['subtype']=='date'){
			  		if(request()->has('f_'.$field_name.'_from')){
			  	      $action_value[request()->input('f_'.$field_name.'_from')] = 'is_greater';
			  		}
			  		if(request()->has('f_'.$field_name.'_to')){
			  	      $action_value[request()->input('f_'.$field_name.'_to')] = 'is_less';
			  		}
			  	} else if($field['subtype']=='string'){
			  		if(request()->has('f_'.$field_name)){
			  	      $action_value[request()->input('f_'.$field_name)] = request()->input('f_'.$field_name.'_action');
			  	    }
			  	} else {
			  	  if(request()->has('f_'.$field_name)&&is_array(request()->input('f_'.$field_name))){
			  	    foreach(request()->input('f_'.$field_name) as $subfield_key => $subfield_val){
			  	      $action_value[$subfield_val] = 'is';
			  	    }
			  	  } else if(request()->has('f_'.$field_name)){
			  	    $action_value[request()->input('f_'.$field_name)] = 'is';
			  	  }
			  	}
			  	$filter->action_value = json_encode($action_value);
			  	$filter->save();
			  }
			  if(config('solunes.update_indicator_values')){
			  	if(config('solunes.custom_indicator_values')){
			  		\CustomFunc::update_indicator_values($indicator);
			  	} else {
			  		\FuncNode::update_indicator_values($indicator);
			  	}
			  }
			}
			$filled_items = $indicator_model;
			$array = \AdminList::filter_node($array, $indicator->node, $indicator_model, $filled_items, 'indicator');
		  	$array['items'] = $array['items']->get();
	    }

        $options = [];
	    $additional_vars = $array;

	    \AdminItem::check_item_permission($this->module, $node, $action, $id);

        if($action=='delete'||$action=='restore'){
            return \AdminItem::delete_restore_item($this->module, $this->prev, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        } 
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);

        return \AdminItem::get_item_view($node, $single_model, $variables);
	}

    public function postModel(Request $request) {
      $model = $request->input('model_node');
      $action = $request->input('action_form');
      $lang_code = $request->input('lang_code');
      $response = AdminItem::post_request($model, $action, $request);
      $item = $response[1];
	  if($response[0]->passes()) {
	  	$item = AdminItem::post_request_success($request, $model, $item, 'admin');
	  	if($model=='indicator'&&$action=='create'){
	  		$indicator = \Solunes\Master\App\Indicator::find($item->id);
		  	if(config('solunes.custom_indicator_values')){
		  		\CustomFunc::update_indicator_values($indicator);
		  	} else {
		  		\FuncNode::update_indicator_values($indicator);
		  	}
	  	}
	  	if($request->has('child-page')){
        	return ['type'=>'success', 'model'=>$model, 'action'=>$action, 'item_id'=>$item->id];
	  	} else {
	  		$redirect = $this->module.'/model/'.$model.'/edit/'.$item->id.'/'.$request->input('lang_code');
        	return AdminItem::post_success($action, $redirect);
	  	}
	  } else {
	  	if($request->has('child-page')){
	  		$redirect = $request->input('child-url');
	  	} else {
	  		$redirect = $this->prev;
	  	}
		return AdminItem::post_fail($action, $redirect, $response[0]);
	  }
    }

	public function getIndicators() {
		$array['indicators'] = \Solunes\Master\App\Indicator::get();
      	return view('master::list.indicators', $array);
    }

	public function changeIndicatorUser($type, $action, $id) {
		if($type=='alert'){
			$indicator = \Solunes\Master\App\IndicatorAlert::find($id)->indicator_alert_users();
		} else {
			$indicator = \Solunes\Master\App\IndicatorGraph::find($id)->indicator_graph_users();
		}
		if($action=='add'){
			$indicator->attach(auth()->user()->id);
			$message = 'El indicador fue agregado correctamente';
		} else {
			$indicator->detach(auth()->user()->id);
			$message = 'El indicador fue retirado correctamente';
		}
	    return redirect($this->prev)->with('message_success', $message);
    }

	public function getModalFilter($category, $type, $category_id, $node_name) {
		$rejected_ids = [];
		$node = \Solunes\Master\App\Node::where('name', $node_name)->first();
		$filters = $node->filters()->checkCategory($category)->checkDisplay();
		if($category=='admin'){
        	$rejected_ids = $filters->where('type','field')->lists('parameter')->toArray();
		} else  {
        	$rejected_ids = $filters->where('category_id', $category_id)->lists('parameter')->toArray();
		}
		$array['category'] = $category;
		$array['type'] = $type;
		$array['node_id'] = $node->id;
		$array['category_id'] = $category_id;
		$array['fields'] = $node->fields()->whereNotIn('name', $rejected_ids)->filters()->get()->lists('label','name')->toArray();
      	return view('master::modal.filter', $array);
	}

	public function postModalFilter(Request $request) {
		if($request->has('select_field')&&$request->input('select_field')!==''){
			$category = $request->input('category');
			$type = $request->input('type');
			if($type=='indicator'){
				$display = 'all';
			} else {
				$display = 'user';
			}
			$category_id = $request->input('category_id');
			$node_id = $request->input('node_id');
			$node = \Solunes\Master\App\Node::find($node_id);
			$field = $node->fields()->where('name', $request->input('select_field'))->first();
			if($field->type=='date'){
				$subtype = 'date';
			} else if($field->type=='string'||$field->type=='text'||$field->type=='barcode'){
				$subtype = 'string';
			} else if($field->type=='field'){
				$subtype = 'field';
			} else {
				$subtype = 'select';
			}
			$filter = new \Solunes\Master\App\Filter;
			$filter->category = $category;
			$filter->category_id = $category_id;
			$filter->node_id = $node_id;
			$filter->user_id = auth()->user()->id;
			$filter->display = $display;
			$filter->type = $type;
			$filter->subtype = $subtype;
			$filter->parameter = $request->input('select_field');
			$filter->save();
			$url = $this->prev;
			if (strpos($url, '?') !== false) {
			    $url .= '&search=1';
			} else {
			    $url .= '?search=1';
			}
	      	return redirect($url);
	      } else {
	      	return redirect($this->prev)->with('message_error','Debe seleccionar un campo para filtrar');
	      }
	}

	public function getDeleteFilter($id) {
		\Solunes\Master\App\Filter::where('id', $id)->where('display','user')->where('user_id',auth()->user()->id)->delete();
      	return redirect($this->prev);
	}

	public function getDeleteAllFilters($category, $category_id, $node_id = NULL) {
		$filters = \Solunes\Master\App\Filter::checkCategory($category)->where('display','user')->where('user_id',auth()->user()->id);
		if($category_id==0){
			$node = \Solunes\Master\App\Node::where('name', $node_id)->first();
			$filters->where('node_id', $node->id)->delete();
		} else {
			$filters->where('category_id', $category_id)->delete();
		}
      	return redirect($this->prev);
	}

	public function getModalMap($name, $value) {
		$array['name'] = $name;
		$array['value'] = $value;
		$value_array = explode(';',$value);
		$array['latitude'] = $value_array[0];
		$array['longitude'] = $value_array[1];
      	return view('master::modal.map', $array);
	}

    public function checkBarcode($node_id, $barcode) {
	    if($id = \Asset::check_barcode($node_id, $barcode)){
	    	$array['id'] = $id;
	    	$array['check'] = true;
	    } else {
	    	$array['id'] = NULL;
	    	$array['check'] = false;
	    }
	    return $array;
	}

    public function redirectBarcode($node_name, $id) {
    	return redirect('admin/model/'.$node_name.'/edit/'.$id.'/es')->with('message_success', 'Se encontró un código de barras bajo este item, recomendamos editarlo aquí a menos que cree un nuevo código para el producto.');
	}

    public function generateBarcodeImage($value) {
    	$code = \Asset::generate_barcode_image($value);
    	$html = '<img src="data:image/png;base64,'.$code.'" />';
        $pdf = \PDF::loadHTML($html)->setPaper('A9')->setOrientation('landscape')->setOption('margin-top', 12)->setOption('margin-left', 3)->setOption('margin-right', 0)->setOption('margin-bottom', 0)->stream('barcode_'.$value.'.pdf');
    	return $pdf;
	}

}