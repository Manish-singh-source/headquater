<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderProduct extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'status' => 'string',
    ];

    // Scope methods for status filtering
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePackaging($query)
    {
        return $query->where('status', 'packaging');
    }

    public function scopePackaged($query)
    {
        return $query->where('status', 'packaged');
    }

    public function scopeReadyToShip($query)
    {
        return $query->where('status', 'ready_to_ship');
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', 'dispatched');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }
    
    public function productMapping()
    {
        return $this->hasOne(ProductMapping::class, 'sku', 'sku');
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
        return $this->hasOne(VendorPIProduct::class, 'vendor_sku_code', 'sku', '');
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

    public function warehouseAllocations()
    {
        return $this->hasMany(WarehouseAllocation::class, 'sales_order_product_id', 'id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetails::class, 'sales_order_product_id', 'id');
    }

    
}
