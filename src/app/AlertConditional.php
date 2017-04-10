<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class AlertConditional extends Model {
	
	protected $table = 'alert_conditionals';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'parent_id'=>'required',
		'field_id'=>'required',
		'active'=>'required',
		'conditional'=>'required',
		'value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'parent_id'=>'required',
		'field_id'=>'required',
		'active'=>'required',
		'conditional'=>'required',
		'value'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Alert');
    }

}