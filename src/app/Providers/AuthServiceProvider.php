<?php

namespace Solunes\Master\App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function boot(GateContract $gate) {

        $gate->define('dashboard', function ($user) {
            return $user->isAdmin();
        });

        $gate->define('node-admin', function ($user, $type, $module, $node, $action, $id = NULL) {
            $custom_check = \CustomFunc::check_permission($type, $module, $node, $action, $id);
            if($module=='process'){
                $return = true;
            } else {
                $return = false;
                if($node->permission){
                    if(auth()->check()&&$user->hasPermission($node->permission)){
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
