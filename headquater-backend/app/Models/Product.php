<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $guarded = [];

    public function warehouse() {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
    
    public function warehouseStock() {
        return $this->hasOne(WarehouseStock::class, 'product_id', 'sku');
        // return $this->belongsTo(WarehouseStock::class, 'id', 'warehouse_id');
    }
}
