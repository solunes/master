<?php

namespace Solunes\Master\App\Traits;

trait LastId {

    public function scopeLastId($query) {
        if(request()->has('last_id')){
            $query->where('id', '>', request()->input('last_id'));
        }
    }

}