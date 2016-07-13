<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
	
	protected $table = 'activities';
	public $timestamps = true;
    protected $appends = ['username'];
    protected $dates = ['deleted_at'];

    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'user_id'=>'required',
		'node'=>'required',
		'node_id'=>'required|integer',
		'action'=>'required',
		'message'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'user_id'=>'required',
		'node'=>'required',
		'node_id'=>'required|integer',
		'action'=>'required',
		'message'=>'required',
	);

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function getUsernameAttribute($value) {
    	if($this->user){
    		return $this->user->name;
    	} else {
        	return trans('admin.'.$value);
    	}
    }

}