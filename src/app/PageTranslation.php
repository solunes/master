<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PageTranslation extends Model {
	
	protected $table = 'page_translation';
    public $timestamps = false;
    protected $fillable = ['name', 'link'];
	
    use Sluggable, SluggableScopeHelpers;
    public function sluggable(){
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
	
    public function page() {
        return $this->belongsTo('Solunes\Master\App\Page', 'page_id', 'id');
    }

}