<?php

namespace Solunes\Master\App\Listeners;

class RegisterActivityModel {

    public function handle($event) {
    	$event_model = '\\'.get_class($event);
    	// Revisar que tenga una sesión y sea un modelo del sitio web.
    	$blocked_array = ['menu','user','activity','notification'];
    	$blocked_array = $blocked_array + config('solunes.blocked_activities');
	    if($event&&request()->segment(1)!='artisan'&&request()->segment(1)!='api'&&$node = \Solunes\Master\App\Node::where('type','!=','subchild')->where('model', '!=', '\App\User')->whereNotIn('name', $blocked_array)->where('model', $event_model)->first()){
			try {
			    $event_string = (string)json_encode($event);
			    $event_decoded = json_decode($event_string);
			} catch (Exception $e) {
			    return false;
			}
		    if(\Auth::check()){
			    $now = new \DateTime();
			    $user = \Auth::user();
        		$user->timestamps = false;
			    $user->last_activity = $now;
			    $user->save();
			    $user_id = 1;
		    	$username = 'user';
		    } else {
		    	$user_id = NULL;
		    	if(strpos(php_sapi_name(), 'cli') !== false){
		    		$username = 'console';
		    	} else {
		    		$username = 'anonym';
		    	}
		    }
		    // CREAR ACTIVIDAD
		    if($event->wasRecentlyCreated==1){
		    	$action = 'node_created';
		    } else {
		    	$action = 'node_edited';
		    }
		    $message = '';
		    if($node->location=='package'){
		    	$lang_folder = 'master::fields.';
		    } else {
		    	$lang_folder = 'fields.';
		    }
		    if(count($event_decoded)>0){
			    foreach($event_decoded as $key => $i){
			    	if(is_string($i)&&is_string($key)&&strpos($key, '_id') === false&&$key!='id'&&$key!='created_at'&&$key!='updated_at'){
			    		$message .= '<strong>'.trans($lang_folder.$key).':</strong> '.strip_tags($i).'<br>';
			    	}
			    }
			}
		    \FuncNode::make_activity($node->id, $event->id, $user_id, $username, $action, $message);
	    }
    }

}
