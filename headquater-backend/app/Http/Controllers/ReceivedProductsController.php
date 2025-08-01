<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderProduct;
use App\Models\VendorPI;

class ReceivedProductsController extends Controller
{

    public function index()
    {
        $purchaseOrders = PurchaseOrder::where('status', 'pending')->get();
        return view('receivedProducts.index', compact('purchaseOrders'));
    }

    public function view(Request $request)
    {
        $vendorPIs = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
        // dd($vendorPIs);
        return view('receivedProducts.view', compact('vendorPIs'));
    }


    public function getVendors(Request $request)
    {
        $vendorsList = VendorPI::where('purchase_order_id', $request->id)->where('status', '!=', 'completed')->get();

        return response()->json([
            'success' => true,
            'message' => 'Vendors retrieved successfully',
            'data' => $vendorsList
        ], 200);
    }

    public function update(Request $request)
    {
        $purchaseOrder = PurchaseOrder::where('id', $request->purchase_order_id)->first();
        $purchaseOrder->status = 'completed';
        $purchaseOrder->save();

        if ($purchaseOrder) {
            return back()->with('success', 'Order Saved Successfully.');
        }

        return back()->with('error', 'Something Went Wrong.');
    }
}
