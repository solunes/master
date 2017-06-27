<?php

namespace Solunes\Master\App\Listeners;

class CreatedNode {

    public function handle($node) {
        $saved = false;
        if(!$node->type){
            $node->type = 'normal';
        }
        if(!$node->folder&&(!$node->type||$node->type=='normal')){
            $node->folder = 'site';
            $saved = true;
        }
        if(!$node->location){
            if($node->folder=='system'||$node->folder=='global'){
                $node->location = 'package';
            } else {
                $node->location = 'app';
            }
            $saved = true;
        }
        if(!$node->table_name){
            $node->table_name = str_replace('-','_',$node->name).'s';
            $saved = true;
        }
        if(!$node->model){
            if($node->location=='package'){
                $node->model = '\Solunes\Master\App\\'.str_replace('_','-',studly_case($node->name));
            } else if($node->location=='store') {
                $node->model = '\Solunes\Store\App\\'.str_replace('_','-',studly_case($node->name));
            } else {
                $node->model = '\App\\'.str_replace('_','-',studly_case($node->name));
            }
            $saved = true;
        }
        if(!$node->permission){
            if($node->type=='normal'){
                $node->permission = $node->folder;
                $saved = true;
            } 
        }
        if($saved===true){
            $node->save();
        }
    }

}
