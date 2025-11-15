<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    //
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function orderedProducts()
    {
        return $this->hasMany(SalesOrderProduct::class, 'sales_order_id', 'id');
    }

    public function notFoundTempOrder()
    {
        return $this->hasMany(NotFoundTempOrder::class, 'sales_order_id', 'id');
    }

    public function notFoundTempOrderByProduct()
    {
        return $this->hasMany(NotFoundTempOrder::class, 'sales_order_id', 'id')->where('product_status', 'Not Found');
    }

    public function notFoundTempOrderByCustomer()
    {
        return $this->hasMany(NotFoundTempOrder::class, 'sales_order_id', 'id')->where('customer_status', 'Not Found');
    }

    public function notFoundTempOrderByVendor()
    {
        return $this->hasMany(NotFoundTempOrder::class, 'sales_order_id', 'id')->where('vendor_status', 'Not Found');
    }

    public function vendorPIs()
    {
        return $this->hasManyThrough(VendorPI::class, PurchaseOrder::class, 'sales_order_id', 'purchase_order_id');
    }

    public function warehouseStockLog()
    {
        return $this->hasOne(WarehouseStockLog::class, 'sales_order_id', 'id');
    }

    public function vendorPIProduct()
    {
        return $this->hasOne(VendorPIProduct::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'sales_order_id', 'id');
    }

    public function tempOrders()
    {
        return $this->hasManyThrough(
            TempOrder::class,
            SalesOrderProduct::class,
            'temp_order_id',      // FK on ordered_products table
            'id',  // FK on temp_orders table
            'id',                  // PK on sales_orders
            'sales_order_id'                   // PK on ordered_products
        );
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'sales_order_id', 'id');
    }

    public function warehouseAllocations()
    {
        return $this->hasMany(WarehouseAllocation::class, 'sales_order_id', 'id');
    }
}
