<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    public function city()
    {
        return $this->hasOne(City::class,  'id', 'shipping_city');
    }
    
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'shipping_state');
    }
    
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'shipping_country');
    }
    
    public function billingCity()
    {
        return $this->hasOne(City::class,  'id', 'billing_city');
    }
    
    public function billingState()
    {
        return $this->hasOne(State::class, 'id', 'billing_state');
    }
    
    public function billingCountry()
    {
        return $this->hasOne(Country::class, 'id', 'billing_country');
    }

}
