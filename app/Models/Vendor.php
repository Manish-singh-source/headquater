<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(PurchaseOrder::class, 'vendor_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeInActive($query)
    {
        return $query->where('status', '0');
    }

    public function shippingCountry()
    {
        return $this->hasOne(Country::class, 'id', 'shipping_country');
    }

    public function shippingState()
    {
        return $this->hasOne(State::class, 'id', 'shipping_state');
    }

    public function shippingCity()
    {
        return $this->hasOne(City::class, 'id', 'shipping_city');
    }

    public function billingCountry()
    {
        return $this->hasOne(Country::class, 'id', 'billing_country');
    }

    public function billingState()
    {
        return $this->hasOne(State::class, 'id', 'billing_state');
    }

    public function billingCity()
    {
        return $this->hasOne(City::class, 'id', 'billing_city');
    }

}
