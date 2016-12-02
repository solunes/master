<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorAlert extends Model {
	
	protected $table = 'indicator_alerts';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'goal'=>'required|numeric|min:1',
		'final_date'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'goal'=>'required|numeric|min:1',
		'final_date'=>'required',
	);

    public function indicator() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

    public function indicator_alert_users() {
        return $this->belongsToMany('App\User','indicator_alert_users');
    }

    public function setGraphAttribute($value) {
        $this->attributes['name'] = $this->indicator->name;
        $this->attributes['graph'] = $value;
    }

}