<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class InboxUser extends Model {
	
	protected $table = 'inbox_users';
	public $timestamps = true;

	/* Creating rules */
	public static $rules_create = array(
		'user_id'=>'required',
		'checked'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'user_id'=>'required',
		'checked'=>'required',
	);

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Inbox');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}