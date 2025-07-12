<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public function group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
