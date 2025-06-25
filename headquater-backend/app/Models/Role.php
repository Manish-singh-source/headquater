<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    //
    public function admins(): HasOne
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }
}
