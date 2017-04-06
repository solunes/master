<?php 

namespace Solunes\Master\App\Helpers;

use Validator;

class AdminItem {

    public static function get_request($single_model, $action, $id, $data, $options = [], $additional_vars = NULL) {
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);

        if (\Gate::denies('node-admin', ['item', $data->module, $node, $action, $id])) {
            return \Login::redirect_dashboard('no_permission');
        }

        if($action=='delete'||$action=='restore'){
            if($node->soft_delete==1){
                $item = $model->withTrashed()->where('id', $id)->first();
            } else {
                $item = $model->find($id);
            }
            if($item){
                if($node->soft_delete==0&&$action=='delete'){
                    $file_fields = $node->fields()->files()->get();
                    \Asset::delete_saved_files($file_fields, $item);
                    if(count($node->children)>0){
                      foreach($node->children as $child){
                        $child_name = $child->table_name;
                        $file_fields = $child->fields()->files()->get();
                        if(is_object($item->$child_name)&&count($item->$child_name)>0){
                            foreach($item->$child_name as $item_child){
                                \Asset::delete_saved_files($file_fields, $item_child);
                            }
                        } else if(!is_object($item->$child_name)&&$item->$child_name) {
                            \Asset::delete_saved_files($file_fields, $item->$child_name);
                        }
                      }
                    }
                }
                $item->$action();
                return redirect($data->prev)->with('message_success', trans('master::admin.'.$action.'_success'));
            } else {
                return redirect($data->prev)->with('message_fail', trans('master::admin.'.$action.'_fail'));
            }
        } else {
            $variables = \AdminItem::get_request_variables($data->module, $node, $model, $single_model, $action, $id, $options, $additional_vars);
            $view = 'master::item.model';
            if($variables['preset_field']===true){
                if($node->name=='indicator'){
                    if(\View::exists('includes.select-parent-indicator')){
                        $view = 'includes.select-parent-indicator';
                    } else {
                        $view = 'master::includes.select-parent-indicator';
                    }
                } else {
                    $view = 'master::includes.select-parent';
                }
            } else if($node->customized){
                $custom_location = 'item.';
                if($node->name=='indicator'){
                    $custom_location = 'master::'.$custom_location;
                }
                $view = $custom_location.$single_model;
            }
            if(request()->has('download-pdf')){
                $variables['pdf'] = true;
                $variables['dt'] = 'view';
                if(config('solunes.custom_field')){
                    $variables['header_title'] = \CustomFunc::custom_pdf_header($node, $id);
                } else {
                    $variables['header_title'] = 'Formulario';
                }
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
            $hidden_array = ['show'];
        } else {
            $hidden_array = ['admin','show'];
        }
        $preset_fields = $node->fields()->displayItem($hidden_array)->preset()->required()->get();
        if($action=='edit'||$action=='view') {
            $item = $model::find($id);
            if($item->parent_id){
                $parent_id = $item->parent_id;
            }
            if(count($preset_fields)>0){
                $variables['parent_nodes'] = [];
                foreach($preset_fields as $preset_field){
                    $subnode = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $preset_field->value))->first();
                    if($node->parent_id==$subnode->id){
                        $iname = 'parent';
                    } else {
                        $iname = $preset_field->value;
                    }
                    $variables['parent_nodes'][$subnode->id] = ['node'=>$subnode,'singular_name'=>$subnode->singular,'iname'=>$iname,'fields'=>$subnode->fields()->whereNotIn('type', ['child','subchild'])->whereNotIn('display_item', ['admin','none'])->get()];
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
                  if($single_model=='indicator'){
                    $preset_items = \Solunes\Master\App\Node::where('folder','form')->get()->lists('singular', 'id');
                  } else {
                    $preset_items = $preset_field->options;
                  }
                  return ['preset_field'=>true, 'single_model'=>$single_model, 'parent'=>$preset_field->trans_name, 'items'=>$preset_items, 'url'=>$url.$separator_sign.$preset_field->name.'='];
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
        $variables['fields'] = $node->fields()->where('type', '!=', 'child')->displayItem($hidden_array)->whereNull('child_table')->checkPermission()->with('translations','field_extras','field_options_active')->get();
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
        foreach($node->fields()->maps()->get() as $field){
            $variables['map_array'][$field->id] = $field;
        }
        return $variables;
    }

    public static function post_request($single_model, $action, $request, $additional_rules = NULL) {
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        if($action=='edit'){
            $id = $request->input('id');
            $item = $model->find($id);
        } else {
            $item = $model;
        }
        $rules = [];
        if($node->dynamic){
            $required_fields = $node->fields()->required()->lists('name')->toArray();
            if(count($required_fields)){
                $rules = array_combine($required_fields, array_fill(1, count($required_fields), 'required'));
            } else {
                $rules = [];
            }
        } else if($action=='send'){
            $rules = $model::$rules_send;
        } else if($action=='edit'){
            $rules = $model::$rules_edit;
        } else if($action=='create'){
            $rules = $model::$rules_create;
        }
        if($additional_rules){
            $rules = $rules + $additional_rules;
        }
        $correctNames = [];
        foreach($node->fields as $field){
            $correctNames[$field->name] = '"'.$field->label.'"';
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($correctNames);
        return [$validator, $item];
    }

    public static function post_request_success($request, $model, $item, $type = 'admin') {
        $node = \Solunes\Master\App\Node::where('name', $model)->first();
        if($type=='admin'){
            if (\Gate::denies('node-admin', ['item', $type, $node, $request->input('action_form'), $request->input('id')])) {
                return \Login::redirect_dashboard('no_permission');
            }
        }
        if($type=='admin'){
            $display_array = ['admin','show'];
        } else {
            $display_array = ['show'];
        }
        $total_ponderation = 0;
        foreach($node->fields()->fillables()->displayItem($display_array)->with('field_extras')->get() as $field){
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
                $subfield_name = str_replace('_', '-', $field->value);
                $sub_node = \Solunes\Master\App\Node::where('name', $subfield_name)->first();
                $sub_node_table = $sub_node->table_name;
                AdminItem::post_subitems($sub_node, $field->name, 'parent_id', $item->id, $sub_node->fields()->displayItem(['admin','show'])->whereNotIn('name', ['id', 'parent_id'])->get());
                foreach($node->fields()->where('child_table', $sub_node_table)->get() as $field_extra){
                    $field_extra_name = $field_extra->name;
                    if($field_extra_name==$sub_node_table.'_count'){
                        $subvalue = count($item->$sub_node_table); 
                    } else {
                        $field_extra_name_fixed = str_replace('_total','',$field_extra_name);
                        $subvalue = 0;
                        foreach($item->$sub_node_table as $sub_item){
                            $subvalue += $sub_item->$field_extra_name_fixed;
                        }
                    }
                    $item->$field_extra_name = $subvalue;
                    $item->save();
                }
            } else {
                $field_name = $field->name;
                if(!$field->multiple){
                    $field_array = [$request->input($field_name)];
                } else if($request->input($field_name)){
                    $field_array = $request->input($field_name);
                } else {
                    $field_array = [];
                }
                $item->$field_name()->sync($field_array);
            }
        }
        foreach($node->indicators as $indicator){
            $node_model = \FuncNode::node_check_model($node);
            $items = \FuncNode::node_check_model($node);
            $array = \AdminList::filter_node(['filter_category_id'=>$indicator->id], $node, $node_model, $items, 'indicator');
            $items = $array['items'];
            if($indicator->type=='count'){
                $indicator_value = $items->count();
            } else {
                $indicator_value = $items->count();
            }
            if($today_indicator = $indicator->indicator_values()->where('date', date('Y-m-d'))->first()) {
            } else {
                $today_indicator = new \Solunes\Master\App\IndicatorValue;
                $today_indicator->parent_id = $indicator->id;
                $today_indicator->date = date('Y-m-d');
            }
            $today_indicator->value = $indicator_value;
            $today_indicator->save();
        }
        \Asset::delete_temp();
        return $item;
    }

    public static function post_success($action, $redirect) {
        $message = trans('master::admin.'.$action.'_success');
        return redirect($redirect)->with('message_success', $message);
    }

    public static function post_fail($action, $redirect, $validator) {
        $message = trans('master::admin.'.$action.'_fail');
        return redirect($redirect)->with('message_error', $message)->withErrors($validator)->withInput();
    }

    public static function post_subitems($node, $single_model, $parent_name, $parent_id, $fields, $parameters = []) {
        if(request()->has($single_model.'_id')){
            $model = \FuncNode::node_check_model($node);
            $model->where($parent_name, $parent_id)->whereNotIn('id', request()->input($single_model.'_id'))->delete();
            foreach( request()->input($single_model.'_id') as $key => $subid ){
              $model = \FuncNode::node_check_model($node);
              $validated = false;
              $fields_array = [];
              foreach($fields as $field){
                $field_input = request()->input($single_model.'_'.$field->name);
                if(isset($field_input[$key])){
                  $fields_array[$field->name] = $field_input[$key];
                }
              }
              if($subid&&$subid!=0){
                if(Validator::make($fields_array, \FuncNode::node_check_rules($node, 'create'))->passes()){
                    $subitem = $model->find($subid);
                    $validated = true;
                }
              } else {
                if(Validator::make($fields_array, \FuncNode::node_check_rules($node, 'edit'))->passes()){
                  $validated = true;
                  $subitem = $model;
                  $subitem->$parent_name = $parent_id;
                }
              }
              if($validated==true){
                foreach($fields as $field){
                    $field_name = $field->name;
                    $subinput = NULL;
                    $field_input = request()->input($single_model.'_'.$field->name);
                    if(isset($field_input[$key])){
                      $subinput = $field_input[$key];
                    }
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
        $title = trans('master::admin.'.$action).' '.$node->singular;
        if($parent_id==NULL||$node->multilevel){
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
        $back = '<a href="'.$back_url.'"><i class="fa fa-arrow-circle-o-left"></i> '.trans('master::admin.back').'</a>';
        $url = request()->fullUrl();
        if(strpos($url, '?') !== false){
            $url .= '&download-pdf=true';
        } else {
            $url .= '?download-pdf=true';
        }
        if($action!='create'){
            $download = ' | <a href="'.url($url).'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.trans('master::admin.generate').' PDF</a>';
        } else {
            $download = NULL;
        }
        $result = '<h3>'.$title.' | '.$back.$download.'</h3>';
        if($action=='edit'&&$i&&$i->created_at){
            $result .= '<p>'.trans('master::admin.created_at').': '.$i->created_at->format('Y-m-d').'</p>';
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
        $return = false;
        if(is_array($item)) {
            if(in_array($key, $item)){
              $return = true;
            }
        } else if(is_object($item)){
            if($item->contains($key)){
                $return = true;
            }
        }
        return $return;
    }

    public static function make_radio_value($key, $item = NULL) {
        $return = false;
        if(is_object($item)){
            if($item->contains($key)){
                $return = true;
            }
        } else if($item||(is_numeric($item)&&$item===0)){
            if($key===$item){
                $return = true;
            }
        } 
        return $return;
    }

}