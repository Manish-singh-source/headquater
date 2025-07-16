<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderProduct extends Model
{
    //
    public function product() {
        return $this->hasOne(Product::class, 'sku', 'sku');
    }
    
    public function tempOrder() {
        return $this->hasOne(TempOrder::class, 'id', 'temp_order_id');
    }
}
