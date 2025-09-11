<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\VendorPIProduct;
use App\Models\SalesOrderProduct;
use App\Http\Controllers\Controller;

class ReadyToShip extends Controller
{
    //
    public function index()
    {
        $orders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->get();
        return view('readyToShip.index', compact('orders'));
    }

    public function view($id)
    {

        $order = SalesOrder::with('orderedProducts')->where('status', 'ready_to_ship')->find($id);

        $facilityNames = SalesOrderProduct::with('customer')
            ->where('sales_order_id', $id)
            ->get()
            ->pluck('customer')
            ->filter()
            ->unique('client_name')
            ->pluck('id');
            
        $customerInfo = Customer::with('groupInfo.customerGroup')->withCount('orders')->whereIn('id', $facilityNames)->get();

        return view('readyToShip.view', compact('customerInfo', 'order'));
    }

    public function viewDetail($id, $c_id)
    {
        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.product',
            'orderedProducts.tempOrder',
            'orderedProducts.customer',
            'orderedProducts.warehouseStock',
        ])->with(['orderedProducts' => function ($q) use ($c_id) {
            $q->where('customer_id', $c_id);
        }])->findOrFail($id);

        $customerInfo = Customer::find($c_id);
        $invoice = Invoice::where('customer_id', $c_id)->where('sales_order_id', $id)->first();
        
        // dd(json_encode($salesOrder, JSON_PRETTY_PRINT));
        return view('readyToShip.view-detail', compact('salesOrder', 'customerInfo', 'invoice'));
    }

    public function issuesProducts()
    {
        $vendorOrders = VendorPIProduct::with(['order', 'product'])->where('issue_reason', 'Shortage')->where('issue_status', 'pending')->get();
        return view('exceed-shortage', compact('vendorOrders'));
    }

    public function returnAccept()
    {
        $vendorOrders = VendorPIProduct::with(['order', 'product'])->where('issue_reason', 'Exceed')->where('issue_status', 'pending')->get();
        // dd($vendorOrders);
        return view('return-or-accept', compact('vendorOrders'));
    }
}
