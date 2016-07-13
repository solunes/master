<?php

namespace Solunes\Master\App\Listeners;

class SavedMenu {

    public function handle($menu) {
        if($page = $menu->page){
            $menu->translateOrNew('es')->name = $page->name;
        }
    }

}
