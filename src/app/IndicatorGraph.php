<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorGraph extends Model {
	
	protected $table = 'indicator_graphs';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'graph'=>'required',
		'color'=>'required',
		'goal'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'graph'=>'required',
		'color'=>'required',
		'goal'=>'required',
	);

    public function indicator() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

}