<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderProduct;
use App\Models\VendorPI;

class ReceivedProductsController extends Controller
{
    public function view(Request $request)
    {

        $vendors = PurchaseOrderProduct::distinct()->pluck('vendor_code');
        $purchaseOrders = PurchaseOrder::get(); 
        if(isset($request->purchase_order_id) && isset($request->vendor_code)) {
            $vendorPIs = VendorPI::where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->get();
            // dd($vendorPIs);
            return view('receivedProducts.view', compact('vendors', 'purchaseOrders', 'vendorPIs'));
        }
        return view('receivedProducts.view', compact('vendors', 'purchaseOrders'));
    }
}
