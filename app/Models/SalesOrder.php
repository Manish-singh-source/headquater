<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    //
    protected $guarded = [];

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
}
