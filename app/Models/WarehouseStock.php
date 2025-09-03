<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    //
    protected $guarded = [];

    public function product() {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    // public function blockProducts() {
    //     return $this->hasMany(BlockProducts::class, 'warehouse_stock_id', 'id');
    // }
}
