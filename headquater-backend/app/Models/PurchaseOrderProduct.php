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

    public function product() : HasOne {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }

    public function tempProduct() : HasOne {
        return $this->hasOne(TempOrder::class, 'sku', 'sku');
    }
}
