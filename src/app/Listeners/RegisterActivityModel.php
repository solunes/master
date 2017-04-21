<?php

namespace Solunes\Master\App\Listeners;

class RegisterActivityModel {

    public function handle($event) {
    	$event_model = '\\'.get_class($event);
    	// Revisar que tenga una sesión y sea un modelo del sitio web.
    	$blocked_array = ['menu','user','activity','notification'];
    	$blocked_array = $blocked_array + config('solunes.blocked_activities');
	    if($event&&request()->segment(1)!='artisan'&&request()->segment(1)!='api'&&!\App::runningInConsole()&&$node = \Solunes\Master\App\Node::where('type','!=','subchild')->where('model', '!=', '\App\User')->whereNotIn('name', $blocked_array)->where('model', $event_model)->first()){
			/*try {
			    $event_string = (string)json_encode($event);
			    $event_decoded = json_decode($event_string, true);
			} catch (Exception $e) {
			    return false;
			}*/
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
		    $fields = $node->fields()->whereNotIn('name', ['id','created_at','updated_at'])->with('field_options')->get();
		    foreach($fields as $field){
		    	$field_name = $field->name;
		    	$field_trans_name = $field->trans_name;
		    	$value = $event->$field_name;
		    	if(($value||$value=='0')&&is_string($value)){
		    		if($field->relation&&$subfield = $event->$field_trans_name){
		    			$value = $subfield->name;
		    		} else if(!$field->relation&&in_array($field->type, ['select','radio','checkbox'])){
		    			if($subfield = $field->field_options->where('name', $value)->first()){
		    				$value = $subfield->label;
		    			}
		    		}
			    	$message .= '<strong>'.$field->label.':</strong> '.$value.'<br>';
		    	}
			}
		    \FuncNode::make_activity($node->id, $event->id, $user_id, $username, $action, $message);
	    }
    }

}
