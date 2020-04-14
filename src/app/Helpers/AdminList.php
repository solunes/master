<?php 

namespace Solunes\Master\App\Helpers;

use Validator;

class AdminList {
   
    public static function get_list($object, $single_model, $extra = []) {
        $module = $object->module;
        $node = \Solunes\Master\App\Node::where('name', $single_model)->with('node_action_fields','node_action_nodes','node_graphs')->first();
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

        $array = \AdminList::graph_node($array, $node, $model, $items, $node->node_graphs);

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
        return $array;
    }

    public static function check_list_permission($module, $node) {
        if (\Gate::denies('node-admin', ['list', $module, $node, 'list'])) {
            return \Login::redirect_dashboard('no_permission');
        } else {
            return false;
        }
    }

    public static function make_fields($langs, $fields, $action_fields = ['edit', 'delete']) {
        if(isset($action_fields['subadmin_child_field'])){
            $node_name = $action_fields['subadmin_child_field'];
            $subaction_fields = config('solunes.customer_dashboard_nodes.'.$node_name);
            $action_fields = [];
            foreach($subaction_fields as $subaction_field => $key){
                if($subaction_field=='create'||$subaction_field=='download'){
                } else {
                    $action_fields[] = $subaction_field;
                }
            }
        } else if(isset($action_fields['child_field'])){
            $node_name = $action_fields['child_field'];
            $node = \Solunes\Master\App\Node::where('name',$node_name)->first();
            if($node_extra = $node->node_extras()->where('type','action_field')->first()){
                $subaction_fields = json_decode($node_extra->value_array, true);
            } else {
                $subaction_fields = ['edit','delete'];
            }
            $action_fields = $subaction_fields;
        }
        if(config('solunes.custom_admin_field_actions')){
            $action_fields = \CustomFunc::custom_admin_field_actions(request()->segment(3), $fields, $action_fields);
        }
        if(count($fields)>0){
            $response = '';
            foreach($fields as $field){
                $response .= '<td>'.$field->label.'</td>';
                if($field->relation&&count($field->field_relations)>0){
                  foreach($field->field_relations as $field_relation){
                    $response .= '<td>'.$field_relation->label.'</td>';
                  }
                }
            }
            if(request()->has('download-pdf')){
                if (($key = array_search('edit', $action_fields)) !== false) {
                    unset($action_fields[$key]);
                }
                if (($key = array_search('delete', $action_fields)) !== false) {
                    unset($action_fields[$key]);
                }
                if (($key = array_search('restore', $action_fields)) !== false) {
                    unset($action_fields[$key]);
                }
            }
            foreach($action_fields as $action_field){
                if($action_field=='edit'){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="edit">'.$language->name.'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.trans('master::admin.edit').'</td>';
                    }
                } else if($action_field=='delete'){
                    if(request()->has('view-trash')&&request()->input('view-trash')=='true'){
                        $response .= '<td class="restore">'.trans('master::admin.restore').'</td>';
                    } else {
                        $response .= '<td class="delete">'.trans('master::admin.delete').'</td>';
                    }
                } else if($action_field=='view'){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="edit">'.$language->name.'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.trans('master::admin.view').'</td>';
                    }
                } else if($action_field=='create-child'){
                    $response .= '<td class="restore">'.trans('master::admin.create-child').'</td>';
                } else {
                    $response .= \SolunesFunc::get_action_field_labels($response, $action_field, $langs);
                    $response .= \CustomFunc::get_action_field_labels($response, $action_field, $langs);
                }
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public static function make_fields_values($item, $fields, $field_options, $appends, $type = 'table', $database = false, $just_last = false) {
        if($type=='excel'){
            $response = [];
        } else {
            $response = '';
        }
        foreach($fields as $field){
            $value = \AdminList::get_field_value($field, $item, $field_options, $appends, $type, $database, $just_last);
            if($type=='table'){
                $response .= '<td ';
                if(in_array($field->type, ['field','custom','title','content','child','subchild','hidden','map','barcode','file','image','text'])){
                    $response .= 'class="ineditable" ';
                }
                $response .= 'data-field="'.$field->name.'" data-id="'.$item->id.'">'.$value.'</td>';
            } else if($type=='excel'){
                array_push($response, $value);
            }
            if($field->relation&&count($field->field_relations)>0){
                foreach($field->field_relations as $field_relation){
                    $relation_name = $field->trans_name;
                    $related_field = $field_relation->related_field;
                    if($related_field){
                        $new_value = \AdminList::get_field_value($related_field, $item->$relation_name, $field_options, $appends, $type, $database, $just_last);
                    } else {
                        $new_value = NULL;
                    }
                    if($type=='table'){
                        $response .= '<td class="ineditable" data-field="'.$field->name.'-'.$relation_name.'" data-id="'.$item->id.'">'.$new_value.'</td>';
                    } else if($type=='excel'){
                        array_push($response, $new_value);
                    }
                }
            }
        }
        return $response;
    }

    public static function get_field_value($field, $item, $field_options, $appends, $type, $database, $just_last = false) {
        if($item){
            $field_name = $field->name;
            $field_trans_name = $field->trans_name;
            $field_type = $field->type;
            if($database&&$field->relation&&in_array($field->type, ['select','radio','checkbox'])){
                $item_val = $item->$field_name;
            } else {
                $item_val = $item->$field_trans_name;
            }
            $count = 0;
            if($type=='excel'){
                $value = NULL;
            } else {
                $value = '-';
            }
            if($field->relation){
                if($field->type=='child'){
                    if(request()->segment(1)=='customer-admin'){
                        $template = 'customer-admin';
                    } else {
                        $template = 'admin';
                    }
                    $url = url($template.'/model-list/'.$field->value.'?parent_id='.$item->id);
                    if($appends){
                        $url .= '&'.$appends;
                    }
                    $value = 'Nº: '.count($item_val).' (<a href="'.$url.'">'.trans('master::admin.view').'</a>)';
                } else if($field->type=='subchild'){
                    $value = 'Nº: '.count($item_val);
                } else if($field->type=='field'){
                    $value = NULL;
                    if($item_val){
                        foreach($item_val as $subkey => $subitem){
                            if($subkey>0){
                                $value .= ';';
                            }
                            if($database){
                                $value .= $subitem->id;
                            } else {
                                $value .= $subitem->name;
                            }
                        }
                    } else {
                        \Log::info('Error al exportar: '.$field_name);
                    }
                } else {
                    if($just_last&&$field_name=='parent_id'){
                        $value = 'new-1';
                    } else {
                        if($item_val&&is_object($item_val)){
                            $value = $item_val->name;
                        } else {
                            $value = $item_val;
                        }
                    }
                }
            } else {
                switch($field_type){
                    case 'string':
                    case 'integer':
                    case 'barcode':
                        if($just_last&&$field_name=='id'){
                            $value = 'new-1';
                        } else {
                            $value = $item_val;
                        }
                    break;
                    case 'select':
                    case 'radio':
                        if(config('solunes.excel_import_select_labels')){
                            if(isset($field_options[$field_name])&&isset($field_options[$field_name][$item_val])&&($item_val||$item_val===0)){
                                $value = $field_options[$field_name][$item_val];
                            }
                        } else {
                            if($item_val||$item_val===0){
                                $value = $item_val;
                            }
                        }
                    break;
                    case 'text':
                        if($type=='excel'){
                            $value = $item_val;
                            $value = str_replace(array("\r", "\n"), '',$value);
                        } else {
                            $value = strip_tags($item_val);
                            if (strlen($value) > 300) {
                                $value = substr($value, 0, 300).'...';
                            }
                        }
                    break;
                    case 'file':
                    case 'image':
                        if($item_val){
                            if($field->multiple){
                                $array_value = json_decode($item_val, true);
                            } else {
                                $array_value = [$item_val];
                            }
                            $value = '';
                            $folder = $field->field_extras()->where('type', 'folder')->first()->value;
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
                                  $value .= asset($file_url);
                                } else {
                                  $value .= '<a href="'.$file_url.'" target="_blank">'.$val.'</a>';
                                }
                            }
                        }
                    break;
                    case 'checkbox':
                        if($item_val){
                            $array_value = json_decode($item_val, true);
                            $value = '';
                            foreach($array_value as $val){
                                $count++;
                                if($count>1){
                                    $value .= ' | ';
                                }
                                $value .= $field_options[$field_name][$val];
                            }
                        }
                    break;
                    case 'datetime':
                    case 'date':
                    case 'time':
                        if($item_val){
                            if($field_type=='datetime'){
                                $value = $item_val->format('M d, Y H:i');
                            } else {
                                $value = $item_val;
                            }
                        }
                    break;
                }
            }
        } else {
            $value = '-';
        }
        return $value;
    }

    public static function make_fields_values_rows($langs, $module, $model, $item, $fields, $field_options, $appends, $action_fields = ['edit', 'delete']) {
        if(isset($action_fields['subadmin_child_field'])){
            $node_name = $action_fields['subadmin_child_field'];
            $subaction_fields = config('solunes.customer_dashboard_nodes.'.$node_name);
            $action_fields = [];
            foreach($subaction_fields as $subaction_field => $key){
                if($subaction_field=='create'||$subaction_field=='download'){
                } else if($subaction_field=='edit'){
                    $action_fields[] = 'edit-child';
                } else if($subaction_field=='view'){
                    $action_fields[] = 'view-child';
                } else {
                    $action_fields[] = $subaction_field;
                }
            }
        } else if(isset($action_fields['child_field'])){
            $node_name = $action_fields['child_field'];
            $node = \Solunes\Master\App\Node::where('name',$node_name)->first();
            if($node_extra = $node->node_extras()->where('type','action_field')->first()){
                $subaction_fields = json_decode($node_extra->value_array, true);
            } else {
                $subaction_fields = ['edit','delete'];
            }
            $action_fields = [];
            foreach($subaction_fields as $sub){
                if($sub=='edit'){
                    $action_fields[] = 'edit-child';
                } else if($sub=='view'){
                    $action_fields[] = 'view-child';
                } else {
                    $action_fields[] = $sub;
                }
            }
        }
        if(config('solunes.custom_admin_field_actions')){
            $action_fields = \CustomFunc::custom_admin_field_actions(request()->segment(3), $fields, $action_fields);
        }
        if(count($fields)>0){
            $response = '';
            $response .= \AdminList::make_fields_values($item, $fields, $field_options, $appends, 'table');
            if(request()->has('download-pdf')){
                if (($key = array_search('edit', $action_fields)) !== false) {
                    unset($action_fields[$key]);
                }
                if (($key = array_search('delete', $action_fields)) !== false) {
                    unset($action_fields[$key]);
                }
                if (($key = array_search('restore', $action_fields)) !== false) {
                    unset($action_fields[$key]);
                }
            }
            foreach($action_fields as $action_field){
                if($action_field=='edit') {
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'edit', false, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'edit', false, 'es').'</td>';
                    }
                } else if($action_field=='delete'){
                    if(request()->has('view-trash')&&request()->input('view-trash')=='true'){
                        $response .= '<td class="ineditable restore">'.AdminList::make_delete($module, $model, $item, $restore = true).'</td>';
                    } else {
                        $response .= '<td class="ineditable delete">'.AdminList::make_delete($module, $model, $item).'</td>';
                    }
                } else if($action_field=='view'){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'view', false, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.AdminList::make_open($module, $model, $appends, $item, 'view', false, 'es').'</td>';
                    }
                } else if($action_field=='edit-child'){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'edit', true, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'edit', true, 'es').'</td>';
                    }
                } else if($action_field=='view-child'){
                    if(count($langs)>0){
                        foreach($langs as $language){
                            $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'view', true, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="ineditable edit">'.AdminList::make_open($module, $model, $appends, $item, 'view', true, 'es').'</td>';
                    }
                } else if($action_field=='create-child'){
                    $preurl = url($module.'/model/'.$model.'/create/'.$item->id);
                    $preurl .= '?level='.($item->level+1).'&parent_id='.$item->id.'&';
                    if($appends!=NULL){
                        $preurl .= $appends;
                    }
                    $response .= '<td class="ineditable restore"><a href="'.$preurl.'">'.trans('master::admin.create-child').'</a></td>';
                } else {
                    $response .= \SolunesFunc::get_action_field_values($response, $module, $model, $item, $action_field, $langs);
                    $response .= \CustomFunc::get_action_field_values($response, $module, $model, $item, $action_field, $langs);
                } 
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public static function make_child_fields_values_rows($parent_key, $langs, $module, $model, $item, $fields, $field_options, $appends, $action_fields) {
        $response = '';
        foreach($item->children as $subkey => $child){
            $new_parent_key = $parent_key.'.'.($subkey+1);
            $response .= '<tr>';
            $response .= '<td>'.$new_parent_key.'</td>';
            $response .= AdminList::make_fields_values_rows($langs, $module, $model, $child, $fields, $field_options, $appends, $action_fields);
            $response .= '</tr>';
            if(count($child->children)>0){
                $response .= AdminList::make_child_fields_values_rows($new_parent_key, $langs, $module, $model, $child, $fields, $field_options, $appends, $action_fields);
            }
        }
        return $response;
    }

    public static function make_open($module, $model, $appends, $item, $type, $child, $lang_code = NULL) {
        if($child){
            $page = 'child-model';
        } else {
            $page = 'model';
        }
        $preurl = $module.'/'.$page.'/'.$model.'/'.$type.'/'.$item->id;
        if($lang_code){
            $preurl .= '/'.$lang_code;
        }
        $url = url($preurl);
        if($child){
            //$url .= '?lightbox[width]=1000&lightbox[height]=600';
            $class = ' data-featherlight="ajax" ';
        } else {
            if($appends!=NULL){
                $url .= '?'.$appends;
            }
            $class = NULL;
        }
        return '<a '.$class.' href="'.$url.'">'.trans('master::admin.'.$type).'</a>';
    }

    public static function make_delete($module, $model, $item, $restore = false) {
        if($restore==true){
            $action = 'restore';
            $delete_confirmation = NULL;
        } else {
            $action = 'delete';
            if(config('solunes.delete_item_custom_message')){
                $delete_confirmation = ' onclick="return confirm(\''.\CustomFunc::delete_item_custom_message($module, $model, $item).'\');"';
            } else {
                $delete_confirmation = ' onclick="return confirm(\''.trans('master::admin.delete_confirmation').'\');"';
            }
        }
        return '<a href="'.url($module.'/model/'.$model.'/'.$action.'/'.$item->id).'"'.$delete_confirmation.'>'.trans('master::admin.'.$action).'</a>';
    }

    public static function make_list_header($module, $node, $id, $parent, $appends, $count = 0, $total_count = 0, $action_nodes = ['back','create','excel']) {
        if(config('solunes.custom_admin_node_actions')){
            $action_nodes = \CustomFunc::custom_admin_node_actions($node, $action_nodes);
        }
        $title = $node->plural;
        if($count == $total_count){
            $title .= ' ( '.$count.' )';
        } else {
            $title .= ' ( '.$count.' / '.$total_count.' )';
        }
        $url = request()->fullUrl();
        $response = '<h3>'.$title;
        foreach($action_nodes as $key => $action_node){
            if($action_node=='back'){
                $back_name = NULL;
                if($id!=NULL){
                    $parent_node = \Solunes\Master\App\Node::where('name', $parent)->first();
                    $submodel = \FuncNode::node_check_model($parent_node);
                    $subitem = $submodel::find($id);
                    $back_name = $parent_node->singular;
                    if($subitem&&$subitem->parent_id){
                        $back_url = url($module.'/model-list/'.$parent.'?parent_id='.$subitem->parent_id);
                    } else {
                        $back_url = url($module.'/model-list/'.$parent);
                    }
                    if($subitem&&$subitem->name){
                        $back_name .= '( '.$subitem->name.' )';
                    }
                    if(request()->has('parameters')){
                        $parameters = json_decode(request()->input('parameters'));
                        $back_url .= '?'.http_build_query($parameters);
                    }
                    if($back_name){
                        $response .= ' | <a href="'.$back_url.'"><i class="fa fa-arrow-circle-o-left"></i> '.trans('master::admin.back').' a '.$back_name.'</a>';
                    } else {
                        $response .= ' | <a href="'.$back_url.'"><i class="fa fa-arrow-circle-o-left"></i> '.trans('master::admin.back').'</a>';
                    }
                }
            } else if($action_node=='create'){
                if($id==NULL){
                    $create_url = url($module.'/model/'.$node->name.'/create');
                    $string_separator = '?';         
                } else {
                    $create_url = url($module.'/model/'.$node->name.'/create?parent_id='.$id);           
                    $string_separator = '&';         
                }
                if($node->multilevel){
                    $create_url .= $string_separator.'&level=1';
                    $string_separator = '&';
                }
                if($appends){
                    $create_url .= $string_separator.$appends;
                }               
                $response .= ' | <a class="admin_link" href="'.$create_url.'"><i class="fa fa-plus"></i> '.trans('master::admin.create').'</a>'; 
            } else if($action_node=='excel'){
                if(strpos($url, '?') !== false){
                    $download_url = '&download-excel=true';
                } else {
                    $download_url = '?download-excel=true';
                }
                $response .= ' | <a href="'.url($url.$download_url).'"><i class="fa fa-download"></i> '.trans('master::admin.download').'</a>';
                if(config('solunes.list_export_pdf')){
                    $response .= ' | <a href="'.url($url.str_replace('download-excel','download-pdf',$download_url)).'"><i class="fa fa-download"></i> '.trans('master::admin.download_pdf').'</a>';
                }
            } else {
                $response .= ' | '.\CustomFunc::get_action_node($response, $node, $id, $action_node);
            }
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
            if($key>0){
                $response .= ' | ';
            }
            $response .= '<a href="'.url($final_archive_url).'"><i class="fa fa-trash"></i> '.trans('master::admin.'.$archive_title).'</a>';
        }
        $response .= '</h3>';
        return $response;
    }

    public static function child_list_header($module, $node_name, $title, $parent_id) {
        $node = \Solunes\Master\App\Node::where('name',$node_name)->first();
        if($node_extra = $node->node_extras()->where('type','action_node')->first()){
            $action_nodes = json_decode($node_extra->value_array, true);
        } else {
            $action_nodes = ['back','create','excel'];
        }
        if($module=='customer-admin'&&in_array('excel',$action_nodes)){
            $pos = array_search('excel', $action_nodes);
            unset($action_nodes[$pos]);
        }
        if(config('solunes.custom_admin_node_actions')){
            $action_nodes = \CustomFunc::custom_admin_node_actions($node, $action_nodes);
        }
        if(($key = array_search('back', $action_nodes)) !== false) {
            unset($action_nodes[$key]);
        }
        $response = '<h3>'.$title;
        foreach($action_nodes as $key => $action_node){
            if($action_node=='create'){
                $create_url = url($module.'/child-model/'.$node->name.'/create?parent_id='.$parent_id);           
                if($node->multilevel){
                    $create_url .= '&level=1';
                }          
                $response .= ' | <a class="admin_link" data-featherlight="ajax" href="'.$create_url.'"><i class="fa fa-plus"></i> '.trans('master::admin.create').'</a>'; 
            } else if($action_node=='excel'){
                $download_url = url($module.'/model-list/'.$node_name.'?parent_id='.$parent_id.'&download-excel=true');
                $response .= ' | <a href="'.$download_url.'"><i class="fa fa-download"></i> '.trans('master::admin.download').'</a>';
            } else {
                $response .= ' | '.\CustomFunc::get_action_node($response, $node, $parent_id, $action_node);
            }
        }
        $response .= '</h3>';
        return $response;
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
            $array['filter_string_options'] = ['is'=>trans('master::fields.is'),'is_not'=>trans('master::fields.is_not'),'is_greater'=>trans('master::fields.is_greater'),'is_less'=>trans('master::fields.is_less'),'where_in'=>trans('master::fields.where_in')];
            foreach($filters as $filter){
                $field_name = $filter->parameter;
                $parent_filter_type = NULL;
                if($filter->type=='parent_field'){
                    $parent_filter_type = 'child';
                }
                $node_double = $node;
                if($filter->type=='parent_field'&&$field_name=='parent_relation'){
                    $action_value = json_decode($filter->action_value, true);
                    $node_double = \Solunes\Master\App\Node::where('name', $action_value['node'])->first();
                    $field_name = $action_value['parent_field'];
                    $parent_filter_type = 'parent';
                    $parent_field_join = str_replace('_id', '', $action_value['original_field']);
                }
                $array['filters'][$field_name] = ['subtype'=>$filter->subtype, 'id'=>$filter->id];
                if($filter->display=='user'){
                    $array['filters'][$field_name]['show_delete'] = true;
                } else {
                    $array['filters'][$field_name]['show_delete'] = false;
                }
                if($type=='custom'||$type=='indicator'){
                    $node = $filter->node;
                    if($type=='custom'){
                        $array['filters'][$field_name]['node_name'] = $node->name;
                    }
                }
                if(config('solunes.custom_filter')&&$custom_check!='false'){
                    $custom_array = \CustomFunc::custom_filter($custom_check, $array, $items, $appends, $node_double, $model, $filter, $type, $field_name, $parent_field_join);
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
                        $custom_array = \AdminList::filter_custom_value($array, $appends, $node_double, $filter, $type, $field_name);
                        $array = $custom_array['array'];
                        $appends = $custom_array['appends'];
                        $custom_value = $custom_array['custom_value'];
                        $field = $custom_array['field'];
                        // Obtener items segun tipo
                        $custom_array = \AdminList::filter_items_get($items, $node_double, $model, $filter, $field, $field_name, $custom_value, $parent_field_join, $parent_filter_type);
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
                $custom_value_flip = array_flip($custom_value);
                if($custom_value&&isset($custom_value_flip['is_greater'])){
                    $appends['f_'.$field_name.'_from'] = $custom_value_flip['is_greater'];
                }
                if($custom_value&&isset($custom_value_flip['is_less'])){
                    $appends['f_'.$field_name.'_to'] = $custom_value_flip['is_less'];
                }
            } else if($filter->subtype=='string'){
                if($custom_value||$custom_value=='0'){
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
            $field_string = request()->input('f_'.$field_name);
            if($field_string||$field_string=='0'){ 
                $custom_value[$field_string] = request()->input('f_'.$field_name.'_action');
                $appends['f_'.$field_name] = $field_string;
            }
        } else if(request()->input('f_'.$field_name)&&is_array(request()->input('f_'.$field_name))){
            foreach(request()->input('f_'.$field_name) as $select_key => $select_value){
                $custom_value[$select_value] = 'is';
            }
            $appends['f_'.$field_name] = array_keys($custom_value);
        } else if(request()->input('f_'.$field_name)){
            $select_value = request()->input('f_'.$field_name);
            $custom_value[$select_value] = 'is';
            $appends['f_'.$field_name] = array_keys($custom_value);
        }
        $field = $node->fields()->where('name', $field_name)->first();
        if($filter->type=='parent_field'){
            $array['filters'][$field_name]['label'] = mb_strtoupper($node->singular, 'UTF-8').': '.$field->label;
        } else {
            $array['filters'][$field_name]['label'] = $field->label;
        }
        $array['filters'][$field_name]['options'] = $field->options;
        return ['array'=>$array, 'appends'=>$appends, 'custom_value'=>$custom_value, 'field'=>$field];
    }

    public static function filter_items_get($items, $node, $model, $filter, $field, $field_name, $custom_value, $parent_field_join = 'parent_id', $parent_type = 'child') {
        $custom_value_count = count($custom_value);
        $date_model = NULL;
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
                if($parent_type=='child'){
                    $parent_array = $parent_array->lists($parent_field_join)->toArray();
                    $items = $items->whereIn('id', $parent_array);
                } else {
                    $parent_array = $parent_array->lists('id')->toArray();
                    $items = $items->whereIn($parent_field_join.'_id', $parent_array);
                }
            }
        }
        return ['items'=>$items, 'date_model'=>$date_model];
    }

    public static function filter_date_field($array, $date_model, $filter, $field_name) {
        if($filter->subtype=='date'){
            \Log::info($date_model);
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
                if($custom_val!='f_all'||$custom_val=='0'){
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
                                $subquery->where(str_replace('-', '_', $field->value).'_id', $custom_val);
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
                        $parent_node = \Solunes\Master\App\Node::where('table_name', $relation_table)->first();
                        $field = $parent_node->fields()->where('name', $graph_item_name)->first();
                    } else {
                        $graph_item_name = $graph_item;
                        $graph_model = $cloned_model->groupBy($graph_item_name)->select($graph_item_name, \DB::raw('count(*) as total'))->get();
                        $field = $node->fields()->where('name', $graph_item_name)->first();
                    }
                    $field_names = [];
                    $field_trans_name = $field->trans_name;
                    foreach($graph_model as $graph_i){
                        if($field->relation&&$graph_i->$field_trans_name){
                            $field_val = $graph_i->$field_trans_name->name;
                        } else if(!$field->relation&&($field->type=='select'||$field->type=='radio'||$field->type=='checkbox')){
                            $field_val = $array['field_options'][$field->name][$graph_i->$graph_item_name];
                        } else {
                            $field_val = $graph_i->$graph_item_name;
                        }
                        $field_val = str_replace('"', '', $field_val);
                        $field_names[$graph_i->$graph_item_name] = $field_val;
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
                        $array['graphs'][$graph_item_name.'-'.$graph->parameter] = ['name'=>$graph_item_name,'label'=>$field->label,'type'=>$graph->parameter,'items'=>$graph_model,'subitems'=>$subitems,'field_names'=>$field_names];
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
        $file = \AdminList::generate_query_excel_file($array, $dir);
        return response()->download($file);
    }

    public static function generate_query_pdf($array) {
        $array['pdf'] = true;
        if(config('solunes.custom_field')){
            $array['header_title'] = \CustomFunc::custom_pdf_header($array['node'], $id);
        } else {
            $array['header_title'] = strtoupper($array['node']->plural);
        }
        $array['title'] = 'Reporte de '.$array['node']->plural;
        $array['site'] = \Solunes\Master\App\Site::find(1);
        $pdf = \PDF::loadView('master::list.general-list', $array);
        $pdf = \Asset::apply_pdf_template($pdf, $array['header_title'], ['margin-top'=>'25mm','margin-bottom'=>'15mm','margin-right'=>'12mm','margin-left'=>'12mm']);
        $zoom = config('snappy.pdf.options.zoom');
        if(!$zoom){
            $zoom = 0.7;
        }
        return $pdf->setOrientation('landscape')->setOption('zoom', round($zoom*0.8, 1))->stream($array['node']->plural.'_'.date('Y-m-d').'.pdf');
    }

    public static function generate_query_excel_file($array, $dir) {
        $filename = str_replace(' ', '-', $array['node']->plural.'_'.date('Y-m-d'));
        $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $filename);
        $file = \Excel::create($filename, function($excel) use($array) {
            $alphabet = \DataManager::generateAlphabet(count($array['fields']));
            $sheet_title = str_replace(' ', '-', $array['node']->plural);
            $sheet_title = substr(preg_replace('/[^A-Za-z0-9\-]/', '', $sheet_title), 0, 30);
            $col_array = [];
            $col_width = [];
            foreach($array['fields'] as $key => $field){
                array_push($col_array, $field->label);
                foreach($field->field_relations as $field_relation){
                    array_push($col_array, $field_relation->label);
                }
                $col_width = \DataManager::generateColWidth($alphabet, $field, $key, $col_width);
            }
            \DataManager::generateSheet($excel, $alphabet, $sheet_title, $col_array, $col_width, $array['fields'], $array['field_options'], $array['items'], []);
            $children = $array['node']->children()->where('type', '!=', 'field')->get();
            if(count($children)>0){
                foreach($children as $child){
                    $child_table = $child->table_name;
                    $sheet_title = str_replace(' ', '-', 'Sub-'.$child->singular);
                    $sheet_title = substr(preg_replace('/[^A-Za-z0-9\-]/', '', $sheet_title), 0, 30);
                    $col_array = [trans('master::fields.parent'), trans('master::fields.counter')];
                    $col_width = [];
                    $child_fields = $child->fields()->where('display_item', 'show')->where('name','!=','parent_id')->get();
                    $alphabet = \DataManager::generateAlphabet(count($child_fields));
                    foreach($child_fields as $key => $field){
                        array_push($col_array, $field->label);
                        foreach($field->field_relations as $field_relation){
                            array_push($col_array, $field_relation->label);
                        }
                        $col_width = \DataManager::generateColWidth($alphabet, $field, $key, $col_width);
                    }
                    \DataManager::generateSheet($excel, $alphabet, $sheet_title, $col_array, $col_width, $child_fields, $array['field_options'], $array['items'], [], $child_table);
                }
            }
        })->store('xlsx', $dir, true);
        return $file['full'];
    }

}