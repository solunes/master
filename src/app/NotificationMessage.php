<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model {
	
	protected $table = 'notification_messages';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'parent_id'=>'required',
		'type'=>'required',
		'message'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'parent_id'=>'required',
		'type'=>'required',
		'message'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Notification');
    }

}