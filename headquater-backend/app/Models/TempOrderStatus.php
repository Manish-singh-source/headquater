<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempOrderStatus extends Model
{
    //
    public function orderedProducts(): HasMany 
    {
        return $this->hasMany(TempOrder::class, 'order_id', 'temp_order_id');
    }
}
