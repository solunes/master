<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model {
	
	protected $table = 'triggers';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'internal_url'=>'required',
		'date'=>'required',
		'time'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
		'internal_url'=>'required',
		'date'=>'required',
		'time'=>'required',
	);

}