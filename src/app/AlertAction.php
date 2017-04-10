<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class AlertAction extends Model {
	
	protected $table = 'alert_actions';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'parent_id'=>'required',
		'type'=>'required',
		'content'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'parent_id'=>'required',
		'type'=>'required',
		'content'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Alert');
    }

}