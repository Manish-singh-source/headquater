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

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeInActive($query)
    {
        return $query->where('status', '0');
    }


}
