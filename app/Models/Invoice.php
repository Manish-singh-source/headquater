<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $fillable = [
        'warehouse_id',
        'invoice_number',
        'customer_id',
        'sales_order_id',
        'invoice_date',
        'round_off',
        'total_amount',
        'subtotal',
        'taxable_amount',
        'tax_amount',
        'discount_amount',
        'paid_amount',
        'balance_due',
        'payment_mode',
        'payment_status',
        'invoice_type',
        'invoice_item_type',
        'notes',
        'po_number',
        'po_date',
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
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'po_date' => 'date',
        'ack_dt' => 'datetime',
        'ewb_dt' => 'datetime',
        'ewb_valid_till' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetails::class, 'invoice_id', 'id');
    }

    public function warehouseItems()
    {
        return $this->hasMany(InvoiceDetails::class)->with(['product', 'warehouse']);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }

    public function dns()
    {
        return $this->hasOne(Dn::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function einvoices() {
        return $this->hasMany(Einvoice::class);
    }

    public function ewaybills() {
        return $this->hasMany(Ewaybill::class);
    }

}
