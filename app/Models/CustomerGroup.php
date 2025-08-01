<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    //
    protected $guarded = [];

    public function customerGroupMembers() {
        return $this->hasMany(CustomerGroupMember::class, 'customer_group_id', 'id');
    }
}
