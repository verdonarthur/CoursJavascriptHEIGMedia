<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Role extends Model {

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission')->withTimestamps();
    }

    public function hasPermission($permissionLabel)
    {
        foreach ($this->permissions as $permission) {
            if ($permission->label == $permissionLabel) {
                return true;
            }
        }
        return false;
    }

}
