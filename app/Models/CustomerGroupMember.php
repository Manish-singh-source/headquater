<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroupMember extends Model
{
    //
    protected $guarded = [];
    
    public function customer() {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
   
    public function customerGroup() {
        return $this->hasOne(CustomerGroup::class, 'id', 'customer_group_id');
    }
}
