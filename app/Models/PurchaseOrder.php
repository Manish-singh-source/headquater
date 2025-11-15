<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    //
    protected $guarded = [];

    public function purchaseOrderProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'id');
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'purchase_order_id', 'id');
    }

    public function vendorPI()
    {
        return $this->hasMany(VendorPI::class, 'purchase_order_id', 'id');
    }

    public function salesOrder()
    {
        return $this->hasOne(SalesOrder::class, 'id', 'sales_order_id');
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function purchaseGrn()
    {
        return $this->hasOne(PurchaseGrn::class, 'purchase_order_id', 'id');
    }

    public function payments()
    {
        return $this->hasManyThrough(VendorPayment::class, VendorPI::class, 'purchase_order_id', 'vendor_pi_id', 'id', 'id');
    }
}
