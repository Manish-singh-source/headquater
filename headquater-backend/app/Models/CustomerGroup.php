<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    //
    protected $table = 'customer_groups';
    protected $fillable = ['group_name', 'customer_id', 'sub_customer_name'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
