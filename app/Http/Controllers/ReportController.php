<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\VendorPI;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\VendorPIProduct;

class ReportController extends Controller
{
    //
    public function vendorPurchaseHistory()
    {
        $purchaseOrdersTotal = VendorPIProduct::sum('mrp');
        $purchaseOrders = VendorPI::with('products')->where('status', 'approve')->get();
        $purchaseOrdersVendors = VendorPI::where('status', 'approve')->pluck('vendor_code', 'vendor_code');
        // dd($purchaseOrders);
        return view('vendor-purchase-history', compact('purchaseOrders', 'purchaseOrdersTotal', 'purchaseOrdersVendors'));
    }

    public function inventoryStockHistory()
    {
        $products = WarehouseStock::with('product', 'warehouse')->get();
        $productsSum = WarehouseStock::sum('original_quantity');
        $blockProductsSum = WarehouseStock::sum('block_quantity');
        // dd($products);
        return view('inventory-stock-history', compact('products', 'productsSum', 'blockProductsSum'));
    }

    public function customerSalesHistory()
    {
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments'])->get(),
            'invoicesAmountSum' => Invoice::sum('total_amount'),
            'invoicesAmountPaidSum' => Invoice::with('payments')->get()->sum(function ($invoice) {
                return $invoice->payments->sum('amount');
            }),
            'customers' => Invoice::with('customer')->get()->map(function ($invoice) {
                return $invoice->customer->client_name ?? null;
            })
        ];
        // dd($data);
        return view('customer-sales-history', $data);
    }
}
