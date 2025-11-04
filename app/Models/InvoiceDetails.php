<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    //
    protected $fillable = [
        'invoice_id',
        'warehouse_id',
        'product_id',
        'hsn',
        'quantity',
        'box_count',
        'weight',
        'unit_price',
        'discount',
        'amount',
        'tax',
        'total_price',
        'description',
        'temp_order_id',
        'sales_order_product_id',
        'po_number',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
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
