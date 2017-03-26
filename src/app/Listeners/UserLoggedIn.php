<?php

namespace Solunes\Master\App\Listeners;

class UserLoggedIn {

    public function handle($event) {
        $now = new \DateTime();
        $user = $event->user;
        $user->timestamps = false;
        $user->last_login = $now;
        $user->last_session = session()->getId();
        $user->save();
    }

}
