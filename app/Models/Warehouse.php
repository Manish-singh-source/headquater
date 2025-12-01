<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    //
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function cities()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function warehouseStock()
    {
        return $this->hasMany(WarehouseStock::class, 'warehouse_id', 'id');
    }

    public function warehouseAllocations()
    {
        return $this->hasMany(WarehouseAllocation::class, 'warehouse_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', '0');
    }
}
