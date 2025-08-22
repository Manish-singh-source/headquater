<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'warehouse_id',
        'invoice_number',
        'customer_id',
        'sales_order_id',
        'invoice_date',
        'round_off',
        'total_amount',
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
        return $this->hasMany(InvoiceDetails::class);
    }

    public function appointment() {
        return $this->hasOne(Appointment::class);
    }

    public function dns() {
        return $this->hasOne(Dn::class);
    }
   
    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
