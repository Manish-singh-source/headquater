<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\WarehouseStock;

class ReportController extends Controller
{
    //
    public function vendorPurchaseHistory() {
        return view('vendor-purchase-history');
    }

    public function inventoryStockHistory() {
        $products = WarehouseStock::with('product', 'warehouse')->get();
        return view('inventory-stock-history', compact('products'));
    }

    public function customerSalesHistory() {
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->get(),
        ];
        return view('customer-sales-history', $data);
    }
}
