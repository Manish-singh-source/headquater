<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ManageOrder extends Model
{
    //
    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(ManageVendor::class, 'order_id', 'id');
    }

    public function manageCustomer(): BelongsTo
    {
        return $this->belongsTo(ManageCustomer::class, 'id', 'order_id');
    }
}
