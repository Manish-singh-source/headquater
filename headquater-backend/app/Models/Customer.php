<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    //
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'gst_number',
        'pan_number',
        'shipping_address',
        'shipping_country',
        'shipping_state',
        'shipping_city',
        'shipping_pincode',
        'billing_address',
        'billing_country',
        'billing_state',
        'billing_city',
        'billing_pincode',
        'status',
        'group_id',
        'created_at',
        'updated_at',
    ];

    // In app/Models/Customer.php
    protected $casts = [
        'status' => 'integer',
    ];


    public function shippingCountry(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'shipping_country');
    }

    public function billingCountry(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'billing_country');
    }

    public function admins(): HasOne
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }
    
    public function orders() : HasMany {
        return $this->hasMany(OrderItem::class, 'customer_id', 'client_name');
    }
}
