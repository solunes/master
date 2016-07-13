<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class SiteTranslation extends Model {
	
	protected $table = 'site_translation';
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'keywords'];
	
}