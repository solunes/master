<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {
	
	protected $table = 'sites';
	public $timestamps = true;

    public $translatedAttributes = ['title', 'description', 'keywords'];
    protected $fillable = ['name', 'title', 'description', 'keywords'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'domain'=>'required',
		'root'=>'required',
		'title'=>'required'
	);

	/* Updating rules */
	public static $rules_edit = array(
		'name'=>'required',
		'domain'=>'required',
		'root'=>'required',
		'title'=>'required'
	);

}