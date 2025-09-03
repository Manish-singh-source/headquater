<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroupMember extends Model
{
    //
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function customerGroup()
    {
        return $this->hasOne(CustomerGroup::class, 'id', 'customer_group_id');
    }
}
