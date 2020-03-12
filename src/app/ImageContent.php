<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class ImageContent extends Model {
	
	protected $table = 'image_contents';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'code'=>'required',
		'name'=>'name',
		'type'=>'required',
		'extension'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'code'=>'required',
		'name'=>'name',
		'image'=>'name',
		'type'=>'required',
		'extension'=>'required',
	);

}
