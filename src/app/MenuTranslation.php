<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class MenuTranslation extends Model {
	
	protected $table = 'menu_translation';
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
	
    public function menu() {
        return $this->belongsTo('Solunes\Master\App\Menu', 'menu_id', 'id');
    }

}