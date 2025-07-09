<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TempOrder extends Model
{
    //

    public function vendorInfo(): HasOne {
        return $this->hasOne(Vendor::class, 'vendor_code', 'vendor_code');
    }

}
