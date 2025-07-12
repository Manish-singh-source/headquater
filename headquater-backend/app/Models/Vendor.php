<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vendor extends Model
{
    //
    public function vendorOrders() : HasMany{
        return $this->hasMany(TempOrder::class, 'vendor_code', 'vendor_code')
            ->distinct('vendor_code');
    }            

    public function orderDetail() : HasMany {
        return $this->hasMany(TempOrder::class, 'vendor_code', 'vendor_code');
    }

    public function city() : HasOne {
        return $this->hasOne(City::class, 'id', 'city');
    }
}
