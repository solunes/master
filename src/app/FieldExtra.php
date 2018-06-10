<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class FieldExtra extends Model {
	
	protected $table = 'field_extras';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'type'=>'required',
		'value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'type'=>'required',
		'value'=>'required',
	);

    public function field() {
        return $this->belongsTo('Solunes\Master\App\Field', 'parent_id');
    }

}