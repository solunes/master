<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class FieldTranslation extends Model {
	
	protected $table = 'field_translation';
    public $timestamps = false;
    protected $fillable = ['label','tooltip','message'];	
}