<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerGroupMember extends Model
{
    protected $table = 'customer_group_members';

    public function customer() : HasOne {
        return $this->hasOne(Customer::class, 'client_name', 'customer_id');
    }
}
