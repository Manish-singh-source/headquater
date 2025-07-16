<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    //
    protected $guarded = [];

    public function warehouse() {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
