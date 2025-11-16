<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dn extends Model
{
    protected $fillable = [
        'invoice_id',
        'dn_amount',
        'dn_reason',
        'dn_receipt',
    ];
}
