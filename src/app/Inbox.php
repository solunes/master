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

    public function me() {
        return $this->hasOne('Solunes\Master\App\InboxUser', 'parent_id', 'id')->where('user_id', auth()->user()->id);
    }

    public function other_users() {
        return $this->hasMany('Solunes\Master\App\InboxUser', 'parent_id', 'id')->where('user_id', '!=', auth()->user()->id)->orderBy('updated_at', 'DESC');
    }

    public function other_user() {
        return $this->hasOne('Solunes\Master\App\InboxUser', 'parent_id', 'id')->where('user_id', '!=', auth()->user()->id)->orderBy('updated_at', 'DESC');
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

    public function last_inbox_messages() {
        return $this->hasMany('Solunes\Master\App\InboxMessage', 'parent_id', 'id')->orderBy('id','DESC')->take(10);
    }

    public function last_inbox_message() {
        return $this->hasOne('Solunes\Master\App\InboxMessage', 'parent_id', 'id')->orderBy('id','DESC');
    }

    public function scopeUserInbox($query, $user_id) {
        return $query->whereHas('inbox_users', function($q) use($user_id) {
            $q->where('user_id', $user_id);
        });
    }

    public function scopeUserUnreadInbox($query, $user_id) {
        return $query->whereHas('inbox_users', function($q) use($user_id) {
            $q->where('user_id', $user_id)->where('checked', 0);
        });
    }

}