<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $guarded = [];

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    
    public function state() {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    
    public function cities() {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function warehouseStock() {
        return $this->hasMany(WarehouseStock::class, 'warehouse_id', 'id');
    }
}
