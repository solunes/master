<?php

namespace Solunes\Master\App\Traits;

trait UserPermission {

    // Indicators
    public function indicator_alert_users() {
        return $this->belongsToMany('Solunes\Master\App\IndicatorAlert','indicator_alert_users');
    }

    public function indicator_graph_users() {
        return $this->belongsToMany('Solunes\Master\App\IndicatorGraph','indicator_graph_users');
    }

    public function hasIndicatorAlert($id) {
        $array = $this->indicator_alert_users->lists('id')->toArray();
        return in_array($id, $array);
    }

    public function hasIndicatorGraph($id) {
        $array = $this->indicator_graph_users->lists('id')->toArray();
        return in_array($id, $array);
    }

    // Permission
    public function role_user() {
        return $this->belongsToMany('Solunes\Master\App\Role','role_user');
    }

    public function isAdmin() {
        return $this->hasPermission('dashboard');
    }

    public function isSuperAdmin() {
        return $this->id == config('solunes.master_admin_id');
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

    public function getProfileImgNormalAttribute() {
        if($this->image){
            return asset('assets/admin/img/user.jpg');
        } else {
            return $path;
        }
    }

}