<?php

namespace Solunes\Master\App\Traits;

trait UserPermission {

    public function role_user() {
        return $this->belongsToMany('Solunes\Master\App\Role','role_user');
    }

    public function isAdmin() {
        return $this->hasPermission('dashboard');
    }

    public function isSuperAdmin() {
        return $this->role_user->contains('name', 'superadmin');
    }

    public function hasRole($roleName) {
        if(is_string($roleName)){
            return $this->role_user->contains('name', $roleName);
        } else {
            return !! $roleName->intersect($this->roles)->count();
        }
    }

    public function hasPermission($permissionName) {
        foreach ($this->role_user()->with('permission_role')->get() as $role) {
            return $role->permission_role->contains('name', $permissionName);
        }
        return false;
    }

    public function getPermission() {
    	$array = [];
        foreach ($this->role_user()->with('permission_role')->get() as $role) {
            $array = $role->permission_role->lists('name');
        }
        return $array;
    }

}