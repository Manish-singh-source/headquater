<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use Illuminate\Http\Request;

class PackagingController extends Controller
{
    //
    public function index()
    {
        $orders = SalesOrder::where('status', 'ready_to_package')->with('customerGroup')->get();
        return view('packagingList.index', compact('orders'));
    }

    public function view($id)
    {
        $salesOrder = SalesOrder::with('orderedProducts.product', 'orderedProducts.customer', 'orderedProducts.tempOrder')->findOrFail($id);

        // i want this type of 'client_name' names unique 
        // $facilityNames = Customer::pluck('client_name');

        // but these names are placed in customer tabel and customer id is placed in sales order product and both have hasOne connection
        // $facilityNames = SalesOrderProduct::with(['customer' => function ($query) {
        //     $query->pluck('client_name');
        // }])->get();

        $facilityNames = SalesOrderProduct::with('customer')
            ->where('sales_order_id', $id)
            ->get()
            ->pluck('customer.client_name')
            ->filter()
            ->unique()
            ->values();

        // dd($facilityNames->toArray());
        return view('packagingList.view', compact('salesOrder', 'facilityNames'));
    }
}
