<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrderProduct;

class PackagingController extends Controller
{
    //
    public function index()
    {
        $orders = SalesOrder::with('customerGroup')->get();
        return view('packagingList.index', compact('orders'));
    }

    public function view($id)
    {

        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.product',
            'orderedProducts.customer',
            'orderedProducts.tempOrder',
            'orderedProducts.warehouseStock',
        ])
            ->findOrFail($id);


        $facilityNames = SalesOrderProduct::with('customer')
            ->where('sales_order_id', $id)
            ->get()
            ->pluck('customer.client_name')
            ->filter()
            ->unique()
            ->values();

        return view('packagingList.view', compact('salesOrder', 'facilityNames'));
    }
}
