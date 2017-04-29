<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model {
	
	protected $table = 'notification_messages';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'type'=>'required',
		'message'=>'required',
		'is_sent'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'type'=>'required',
		'message'=>'required',
		'is_sent'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Notification', 'parent_id');
    }

}