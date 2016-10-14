<?php

namespace Solunes\Master\App\Listeners;

class CreatedIndicator {

    public function handle($indicator) {
        if(auth()->check()){
        	$indicator->user_id = auth()->user()->id;
        	$indicator->save();
        }
    }

}
