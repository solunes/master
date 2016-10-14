<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorAlert extends Model {
	
	protected $table = 'indicator_alerts';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'goal'=>'required|numeric|min:1',
		'initial_date'=>'required',
		'final_date'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'goal'=>'required|numeric|min:1',
		'initial_date'=>'required',
		'final_date'=>'required',
	);

    public function indicator() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

}