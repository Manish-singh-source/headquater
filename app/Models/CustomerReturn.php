<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReturn extends Model
{
    //
    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }
}
