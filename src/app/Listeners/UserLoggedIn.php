<?php

namespace Solunes\Master\App\Listeners;

class UserLoggedIn {

    public function handle($event) {
        $now = new \DateTime();
        $user = \Auth::user();
        $user->timestamps = false;
        $user->last_login = $now;
        $user->save();
    }

}
