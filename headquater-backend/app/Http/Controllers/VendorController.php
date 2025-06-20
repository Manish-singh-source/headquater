<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    //
    public function vendorList() {
        return view('vendor');
    }

    public function createVendor() {
        return view('create-vendor');
    }

    public function vendorDetails() {
        return view('vendor-details');
    }

    public function vendorOrderView() {
        return view ('vendor-order-view');
    }
}
