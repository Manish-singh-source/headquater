<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ewaybill extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'einvoice_id',
        'ewb_no',
        'ewb_dt',
        'ewb_valid_till',
        'ewaybill_pdf'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function einvoice()
    {
        return $this->belongsTo(Einvoice::class);
    }
}
