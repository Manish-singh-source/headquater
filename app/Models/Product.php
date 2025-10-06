<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $guarded = [];

    public function warehouseStock()
    {
        return $this->hasOne(WarehouseStock::class, 'sku', 'sku');
    }
}
