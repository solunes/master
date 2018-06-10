<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorValue extends Model {
	
	protected $table = 'indicator_values';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'date'=>'required',
		'type'=>'required',
		'value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'date'=>'required',
		'type'=>'required',
		'value'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

}