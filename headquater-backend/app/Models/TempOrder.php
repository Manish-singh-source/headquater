<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TempOrder extends Model
{
    //

    public function vendorInfo(): HasOne
    {
        return $this->hasOne(Vendor::class, 'vendor_code', 'vendor_code');
    }

    public function warehouseStock()
    {
        return $this->hasOne(WarehouseStock::class, 'product_id', 'sku');
        // return $this->belongsTo(WarehouseStock::class, 'id', 'warehouse_id');
    }
}
