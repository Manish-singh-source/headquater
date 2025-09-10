<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPI extends Model
{
    //
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'vendor_sku_code');
    }

    public function products()
    {
        return $this->hasMany(VendorPIProduct::class, 'vendor_pi_id', 'id');
    }

    public function purchaseOrder() {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }
    

}
