<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model {
	
	protected $table = 'alerts';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
        'node_id'=>'required',
        'name'=>'required',
        'type'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
        'node_id'=>'required',
        'name'=>'required',
        'type'=>'required',
	);

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function alert_actions() {
        return $this->hasMany('Solunes\Master\App\AlertAction', 'parent_id', 'id');
    }

    public function alert_conditionals() {
        return $this->hasMany('Solunes\Master\App\AlertConditional', 'parent_id', 'id');
    }

    public function alert_users() {
        return $this->hasMany('Solunes\Master\App\AlertUser', 'parent_id', 'id');
    }

}