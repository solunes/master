<?php 

namespace Solunes\Master\App\Helpers;

use Validator;

class AdminList {
   
    public static function get_list($object, $single_model, $extra = []) {
        $module = $object->module;
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = \FuncNode::node_check_model($node);
        if (\Gate::denies('node-admin', ['list', $module, $node, 'list'])) {
            return \Login::redirect_dashboard('no_permission');
        }

        $array = ['module'=>$module, 'node'=>$node, 'model'=>$single_model, 'i'=>NULL, 'filter_category'=>'admin', 'filter_category_id'=>'0', 'filter_type'=>'field', 'filter_node'=>$node->name, 'dt'=>'form', 'id'=>NULL, 'parent'=>NULL, 'action_fields'=>['create','edit','delete']];
        
        if($action_field = $node->node_extras()->where('type','action_field')->first()){
            $array['action_fields'] = json_decode($action_field->value_array, true);
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

        if($node){
            if($node->soft_delete==1&&request()->has('view-trash')&&request()->input('view-trash')=='true'){
                $items->onlyTrashed();
            }
            if($node->translation){
                $items->with('translations');
            }
            if($node->parent){
                $array['parent'] = $node->parent->name;
            }
            if(request()->has('download-excel')){
                $display_fields = ['show','excel'];
            } else {
                $display_fields = ['show'];
            }
            $array['fields'] = $node->fields()->displayList($display_fields)->where('type', '!=', 'field')->with('translations', 'field_options', 'field_extras')->get();
            $relation_fields = $node->fields()->displayList($display_fields)->where('type','relation')->get();
            if(count($relation_fields)>0){
                foreach($relation_fields as $relation){
                    $sub_node = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $relation->value))->first();
                    if($sub_node->translation){
                        $items = $items->with([$relation->trans_name, $relation->trans_name.'.translations']);
                    } else {
                        $items = $items->with($relation->trans_name);
                    }
                }
            }
        }

        $array = \AdminList::filter_node($array, $node, $model, $items, 'admin');
        $items = $array['items'];

        $graphs = $node->node_extras()->whereIn('type', ['graph','parent_graph'])->get();
        $array = \AdminList::graph_node($array, $node, $model, $items, $graphs);

        $items_relations = $node->fields()->where('name','!=','parent_id')->whereIn('type', ['relation','child','subchild'])->get();
        if(count($items_relations)>0){
            foreach($items_relations as $item_relation){
                $items->with($item_relation->trans_name);
            }
        }

        $array['items'] = $items->get();
        if($node->translation==1){
            $array['langs'] = \Solunes\Master\App\Language::get();
        } else {
            $array['langs'] = [];
        }

        if(request()->has('download-excel')){
            return AdminList::generate_query_excel($array);
        } else if(config('solunes.list_extra_actions')&&$extra_actions = \CustomFunc::list_extra_actions($array)){
            return $extra_actions;
        } else {
            return view('master::list.general-list', $array);
        }
    }

    public static function make_fields($langs, $fields, $action_fields = ['edit', 'delete']) {
        if(count($fields)>0){
            $response = '';
            foreach($fields as $field){
                $response .= '<td>'.$field->label.'</td>';
            }
            if(is_array($action_fields)){
                if(in_array('view', $action_fields)){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="edit">'.$language->name.'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.trans('master::admin.view').'</td>';
                    }
                }
                if(in_array('edit', $action_fields)){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="edit">'.$language->name.'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.trans('master::admin.edit').'</td>';
                    }
                }
                if(in_array('delete', $action_fields)){
                    if(request()->has('view-trash')&&request()->input('view-trash')=='true'){
                        $response .= '<td class="restore">'.trans('master::admin.restore').'</td>';
                    } else {
                        $response .= '<td class="delete">'.trans('master::admin.delete').'</td>';
                    }
                }
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public static function make_fields_values($item, $fields, $appends, $type = 'table') {
        if($type=='excel'){
            $response = [];
        } else {
            $response = '';
        }
        foreach($fields as $field){
            $field_name = $field->name;
            $field_trans_name = $field->trans_name;
            $field_type = $field->type;
            $item_val = $item->$field_trans_name;
            $count = 0;
            if($field_type=='string'){
                $value = $item_val;
            } else if($field_type=='text') {
                $value = strip_tags($item_val);
                if (strlen($value) > 300) {
                    $value = substr($value, 0, 300).'...';
                }
            } else if(($item_val||$item_val===0)&&($field_type=='select'||$field_type=='radio')) {
                $value = $field->field_options()->where('name', $item_val)->first()->label;
            } else if($field_type=='relation') {
                if($item->$field_trans_name){
                    $value = $item->$field_trans_name->name;
                } else {
                    $value = NULL;
                }
            } else if($field_type=='child') {
                $url = url('admin/model-list/'.$field->value.'?parent_id='.$item->id);
                if($appends){
                    $url .= '&'.$appends;
                }
                $value = 'Nº: '.count($item_val).' (<a href="'.$url.'">'.trans('master::admin.view').'</a>)';
            } else if($field_type=='subchild') {
                $value = 'Nº: '.count($item_val);
            } else if(($field_type=='image'||$field_type=='file')&&$item_val) {
                if($field->multiple){
                    $array_value = json_decode($item_val, true);
                } else {
                    $array_value = [$item_val];
                }
                $value = '';
                $folder = $field->field_extras->where('type', 'folder')->first()->value;
                foreach($array_value as $key => $val){
                    $count++;
                    if($count>1){
                        $value .= ' | ';
                    }
                    if($field_type=='image'){
                      $file_url = Asset::get_image_path($folder, 'normal', $val);
                    } else {
                      $file_url = Asset::get_file($folder, $val);
                    }
                    if($type=='excel'){
                      $value .= $file_url;
                    } else {
                      $value .= '<a href="'.$file_url.'" target="_blank">'.$val.'</a>';
                    }
                }
            } else if($item_val&&$field_type=='checkbox') {
                $array_value = json_decode($item_val, true);
                $value = '';
                foreach($array_value as $val){
                    $count++;
                    if($count>1){
                        $value .= ' | ';
                    }
                    $value .= $field->field_options()->where('name', $val)->first()->label;
                }
            } else if($field_type=='datetime'||$field->type=='date'||$field->type=='time') {
                if($item_val){
                    if($field_type=='datetime'){
                        $value = $item_val->format('M d, Y H:i');
                    } else {
                        $value = $item_val;
                    }
                } else {
                    $value = '-';
                }
            } else {
                $value = '-';
            }
            if($type=='table'){
                $response .= '<td>'.$value.'</td>';
            } else if($type=='excel'){
                array_push($response, $value);
            }
        }
        return $response;
    }

    public static function make_fields_values_rows($langs, $module, $model, $item, $fields, $appends, $action_fields = ['edit', 'delete']) {
        if(count($fields)>0){
            $response = '';
            $response .= \AdminList::make_fields_values($item, $fields, $appends, 'table');
            if(is_array($action_fields)){
                if(in_array('view', $action_fields)){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="edit">'.AdminList::make_view($module, $model, $appends, $item, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.AdminList::make_view($module, $model, $appends, $item, 'es').'</td>';
                    }
                }
                if(in_array('edit', $action_fields)){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="edit">'.AdminList::make_edit($module, $model, $appends, $item, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.AdminList::make_edit($module, $model, $appends, $item, 'es').'</td>';
                    }
                }
                if(in_array('delete', $action_fields)){
                    if(request()->has('view-trash')&&request()->input('view-trash')=='true'){
                        $response .= '<td class="restore">'.AdminList::make_delete($module, $model, $item, $restore = true).'</td>';
                    } else {
                        $response .= '<td class="delete">'.AdminList::make_delete($module, $model, $item).'</td>';
                    }
                }
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public static function make_section_buttons($model, $item, $page_id = NULL) {
        if(\Auth::check()){
            $result = '<table class="admin-table section-buttons"><tr>'.AdminList::make_panel_title().'</tr>';
            $result .= '<tr>'.AdminList::make_panel_buttons($model, $item, $page_id).'</tr></table>';            
        } else {
            $result = NULL;
        }
        return $result;
    }

    public static function make_create($module, $model, $appends, $id = NULL) {
        if($id==NULL){
            $url = url($module.'/model/'.$model.'/create');
            $string_separator = '?';         
        } else {
            $url = url($module.'/model/'.$model.'/create?parent_id='.$id);           
            $string_separator = '&';         
        }
        if($appends){
            $url .= $string_separator.$appends;
        }
        $action = trans('master::admin.create');
        return ' | <a class="admin_link" href="'.$url.'"><i class="fa fa-plus"></i> '.$action.'</a>';   
    }

    public static function make_edit($module, $model, $appends, $item, $lang_code = NULL) {
        $preurl = $module.'/model/'.$model.'/edit/'.$item->id;
        if($lang_code){
            $preurl .= '/'.$lang_code;
        }
        $url = url($preurl);
        if($appends!=NULL){
            $url .= '?'.$appends;
        }
        return '<a href="'.$url.'">'.trans('master::admin.edit').'</a>';
    }

    public static function make_view($module, $model, $appends, $item, $lang_code = NULL) {
        $preurl = $module.'/model/'.$model.'/view/'.$item->id;
        if($lang_code){
            $preurl .= '/'.$lang_code;
        }
        $url = url($preurl);
        if($appends!=NULL){
            $url .= '?'.$appends;
        }
        return '<a href="'.$url.'">'.trans('master::admin.view').'</a>';
    }

    public static function make_delete($module, $model, $item, $restore = false) {
        if($restore==true){
            $action = 'restore';
            $delete_confirmation = NULL;
        } else {
            $action = 'delete';
            $delete_confirmation = ' onclick="return confirm(\''.trans('master::admin.delete_confirmation').'\');"';
        }
        return '<a href="'.url($module.'/model/'.$model.'/'.$action.'/'.$item->id).'"'.$delete_confirmation.'>'.trans('master::admin.'.$action).'</a>';
    }

    public static function make_panel_title($lang = false) {
        $panel = '';
        if($lang==true){
            foreach(\Solunes\Master\App\Language::get() as $language){
                $panel .= '<td><img src="'.$language->image_path.'" /></td>';
            }
        } else {
            $panel .= '<td><i class="fa fa-pencil"></i></td>';
        }
        $panel .= '<td><i class="fa fa-trash"></i></td>';
        return $panel;
    }

    public static function make_panel_buttons($module, $model, $item, $lang = false) {
        $panel = '';
        if($lang==true){
            foreach(\Solunes\Master\App\Language::get() as $language){
                if($item->hasTranslation($language->code)){
                    $panel .= '<td><a href="'.url($module.'/'.$model.'/edit/'.$item->id.'/'.$language->code).'"><div class="edit"><i class="fa fa-pencil"></i></div></a></td>';
                } else {
                    $panel .= '<td><a href="'.url($module.'/'.$model.'/edit/'.$item->id.'/'.$language->code).'"><div class="edit"><i class="fa fa-plus"></i></div></a></td>';
                }
            }
        } else {
            $panel .= '<td><a href="'.url($module.'/'.$model.'/edit/'.$item->id).'"><div class="edit"><i class="fa fa-pencil"></i></div></a></td>';
        }
        $panel .= '<td>'.AdminList::make_delete($module, $model, $item).'</td>';
        return $panel;
    }

    public static function make_list_header($module, $node, $id, $parent, $appends, $count = 0, $action_fields = ['create']) {
        $title = $node->plural.' ('.$count.')';
        $create = NULL;
        if($id!=NULL){
            if(in_array('create', $action_fields)){
                $create = AdminList::make_create($module, $node->name, $appends, $id);
            } 
            $back_url = url($module.'/model-list/'.$parent);
            if(request()->has('parameters')){
                $parameters = json_decode(request()->input('parameters'));
                $back_url .= '?'.http_build_query($parameters);
            }
            $back = ' | <a href="'.$back_url.'"><i class="fa fa-arrow-circle-o-left"></i> ATRAS</a>';
        } else {
            if(in_array('create', $action_fields)){
                $create = AdminList::make_create($module, $node->name, $appends);
            } 
            $back = '';
        }
        $url = request()->fullUrl();
        if(strpos($url, '?') !== false){
            $download_url = '&download-excel=true';
        } else {
            $download_url = '?download-excel=true';
        }
        if($node->soft_delete==1){
            if(strpos($url, '?') !== false){
                $archive_url = '&view-trash=true';
            } else {
                $archive_url = '?view-trash=true';
            }
            if(request()->has('view-trash')&&request()->input('view-trash')=='true'){
                $final_archive_url = str_replace('view-trash=true', 'view-trash=false', $url);
                $archive_title = 'stop_trash';
            } else {
                $final_archive_url = $url.$archive_url;
                $archive_title = 'view_trash';
            }
            $archive = ' | <a href="'.url($final_archive_url).'"><i class="fa fa-trash"></i> '.trans('master::admin.'.$archive_title).'</a>';
        } else {
            $archive = '';
        }
        $download = ' | <a href="'.url($url.$download_url).'"><i class="fa fa-download"></i> '.trans('master::admin.download').'</a>';
        if(config('solunes.list_header_extra_buttons')){
            $extras = \CustomFunc::list_header_extra_buttons($url, $node, $action_fields, $id);
        } else {
            $extras = NULL;
        }
        $result = '<h3>'.$title.$back.$create.$archive.$download.$extras.'</h3>';
        return $result;
    }

    public static function filter_node($array, $node, $model, $items, $type = 'admin', $parent_field_join = 'parent_id') {
        if($type=='custom'||$type=='indicator'){
            $filters = \Solunes\Master\App\Filter::checkCategory($type)->checkDisplay()->where('category_id', $array['filter_category_id']);
        } else {
            $filters = $node->filters()->checkCategory($type)->checkDisplay();
        }
        $filters = $filters->orderBy('order','ASC')->get();
        if(config('solunes.check_custom_filter')){
            $custom_check = \CustomFunc::check_custom_filter($type, $node);
        } else {
            $custom_check = 'false';
        }
        if(count($filters)>0){
            $appends = NULL;
            $array['additional_queries'] = [];
            /*foreach(request()->all() as $input_key => $input_val){
                if(stripos($input_key, 'f_') === false){
                    $array['additional_queries'][$input_key] = $input_val;
                }
            }*/
            if(request()->input('search')){
                $array['search'] = 1;
            }
            $array['filters'] = [];
            $array['filter_string_options'] = ['none'=>trans('master::fields.none'),'is'=>trans('master::fields.is'),'is_not'=>trans('master::fields.is_not'),'is_greater'=>trans('master::fields.is_greater'),'is_less'=>trans('master::fields.is_less'),'where_in'=>trans('master::fields.where_in')];
            foreach($filters as $filter){
                $field_name = $filter->parameter;
                $array['filters'][$field_name] = ['subtype'=>$filter->subtype, 'id'=>$filter->id];
                if($type=='custom'||$type=='indicator'){
                    $node = $filter->node;
                    if($type=='custom'){
                        $array['filters'][$field_name]['node_name'] = $node->name;
                    }
                }
                if(config('solunes.custom_filter')&&$custom_check!='false'){
                    $custom_array = \CustomFunc::custom_filter($custom_check, $array, $items, $appends, $node, $model, $filter, $type, $field_name, $parent_field_join);
                    $array = $custom_array['array'];
                    $appends = $custom_array['appends'];
                    $items = $custom_array['items'];
                } else {
                    if(config('solunes.custom_filter_field')&&$filter->type=='custom'){
                        $custom_array = \CustomFunc::custom_filter_field($array, $items, $appends, $field_name, $custom_data);
                        $array = $custom_array['array'];
                        $appends = $custom_array['appends'];
                        $items = $custom_array['items'];
                    } else {
                        // Calcular Custom Value
                        $custom_array = \AdminList::filter_custom_value($array, $appends, $node, $filter, $type, $field_name);
                        $array = $custom_array['array'];
                        $appends = $custom_array['appends'];
                        $custom_value = $custom_array['custom_value'];
                        $field = $custom_array['field'];
                        // Obtener items segun tipo
                        $custom_array = \AdminList::filter_items_get($items, $node, $model, $filter, $field, $field_name, $custom_value, $parent_field_join);
                        $items = $custom_array['items'];
                        $date_model = $custom_array['date_model'];
                        // Corregir campos de fecha
                        $array = \AdminList::filter_date_field($array, $date_model, $filter, $field_name);
                    }
                }
            }
            $array['filter_values'] = $appends;
            if($appends){
                $appends = 'parameters='.htmlentities(json_encode($appends));
            }
            $array['appends'] = $appends;
        } else {
            $array['appends'] = NULL;
            $array['filters'] = false;
        }
        $array['items'] = $items;
        return $array;
    }

    public static function filter_custom_value($array, $appends, $node, $filter, $type, $field_name) {
        $custom_value = [];
        if($filter->subtype=='date'){
            $appends['f_'.$field_name.'_from'] = NULL;
            $appends['f_'.$field_name.'_to'] = NULL;
        } else if($filter->subtype=='string'){
            $appends['f_'.$field_name] = NULL;
            $appends['f_'.$field_name.'_action'] = NULL;
        } else {
            $appends['f_'.$field_name] = NULL;
        }
        if($type=='indicator'){
            $custom_value = json_decode($filter->action_value, true);
            if($filter->subtype=='date'){
                if($custom_value&&isset($custom_value['is_greater'])){
                    $appends['f_'.$field_name.'_from'] = $custom_value['is_greater'];
                }
                if($custom_value&&isset($custom_value['is_less'])){
                    $appends['f_'.$field_name.'_to'] = $custom_value['is_less'];
                }
            } else if($filter->subtype=='string'){
                if($custom_value){
                    $appends['f_'.$field_name] = key($custom_value);
                    $appends['f_'.$field_name.'_action'] = $custom_value[$appends['f_'.$field_name]];
                }
            } else if($custom_value){
                $appends['f_'.$field_name] = array_keys($custom_value);
            }
        } else if($filter->subtype=='date'){
            if($field_from = request()->input('f_'.$field_name.'_from')){ 
                $custom_value[$field_from] = 'is_greater';
            }
            $appends['f_'.$field_name.'_from'] = $field_from;
            if($field_to = request()->input('f_'.$field_name.'_to')){ 
                $custom_value[$field_to] = 'is_less';
            }
            $appends['f_'.$field_name.'_to'] = $field_to;
        } else if($filter->subtype=='string') {
            if($field_string = request()->input('f_'.$field_name)){ 
                $custom_value[$field_string] = request()->input('f_'.$field_name.'_action');
                $appends['f_'.$field_name] = $field_string;
            }
        } else if(request()->input('f_'.$field_name)){
            foreach(request()->input('f_'.$field_name) as $select_key => $select_value){
                $custom_value[$select_value] = 'is';
            }
            $appends['f_'.$field_name] = array_keys($custom_value);
        }
        $field = $node->fields()->where('name', $field_name)->first();
        if($filter->type=='parent_field'){
            $array['filters'][$field_name]['label'] = strtoupper($node->name).': '.$field->label;
        } else {
            $array['filters'][$field_name]['label'] = $field->label;
        }
        $array['filters'][$field_name]['options'] = $field->options;
        return ['array'=>$array, 'appends'=>$appends, 'custom_value'=>$custom_value, 'field'=>$field];
    }

    public static function filter_items_get($items, $node, $model, $filter, $field, $field_name, $custom_value, $parent_field_join = 'parent_id') {
        $custom_value_count = count($custom_value);
        if($filter->type=='field'){
            $date_model = $model;
            if($custom_value_count>0){  
                $items = \AdminList::filter_custom_array($items, $custom_value, $field, $field_name);
            }
        } else if($filter->type=='parent_field') {
            $parent_model = $node->model;
            $date_model = $parent_model;
            if($custom_value_count>0){
                $parent_array = $parent_model::whereNotNull('id');
                $parent_array = \AdminList::filter_custom_array($parent_array, $custom_value, $field, $field_name);
                $parent_array = $parent_array->lists($parent_field_join)->toArray();
            }
            $items = $items->whereIn('id', $parent_array);
        }
        return ['items'=>$items, 'date_model'=>$date_model];
    }

    public static function filter_date_field($array, $date_model, $filter, $field_name) {
        if($filter->subtype=='date'){
            if($first_day_field = $date_model::whereNotNull($field_name)->orderBy($field_name,'ASC')->first()){
                $array['filters'][$field_name]['first_day'] = $first_day_field->$field_name;
            } else {
                $array['filters'][$field_name]['first_day'] = NULL;
            }
            if($last_day_field = $date_model::orderBy($field_name,'DESC')->first()){
                $array['filters'][$field_name]['last_day'] = date('Y-m-d', strtotime($last_day_field->$field_name . ' +1 day'));
            } else {
                $array['filters'][$field_name]['last_day'] = NULL;
            }
            $array['filters'][$field_name]['label_from'] = $array['filters'][$field_name]['label'].' ('.trans('master::fields.from').')';
            $array['filters'][$field_name]['label_to'] = $array['filters'][$field_name]['label'].' ('.trans('master::fields.to').')';
        }
        return $array;
    }

    public static function filter_custom_array($items, $custom_value, $field, $field_name) {
        $items = $items->where(function ($query) use($custom_value, $field, $field_name) {
            $count = 0;
            foreach($custom_value as $custom_val => $custom_action){
                if($count>0){
                    $main_action = 'orWhere';
                } else {
                    $main_action = 'where';
                }
                if($custom_action=='is'){
                    $action = '=';
                } else if($custom_action=='is_not'){
                    $action = '!=';
                } else if($custom_action=='is_greater'){
                    $action = '>=';
                } else if($custom_action=='is_less'){
                    $action = '<=';
                } else if($custom_action=='where_in'){
                    $action = '=';
                    $main_action .= 'In';
                    $custom_val = explode(',', $custom_val);
                } else {
                    $action = 'none';
                }
                if($action!='none'){
                    if($field->type=='checkbox'){
                        $custom_val = '%'.$custom_val.'%';
                        $action = 'LIKE';
                    }
                    if($field->type=='field'){
                        $main_action .= 'Has';
                        $query = $query->$main_action($field_name, function ($subquery) use($field, $custom_val) {
                            $subquery->where($field->value.'_id', $custom_val);
                        });
                    } else if($custom_action=='where_in') {
                        $query = $query->$main_action($field->name, $custom_val);
                    } else {
                        $query = $query->$main_action($field->name, $action, $custom_val);
                    }
                    if($field->type!='date'){
                        $count++;
                    }
                }
            }
        });
        return $items;
    }

    public static function graph_node($array, $node, $model, $items, $graphs) {
        $graph_count = 0;
        if(count($graphs)>0&&$model::count()>0){
            foreach($graphs as $graph){
                $graph_value = json_decode($graph->value_array, true);
                foreach($graph_value as $graph_item){
                    $cloned_model = clone $items;
                    if($graph->type=='parent_graph'){
                        $relation_table = $graph_item['parent'];
                        $relation_field = $graph_item['data'];
                        $node_table = $node->table_name;
                        $graph_item_name = $graph_item['name'];
                        $graph_model_array = $cloned_model->lists('id')->toArray();
                        $graph_model = $model::leftJoin($relation_table, $relation_table.'.id', '=', $node_table.'.'.$relation_field)->whereIn($node_table.'.id', $graph_model_array)->groupBy($graph_item_name)->select($graph_item_name, \DB::raw('count(*) as total'))->get();
                    } else {
                        $graph_item_name = $graph_item;
                        $graph_model = $cloned_model->groupBy($graph_item_name)->select($graph_item_name, \DB::raw('count(*) as total'))->get();
                    }
                    if(count($graph_model)>0){
                        $graph_count++;
                        $subitems = [];
                        if($graph->parameter=='lines'){
                          $field_name = 'created_at';
                          foreach($graph_model as $graph_subitem){
                            $first_day = $model::orderBy($field_name,'ASC')->first()->$field_name->format('Y-m-d');
                            $last_day = $model::orderBy($field_name,'DESC')->first()->$field_name->format('Y-m-d');
                            $range = range(1,12);
                            $count = '[';
                            foreach($range as $month){
                              $cloned_model = clone $items;
                              if($graph->type=='parent_graph'){
                                $count .= $model::leftJoin($relation_table, $relation_table.'.id', '=', $node_table.'.'.$relation_field)->whereIn($node_table.'.id', $graph_model_array)->where($graph_item_name, $graph_subitem->$graph_item_name)->where( \DB::raw('MONTH('.$node_table.'.created_at)'), '=', $month )->count().',';
                              } else {
                                $count .= $cloned_model->where($graph_item_name, $graph_subitem->$graph_item_name)->where( \DB::raw('MONTH(created_at)'), '=', $month )->count().',';
                              }
                            }
                            $count .= ']';
                            $subitems[$graph_subitem->$graph_item_name] = $count;
                          }
                        }
                        $array['graphs'][$graph_item_name.'-'.$graph->parameter] = ['name'=>$graph_item_name,'type'=>$graph->parameter,'items'=>$graph_model,'subitems'=>$subitems];
                    }
                }
            }
            if($graph_count<2){
                $array['graph_col_size'] = 12;
            } else if($graph_count==2){
                $array['graph_col_size'] = 6;
            } else {
                $array['graph_col_size'] = 4;
            }
        }
        return $array;
    }

    public static function generate_query_excel($array) {
        $dir = public_path('excel');
        array_map('unlink', glob($dir.'/*'));
        $file = \Excel::create($array['node']->plural.'_'.date('Y-m-d'), function($excel) use($array) {
            $sheet_title = str_replace(' ', '-', $array['node']->plural);
            $sheet_title = substr(preg_replace('/[^A-Za-z0-9\-]/', '', $sheet_title), 0, 30);
            $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->sheet($sheet_title, function($sheet) use($array) {
                $col_array = [];
                foreach($array['fields'] as $field){
                    array_push($col_array, $field->label);
                }
                $sheet->row(1, $col_array);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });

                $fila = 2;
                foreach($array['items'] as $item){
                    $sheet->row($fila, AdminList::make_fields_values($item, $array['fields'], '','excel'));
                    $fila++;
                }
            });
        })->store('xlsx', $dir, true);
        return response()->download($file['full']);
    }

}