<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
	
	protected $table = 'notifications';
	public $timestamps = true;

    use \Solunes\Master\App\Traits\LastId;

	/* Creating rules */
	public static $rules_create = array(
		'user_id'=>'required',
		'name'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'user_id'=>'required',
		'name'=>'required',
	);

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function notification_messages() {
        return $this->hasMany('Solunes\Master\App\NotificationMessage', 'parent_id');
    }

    public function scopeMe($query) {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeType($query, $type) {
        return $query->whereHas('notification_messages', function ($subquery) use($type) {
		    $subquery->where('type', $type);
		})->with('notification_messages');
    }

    public function scopeSent($query) {
        return $query->whereNotNull('checked_date');
    }

    public function scopeNotSent($query) {
        return $query->whereNull('checked_date');
    }

}