<?php

namespace Solunes\Master\App\Listeners;

class RestoredNode {

    public function handle($node) {
        $menus = \Solunes\Master\App\Menu::withTrashed()->whereTranslation('link', 'admin/model-list/'.$node->name)->restore();
    }

}
