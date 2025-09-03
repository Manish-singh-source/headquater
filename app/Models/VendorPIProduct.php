<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPIProduct extends Model
{
    //
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(VendorPI::class, 'vendor_pi_id', 'id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'vendor_sku_code');
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }

    public function tempOrder()
    {
        return $this->hasOne(TempOrder::class, 'id', 'temp_order_id');
    }
}
