<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class ImageFolder extends Model {
	
	protected $table = 'image_folders';
	public $timestamps = true;
    protected $dates = ['deleted_at'];

    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'extension'=>'required'
	);

	/* Updating rules */
	public static $rules_edit = array(
		'name'=>'required',
		'extension'=>'required'
	);

    public function image_sizes() {
        return $this->hasMany('Solunes\Master\App\ImageSize', 'parent_id');
    }

    public function site() {
        return $this->belongsTo('Solunes\Master\App\Site');
    }

}
