<?php

namespace App\Http\Controllers;

use App\Models\ManageOrder;

class PlaceOrderController extends Controller
{
    //
    public function assignOrder()
    {
        $holdedOrders = ManageOrder::where('status', '2')->with(['warehouse', 'vendorCodes'])->get();
        return view('assign-order', compact('holdedOrders'));
    }

    public function assignOrderToVendor()
    {
        return view('assign-order-to-vendor');
    }
}
