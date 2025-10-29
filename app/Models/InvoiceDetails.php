<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    //
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'amount',
        'tax',
        'total_price',
        'description',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tempOrder()
    {
        return $this->belongsTo(TempOrder::class, 'temp_order_id', 'id');
    }

    public function salesOrderProduct()
    {
        return $this->belongsTo(SalesOrderProduct::class, 'sales_order_product_id', 'id');  
    }

}
