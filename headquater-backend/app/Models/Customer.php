<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $guarded = [];

    public function groupInfo() {
        return $this->hasOne(CustomerGroupMember::class, 'customer_id', 'id');
    }
}
