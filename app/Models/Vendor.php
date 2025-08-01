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

}
