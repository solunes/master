<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
	
	protected $table = 'notifications';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'user_id'=>'required',
		'message'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'user_id'=>'required',
		'message'=>'required',
	);

    public function node() {
        return $this->belongsTo('Solunes\Master\App\node');
    }

}