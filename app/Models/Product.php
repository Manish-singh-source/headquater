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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function productMapping()
    {
        // 
        $productMapping = ProductMapping::where('sku', $this->sku)
            ->where('portal_code', $this->portal_code)
            ->where('item_code', $this->item_code)
            ->first();

        return $productMapping;
    }
}
