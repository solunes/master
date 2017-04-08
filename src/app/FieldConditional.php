<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class FieldConditional extends Model {
	
	protected $table = 'field_conditionals';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'trigger_field'=>'required',
		'trigger_show'=>'required',
		'trigger_value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'trigger_field'=>'required',
		'trigger_show'=>'required',
		'trigger_value'=>'required',
	);

    public function field() {
        return $this->belongsTo('Solunes\Master\App\Field', 'parent_id');
    }

}