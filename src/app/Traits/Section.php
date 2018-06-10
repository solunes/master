<?php

namespace Solunes\Master\App\Traits;

trait Section {

    public function scopeGetCode($query, $code) {
        return $query->where('code', $code)->first();
    }

}