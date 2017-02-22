<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PageTranslation extends Model {
	
	protected $table = 'page_translation';
    public $timestamps = false;
    protected $fillable = ['slug','name'];
	
    use Sluggable, SluggableScopeHelpers;
    public function sluggable(){
        return [
            'slug' => [
                'source' => 'slugtitle'
            ]
        ];
    }
	
    public function page() {
        return $this->belongsTo('Solunes\Master\App\Page', 'page_id', 'id');
    }
    
    public function getSlugtitleAttribute() {
        $slug = $this->name;
        if($this->meta_title){
            $slug .= '-'.$this->meta_title;
        }
        return $slug;
    }

}