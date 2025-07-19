<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    //
    public $table = 'staff';

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission()
    {
        return $this->role;
        foreach ($this->role->permissions as $role) {
            if ($role == $permission) {
                return true;
            }
        }
        return false;
    }
}
