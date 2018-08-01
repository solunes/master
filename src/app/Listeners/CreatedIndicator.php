<?php

namespace Solunes\Master\App\Listeners;

class CreatedIndicator {

    public function handle($indicator) {
        if(auth()->check()){
        	$indicator->user_id = auth()->user()->id;
        	if(!$indicator->filter_query){
        		$indicator->filter_query = json_encode([]);
        	}
        	$indicator->save();
        }
    }

}
