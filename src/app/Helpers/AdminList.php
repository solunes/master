<?php 

namespace Solunes\Master\App\Helpers;

use Validator;

class AdminList {

    public static function make_section_buttons($model, $item, $page_id = NULL) {
        if(\Auth::check()){
            $result = '<table class="admin-table section-buttons"><tr>'.AdminList::make_panel_title().'</tr>';
            $result .= '<tr>'.AdminList::make_panel_buttons($model, $item, $page_id).'</tr></table>';            
        } else {
            $result = NULL;
        }
        return $result;
    }
    
    public static function get_list($object, $single_model, $extra = []) {
        $module = $object->module;
        $node = \Solunes\Master\App\Node::where('name', $single_model)->first();
        $model = $node->model;
        if(\Login::check_permission('list', $module, $node, 'list')===false){
            return \Login::redirect_dashboard('no_permission');
        }

        $array = ['module'=>$module, 'node'=>$node, 'model'=>$single_model, 'i'=>NULL,  'dt'=>'form', 'id'=>NULL, 'parent'=>NULL, 'action_fields'=>['create','edit','delete']];
        
        if($action_field = $node->node_extras()->where('type','action_field')->first()){
            $array['action_fields'] = json_decode($action_field->value_array, true);
            // PROBAR
        }

        if(request()->has('parent_id')){
            $id = request()->input('parent_id');
            $array['id'] = $id;
            $items = $model::whereHas('parent', function($q) use($id) {
                $q->where('id', $id);
            });
        } else {
            $items = $model::whereNotNull('id');
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
            $array['fields'] = $node->fields()->whereIn('display_list', $display_fields)->where('type', '!=', 'field')->get();
            $relation_fields = $node->fields()->whereIn('display_list', $display_fields)->where('type','relation')->get();
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

        $array['items'] = $items->get();

        if(request()->has('download-excel')){
            return AdminList::generate_query_excel($array);
        } else {
            return view('master::list.general-list', $array);
        }

    }

    public static function make_fields($fields, $action_fields = ['edit', 'delete']) {
        if(count($fields)>0){
            $response = '';
            foreach($fields as $field){
                $response .= '<td>'.$field->label.'</td>';
            }
            if(is_array($action_fields)){
                if(in_array('edit', $action_fields)){
                    $languages = \Solunes\Master\App\Language::get();
                    if(count($languages)>0){
                        foreach($languages as $language){
                            $response .= '<td class="edit">'.$language->name.'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.trans('admin.edit').'</td>';
                    }
                }
                if(in_array('delete', $action_fields)){
                    if(request()->has('view-trash')&&request()->input('view-trash')=='true'){
                        $response .= '<td class="restore">'.trans('admin.restore').'</td>';
                    } else {
                        $response .= '<td class="delete">'.trans('admin.delete').'</td>';
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
            if($field_type=='string'){
                $value = $item_val;
            } else if($field_type=='text') {
                $value = strip_tags($item_val);
                if (strlen($value) > 300) {
                    $value = substr($value, 0, 300).'...';
                }
            } else if($field_type=='select') {
                $value = trans('admin.'.$item_val);
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
                $value = 'Nº: '.count($item_val).' (<a href="'.$url.'">'.trans('admin.view').'</a>)';
            } else if($field_type=='subchild') {
                $value = 'Nº: '.count($item_val);
            } else if(($field_type=='image'||$field_type=='file')&&$item_val) {
                if($field->multiple){
                    $array_value = json_decode($item_val, true);
                } else {
                    $array_value = [$item_val];
                }
                $count = 0;
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
                      $value .= $file_url;
                    } else {
                      $value .= '<a href="'.$file_url.'" target="_blank">'.$val.'</a>';
                    }
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

    public static function make_fields_values_rows($module, $model, $item, $fields, $appends, $action_fields = ['edit', 'delete']) {
        if(count($fields)>0){
            $response = '';
            $response .= \AdminList::make_fields_values($item, $fields, $appends, 'table');
            if(is_array($action_fields)){
                if(in_array('edit', $action_fields)){
                    $languages = \Solunes\Master\App\Language::get();
                    if(count($languages)>0){
                        foreach($languages as $language){
                            $response .= '<td class="edit">'.AdminList::make_edit($module, $model, $appends, $item, $language->code).'</td>';
                        }
                    } else {
                        $response .= '<td class="edit">'.AdminList::make_edit($module, $model, $appends, $item, $language->code).'</td>';
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
        $action = trans('admin.create');
        return '<a class="admin_link" href="'.$url.'"><i class="fa fa-plus"></i> '.$action.'</a>';   
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
        return '<a href="'.$url.'">'.trans('admin.edit').'</a>';
    }

    public static function make_delete($module, $model, $item, $restore = false) {
        if($restore==true){
            $action = 'restore';
            $delete_confirmation = NULL;
        } else {
            $action = 'delete';
            $delete_confirmation = ' onclick="return confirm(\''.trans('admin.delete_confirmation').'\');"';
        }
        return '<a href="'.url($module.'/model/'.$model.'/'.$action.'/'.$item->id).'"'.$delete_confirmation.'>'.trans('admin.'.$action).'</a>';
    }

    public static function make_put_off($module, $model, $item) {
        return '<a href="'.url($module.'/model/'.$model.'/put-off/'.$item->id).'" onclick="return confirm(\''.trans('admin.put_off_confirmation').'\');"><div class="delete"><i class="fa fa-times"></i> '.trans('admin.put_off').' '.trans('admin.'.$model).'</div></a>';
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

    public static function make_list_header($module, $node, $id, $parent, $appends, $action_fields = ['create']) {
        $title = $node->plural;
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
            $archive = ' | <a href="'.url($final_archive_url).'"><i class="fa fa-trash"></i> '.trans('admin.'.$archive_title).'</a>';
        } else {
            $archive = '';
        }
        $download = '<a href="'.url($url.$download_url).'"><i class="fa fa-download"></i> '.trans('admin.download').'</a>';
        $result = '<h3>'.$title.$back.' | '.$create.$archive.' | '.$download.'</h3>';
        return $result;
    }

    public static function filter_node($array, $node, $model, $items, $type = 'admin') {
        $filters = $node->node_extras()->where('type','filter')->get();
        if(count($filters)>0&&$model::count()>0){
            $appends = NULL;
            $array['additional_queries'] = [];
            foreach(request()->all() as $input_key => $input_val){
                if(stripos($input_key, 'f_') === false){
                    $array['additional_queries'][$input_key] = $input_val;
                }
            }
            if(request()->input('search')){
                $array['search'] = 1;
            }
            $array['filters'] = [];
            foreach($filters as $filter){
                $filter_value = json_decode($filter->value_array, true);
                foreach($filter_value as $fil_val){
                    if($filter->parameter=='custom'||$filter->parameter=='parent_field'||$filter->parameter=='custom_function'){
                        $field_name = $fil_val['name'];
                        if($filter->parameter=='parent_field'){
                            $parent_node = \Solunes\Master\App\Node::where('name', $fil_val['parent'])->first();
                        }
                        $custom_data = $fil_val['data'];
                   } else {
                        $field_name = $fil_val;
                    }
                    $array['filters'][$field_name] = $filter->parameter;
                    if($filter->parameter=='dates'){
                        $array['first_day'] = $model::orderBy($field_name,'ASC')->first()->$field_name->format('Y-m-d');
                        $array['last_day'] = $model::orderBy($field_name,'DESC')->first()->$field_name->format('Y-m-d');
                        $f_date_from = NULL;
                        $f_date_to = NULL;
                        if(request()->input('f_date_from')){ $f_date_from = request()->input('f_date_from'); }
                        if(request()->input('f_date_to')){ $f_date_to = request()->input('f_date_to'); }
                        if($f_date_from){
                          $items = $items->where($field_name, '>=', $f_date_from.' 00:00:00');
                          $appends['f_date_from'] = $f_date_from;
                        }
                        if($f_date_to){
                          $items = $items->where($field_name, '<=', $f_date_to.' 23:59:59');
                          $appends['f_date_to'] = $f_date_to;
                        }
                    } else if($filter->parameter=='field'){
                        $field = $node->fields()->where('name', $field_name)->first();
                        $options = $field->options;
                        if(isset($options[0])){
                            unset($options[0]);
                        }
                        $array['filter_options'][$field_name] = ['any'=>trans('admin.any')]+$options;
                        $custom_value = 'any';
                        if(request()->input('f_'.$field_name)){ $custom_value = request()->input('f_'.$field_name); }
                        if($custom_value!='any'){
                            if($field->type=='field'){
                              $items = $items->whereHas($field_name, function ($query) use($field, $field_name, $custom_value) {
                                $query->where($field->value.'_id', $custom_value);
                              });
                            } else {
                              $items = $items->where($field->name, $custom_value);
                            }
                            $appends['f_'.$field_name] = $custom_value;
                        }
                    } else if($filter->parameter=='parent_field'){
                        $parent_model = $parent_node->model;
                        $field = $parent_node->fields()->where('name', $field_name)->first();
                        $options = $field->options;
                        if(isset($options[0])){
                            unset($options[0]);
                        }
                        $array['filter_options'][$field_name] = ['any'=>trans('admin.any')]+$options;
                        $custom_value = 'any';
                        if(request()->input('f_'.$field_name)){ $custom_value = request()->input('f_'.$field_name); }
                        if($custom_value!='any'){
                            $parent_array = $parent_model::where($field->name, $custom_value)->lists('id')->toArray();
                            $items = $items->whereIn($custom_data, $parent_array);
                            $appends['f_'.$field_name] = $custom_value;
                        }
                    } else if($filter->parameter=='custom'){
                        $custom_options = [];
                        foreach($custom_data as $custom_item){
                            $custom_options[$custom_item] = trans('admin.'.$custom_item);
                        }
                        $array['filter_options'][$field_name] = $custom_options;
                        $custom_value = 'any';
                        if(request()->input('f_'.$field_name)){ $custom_value = request()->input('f_'.$field_name); }
                        if($custom_value!='any'){
                            $appends['f_'.$field_name] = $custom_value;
                        }
                    } else if($filter->parameter=='custom_function'){
                        $items = \CustomFunc::custom_filter($array, $items, $field_name, $custom_data);
                    }
                }
            }
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
            $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->sheet($array['node']->plural, function($sheet) use($array) {
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