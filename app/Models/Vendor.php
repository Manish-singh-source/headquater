<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(PurchaseOrder::class, 'vendor_id', 'id');
    }
}
