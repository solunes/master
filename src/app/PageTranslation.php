<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class PageTranslation extends Model implements SluggableInterface {
	
	protected $table = 'page_translation';
    public $timestamps = false;
    protected $fillable = ['name', 'link'];
	
    use SluggableTrait;
    protected $sluggable = array('build_from'=>'name');
	
    public function page() {
        return $this->belongsTo('Solunes\Master\App\Page', 'page_id', 'id');
    }

}