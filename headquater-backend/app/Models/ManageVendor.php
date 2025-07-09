<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ManageVendor extends Model
{
    //
    public function vendor() : HasOne {
        return $this->hasOne(Vendor::class, 'vendor_code', 'vendor_id');
    }
}
