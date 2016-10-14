<?php

namespace Solunes\Master\App\Listeners;

class CreatedIndicatorChild {

    public function handle($subindicator) {
        if(auth()->check()){
        	$subindicator->user_id = auth()->user()->id;
        	$subindicator->save();
        }
    }

}
