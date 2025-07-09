<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempOrderStatus extends Model
{
    //
    public function orderedProducts(): HasMany 
    {
        return $this->hasMany(TempOrder::class, 'order_id', 'id');
    }
    
    public function customerGroup(): HasOne 
    {
        return $this->hasOne(CustomerGroup::class, 'id', 'customer_group_id');
    }
    
    public function warehouse(): HasOne 
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
}
