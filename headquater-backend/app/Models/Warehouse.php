<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Warehouse extends Model
{
    //
    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }

    public function state(): HasOne
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function cities(): HasOne
    {
        return $this->hasOne(City::class, 'id', 'city');
    }
}
