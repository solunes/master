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
        if(config('solunes.indicators')){
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
			if(request()->has('graph_type')){
				$array['graph_type'] = request()->input('graph_type');
				$array['graph_type_isset'] = true;
			} else {
				$array['graph_type'] = 'lines';
				$array['graph_type_isset'] = false;
			}
			$user_id = auth()->user()->id;
        	$indicators = \Solunes\Master\App\Indicator::where('user_id', $user_id)->orWhereHas('indicator_users', function ($query) use($user_id) {
	            $query->where('user_id', $user_id);
	        })->get();
		    $not_added_indicators = \Solunes\Master\App\Indicator::where('user_id', '!=', $user_id)->whereDoesntHave('indicator_users', function ($query) use($user_id) {
		        $query->where('user_id', $user_id);
		    })->count();
	        $array['indicators'] = $indicators;
	        $array['not_added_indicators'] = $not_added_indicators;
	        $url = url()->full();
	        if(strpos($url, '?') === false){
	        	$url = $url.'?';
	        } else {
	        	$url = $url.'&';
	        }
	        $array['url'] = $url;
	        if(request()->has('indicator_id')&&$indicator = \Solunes\Master\App\Indicator::find(request()->input('indicator_id'))){
        		$indicator = $indicator;
	        } else if(count($indicators)>0){
        		$indicator = $indicators->first();
	        } else {
	        	$indicator = NULL;
	        }
	        $graphs = NULL;
        	if($indicator){
        		if(!$array['graph_type_isset']){
        			$array['graph_type'] = $indicator->graph_type;
        		}
        		$filter_query = json_decode($indicator->filter_query, true);
        		$my_indicator_value = $indicator->my_indicator_value;
        		$node = \Solunes\Master\App\Node::find($indicator->node_id);
        		$fields = $node->fields()->whereIn('type',['select','radio','checkbox'])->get();
        		$items = \FuncNode::node_check_model($node);
        		if(count($filter_query)>0){
        			if(isset($filter_query['dates'])){
		        		if($array['start_date']){
		        			$items = $items->where('created_at', '>=', $array['start_date'].' 00:00:00');
		        		}
		        		if($array['end_date']){
		        			$items = $items->where('created_at', '<=', $array['end_date'].' 23:59:59');
		        		}
        				unset($filter_query['dates']);
        			}
        			foreach($filter_query as $filter_query_key => $filter_query_item){
        				$items = $items->where($filter_query_key, $filter_query_item);
        			}
        		}
        		$array_items = [];
        		if($array['graph_type'] == 'lines'){
	                $range = range(1,12);
                    $count_text = '[';
                    $count = 0;
	                foreach($range as $month){
	                  $cloned_model = clone $items;
	                  $month_count = $cloned_model->where( \DB::raw('MONTH(created_at)'), '=', $month )->count();
	                  $count += $month_count;
	                  $count_text .= $month_count.',';
	                }
                    $count_text .= ']';
                    $label_name = 'Total';
                    if(config('solunes.indicator_total_count')){
                    	$label_name .= ' ('.$count.')';
                    }
		    		$array_items[$label_name] = $count_text;
        		} else {
		    		$count = $items->count();
        		}
		    	$total_items_count = $count;
		    	$no_items_count = 0;
		    	$items_count = 0;
        		$field = NULL;
        		if(request()->has('field_name')){
        			$field = $fields->where('name', request()->input('field_name'))->first();
        			if($field){
        				if(!$my_indicator_value){
        					$my_indicator_value = new \Solunes\Master\App\IndicatorUser;
        					$my_indicator_value->parent_id = $indicator->id;
        					$my_indicator_value->user_id = auth()->user()->id;
        				}
        				$my_indicator_value->field_id = $field->id;
	        			$my_indicator_value->save();
        			}
        		} else if($my_indicator_value&&$my_indicator_value->field_id){
        			$field = $fields->where('id', $my_indicator_value->field_id)->first();
        		} else if(count($fields)>0){
        			$field = $fields->first();
        		}
		    	$array['fields'] = $fields;
		    	$array['field'] = $field;
		    	$array['items'] = $total_items_count;
        		if($field){
        			$no_results = 0;
        			$no_results_count = 0;
	        		foreach($field->options as $option_value => $option_name){
	        			$check = false;
		        		if($array['graph_type'] == 'lines'){
			                $range = range(1,12);
		                    $count_text = '[';
		                    $count = 0;
			                foreach($range as $month){
			                  $cloned_model = clone $items;
			                  $month_count = $cloned_model->where($field->name, $option_value)->where( \DB::raw('MONTH(created_at)'), '=', $month )->count();
			                  $count += $month_count;
			                  $count_text .= $month_count.',';
			                  if($month_count>0){
			                  	$check = true;
			                  }
			                }
		                    $count_text .= ']';
		        		} else {
			                $cloned_model = clone $items;
				    		$count = $cloned_model->where($field->name, $option_value)->count();
			                if($count>0){
			                  	$check = true;
			                }
		        		}
		        		if($check){
		        			$label_name = $field->label.': '.$option_name;
                    		if(config('solunes.indicator_total_count')){
                    			$label_name .= ' ('.$count.')';
                    		}
		        			if($array['graph_type'] == 'lines'){
		    					$array_items[$label_name] = $count_text;
		        			} else {
		    					$array_items[$label_name] = $count;
		        			}
		    				$items_count += $count;
		        		} else {
		        			$no_results_count++;
		        		}
	        		}
	        		$no_items_count = $total_items_count - $items_count;
	        		if($array['graph_type'] != 'bar' && $no_results_count>0){
	        			$label_name = $field->label.': '.$no_results_count.' Sin Resultados';
                		if(config('solunes.indicator_total_count')){
                			$label_name .= ' (0)';
                		}
		    			$array_items[$label_name] = 0;
	        		}
	        		if($array['graph_type'] != 'lines'){
		    			if($no_items_count>0){
		        			$label_name = 'Items sin elemento';
	                		if(config('solunes.indicator_total_count')){
	                			$label_name .= ' ('.$no_items_count.')';
	                		}
		    				$array_items[$label_name] = $no_items_count;
		    			}
	        		}
        		}
        		$graphs['indicator-'.$indicator->id] = ['type'=>$array['graph_type'], 'name'=>'name', 'label'=>$indicator->name, 'items'=>$array_items];
        	} else {
        		$array['items'] = [];
        	}
        	$array['graph_type_label'] = trans('master::admin.'.$array['graph_type']);
        	$array['graphs'] = $graphs;
        	$array['indicator'] = $indicator;
        	$view = 'master::list.dashboard-new';
        } else {
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
	        $view = 'master::list.dashboard';
        }
      	return view($view, $array);
	}

    public function getAssignIndicatorModal() {
    	$user_id = auth()->user()->id;
        $indicators = \Solunes\Master\App\Indicator::where('user_id', '!=', $user_id)->whereDoesntHave('indicator_users', function ($query) use($user_id) {
	        $query->where('user_id', $user_id);
	    })->lists('name','id')->toArray();
    	$array['items'] = $indicators;
      	return view('master::modal.assign-indicator', $array);
	}

    public function postAssignIndicators(Request $request) {
    	$user_id = auth()->user()->id;
    	$indicators_id = $request->input('indicator_id');
    	if(count($indicators_id)>0){
    	  foreach($indicators_id as $indicator_id){
    	  	$indicator = \Solunes\Master\App\Indicator::find($indicator_id);
    	  	if($indicator){
    	  		$indicator_user = new \Solunes\Master\App\IndicatorUser;
    	  		$indicator_user->parent_id = $indicator->id;
    	  		$indicator_user->user_id = $user_id;
    	  		$indicator_user->save();
    	  	}
    	  }
    	}
	    return redirect($this->prev)->with('message_success','Indicadores actualizados correctamente.');
	}

    public function getRemoveIndicator($indicator_id) {
    	if($indicator = \Solunes\Master\App\Indicator::find($indicator_id)){
    		$indicator_user = $indicator->indicator_users()->where('user_id', auth()->user()->id)->first();
    		if($indicator_user){
    			$indicator_user->delete();
    		}
    	}
	    return redirect($this->prev)->with('message_success','El indicador fue removido correctamente.');
	}

    public function getMyNotifications() {
    	$array['items'] = \Solunes\Master\App\Notification::me()->type('dashboard')->orderBy('created_at','DESC')->paginate(25);
      	return view('master::list.notifications', $array);
	}

    public function postReadNotifications(Request $request) {
    	$id = $request->input('id');
    	$items = \Solunes\Master\App\Notification::whereIn('id', $id)->me()->get();
    	foreach($items as $item){
	    	$item->checked_date = date('Y-m-d H:i:s');
	    	$item->save();
    	}
    	return ['read'=>true, 'count'=>count($items)];
	}

    public function getMyInbox() {
        $array['items'] = \Solunes\Master\App\Inbox::userInbox(auth()->user()->id)->with('me','other_users','last_message')->orderBy('updated_at','DESC')->paginate(25);  	
     	return view('master::list.my-inbox', $array);
	}

    public function getCreateInbox() {
    	$node = \Solunes\Master\App\Node::where('name','inbox-message')->first();
    	$array['attachment_field'] = $node->fields()->where('name','attachments')->first();
        $array['users'] = \App\User::where('id', '!=', auth()->user()->id)->get();
     	return view('master::list.create-inbox', $array);
	}

	public function postCreateInbox(Request $request) {
		if($request->has('message')&&$request->has('users')&&$request->input('message')!==''){
			$inbox = new \Solunes\Master\App\Inbox;
			$inbox->user_id = auth()->user()->id;
			$inbox->save();
			$users_array = $request->input('users');
			array_unshift($users_array, auth()->user()->id);
			foreach($users_array as $user_id){
				$user = new \Solunes\Master\App\InboxUser;
				$user->parent_id = $inbox->id;
				$user->user_id = $user_id;
				$user->save();
			}
			$message = new \Solunes\Master\App\InboxMessage;
			$message->parent_id = $inbox->id;
			$message->user_id = auth()->user()->id;
			$message->message = $request->input('message');
			if($request->input('attachments')&&count($request->input('attachments'))>0){
				$message->attachments = json_encode($request->input('attachments'));
			}
			$message->save();
	      	return redirect('admin/inbox/'.$inbox->id);
	    } else {
	      	return redirect($this->prev)->with('message_error','Debe introducir algún texto y participantes para crear la conversación.')->withInput();
	    }
	}

    public function getInboxId($id) {
    	$user_id = auth()->user()->id;
    	$inbox = \Solunes\Master\App\Inbox::userInbox($user_id)->where('id', $id)->with('me')->first();
    	$me = $inbox->me;
    	$me->checked = true;
    	$me->save();
    	$node = \Solunes\Master\App\Node::where('name','inbox-message')->first();
    	$array['attachment_field'] = $node->fields()->where('name','attachments')->first();
    	$array['user_id'] = $user_id;
    	$array['last_user_id'] = 0;
    	$array['inbox'] = $inbox;
    	$array['users'] = $inbox->inbox_users()->get();
    	$array['items'] = $inbox->inbox_messages()->orderBy('created_at', 'DESC')->with('user')->paginate(25);
      	return view('master::list.view-inbox', $array);
	}

	public function postInboxReply(Request $request) {
		if(($request->has('message')&&$request->input('message')!=='')||($request->input('attachments')&&count($request->input('attachments'))>0)){
			if($request->has('parent_id')&&$inbox = \Solunes\Master\App\Inbox::find($request->input('parent_id'))){
				$message = new \Solunes\Master\App\InboxMessage;
				$message->parent_id = $inbox->id;
				$message->user_id = auth()->user()->id;
				$message->message = $request->input('message');
				if($request->input('attachments')&&count($request->input('attachments'))>0){
					$message->attachments = json_encode($request->input('attachments'));
				}
				$message->save();
				$inbox->touch();
                if(count($inbox->other_checked_users)>0){
                    foreach($inbox->other_checked_users as $checked_user){
                        $checked_user->checked = false;
                        $checked_user->save();
                    }
                }
		      	return redirect($this->prev);
			} else {
	      		return redirect($this->prev)->with('message_error','Hubo un error al enviar el mensaje.');
			}
	    } else {
	      	return redirect($this->prev)->with('message_error','Debe introducir algún texto o archivo para enviar.');
	    }
	}

    public function getGenerateManual($role_name = NULL) {
	    $permission_array = \Login::get_role_permissions($role_name);
	    $array = ['role_name'=>$role_name];
	    $array['title'] = 'Manual de Administrador';
	    $array['header_title'] = 'Manual de Administrador';    
	    $array['site'] = \Solunes\Master\App\Site::find(1);
	    $array['nodes'] = \Solunes\Master\App\Node::whereNull('parent_id')->whereIn('permission', $permission_array)->with('fields', 'children.fields', 'children.children.fields')->get();
        $pdf = \PDF::loadView('master::pdf.manual', $array);
        $pdf = \Asset::apply_pdf_template($pdf, 'MANUAL DE ADMINISTRACIÓN', ['margin-top'=>'35mm','margin-bottom'=>'25mm','margin-right'=>'25mm','margin-left'=>'25mm']);
        return $pdf->stream();
	}

	public function getModelList($model) {
        $node = \Solunes\Master\App\Node::where('name', $model)->first();
	    if($check_list_permission = \AdminList::check_list_permission($this->module, $node)){
	    	return $check_list_permission;
	    }

		$array = AdminList::get_list($this, $model);
        if(request()->has('download-excel')){
            return AdminList::generate_query_excel($array);
        } else if(request()->has('download-pdf')){
            return AdminList::generate_query_pdf($array);
        } else if(config('solunes.list_extra_actions')&&$extra_actions = \CustomFunc::list_extra_actions($array)){
            return $extra_actions;
        } else {
            if($array['node']->multilevel){
                return view('master::list.multilevel-list', $array);
            }
            return view('master::list.general-list', $array);
        }
	}

	public function getModel($single_model, $action, $id = NULL, $lang = NULL) {
		if($lang){
			\App::setLocale($lang);
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
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        if(config('solunes.item_get_after_vars')&&in_array($single_model, config('solunes.item_get_after_vars'))){
        	$variables = $model->item_get_after_vars($this->module, $node, $single_model, $id, $variables);
        }
        if(config('solunes.store')&&config('store.item_get_after_vars')&&in_array($single_model, config('store.item_get_after_vars'))){
        	$variables = $model->item_get_after_vars($this->module, $node, $single_model, $id, $variables);
        }
        return \AdminItem::get_item_view($this->module, $node, $single_model, $id, $variables);
	}

	public function getChildModel($single_model, $action, $id = NULL, $lang = NULL) {
		if($lang){
			\App::setLocale($lang);
		}
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        $options = ['child'=>true];
	    $additional_vars = [];

	    if($check_item_permission = \AdminItem::check_item_permission($this->module, $node, $action, $id)){
	    	return $check_item_permission;
	    }

        if($action=='delete'||$action=='restore'){
            return \AdminItem::delete_restore_item($this->module, $this->prev, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        }
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        if(config('solunes.item_child_after_vars')&&in_array($single_model, config('solunes.item_child_after_vars'))){
        	$variables = $model->item_child_after_vars($this->module, $node, $single_model, $id, $variables);
        }
        if(config('solunes.store')&&config('store.item_child_after_vars')&&in_array($single_model, config('store.item_child_after_vars'))){
        	$variables = $model->item_child_after_vars($this->module, $node, $single_model, $id, $variables);
        }
        return \AdminItem::get_item_view($this->module, $node, $single_model, $id, $variables);
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
			if(request()->has('search')&&request()->input('search')==1&&isset($array['filters'])&&is_array($array['filters'])){
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

	    if($check_item_permission = \AdminItem::check_item_permission($this->module, $node, $action, $id)){
	    	return $check_item_permission;
	    }
	    
        if($action=='delete'||$action=='restore'){
            return \AdminItem::delete_restore_item($this->module, $this->prev, $node, $model, $single_model, $action, $id, $options, $additional_vars);
        } 
        $variables = \AdminItem::get_request_variables($this->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);

        return \AdminItem::get_item_view($this->module, $node, $single_model, $id, $variables);
	}

    public function postModel(Request $request) {
      $model = $request->input('model_node');
      $action = $request->input('action_form');
      $lang_code = $request->input('lang_code');
      $custom_type = $request->input('custom_type');
	  if($lang_code){
		\App::setLocale($lang_code);
	  }
      $response = AdminItem::post_request($this->module, $model, $action, $request, NULL, $custom_type);
      $item = $response[1];
      $node_model = $response[2];
	  if($response[0]->passes()) {
	  	$item = AdminItem::post_request_success($this->module, $request, $model, $item, 'admin', $custom_type);
	  	/*if($model=='indicator'&&$action=='create'){
	  		$indicator = \Solunes\Master\App\Indicator::find($item->id);
		  	if(config('solunes.custom_indicator_values')){
		  		\CustomFunc::update_indicator_values($indicator);
		  	} else {
		  		\FuncNode::update_indicator_values($indicator);
		  	}
	  	}*/
        if(config('solunes.item_post_redirect_success')&&in_array($model, config('solunes.item_post_redirect_success'))){
        	if($custom_redirect = $node_model->item_post_redirect_success($this->module, $model, $item->id, $action)){
        		return $custom_redirect;
        	}
        }
	  	if($request->has('child-page')){
        	return ['type'=>'success', 'model'=>$model, 'action'=>$action, 'item_id'=>$item->id];
	  	} else {
	  		$redirect = $this->module.'/model/'.$model.'/edit/'.$item->id.'/'.$request->input('lang_code');
        	return AdminItem::post_success($action, $redirect);
	  	}
	  } else {
        if(config('solunes.item_post_redirect_fail')&&in_array($model, config('solunes.item_post_redirect_fail'))){
        	if($custom_redirect = $node_model->item_post_redirect_fail($this->module, $model, $action)){
        		return $custom_redirect;
        	}
        }
	  	if($request->has('child-page')){
	  		$redirect = $request->input('child-url');
	  	} else {
	  		$redirect = $this->prev;
	  	}
		return AdminItem::post_fail($action, $redirect, $response[0]);
	  }
    }

	/*public function getIndicators() {
		$array['indicators'] = \Solunes\Master\App\Indicator::get();
      	return view('master::list.indicators', $array);
    }*/

	/*public function changeIndicatorUser($type, $action, $id) {
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
    }*/

	public function getModalEditList($category, $type, $category_id, $node_name) {
		$node = \Solunes\Master\App\Node::where('name', $node_name)->first();
		$fields = [];
		$relation_fields = [];
      	foreach($node->fields()->where('name','!=','id')->get() as $field){
      		$fields[$field->name] = ['name'=>$field->name, 'label'=>$field->label, 'value'=>$field->display_list];
      		$node_name_rel = str_replace('_','-',$field->value);
      		if($field->relation&&$subnode = \Solunes\Master\App\Node::where('name', $node_name_rel)->first()){
      			$subfield_relations = $field->field_relations()->lists('related_field_code')->toArray();
      			foreach($subnode->fields()->whereNotIn('name',['id','name'])->orderBy('order','ASC')->get() as $relation){
      				$relation_display = 'hide';
      				if(in_array($relation->id, $subfield_relations)){
      					$relation_display = 'show';
      				}
      				$relation_fields[$subnode->singular][$relation->name] = ['name'=>$field->name.'-'.$relation->name, 'label'=>$relation->label, 'value'=>$relation_display];
      			}
      		}
      	}
      	$array['node_name'] = $node_name;
      	$array['fields'] = $fields;
      	$array['relation_fields'] = $relation_fields;
      	$array['options'] = ['excel'=>'Ocultar', 'show'=>'Mostrar'];
      	$array['relation_options'] = ['hide'=>'Ocultar', 'show'=>'Mostrar'];
      	return view('master::modal.list-fields', $array);
	}

	public function postModalEditList(Request $request) {
		if($request->has('node_name')&&$node = \Solunes\Master\App\Node::where('name', $request->input('node_name'))->first()){
	      	foreach($node->fields()->where('name','!=','id')->get() as $field){
	      		if($field->display_list!=$request->input($field->name)){
	      			$field->display_list = $request->input($field->name);
	      			$field->save();
	      		}
      			$node_name_rel = str_replace('_','-',$field->value);
	      		if($field->relation&&$subnode = \Solunes\Master\App\Node::where('name', $node_name_rel)->first()){
	      			$subfield_relations = $field->field_relations()->lists('related_field_code')->toArray();
	      			foreach($subnode->fields()->whereNotIn('name',['id','name'])->orderBy('order','ASC')->get() as $relation){
	      				$field_name = $field->name.'-'.$relation->name;
	      				if($request->has($field_name)){
		      				if($request->input($field_name)=='hide'&&in_array($relation->id, $subfield_relations)){
		      					$field->field_relations()->where('related_field_code', $relation->id)->delete();
		      				} else if($request->input($field_name)=='show'&&!in_array($relation->id, $subfield_relations)) {
		      					$new_relation = new \Solunes\Master\App\FieldRelation;
		      					$new_relation->parent_id = $field->id;
		      					$new_relation->related_field_code = $relation->id;
		      					$new_relation->name = $relation->name;
		      					$new_relation->label = $field->label.' - '.$relation->label;
		      					$new_relation->save();
		      				}
	      				}
		      		}
	      		}
	      	}
	      	return redirect($this->prev)->with('message_success','Campos actualizados correctamente.');
	      } else {
	      	return redirect($this->prev)->with('message_error','Hubo un error.');
	      }
	}

	public function getModalFilter($category, $type, $category_id, $node_name) {
		$rejected_ids = [];
		$node = \Solunes\Master\App\Node::where('name', $node_name)->first();
		$filters = $node->filters()->checkCategory($category)->checkDisplay();
		$new_filters = clone $filters;
		if($category=='admin'){
        	$rejected_ids = $filters->where('type','field')->lists('parameter')->toArray();
        	$rejected_parent_ids = $new_filters->where('type','parent_field')->lists('action_value')->toArray();
		} else  {
        	$rejected_ids = $filters->where('category_id', $category_id)->lists('parameter')->toArray();
        	$rejected_parent_ids = [];
		}
		$array['category'] = $category;
		$array['type'] = $type;
		$array['node_id'] = $node->id;
		$array['category_id'] = $category_id;
		$array['fields'] = $node->fields()->whereNotIn('name', $rejected_ids)->filters()->get()->lists('label','name')->toArray();
      	$subfields = [];
      	$new_rejected_ids = ['id'];
    	if(count($rejected_parent_ids)>0){
    		foreach($rejected_parent_ids as $rejected_parent_id){
    			$rejected_parent_id = json_decode($rejected_parent_id, true);
    			$new_rejected_ids[] = $rejected_parent_id['parent_field'];
    		}
    	}
      	foreach($node->fields()->where('relation', 1)->get() as $subfield){
      		$subfield_replace = str_replace('_','-',$subfield->value);
      		if($subnode = \Solunes\Master\App\Node::where('name', $subfield_replace)->first()){
      			$subnode_replace = str_replace('-','_',$subnode->name);
      			$subfields[$subnode_replace]['label'] = $subfield->label;
				$subfields[$subnode_replace]['fields'] = array_merge(['no-subfilter'=>'Sin Subfiltro'], $subnode->fields()->filters()->whereNotIn('name', $new_rejected_ids)->get()->lists('label','name')->toArray());
      		}
      	}
      	$array['subfields'] = $subfields;
      	return view('master::modal.filter', $array);
	}

	public function postModalFilter(Request $request) {
		if($request->has('select_field')&&$request->input('select_field')!==''){
			$category = $request->input('category');
			$type = $request->input('type');
			if($category=='indicator'){
				$display = 'all';
			} else {
				$display = 'user';
			}
			$category_id = $request->input('category_id');
			$node_id = $request->input('node_id');
			$node = \Solunes\Master\App\Node::find($node_id);
			$action_value = NULL;
			$parameter = $request->input('select_field');
			$field = $node->fields()->where('name', $request->input('select_field'))->first();
			if($field->relation){
      			$field_replace = str_replace('-','_',$field->value);
      			$node_replace = str_replace('_','-',$field->value);
				$subfield = 'select_subfield_'.$field_replace;
				if($request->has($subfield)&&$request->input($subfield)!==''&&$request->input($subfield)!=='no-subfilter'&&$node = \Solunes\Master\App\Node::where('name',$node_replace)->first()){
					$field = $node->fields()->where('name', $request->input($subfield))->first();
					$type = 'parent_field';
					$old_parameter = $parameter;
					$parameter = 'parent_relation';
					$action_value = json_encode(['node'=>$node->name, 'parent_field'=>$field->name, 'field'=>$parameter, 'original_field'=>$old_parameter]);
				}
			}
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
			$filter->parameter = $parameter;
			$filter->action_value = $action_value;
			$filter->save();
			$url = $this->prev;
			if (strpos($url, '?') !== false) {
			    $url .= '&search=2';
			} else {
			    $url .= '?search=2';
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

    public function generateItemField($node_name, $field_name, $item_id) {
    	if($node=\Solunes\Master\App\Node::where('name',$node_name)->first()){
    		if($field = $node->fields()->where('name', $field_name)->first()){
    			$item = \FuncNode::node_check_model($node);
    			$item = $item->where('id',$item_id)->first();
    			if($item){
    				$html = \Field::form_input($item, 'edit', $field->toArray(), $field->extras+['subtype'=>'multiple', 'subinput'=>'new', 'subcount'=>$item->id]);
    				return ['name'=>$field->name,'type'=>$field->type,'html'=>(string)$html];
    			} else {
    				return 'Item no encontrado';
    			}
    		} else {
    			return 'Campo no encontrado';
    		}
    	} else {
    		return 'Nodo no encontrado';
    	}
	}

    public function postItemFieldUpdate(Request $request) {
    	$node_name = $request->input('node_name');
    	$field_name = $request->input('field_name');
    	$item_id = $request->input('item_id');
    	$value = $request->input('value');
    	if($node=\Solunes\Master\App\Node::where('name',$node_name)->first()){
    		if($field = $node->fields()->where('name', $field_name)->first()){
    			$item = \FuncNode::node_check_model($node);
    			$item = $item->where('id',$item_id)->first();
    			if($item){
    				$item = \FuncNode::put_data_field($item, $field, $value);
    				$item->save();
    				if($field->type=='select'||$field->type=='radio'||$field->type=='checkbox'){
    					if($field->relation){
    						$field_name = str_replace('_id', '', $field_name);
    						if($relation = $item->$field_name){
    							$value = $relation->name;
    						}
    					} else {
    						if($option = $field->field_options()->where('name',$value)->first()){
    							$value = $option->label;
    						}
    					}
    				}
    				return ['done'=>true,'message'=>'Campo editado correctamente','new_value'=>$value];
    			} else {
    				return ['done'=>false,'message'=>'No se encontró un item','new_value'=>NULL];
    			}
    		} else {
    			return ['done'=>false,'message'=>'No se encontró el campo','new_value'=>NULL];
    		}
    	} else {
    		return ['done'=>false,'message'=>'No se encontró el nodo','new_value'=>NULL];
    	}
	}

}