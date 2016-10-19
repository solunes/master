<?php

namespace Solunes\Master\App\Listeners;

class SavedMenu {

    public function handle($menu) {
        if($page = $menu->page){
        	foreach(\Solunes\Master\App\Language::get() as $language){
                \App::setLocale($language->code);
            	$menu->translateOrNew($language->code)->name = $page->name;
        	}
        }

        // Definir Order
        if(!$menu->order){
            if($menu_group = \Solunes\Master\App\Menu::where('type', $menu->type)->where("level", $menu->level)->orderBy("order", "DESC")->first()){
                $order = $menu_group->order;
            } else {
                $order = 0;
            }
            $order = $order+1;
            $menu->order = $order;
        }

    }

}
