<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseAllocation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'allocated_quantity' => 'integer',
        'final_dispatched_quantity' => 'integer',
        'box_count' => 'integer',
        'weight' => 'integer',
        'sequence' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }

    public function salesOrderProduct()
    {
        return $this->belongsTo(SalesOrderProduct::class, 'sales_order_product_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }

    public function warehouseStock()
    {
        return $this->hasOne(WarehouseStock::class, 'sku', 'sku')
            ->where('warehouse_id', $this->warehouse_id);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAllocated($query)
    {
        return $query->where('status', 'allocated');
    }

    public function scopeFulfilled($query)
    {
        return $query->where('status', 'fulfilled');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeBySku($query, $sku)
    {
        return $query->where('sku', $sku);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('sales_order_id', $orderId);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Get allocation summary for a sales order
     */
    public static function getAllocationSummary($salesOrderId)
    {
        return self::where('sales_order_id', $salesOrderId)
            ->with(['warehouse', 'product'])
            ->orderBy('sku')
            ->orderBy('sequence')
            ->get()
            ->groupBy('sku');
    }
}

