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
    }

}
