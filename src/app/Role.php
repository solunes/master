<?php

namespace Solunes\Master\App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {
	
	protected $table = 'roles';
	public $timestamps = true;
    protected $dates = ['deleted_at'];

    use \Illuminate\Database\Eloquent\SoftDeletes;
    
	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'name'=>'required',
	);

    public function permission_role() {
        return $this->belongsToMany('Solunes\Master\App\Permission','permission_role');
    }

}