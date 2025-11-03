<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_utr_no',
        'payment_method',
        'payment_status',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
