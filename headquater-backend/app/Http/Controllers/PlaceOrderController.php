<?php

namespace App\Http\Controllers;

use App\Models\TempOrderStatus;
use Illuminate\Http\Request;

class PlaceOrderController extends Controller
{
    //
    public function assignOrder() {
        $vendorOrders = TempOrderStatus::where('status', '0')->with(['orderedProducts.vendorInfo', 'warehouse'])->get();
        // dd($vendorOrders);
        return view('assign-order', compact('vendorOrders'));
    }

    public function assignOrderToVendor() {
        return view('assign-order-to-vendor');
    }
}
