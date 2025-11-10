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

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'vendor_code', 'vendor_code');
    }

    public function payments()
    {
        return $this->hasMany(VendorPayment::class, 'vendor_pi_id', 'id');
    }

    public function purchaseInvoice() {
        return $this->hasOne(PurchaseInvoice::class, 'purchase_order_id', 'purchase_order_id');
    }

    public function purchaseGrn() {
        return $this->hasOne(PurchaseGrn::class, 'purchase_order_id', 'purchase_order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
