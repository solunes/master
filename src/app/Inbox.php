<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model {
	
	protected $table = 'inbox';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'user_id'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'user_id'=>'required',
	);

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function other_users() {
        return $this->hasMany('Solunes\Master\App\InboxUser', 'parent_id', 'id')->where('user_id', '!=', auth()->user()->id)->orderBy('updated_at', 'DESC')->limit(2);
    }

    public function last_message() {
        return $this->hasOne('Solunes\Master\App\InboxMessage', 'parent_id', 'id')->orderBy('created_at', 'DESC');
    }

    public function inbox_users() {
        return $this->hasMany('Solunes\Master\App\InboxUser', 'parent_id', 'id');
    }

    public function inbox_messages() {
        return $this->hasMany('Solunes\Master\App\InboxMessage', 'parent_id', 'id');
    }

}