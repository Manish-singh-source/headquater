<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManageCustomer extends Model
{
    //
    public function customerGroup() : HasMany {
        return $this->hasMany(Customer::class, 'group_id', 'customer_id');
    }
}
