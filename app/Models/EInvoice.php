<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EInvoice extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'einvoice_id',
        'irn',
        'ack_no',
        'ack_dt',
        'signed_invoice',
        'signed_qr_code',
        'ewb_no',
        'ewb_dt',
        'ewb_valid_till',
        'einvoice_pdf',
        'ewaybill_pdf',
        'qr_code_url',
        'einvoice_status',
        'cancel_reason',
        'cancel_remarks',
        'created_by',
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function ewaybills() {
        return $this->hasMany(Ewaybill::class, 'einvoice_id', 'id');
    }

    public function isActive() {
        return $this->einvoice_status == 'ACT';
    }
}
