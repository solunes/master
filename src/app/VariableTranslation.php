<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class VariableTranslation extends Model {
	
	protected $table = 'variable_translation';
    public $timestamps = false;
    protected $fillable = ['value'];	
}