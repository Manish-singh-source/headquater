<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseOrderProduct extends Model
{
    //
    protected $guarded = [];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }

    public function tempOrder(): HasOne
    {
        return $this->hasOne(TempOrder::class, 'sku', 'sku');
    }

    public function tempOrderThrough()
    {
        return $this->hasOneThrough(
            TempOrder::class,
            SalesOrderProduct::class,
            'temp_order_id',      // FK on ordered_products table
            'id',  // FK on temp_orders table
            'sales_order_product_id',                  // PK on sales_orders
            'id'                   // PK on ordered_products
        );
    }
}
