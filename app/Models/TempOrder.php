<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempOrder extends Model
{
    //
    protected $guarded = [];

    public function purchaseOrderProduct() {
        return $this->hasOne(purchaseOrderProduct::class, 'temp_order_id', 'id');
    }

    public function vendorPIProduct() {
        return $this->hasOne(VendorPIProduct::class, 'id', 'vendor_pi_id');
    }
}
