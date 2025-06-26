<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    //
    public function shippingCountry(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'shipping_country');
    }
    
    public function billingCountry(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'billing_country');
    }

    public function admins(): HasOne
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }
}
