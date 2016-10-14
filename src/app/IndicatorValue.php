<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorValue extends Model {
	
	protected $table = 'indicator_values';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'trigger_field'=>'required',
		'trigger_value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'trigger_field'=>'required',
		'trigger_value'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

}