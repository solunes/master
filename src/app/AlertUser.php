<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class AlertUser extends Model {
	
	protected $table = 'alert_users';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'parent_id'=>'required',
		'user_id'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'parent_id'=>'required',
		'user_id'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Alert');
    }

}