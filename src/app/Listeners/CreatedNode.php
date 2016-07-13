<?php

namespace Solunes\Master\App\Listeners;

class CreatedNode {

    public function handle($node) {
        $saved = false;
        if(!$node->model){
            if($node->type&&($node->type=='global'||$node->type=='system')){
                $node->model = '\Solunes\Master\App\\'.str_replace('_','-',studly_case($node->name));
            } else {
                $node->model = '\App\\'.str_replace('_','-',studly_case($node->name));
            }
            $saved = true;
        }
        if(!$node->location){
            if(strpos($node->model, '\Solunes\Master') !== false){
                $node->location = 'package';
            } else {
                $node->location = 'app';
            }
            $saved = true;
        }
        if(!$node->permission){
            if($node->type&&!in_array($node->type, ['child','subchild','field'])){
                $node->permission = $node->type;
                $saved = true;
            } 
        }
        if($node->location=='package'){
            $lang_folder = 'master::model.';
        } else {
            $lang_folder = 'model.';
        }
        $node->singular = trans_choice($lang_folder.$node->name, 1);
        $node->plural = trans_choice($lang_folder.$node->name, 0);
        if($saved===true){
            $node->save();
        }
    }

}
