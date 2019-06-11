<?php

namespace Solunes\Master\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdminItem;
use View;

class DynamicFormController extends Controller {

    protected $request;
    protected $url;

    public function __construct(UrlGenerator $url) {
      $this->middleware('auth');
      $this->middleware('permission:dashboard')->only('getIndex');
      $this->prev = $url->previous();
      $this->module = 'admin';
      $this->nodesCount = 0;
      $this->rowsCount = 0;
    }
    public function getImportNodes($node_id = NULL) {
        if($node_id){
            $array['items'] = \Solunes\Master\App\Node::where('id',$node_id)->get();
        } else {
            $array['items'] = \Solunes\Master\App\Node::where('location', '!=','package')->whereNull('parent_id')->withTrashed()->get();
        }
        return view('master::list.import-nodes', $array);
    }

    public function postImportNodes(Request $request) {
        if(!$request->hasFile('file')||!$request->file('file')->isValid()){
            return redirect($this->prev)->with('message_error', 'Por favor seleccione un archivo para importar en XLS o XLSX.');
        }
        if(auth()->user()->isSuperAdmin()){
            $name_array = \Solunes\Master\App\Node::withTrashed()->lists('name')->toArray();
        } else {
            $name_array = \Solunes\Master\App\Node::where('location', '!=','package')->withTrashed()->lists('name')->toArray();
            $name_array = array_merge(['image-folder','email'], $name_array);
        }
        $languages = \Solunes\Master\App\Language::get();
        $total_count = 0;
        $nodes_count = 0;
        $count = \Excel::load($request->file('file'), function($reader) use($languages, $name_array, $total_count, $nodes_count) {
            $super_parent_sheet = null;
            $super_parent_array = [];
            foreach($reader->get() as $sheet){
                $sheet_model = $sheet->getTitle();
                $strpos_sheet = strpos($sheet_model, '#');
                if($strpos_sheet !== false){
                    $sheet_model = substr($sheet_model, 0, $strpos_sheet);
                }
                if(in_array($sheet_model, $name_array)){
                    if(!$super_parent_sheet){
                        $import_excel_results = \DataManager::importExcelRows($sheet, $languages, $super_parent_sheet, $super_parent_array);
                        $super_parent_array = $import_excel_results['super_parent_array'];
                        $super_parent_sheet = $sheet_model;
                    } else {
                        $import_excel_results = \DataManager::importExcelRows($sheet, $languages, $super_parent_sheet, $super_parent_array);
                    }
                    $count_rows = $import_excel_results['count_rows'];
                    if($count_rows>0){
                        $nodes_count++;
                    }
                    $total_count += $count_rows;
                }
            }
            $this->nodesCount = $nodes_count;
            $this->rowsCount = $total_count;
        });
        \Log::info(json_encode($count));
        if($this->rowsCount==0){
            return redirect($this->prev)->with('message_error', 'No se encontraron registros para importar en el documento.');
        } else {
            return redirect($this->prev)->with('message_success', 'Se importó el documento correctamente con '.$this->nodesCount.' nodo(s) y '.$this->rowsCount.' item(s) en total.');
        }
    }

    public function getExportNodeSystem($node_name) {
        $dir = public_path('excel');
        array_map('unlink', glob($dir.'/*'));
        $search_node = \Solunes\Master\App\Node::where('name', $node_name)->with('fields')->first();
        $node = \Solunes\Master\App\Node::where('name', 'node')->with('fields')->first();
        $alphabet = \DataManager::generateAlphabet(count($node->fields));
        $file = \Excel::create($node_name, function($excel) use($node, $search_node, $alphabet) {
            \DataManager::generateInstructionsSheet($excel);
            \DataManager::exportNodeExcel($excel, $alphabet, $node, false, true, ['id'=>$search_node->id]);
            $children = $node->children()->where('type', '!=', 'field')->get();
            if(count($children)>0){
                foreach($children as $child){
                    $alphabet = \DataManager::generateAlphabet(count($child->fields));
                    \DataManager::exportNodeExcel($excel, $alphabet, $child, false, true, ['parent_id'=>$search_node->id]);
                }
            }
        })->store('xlsx', $dir, true);
        return response()->download($file['full']);
    }

    public function getExportNode($node_name) {
        $dir = public_path('excel');
        array_map('unlink', glob($dir.'/*'));
        $node = \Solunes\Master\App\Node::where('name', $node_name)->with('fields')->first();
        $alphabet = \DataManager::generateAlphabet(count($node->fields));
        $file = \Excel::create($node_name, function($excel) use($node, $alphabet) {
            \DataManager::generateInstructionsSheet($excel);
            \DataManager::exportNodeExcel($excel, $alphabet, $node, true, true);
            $children = $node->children()->where('type', '!=', 'field')->get();
            if(count($children)>0){
                foreach($children as $child){
                    $alphabet = \DataManager::generateAlphabet(count($child->fields));
                    \DataManager::exportNodeExcel($excel, $alphabet, $child, true, true);
                }
            }
        })->store('xlsx', $dir, true);
        return response()->download($file['full']);
    }

    public function getExportNodes() {
        $array['items'] = \Solunes\Master\App\Node::where('location', '!=','package')->withTrashed()->get();
        return view('master::list.export-nodes', $array);
    }

    public function postExportNodes(Request $request) {
        $name_array = $request->input('nodes');
        if(!$name_array||count($name_array)==0){
            return redirect($this->prev)->with('message_error', 'Por favor seleccione los nodos que desea descargar.');
        }
        $name_array = array_merge(['image-folder','email'], $name_array);
        $nodes = \Solunes\Master\App\Node::whereIn('name', $name_array)->with('fields')->get();
        $dir = public_path('excel');
        array_map('unlink', glob($dir.'/*'));
        $languages = \Solunes\Master\App\Language::where('code','!=',config('solunes.main_lang'))->lists('code');
        $used_array = [];
        $file = \Excel::create('import', function($excel) use($nodes, $used_array, $languages) {
            foreach($nodes as $node){
                $alphabet = \DataManager::generateAlphabet(count($node->fields));
                if(!in_array($node->name, $used_array)){
                    $used_array[] = $node->name;
                    \DataManager::exportNodeExcel($excel, $alphabet, $node, false, true);
                    $children = $node->children()->where('type', '!=', 'field')->get();
                    if(count($children)>0){
                        foreach($children as $child){
                            if(!in_array($node->name, $used_array)){
                                $used_array[] = $node->name;
                                $alphabet = \DataManager::generateAlphabet(count($child->fields));
                                \DataManager::exportNodeExcel($excel, $alphabet, $child, false, true);
                            }
                        }
                    }
                }
            }
        })->store('xlsx', $dir, true);
        return response()->download($file['full']);
    }

    public function getFormList() {
        $node = \Solunes\Master\App\Node::where('name', 'node')->first();
        $array = ['module'=>'node', 'model'=>'node', 'langs'=>NULL, 'appends'=>NULL, 'action_fields'=>['create','edit']];
        $array['items'] = \Solunes\Master\App\Node::where('dynamic', 1)->whereNull('parent_id')->withTrashed()->get();
        $array['fields'] = $node->fields()->displayList('show')->get();
        if(View::exists('list.dynamic-form')){
            return view('list.dynamic-form', $array);
        }
        return view('master::list.dynamic-form', $array);
    }

    public function getFormFields($id) {
        $node = \Solunes\Master\App\Node::find($id);
        $array = ['node'=>$node, 'i'=>NULL, 'action'=>'create', 'dt'=>'editor'];
        $array['fields'] = $node->fields()->whereNotIn('name', ['id','filled_form_id','created_at','updated_at','deleted_at'])->with('translations','field_extras','field_options_active')->get();
        foreach($node->fields()->maps()->get() as $field){
            $array['map_array'][$field->id] = $field;
        }
        if(count($array['fields'])==0){
            if($node->permission=='form'){
                $node_field = 'id';
            } else {
                $node_field = 'filled_form_id';
            }
            return redirect('admin/form-field/create/'.$id.'/'.$node_field);
        }
        if(View::exists('item.form-fields')){
            return view('item.form-fields', $array);
        }
        return view('master::item.form-fields', $array);
    }

    public function getForm($action, $id = NULL) {
        $node = \Solunes\Master\App\Node::where('name','node')->first();
        $array = ['model'=>'node', 'node'=>$node, 'i'=>NULL, 'id'=>$id, 'dt'=>'form', 'action'=>$action];
        if($action=='edit'){
            $array['i'] = \Solunes\Master\App\Node::find($id);
        }
        $array['fields'] = $node->fields()->whereIn('name', ['name','permission','singular','plural'])->with('translations','field_extras','field_options_active')->get();
        if($action=='create'){
            $array['options_menu'] = [];
            if(config('solunes.dyanmic_form_create_menu')){
                $menus = \CustomFunc::dyanmic_form_create_menu();
            } else {
                $menus = \Solunes\Master\App\Menu::where('level', '!=', 3)->where('type', 'blank')->with('parent')->get();
            }
            foreach($menus as $menu){
                if($menu->level==2){
                    $menu_name = $menu->parent->name.' | '.$menu->name;
                } else {
                    $menu_name = $menu->name;
                }
                $array['options_menu'][$menu->id] = $menu_name;
            }
            $array['menu_name'] = NULL;
        } else {
            $array['menu_name'] = \Solunes\Master\App\Menu::whereTranslation('link', 'admin/model-list/'.$array['i']->name)->first()->name;
        }
        if(View::exists('item.form')){
            return view('item.form', $array);
        }
        return view('master::item.form', $array);
    }

    public function postForm(Request $request) {
        $action = $request->input('action');
        $rules = [
            'singular'=>'required',
            'plural'=>'required',
        ];
        if($action=='create'){
            $rules = $rules + [
                'name'=>'required|alpha_dash',
                'permission'=>'required',
                'menu_parent'=>'required',
                'menu_name'=>'required',
            ];
        }
        $languages = \Solunes\Master\App\Language::get();
        $validator = \Validator::make($request->all(), $rules);
        if($validator->passes()) {
            $node_array = [];
            if($action=='create'){
                $node_name = \Dynamic::check_node_exists('form-'.str_replace('_', '-', $request->input('name')), 0);
                $node = \Dynamic::generate_node($node_name);
                $node_array['model'] = '\App\FormModel';
                $node_array['location'] = 'app';
                $node_array['type'] = 'normal';
                $node_array['folder'] = 'form';
                $node_array['permission'] = $request->input('permission');
                $node_array['dynamic'] = 1;
                $initial_array = ['id'=>'increments'];
                if($request->input('permission')!='form'){
                    $initial_array = $initial_array + ['filled_form_id'=>'integer'];
                }
                $initial_array = $initial_array + ['timestamps'=>'timestamps'];
                \Dynamic::generate_node_table($node->table_name, $initial_array);
            } else {
                $node = \Solunes\Master\App\Node::find($request->input('id'));
            }
            $node_array['additional_permission'] = $request->input('additional_permission');
            $node_array['singular'] = $request->input('singular');
            $node_array['plural'] = $request->input('plural');
            $node = \Dynamic::edit_node($node, $node_array);
            if($action=='create'){
                // Agregar a menu correspondiente
                if($menu_parent = \Solunes\Master\App\Menu::find($request->input('menu_parent'))){
                    $menu = \Solunes\Master\App\Menu::create(['type'=>'normal', 'menu_type'=>'admin', 'permission'=>$menu_parent->permission, 'order'=>count($menu_parent->children)+1, 'parent_id'=>$menu_parent->id, 'level'=>intval($menu_parent->level)+1, 'icon'=>'th-list']);
                    foreach($languages as $language){
                      \App::setLocale($language->code);
                      $menu->translateOrNew($language->code)->name = \DataManager::generateGoogleTranslation(config('solunes.main_lang'), $language->code, request()->input('menu_name'));
                      $menu->translateOrNew($language->code)->link = 'admin/model-list/'.$node->name;
                    }
                    \App::setLocale(config('solunes.main_lang'));
                    $menu->save();
                }
                $count = 0;
                $columns = \Schema::getColumnListing($node->table_name);
                foreach($columns as $col){
                    $count = \FuncNode::node_field_creation($node->table_name, $node, $col, 0, $count, $languages);
                }
                $node->fields()->where('name', 'filled_form_id')->update(['display_item'=>'none']);
                // Agregar action buttons a nodo
                $action_fields = ['edit','delete'];
                \Dynamic::generate_node_extra($node, 'action_field', $action_fields);
                $action_nodes = ['back','create','excel'];
                \Dynamic::generate_node_extra($node, 'action_node', $action_nodes);
            } else {
                $menu = \Solunes\Master\App\Menu::whereTranslation('link', 'admin/model-list/'.$node->name)->first();
                foreach($languages as $language){
                  \App::setLocale($language->code);
                  $menu->translateOrNew($language->code)->name = \DataManager::generateGoogleTranslation(config('solunes.main_lang'), $language->code, request()->input('menu_name'));
                  $menu->translateOrNew($language->code)->link = 'admin/model-list/'.$node->name;
                }
                \App::setLocale(config('solunes.main_lang'));
                $menu->save();
            }
            return AdminItem::post_success($action, 'admin/form/edit/'.$node->id);
        } else {
            return AdminItem::post_fail($action, $this->prev, $validator);
        }
    }

    public function getFormField($action, $parent_id, $name = NULL) {
        $array = ['model'=>'field', 'pdf'=>false, 'dt'=>$action, 'action'=>$action];
        $field = \Solunes\Master\App\Field::where('parent_id', $parent_id)->where('name', $name)->first();
        $array['field'] = $field;
        if($action=='create'){
            $array['type_class'] = [];
            $array['cols'] = NULL;
            $array['i'] = NULL;
            $array['past_field'] = $field;
        } else {
            $array['type_class'] = ['disabled'=>1];
            if($col = $field->field_extras()->where('type', 'cols')->first()){
                $array['cols'] = $col->value;
            } else {
                $array['cols'] = NULL;
            }
            $array['i'] = $field;
            $array['past_field'] = NULL;
        }
        $node = $field->parent;
        $array['types_array'] = ['string'=>'Campo de Texto', 'text'=>'Campo de Párrafo', 'radio'=>'Opción Multiple', 'checkbox'=>'Selector Multiple', 'date'=>'Fecha', 'image'=>'Archivo de Imagen', 'file'=>'Archivo', 'map'=>'Mapa por Coordenadas', 'title'=>'Título (Texto resaltado)', 'content'=>'Subtítulo (Solo texto)'];
        $array['array_active'] = [0=>'Inactivo', 1=>'Activo'];
        $trigger_fields = [];
        foreach($node->fields()->whereIn('type', ['select','radio','checkbox'])->displayItem(['show','admin'])->where('id', '!=', $field->id)->get() as $subfield){
            $trigger_fields[$subfield->name] = $subfield->label.' ('.implode(', ', $subfield->field_options->lists('label')->toArray()).')';
        }
        $array['trigger_fields'] = $trigger_fields;
        $array['trigger_actions'] = ['is'=>'Es igual a', 'is_not'=>'Es distinto a', 'is_greater'=>'Es mayor a', 'is_less'=>'Es menor a', 'in_array'=>'Está dentro del array'];
        if(View::exists('item.form-field')){
            return view('item.form-field', $array);
        }
        return view('master::item.form-field', $array);
    }

    public function postFormField(Request $request) {
        $action = $request->input('action');
        $rules = [
            'display_list'=>'required',
            'display_item'=>'required',
            'label'=>'required',
            'required'=>'required',
            'new_row'=>'required',
            'relation'=>'required',
            'cols'=>'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if($validator->passes()) {
            $field_array = [];
            $field_type = $request->input('type');
            if($action=='create'){
                $node = \Solunes\Master\App\Node::find($request->input('parent_id'));
                $last_field = \Solunes\Master\App\Field::find($request->input('field_id'));

                // Ajustar Orden
                $order = $last_field->order;
                $suborder = $order;
                foreach($node->fields()->where('order', '>', $order)->get() as $subfield){
                  $suborder++;
                  $subfield->order = $suborder+1;
                  $subfield->save();
                }

                // Ajustar ultimo campo en caso de que sea titulo o subtitulo
                if($last_field->type=='title'||$last_field->type=='content'||$last_field->type=='custom'){
                  $last_field = $node->fields()->where('order', '<=', $last_field->order)->whereNotIn('type', ['title','content','custom'])->orderBy('order', 'DESC')->orderBy('id', 'DESC')->first();
                }

                // Asignar un nombre al campo y verificar que no exista.
                $field_id = count($node->fields);
                $field_name = \Dynamic::check_field_exists($node, $node->table_name.'_field_'.$field_id);
                $field = \Dynamic::generate_field($node, $field_name, $field_type);
                $field_array = ['order'=>$order+1, 'trans_name'=>$field_name];
            } else {
                $field = \Solunes\Master\App\Field::find(request()->input('field_id'));
            }
            if($field_type=='title'||$field_type=='content'||$field_type=='custom'){
                $field_array['display_list'] = 'none';
                $field_array['required'] = 0;
            } else {
                $field_array['display_list'] = $request->input('display_list');
                $field_array['required'] = $request->input('required');
            }
            $field_array['display_item'] = $request->input('display_item');
            $field_array['label'] = $request->input('label');
            $field_array['tooltip'] = $request->input('tooltip');
            $field_array['message'] = $request->input('message');
            $field_array['new_row'] = $request->input('new_row');
            $field_array['relation'] = $request->input('relation');
            $field = \Dynamic::edit_field($field, $field_array);
            if($action=='create'){
                \Dynamic::generate_field_table($node, $field->type, $field->name, $field->relation, $last_field);
                // Image folder
                if($field_type=='image'){
                    $field_folder = \Dynamic::generate_field_extra($field, $node->name.'-'.$field_name, 'jpg');
                    \Dynamic::generate_image_size($field_folder, 'normal', 'resize', 600) ;
                }
                if($field_type=='image'||$field_type=='file'){
                    \Dynamic::generate_field_extra($field, 'folder', $node->name.'-'.$field_name) ;
                }
                // Datepicker class correction
                if($field_type=='date'){
                    \Dynamic::generate_field_extra($field, 'class', 'datepicker') ;
                }
            }
            // Cols extra class
            \Dynamic::generate_field_extra($field, 'cols', $request->input('cols')) ;
            // Agregar array de opciones
            //$sub_node = \Solunes\Master\App\Node::where('name', 'field-option')->first();
            //AdminItem::post_subitems($sub_node, 'options', 'parent_id', $field->id, $sub_node->fields()->displayItem(['admin','show'])->whereNotIn('name', ['id', 'parent_id'])->get());
            $options_array = [];
            foreach($request->input('options_id') as $option_key => $option){
              if($request->input('options_label')[$option_key]){
                $options_array[$option_key] = ['name'=>$request->input('options_name')[$option_key], 'label'=>$request->input('options_label')[$option_key], 'active'=>$request->input('options_active')[$option_key]];
              }
            }
            \Dynamic::generate_field_options($options_array, $field, config('solunes.main_lang'));
            // Agregar array de condicionantes
            $sub_node = \Solunes\Master\App\Node::where('name', 'field-conditional')->first();
            if(count(request()->input('conditionals_trigger_value'))>0){
                $input_array = [];
                foreach(request()->input('conditionals_trigger_value') as $input_key => $input){
                    if($input&&$sub_field = \Solunes\Master\App\Field::where('name', request()->input('conditionals_trigger_field')[$input_key])->first()){
                      $subarray = [];
                      foreach(explode('|', $input) as $subinput){
                        if($sub_field_name = $sub_field->field_options()->whereTranslation('label', $subinput)->first()){
                            $subarray[] = $sub_field_name->name;
                        }
                      }
                      $input_array[$input_key] = implode(',', $subarray);
                    }
                }
                request()->merge(['conditionals_trigger_value'=>$input_array]); 
            }
            AdminItem::post_subitems($sub_node, 'conditionals', 'parent_id', $field->id, $sub_node->fields()->displayItem(['admin','show'])->whereNotIn('name', ['id', 'parent_id'])->get());
            return AdminItem::post_success($action, 'admin/form-field/edit/'.$request->input('parent_id').'/'.$field->name);
        } else {
            return AdminItem::post_fail($action, $this->prev, $validator);
        }
    }

    public function getFormFieldOrder($parent_id, $name, $action) {
        $field = \Solunes\Master\App\Field::where('parent_id', $parent_id)->where('name', $name)->first();
        $field_order = $field->order;
        if($action=='up'&&$field_order>2){
            $field_order -= $field->decrement('order');
        } else if($action=='down') {
            $field_order += $field->increment('order');
        } else {
            $field_order = 0;
        }
        if($field_order>0){
            $other_fields = \Solunes\Master\App\Field::where('parent_id', $parent_id)->where('name', '!=', $name)->where('order', $field_order);
            if($action=='up'){
                $other_fields = $other_fields->increment('order');
            } else {
                $other_fields = $other_fields->decrement('order');
            }
        }
        return back();
    }

    public function getExportForms() {
        $dir = public_path('excel');
        array_map('unlink', glob($dir.'/*'));
        $file = \Excel::create('dynamic-forms', function($excel) {
            $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $nodes = \Solunes\Master\App\Node::where('dynamic', 1)->get();
            $nodes_array = [];
            $edits_array = [];
            $extras_array = [];
            $conditionals_array = [];
            $options_array = [];
            $field_node = \Solunes\Master\App\Node::where('name', 'field')->first();
            $fields = $field_node->fields()->whereIn('name', ['name','type','relation','required'])->get();
            foreach($nodes as $node){
                $export_array[$node->name]['name'] = $node->name;
                $col_array = [];
                foreach($fields as $field){
                    array_push($col_array, $field->name);
                }
                array_push($col_array, 'cols');
                array_push($col_array, 'label_es');
                $export_array[$node->name]['columns'] = $col_array;
                if($node->parent_id){
                    $parent = $node->parent->name;
                } else {
                    $parent = NULL;
                }
                array_push($nodes_array, [$node->name, $node->table_name, $node->type, $node->model, $parent, $node->folder, $node->permission, $node->singular, $node->plural]);
                foreach($node->fields()->whereNotIn('type', ['child','subchild'])->get() as $item){
                  $row_array = [];
                  foreach($fields as $field){
                    $field_name = $field->name;
                    array_push($row_array, $item->$field_name);
                  }
                  // EDITS
                  if($item->display_list!='excel'&&$item->name!='id'){
                    array_push($edits_array, [$node->name, $item->name, 'display_list', $item->display_list]);
                  }
                  if($item->display_item!='show'&&$item->name!='id'){
                    array_push($edits_array, [$node->name, $item->name, 'display_item', $item->display_item]);
                  }
                  foreach(['new_row', 'multiple'] as $subfield){
                    if($item->$subfield!=0){
                        array_push($edits_array, [$node->name, $item->name, $subfield, $item->$subfield]);
                    }
                  }
                  if($item->trans_name!=$item->name){
                    array_push($edits_array, [$node->name, $item->name, 'trans_name', $item->trans_name]);
                  }
                  foreach(['tooltip', 'message', 'permission', 'child_table','value'] as $subfield){
                    if($item->$subfield){
                        array_push($edits_array, [$node->name, $item->name, $subfield, $item->$subfield]);
                    }
                  }
                  // EXTRAS
                  $cols = 6;
                  foreach($item->extras as $extra_key => $extra_val){
                    if($extra_key=='cols'){
                      $cols = $extra_val;
                    } else if($extra_key=='class'&&in_array($extra_val, ['timestamppicker','datepicker','timepicker'])){

                    } else {
                      array_push($extras_array, [$node->name, $item->name, $extra_key, $extra_val]);
                    }
                  }
                  // Añadir col a campo
                  array_push($row_array, $cols);
                  // Añadir español label
                  array_push($row_array, $item->label);
                  $export_array[$node->name]['rows'][$item->id] = $row_array;
                  // OPCIONES
                  if(!$item->relation&&in_array($item->type, ['select','radio','checkbox'])&&count($item->options)>0){
                    foreach($item->options as $option_key => $option_val){
                      array_push($options_array, [$node->name, $item->name, $option_key, $option_val]);
                    }
                  }
                  foreach($item->field_conditionals as $cond){
                    array_push($conditionals_array, [$node->name, $item->name, $cond->trigger_field, $cond->trigger_show, $cond->trigger_value]);
                  }
                }
            }
            $excel->sheet('nodes', function($sheet) use ($nodes_array) {
                $sheet->row(1, ['name','table_name','type','model','parent','folder','permission','singular_es','plural_es']);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });
                $fila = 2;
                foreach($nodes_array as $node){
                  $sheet->row($fila, $node);
                  $fila++;
                }
            });
            $excel->sheet('edits', function($sheet) use ($edits_array) {
                $sheet->row(1, ['form','field','column','value']);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });
                $fila = 2;
                foreach($edits_array as $edit){
                  $sheet->row($fila, $edit);
                  $fila++;
                }
            });
            $excel->sheet('extras', function($sheet) use ($extras_array) {
                $sheet->row(1, ['form','field','type','value']);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });
                $fila = 2;
                foreach($extras_array as $extra){
                  $sheet->row($fila, $extra);
                  $fila++;
                }
            });
            $excel->sheet('conditionals', function($sheet) use ($conditionals_array) {
                $sheet->row(1, ['form','field','trigger_field','trigger_show','trigger_value']);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });
                $fila = 2;
                foreach($conditionals_array as $cond){
                  $sheet->row($fila, $cond);
                  $fila++;
                }
            });
            $excel->sheet('options', function($sheet) use ($options_array) {
                $sheet->row(1, ['form','field','name','label_es']);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });
                $fila = 2;
                foreach($options_array as $option){
                  $sheet->row($fila, $option);
                  $fila++;
                }
            });
            foreach($export_array as $export){
              $excel->sheet($export['name'], function($sheet) use($export) {
                $sheet->row(1, $export['columns']);
                $sheet->row(1, function($row) {
                  $row->setFontWeight('bold');
                });

                $fila = 2;
                foreach($export['rows'] as $row){
                  $sheet->row($fila, $row);
                  $fila++;
                }
              });
            }
        })->store('xlsx', $dir, true);
        return response()->download($file['full']);
    }

}