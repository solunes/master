<?php

namespace Solunes\Master\App\Listeners;

class CreatingMenu {

    public function handle($menu) {
        if($page = $menu->page){
        	foreach(\Solunes\Master\App\Language::get() as $language){
                \App::setLocale($language->code);
            	$menu->translateOrNew($language->code)->name = $page->name;
        	}
            //$menu->save();
        }

        // Definir Order
        if(!$menu->order){
            if($menu_group = \Solunes\Master\App\Menu::where('parent_id', $menu->parent_id)->where('level', $menu->level)->orderBy("order", "DESC")->first()){
                $order = $menu_group->order;
            } else {
                $order = 0;
            }
            $menu->order = $order+1;
            //$menu->save();
        }

    }

}
