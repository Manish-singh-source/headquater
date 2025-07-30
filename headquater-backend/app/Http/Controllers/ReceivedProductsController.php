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
        $vendorsCompletedPI = VendorPI::where('status', '=', 'completed')->pluck('vendor_code');
        $purchaseOrders = PurchaseOrder::get(); 
        if(isset($request->purchase_order_id) && isset($request->vendor_code)) {
            $vendorPIs = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            return view('receivedProducts.view', compact('vendors', 'purchaseOrders', 'vendorPIs', 'vendorsCompletedPI'));
        }
        return view('receivedProducts.view', compact('vendors', 'purchaseOrders', 'vendorsCompletedPI'));
    }
    
    public function update(Request $request) {
        $purchaseOrder = PurchaseOrder::where('id', $request->purchase_order_id)->first();
        $purchaseOrder->status = 'completed';
        $purchaseOrder->save(); 
        
        if($purchaseOrder) {
            return back()->with('success', 'Order Saved Successfully.');
        }
        
        return back()->with('error', 'Something Went Wrong.');
    }
}
