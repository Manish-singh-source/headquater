<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\VendorPI;
use App\Models\VendorPIProduct;
use App\Models\WarehouseStock;

class ReportController extends Controller
{
    //
    public function vendorPurchaseHistory()
    {
        $purchaseOrdersTotal = VendorPIProduct::sum('mrp');
        $purchaseOrders = VendorPI::with('products')->where('status', 'approve')->get();
        $purchaseOrdersVendors = VendorPI::where('status', 'approve')->pluck('vendor_code', 'vendor_code');
        return view('vendor-purchase-history', compact('purchaseOrders', 'purchaseOrdersTotal', 'purchaseOrdersVendors'));
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
            'customers' => Invoice::with('customer')->get()->map(function ($invoice) {
                return $invoice->customer->client_name ?? null;
            })
        ];
        // dd($data);
        return view('customer-sales-history', $data);
    }
}
