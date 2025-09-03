<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderProduct extends Model
{
    //
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }

    public function tempOrder()
    {
        return $this->hasOne(TempOrder::class, 'id', 'temp_order_id');
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class, 'sales_order_id', 'sales_order_id');
    }

    public function vendorPIProduct()
    {
        return $this->hasOne(VendorPIProduct::class, 'vendor_sku_code', 'sku');
    }

    public function warehouseStock()
    {
        return $this->hasOne(WarehouseStock::class, 'id', 'warehouse_stock_id');
    }

    // public function warehouseStockLog()
    // {
    //     return $this->hasOne(WarehouseStockLog::class, 'sales_order_id', 'sales_order_id');
    // }

    public function warehouseStockLog()
    {
        return $this->hasOne(WarehouseStockLog::class, 'sales_order_id', 'id')->whereColumn('sku', 'warehouse_stock_logs.sku');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
