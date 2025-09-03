<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPIProduct extends Model
{
    //
    protected $guarded = [];
    public $table = 'vendor_p_i_products';

    public function order()
    {
        return $this->belongsTo(VendorPI::class, 'vendor_pi_id', 'id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'sku', 'vendor_sku_code');
    }
}
