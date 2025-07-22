<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class ReadyToShip extends Controller
{
    //
    public function index()
    {
        $orders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->get();
        return view('readyToShip.index', compact('orders'));
    }

    public function view($id)
    {
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->findOrFail($id);
        return view('readyToShip.view', compact('salesOrder'));
    }
}
