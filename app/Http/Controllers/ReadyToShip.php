<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrderProduct;

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
            'orderedProducts' => function ($query) use ($c_id) {
                $query->where('customer_id', $c_id);
            }
        ])->findOrFail($id);

        $customerInfo = Customer::with('addresses')->find($c_id);
        $invoice = Invoice::where('customer_id', $c_id)->where('sales_order_id', $id)->first();

        return view('readyToShip.view-detail', compact('salesOrder', 'customerInfo', 'invoice'));
    }
}
