<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class FieldOptionTranslation extends Model {
	
	protected $table = 'field_option_translation';
    public $timestamps = false;
    protected $fillable = ['label'];	
}