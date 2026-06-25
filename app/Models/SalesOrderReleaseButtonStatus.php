<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderReleaseButtonStatus extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_clicked' => 'boolean',
        'clicked_at' => 'datetime',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }

    public function clickedBy()
    {
        return $this->belongsTo(User::class, 'clicked_by', 'id');
    }
}
