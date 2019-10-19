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

class CustomerAdminController extends Controller {

	protected $request;
	protected $url;

	public function __construct(UrlGenerator $url) {
	  $this->middleware('auth');
	  $this->middleware('permission:dashboard')->only('getIndex');
	  $this->prev = $url->previous();
	  $this->module = 'customer-admin';
	}

	public function getModelList($single_model) {
		$user = auth()->user();
		$customer = $user->customer;
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();

	    $object = $this;
        $module = $object->module;
        $model = \FuncNode::node_check_model($node);

        $array = ['module'=>$module, 'node'=>$node, 'model'=>$single_model, 'i'=>NULL, 'filter_category'=>'admin', 'filter_category_id'=>'0', 'filter_type'=>'field', 'filter_node'=>$node->name, 'dt'=>'form', 'id'=>NULL, 'parent'=>NULL, 'action_nodes'=>['back','create','excel'], 'action_fields'=>['edit','delete']];
        
        if($action_field = $node->node_action_fields->first()){
            $array['action_fields'] = json_decode($action_field->value_array, true);
        }
        if($action_node = $node->node_action_nodes->first()){
            $array['action_nodes'] = json_decode($action_node->value_array, true);
        }
        if(request()->has('parent_id')){
            $id = request()->input('parent_id');
            $array['id'] = $id;
            $items = $model->whereHas('parent', function($q) use($id) {
                $q->where('id', $id);
            });
        } else {
            $items = $model->whereNotNull('id');
        }

        if($node->translation==1){
            $array['langs'] = \Solunes\Master\App\Language::get();
        } else {
            $array['langs'] = [];
        }

        if($node->soft_delete==1&&request()->has('view-trash')&&request()->input('view-trash')=='true'){
            $items->onlyTrashed();
        }
        if($node->translation){
            $items->with('translations');
        }
        if($node->parent){
            $array['parent'] = $node->parent->name;
        }
        if($node->multilevel){
            $items = $items->whereNull('parent_id')->with('children','children.children');
        }
        if($single_model=='customer'){
        	$items =  $items->where('id', $customer->id);// IMPORTANTE
        } else {
        	$items =  $items->where('customer_id', $customer->id);// IMPORTANTE
        }
        $node_array = [$node->id];
        if(request()->has('download-excel')){
            $display_fields = ['show','excel'];
            foreach($node->children as $child){
                array_push($node_array, $child->id);
            }
        } else {
            $display_fields = ['show'];
        }
        $array['fields'] = $node->fields()->displayList($display_fields)->where('type', '!=', 'field')->with('translations', 'field_options', 'field_extras', 'field_relations')->get();
        $field_ops = \Solunes\Master\App\Field::whereIn('parent_id', $node_array)->displayList($display_fields)->has('field_options')->with('field_options')->get();
        $array['field_options'] = [];
        foreach($field_ops as $field_op){
            foreach($field_op->field_options as $field_option){
                $array['field_options'][$field_op->name][$field_option->name] = $field_option->label;
            }
        }
        $relation_fields = $node->fields()->displayList($display_fields)->where('relation', 1)->with('field_relations')->get();
        if(count($relation_fields)>0){
            foreach($relation_fields as $relation){
                $sub_node = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $relation->value))->first();
                // Error en caso de que no esté bien definida la relación
                if(!$sub_node){
                    $message = 'Agregar a nodes.xlsx, hoja "edit-fields" las <br>siguientes lineas o cambiar su "value" por el correcto y luego haga un deploy:<br><br>';
                    $message .= ''.$single_model.' | '.$relation->name.' | relation | 0<br>';
                    $message .= ''.$single_model.' | '.$relation->name.' | type | string';
                    abort(506, $message);
                }
                if($sub_node->translation){
                    $items = $items->with([$relation->trans_name, $relation->trans_name.'.translations']);
                } else {
                    $items = $items->with($relation->trans_name);
                }
                foreach($relation->field_relations as $field_relation){
                    if($related_field = $field_relation->related_field){
                        foreach($related_field->field_options as $field_option){
                            $array['field_options'][$related_field->name][$field_option->name] = $field_option->label;
                        }
                    }
                }
            }
        }

        $array = \AdminList::filter_node($array, $node, $model, $items, 'admin');
        $items = $array['items'];
        if(config('solunes.custom_admin_get_list')){
            $items = \CustomFunc::custom_admin_get_list($module, $node, $items, $array);
        }
        $items_relations = $node->fields()->where('name','!=','parent_id')->where(function ($query) {
            $query->whereIn('type', ['child','subchild'])->orWhere('relation', 1);
        })->get();
        if(count($items_relations)>0){
            foreach($items_relations as $item_relation){
                $items->with($item_relation->trans_name);
            }
        }

        $array['items_count'] = $items->count();
        if(request()->has('download-excel')||request()->has('download-pdf')){
            $array['items'] = $items->get();
        } else {
            $array['items'] = $items->paginate(config('solunes.pagination_count'));
        }
        $array['pdf'] = false;

		//$array = AdminList::get_list($this, $model);
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
            return view('master::list.subadmin-list', $array);
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

    public function getMyInbox($id = NULL) {
        $array['items'] = \Solunes\Master\App\Inbox::userInbox(auth()->user()->id)->with('me','other_users','last_message')->orderBy('updated_at','DESC')->paginate(25);    
        $item = NULL;
        if($id){
            $item = \Solunes\Master\App\Inbox::userInbox(auth()->user()->id)->where('id', $id)->with('me','other_users','last_message')->first();    
        }
        $array['preset_item'] = $item;
        return view('master::list.my-inbox-2', $array);
    }

    public function getInboxConversation($id) {
        $array['item'] = \Solunes\Master\App\Inbox::userInbox(auth()->user()->id)->where('id', $id)->with('me','other_users','last_message')->first();    
        return view('master::includes.chat', $array);
    }

    public function getCreateInbox() {
        $node = \Solunes\Master\App\Node::where('name','inbox-message')->first();
        $array['attachment_field'] = $node->fields()->where('name','attachments')->first();
        $array['users'] = \App\User::where('id', '!=', auth()->user()->id)->get();
        return view('master::list.create-inbox-2', $array);
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
            return redirect('customer-admin/my-inbox/'.$inbox->id);
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
        return view('master::list.view-inbox-2', $array);
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
                return redirect($this->prev);
            } else {
                return redirect($this->prev)->with('message_error','Hubo un error al enviar el mensaje.');
            }
        } else {
            return redirect($this->prev)->with('message_error','Debe introducir algún texto o archivo para enviar.');
        }
    }

}