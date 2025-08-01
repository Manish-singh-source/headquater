<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    //
    public function index(Request $request)
    {
        if (isset($request->order_id)) {
            $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->find($request->order_id);
            if(!isset($salesOrder)) {
                return view('trackOrder.index')->with('error', 'Order Not Found.');
            }
            return view('trackOrder.index', compact('salesOrder'));
        }
        return view('trackOrder.index');
    }
}
