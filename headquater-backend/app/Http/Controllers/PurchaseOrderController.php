<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\TempOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    //
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')->get();
        $vendorCodes = $purchaseOrders->flatMap(function ($po) {
            return $po->purchaseOrderProducts->pluck('vendor_code');
        })->unique()->values();
        return view('purchaseOrder.index', compact('purchaseOrders', 'vendorCodes'));
    }

    public function view($id)
    {
        $tempOrder = TempOrder::get();
        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $id)->with('purchaseOrder', 'tempProduct')->get();
        $vendors = PurchaseOrderProduct::distinct()->pluck('vendor_code');
        return view('purchaseOrder.view', compact('purchaseOrderProducts', 'vendors'));
    }
}
