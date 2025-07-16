<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    //
    public function customerGroup() {
        return $this->hasOne(CustomerGroup::class, 'id', 'customer_group_id');
    }
    
    public function warehouse() {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
    
    public function orderedProducts() {
        return $this->hasMany(SalesOrderProduct::class, 'sales_order_id', 'id');
    }
    
}
