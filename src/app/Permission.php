<?php

namespace Solunes\Master\App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {
	
	protected $table = 'permissions';
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
        return $this->belongsToMany('Solunes\Master\App\Role','permission_role');
    }

}