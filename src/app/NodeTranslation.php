<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class NodeTranslation extends Model {
	
	protected $table = 'node_translation';
    public $timestamps = false;
    protected $fillable = ['singular','plural'];	
}