<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceOrderController extends Controller
{
    //
    public function assignOrder() {
        return view('assign-order');
    }

    public function assignOrderToVendor() {
        return view('assign-order-to-vendor');
    }
}
