<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class UniqueCheck extends Model {
	
	protected $table = 'unique_checks';
	public $timestamps = true;
    protected $fillable = ['key', 'value'];

	/* Creating rules */
	public static $rules_create = array(
		'key'=>'required',
		'value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'key'=>'required',
		'value'=>'required',
	);

}
