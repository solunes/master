<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function boot(GateContract $gate) {

        $gate->before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        $gate->define('dashboard', function ($user) {
            return $user->isAdmin();
        });

        $gate->define('node-admin', function ($user, $type, $module, $node, $action, $id = NULL) {
            $custom_check = 'none';
            if(config('solunes.store')&&config('store.check_permission')){
                $custom_check = \CustomStore::check_permission($type, $module, $node, $action, $id);
            }
            if($custom_check=='none'&&config('solunes.check_permission')){
                $custom_check = \CustomFunc::check_permission($type, $module, $node, $action, $id);
            }
            if($custom_check!='none'){
                if($custom_check=='true'){
                    $return = true;
                } else {
                    $return = false;
                }
            } else {
                $return = false;
                if($node->permission){
                    if($user->hasPermission($node->permission)){
                        $return = true;
                    }
                } else {
                    $return = true;
                }
            }
            return $return;
        });

    }

}
