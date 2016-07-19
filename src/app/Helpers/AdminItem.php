<?php 

namespace Solunes\Master\App\Helpers;

use Validator;

class AdminItem {

    public static function get_request($single_model, $action, $id, $data, $options = [], $additional_vars = NULL) {
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = $node->model;
        if (\Gate::denies('node-admin', ['item', $data->module, $node, $action, $id])) {
            return \Login::redirect_dashboard('no_permission');
        }

        if($action=='delete'||$action=='restore'){
            if($item = $model::withTrashed()->where('id', $id)->first()){
                if($node->soft_delete==0&&$action=='delete'){
                    $file_fields = $node->fields()->whereIn('type', ['image','file'])->get();
                    \Asset::delete_saved_files($file_fields, $item);
                    if(count($node->children)>0){
                      foreach($node->children as $child){
                        $child_name = $child->table_name;
                        $file_fields = $child->fields()->whereIn('type', ['image','file'])->get();
                        \Asset::delete_saved_files($file_fields, $item->$child_name);
                      }
                    }
                }
                $item->$action();
                return redirect($data->prev)->with('message_success', trans('admin.'.$action.'_success'));
            } else {
                return redirect($data->prev)->with('message_fail', trans('admin.'.$action.'_fail'));
            }
        } else {
            $variables = \AdminItem::get_request_variables($data->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);
            if($variables['preset_field']===true){
                $view = 'master::includes.select-parent';
            } else if($node->customized){
                $view = 'item.'.$single_model;
            } else {
                $view = 'master::item.model';
            }
            if(request()->has('download-pdf')){
                $variables['pdf'] = true;
                $variables['dt'] = 'view';
                $variables['title'] = 'Formulario de '.$node->singular;
                $variables['site'] = \Solunes\Master\App\Site::find(1);
                $pdf = \PDF::loadView($view, $variables);
                $header = \View::make('pdf.header', $variables);
                return $pdf->setPaper('letter')->setOption('header-html', $header->render())->stream($node->singular.'_'.date('Y-m-d').'.pdf');
            } else {
                return view($view, $variables);
            }
        }
    }

    public static function get_request_variables($module, $node, $model, $single_model, $action, $id, $options, $additional_vars = NULL) {
        $variables = ['module'=>$module, 'node'=>$node, 'model'=>$single_model, 'action'=>$action, 'id'=>$id, 'preset_field'=>false, 'dt'=>'form', 'pdf'=>false];
        $parent_id = NULL;
        if($module=='process'){
            $hidden_array = ['admin','none'];
        } else {
            $hidden_array = ['none'];
        }
        $preset_fields = $node->fields()->whereNotIn('display_item', $hidden_array)->where('preset', 1)->where('required', 1)->get();
        if($action=='edit'||$action=='view') {
            $item = $model::find($id);
            if($item->parent_id){
                $parent_id = $item->parent_id;
            }
            if(count($preset_fields)>0){
                $variables['parent_nodes'] = [];
                foreach($preset_fields as $subnode){
                    $subnode = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $subnode->value))->first();
                    if($node->parent_id==$subnode->id){
                        $iname = 'parent';
                    } else {
                        $iname = $subnode->table_name;
                    }
                    $variables['parent_nodes'][$subnode->id] = ['node'=>$subnode,'iname'=>$iname,'fields'=>$subnode->fields()->whereNotIn('type', ['child','subchild'])->whereNotIn('display_item', ['admin','none'])->get()];
                }
            }
            $variables['activities'] = \Solunes\Master\App\Activity::where('node_id', $node->id)->where('item_id', $id)->orderBy('created_at', 'DESC')->get();
            if($action=='view'){
                $variables['dt'] = 'view';
            }
        } else {
            $item = NULL;
            if(request()->has('parent_id')){
                $parent_id = request()->input('parent_id');
            } 
            if(count($preset_fields)>0){
              foreach($preset_fields as $preset_field){
                if(!request()->has($preset_field->name)){
                  $url = request()->fullUrl();
                  if(stripos($url, '?') !== false){
                    $separator_sign = '&';
                  } else {
                    $separator_sign = '?';
                  }
                  return ['preset_field'=>true, 'parent'=>$preset_field->trans_name, 'items'=>$preset_field->options, 'url'=>$url.$separator_sign.$preset_field->name.'='];
                }
              }
            }
            $variables['activities'] = [];
        }
        if($additional_vars!=NULL){
            $variables = array_merge($variables, $additional_vars);
        }
        $variables['parent_id'] = $parent_id;
        $variables['i'] = $item;
        $variables['fields'] = $node->fields()->where('type', '!=', 'child')->whereNotIn('display_item', $hidden_array)->with('translations')->get();
        if($node->fields()->whereIn('type', ['image', 'file'])->count()>0){
            $variables['files'] = true;
        } else {
            $variables['files'] = false;
        }
        foreach($node->fields()->has('field_conditionals')->with('field_conditionals')->get() as $field){
            foreach($field->field_conditionals as $conditional){
                $variables['conditional_array'][$conditional->id] = $conditional;
            }
        }
        return $variables;
    }

    public static function post_request($single_model, $action, $request, $additional_rules = NULL) {
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = $node->model;
        if($action=='send'){
            $rules = $model::$rules_send;
            $item = new $model;
        } else if($action=='edit'){
            $id = $request->input('id');
            $rules = $model::$rules_edit;
            $item = $model::find($id);
        } else if($action=='create'){
            $rules = $model::$rules_create;
            $item = new $model;
        }
        if($additional_rules){
            $rules = $rules + $additional_rules;
        }
        $correctNames = [];
        foreach($node->fields as $field){
            $correctNames[$field->name] = '"'.trans('fields.'.$field->name).'"';
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($correctNames);
        return [$validator, $item];
    }

    public static function post_request_success($request, $model, $item, $type = 'admin') {
        $node = \Solunes\Master\App\Node::where('name', $model)->first();
        if (\Gate::denies('node-admin', ['item', $type, $node, $request->input('action'), $request->input('id')])) {
            return \Login::redirect_dashboard('no_permission');
        }
        if($type=='admin'){
            $display_array = ['none'];
        } else {
            $display_array = ['item_admin','none'];
        }
        $total_ponderation = 0;
        foreach($node->fields()->whereNotIn('type', ['child', 'subchild', 'field'])->whereNotIn('display_item', $display_array)->with('field_extras')->get() as $field){
            $field_name = $field->name;
            $input = NULL;
            if($request->has($field_name)) {
                $input = $request->input($field_name);
            }
            if($input&&$input!=0&&$pond = $field->field_extras()->where('type','ponderation')->first()){
                $total_ponderation = $total_ponderation+$pond->value;
            }
            $item = \FuncNode::put_data_field($item, $field, $input);
        }
        if($total_ponderation>0){
            $item->total_ponderation = $total_ponderation;
        }
        $item->save();
        foreach($node->fields()->whereIn('type', ['subchild', 'field'])->get() as $field){
            if($field->type=='subchild'){
                $sub_node = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $field->value))->first();
                AdminItem::post_subitems($sub_node->model, $field->name, 'parent_id', $item->id, $sub_node->fields()->whereNotIn('name', ['id', 'parent_id'])->get());
            } else {
                $field_name = $field->name;
                if($field->multiple){
                    $item->$field_name()->sync($request->input($field_name));
                } else {
                    $item->$field_name()->sync([$request->input($field_name)]);
                }
            }
        }
        \Asset::delete_temp();
        return $item;
    }

    public static function post_success($action, $redirect) {
        return redirect($redirect)->with('message_success', trans('admin.'.$action.'_success'));
    }

    public static function post_fail($action, $redirect, $validator) {
        return redirect($redirect)->with('message_error', trans('admin.'.$action.'_fail'))->withErrors($validator)->withInput();
    }

    public static function post_subitems($model, $single_model, $parent_name, $parent_id, $fields, $parameters = []) {
        if(request()->has($single_model.'_id')){
            $model::where($parent_name, $parent_id)->whereNotIn('id', request()->input($single_model.'_id'))->delete();
            foreach( request()->input($single_model.'_id') as $key => $subid ){
              $validated = false;
              $fields_array = [];
              foreach($fields as $field){
                $fields_array[$field->name] = request()->input($single_model.'_'.$field->name)[$key];
              }
              if($subid&&$subid!=0){
                if(Validator::make($fields_array, $model::$rules_edit)->passes()){
                    $subitem = $model::find($subid);
                    $validated = true;
                }
              } else {
                if(Validator::make($fields_array, $model::$rules_create)->passes()){
                  $validated = true;
                  $subitem = new $model;
                  $subitem->$parent_name = $parent_id;
                }
              }
              if($validated==true){
                foreach($fields as $field){
                    $field_name = $field->name;
                    $subinput = NULL;
                    $subinput = request()->input($single_model.'_'.$field_name)[$key];
                    $subitem = \FuncNode::put_data_field($subitem, $field, $subinput);
                }
                $subitem->save();
              }
            }
            return true;
        }
        return false;
    }

    public static function make_form($module, $model, $action, $images = false) {
        $result = array('name'=>$action.'_'.$model, 'id'=>$action.'_'.$model, 'role'=>'form', 'url'=>$module.'/model/', 'class'=>'form-horizontal prevent-double-submit', 'autocomplete'=>'off');
        if($images==true){
            $result['files'] = true;
        }
        return $result;
    }

    public static function make_item_header($i, $module, $node, $action, $parent_id = false) {
        $title = trans('admin.'.$action).' '.$node->singular;
        if($parent_id==NULL){
            $back_url = url($module.'/model-list/'.$node->name);
            $separator_sign = '?';
        } else {
            $back_url = url($module.'/model-list/'.$node->name.'?parent_id='.$parent_id);
            $separator_sign = '&';
        }
        if(request()->has('parameters')){
            $parameters = json_decode(request()->input('parameters'));
            $back_url .= $separator_sign.http_build_query($parameters);
        }
        $back = '<a href="'.$back_url.'"><i class="fa fa-arrow-circle-o-left"></i> ATRAS</a>';
        $url = request()->fullUrl();
        if(strpos($url, '?') !== false){
            $url .= '&download-pdf=true';
        } else {
            $url .= '?download-pdf=true';
        }
        if($action!='create'){
            $download = ' | <a href="'.url($url).'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.trans('admin.generate').' PDF</a>';
        } else {
            $download = NULL;
        }
        $result = '<h3>'.$title.' | '.$back.$download.'</h3>';
        if($action=='edit'&&$i&&$i->created_at){
            $result .= '<p>'.trans('admin.created_at').': '.$i->created_at->format('Y-m-d').'</p>';
        }
        return $result;
    }   

    public static function make_value($name, $item = NULL) {
        if($item&&$item->$name){
            return $item->$name;
        } else {
            return NULL;
        }
    }

    public static function make_checkbox_value($key, $item = NULL) {
        if($item==NULL){
            return false;
        } else {
            if($item->contains($key)){
              return true;
            } else {
              return false;
            }
        }
    }

    public static function make_radio_value($key, $item = NULL) {
        $return = false;
        if(is_object($item)){
            if($item->contains($key)){
                $return = true;
            }
        } else if($item){
            if($key==$item){
                $return = true;
            }
        } 
        return $return;
    }

}