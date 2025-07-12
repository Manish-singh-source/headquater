<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerGroup extends Model
{
    //
    protected $table = 'customer_groups';
    protected $fillable = ['group_name', 'customer_id', 'sub_customer_name'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function customerInfo(): HasMany
    {
        return $this->hasMany(Customer::class, 'group_id', 'id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_group_members');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
