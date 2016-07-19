<?php 

namespace Solunes\Master\App\Helpers;

use Form;

class Segment {
         
    public static function get_node_array($array, $node, $admin = false) {
        $sub_array = [];
        if($node->folder=='form'){
            $admin = true;
        }
        $sub_array = \Segment::get_node_items($sub_array, $node, $admin);
        $array['nodes'][$node->pivot->id]['node'] = $node;
        if($add_custom_array = \CustomFunc::add_node_array($node, $admin)){
            $sub_array['custom'] = $add_custom_array;
        }
        $array['nodes'][$node->pivot->id]['subarray'] = $sub_array;
        return $array;
    }

    public static function get_node_items($sub_array, $node, $admin = false) {
        $model = $node->model;
        if($admin===true){
            if(request()->has($node->table_name)){
                $action = 'edit';
                $id = request()->input($node->table_name);
            } else {
                $action = 'create';
                $id = NULL;
            }
            $sub_array = \AdminItem::get_request_variables('process', $node, $model, $node->name, $action, $id, []);
        } else {
            $items = $model::whereNotNull('id');
            $sub_array = \AdminList::filter_node([], $node, $model, $items, 'site');
            $items = $sub_array['items'];
            $children = $node->children()->where('type', '!=', 'field')->get();
            if(count($children)>0){
                foreach($children as $child){
                    $items = $items->with($child->table_name);
                    if($child->name=='location'){
                        $items = $items->with('locations.parent.member', 'locations.parent.project_sector');
                    }
                }
            }
            $node_requests = $node->node_requests;
            if(count($node_requests)>0){
                $paginate = 0;
                foreach($node_requests as $req){
                    $req_action = $req->action;
                    $req_col = $req->col;
                    $req_value = $req->value;
                    if($req_action=='customRequest'){
                        $items = \CustomFunc::custom_node_request($node, $items, $req_col, $req_value);
                    } else if($req->value_type=='relation'){
                        if($req_value=='node_pivot_id'){
                            $req_value = $node->pivot->id;
                        }
                    } else if($req_action=='whereIn'||$req_action=='with'||$req_action=='has'){
                        $req_value = explode(';', $req_value);
                    }
                    if($req_action=='whereNot'){
                        $items = $items->where($req_col, '!=', $req_value);
                    } else if($req_action=='where'||$req_action=='whereIn'||$req_action=='orderBy'){
                        $items = $items->$req_action($req_col, $req_value);
                    } else if($req_action=='with'||$req_action=='has'){
                        $items = $items->$req_action($req_value);
                    } else if($req_action=='whereNull'||$req_action=='whereNotNull'){
                        $items = $items->$req_action($req_col);
                    }
                    if($req_action=='paginate'){
                        $paginate = $req_value;
                    }
                }
                if($paginate>0){
                    $sub_array['items'] = $items->paginate($paginate);
                } else {
                    $sub_array['items'] = $items->get();
                }
            } else {
                $sub_array['items'] = $items->get();
            }
        }
        return $sub_array;
    }

}