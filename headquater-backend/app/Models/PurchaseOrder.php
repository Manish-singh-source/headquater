<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    //
    public function purchaseOrderProducts() {
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'id');
    }
}
