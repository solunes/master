<?php

namespace Solunes\Master\App\Listeners;

class DeletedNode {

    public function handle($node) {
        $menus = \Solunes\Master\App\Menu::whereTranslation('link', 'admin/model-list/'.$node->name)->delete();
    }

}
