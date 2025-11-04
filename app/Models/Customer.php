<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function groupInfo()
    {
        return $this->hasOne(CustomerGroupMember::class, 'customer_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(SalesOrderProduct::class, 'customer_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', '0');
    }
}
