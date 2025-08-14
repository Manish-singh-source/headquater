<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\VendorPIProduct;
use App\Models\WarehouseStock;

class ReportController extends Controller
{
    //
    public function vendorPurchaseHistory()
    {
        // $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')->where('status', 'pending')->get();
        // $purchaseOrdersCount = PurchaseOrderProduct::with(['purchaseOrder' => function($q) {
        //     $q->where('status', 'pending');
        // }])->count();

        $purchaseOrders = VendorPIProduct::with(['order' => function ($q) {
            $q->where('status', 'approve');
        }])->get();
        $purchaseOrdersTotal = VendorPIProduct::sum('mrp');
        // dd($purchaseOrders);
        // $vendorCodes = $purchaseOrders->flatMap(function ($po) {
        //     return $po->purchaseOrderProducts->pluck('vendor_code');
        // })->unique()->values();
        // dd($purchaseOrders);
        return view('vendor-purchase-history', compact('purchaseOrders', 'purchaseOrdersTotal'));
    }

    public function inventoryStockHistory()
    {
        $products = WarehouseStock::with('product', 'warehouse')->get();
        $productsSum = WarehouseStock::sum('quantity');
        $blockProductsSum = WarehouseStock::sum('block_quantity');
        // dd($productsSum);
        return view('inventory-stock-history', compact('products', 'productsSum', 'blockProductsSum'));
    }

    public function customerSalesHistory()
    {
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->get(),
            'invoicesAmountSum' => Invoice::sum('total_amount'),
        ];
        return view('customer-sales-history', $data);
    }
}
