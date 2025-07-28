<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VendorPI extends Model
{
    //
    protected $guarded = [];
    public $table = 'vendor_p_i_s';

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'sku', 'vendor_sku_code');
    }

    

    public function products() {
        return $this->hasMany(VendorPIProduct::class, 'vendor_pi_id', 'id');
    }
}
