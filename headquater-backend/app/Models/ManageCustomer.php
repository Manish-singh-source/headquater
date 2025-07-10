<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ManageCustomer extends Model
{
    //
    public function customerGroup() : HasOne {
        return $this->hasOne(CustomerGroup::class, 'id', 'customer_id');
    }
}
