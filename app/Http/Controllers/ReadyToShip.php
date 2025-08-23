<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrderProduct;
use App\Models\VendorPIProduct;

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

        $order = SalesOrder::where('status', 'ready_to_ship')->find($id);

        $facilityNames = SalesOrderProduct::with('customer')
            ->where('sales_order_id', $id)
            ->get()
            ->pluck('customer')
            ->filter()
            ->unique('client_name')
            ->pluck('id');
        $customerInfo = Customer::with('groupInfo.customerGroup')->withCount('orders')->whereIn('id', $facilityNames)->get();
        // dd($customerInfo);

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
            'orderedProducts.warehouseStockLog'
            ])->with(['orderedProducts' => function($q) use ($c_id) {
                $q->where('customer_id', $c_id);
            }])->findOrFail($id);

        $customerInfo = Customer::with('address')->find($c_id);
        $invoice = Invoice::where('customer_id', $c_id)->where('sales_order_id', $id)->first();

        // dd($salesOrder->orderedProducts);
        // dd(json_encode($salesOrder, JSON_PRETTY_PRINT));
        return view('readyToShip.view-detail', compact('salesOrder', 'customerInfo', 'invoice'));
    }

    public function issuesProducts()
    {
        $vendorOrders = VendorPIProduct::with(['order', 'product'])->where('issue_item', '>', 0)->where('issue_status', 'pending')->get();
        return view('exceed-shortage', compact('vendorOrders'));
    }

    public function returnAccept()
    {
        $vendorOrders = VendorPIProduct::with(['order', 'product'])->where('issue_status', 'return')->get();
        return view('return-or-accept', compact('vendorOrders'));
    }
}
