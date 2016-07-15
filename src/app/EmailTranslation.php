<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class EmailTranslation extends Model {
	
	protected $table = 'email_translation';
    public $timestamps = false;
    protected $fillable = ['title', 'content'];
	
}