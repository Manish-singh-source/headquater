<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WarehouseStock extends Model
{
    //
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }

    public function productMapping(): HasOne
    {
        return $this->hasOne(ProductMapping::class, 'sku', 'sku')->latestOfMany();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    // public function blockProducts() {
    //     return $this->hasMany(BlockProducts::class, 'warehouse_stock_id', 'id');
    // }
}
