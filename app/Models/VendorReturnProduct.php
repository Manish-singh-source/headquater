<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorReturnProduct extends Model
{
    //
    protected $guarded = [];

    public function vendorPIProduct()
    {
        return $this->belongsTo(VendorPIProduct::class, 'vendor_pi_product_id', 'id');
    }
}
