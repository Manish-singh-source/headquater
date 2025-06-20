<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function vendorPurchaseHistory() {
        return view('vendor-purchase-history');
    }

    public function inventoryStockHistory() {
        return view('inventory-stock-history');
    }

    public function customerSalesHistory() {
        return view('customer-sales-history');
    }
}
