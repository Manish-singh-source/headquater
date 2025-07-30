<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class PackagingController extends Controller
{
    //
    public function index()
    {
        $orders = SalesOrder::where('status', 'ready_to_package')->with('customerGroup')->get();
        return view('packaging-list', compact('orders'));
    }

    public function view($id)
    {
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->findOrFail($id);
        // dd($salesOrder);
        return view('packagingList.packing-products-list', compact('salesOrder'));
    }
    
}
