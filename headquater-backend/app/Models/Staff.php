<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    //
    public $table = 'staff';

    public function role() {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
