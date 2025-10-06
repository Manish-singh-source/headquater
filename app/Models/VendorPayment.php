<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    //
    protected $fillable = [];

    public function vendorPI()
    {
        return $this->belongsTo(VendorPI::class, 'vendor_pi_id', 'id');
    }
}
