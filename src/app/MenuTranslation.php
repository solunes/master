<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class MenuTranslation extends Model implements SluggableInterface {
	
	protected $table = 'menu_translation';
    public $timestamps = false;
    protected $fillable = ['name', 'link'];
	
    use SluggableTrait;
    protected $sluggable = array('build_from'=>'name');
	
    public function menu() {
        return $this->belongsTo('Solunes\Master\App\Menu', 'menu_id', 'id');
    }

}