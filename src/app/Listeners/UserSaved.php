<?php

namespace Solunes\Master\App\Listeners;

class UserSaved {

    public function handle($event) {
		if($event->status=='ask_password'){
			if($notification = \Solunes\Master\App\Notification::where('user_id', $event->id)->where('name', 'ask-password')->first()){
				if($notification->checked_date&&$message = $notification->notification_messages()->where('type','dashboard')->first()){
					$notification->checked_date = NULL;
					$notification->save();
				}
			} else {
				\FuncNode::make_dashboard_notitification('ask-password', $event->id, url('account'), 'Le recomendamos que cambie su contraseña por una segura.');
			}
		}
    }

}
