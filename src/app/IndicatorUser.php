<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorUser extends Model {
	
	protected $table = 'indicator_users';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'parent_id'=>'required',
		'user_id'=>'required',
		'graph_type'=>'required',
		'default_date'=>'required',
		'custom_date'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'parent_id'=>'required',
		'user_id'=>'required',
		'graph_type'=>'required',
		'default_date'=>'required',
		'custom_date'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Indicator');
    }

    public function indicator() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}