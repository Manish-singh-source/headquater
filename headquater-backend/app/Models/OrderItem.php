<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    //
    public function products() : HasOne {
        return $this->hasOne(TempOrder::class, 'sku', 'product_id');
    }
}
