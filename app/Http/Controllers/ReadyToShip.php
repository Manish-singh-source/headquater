<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\VendorPIProduct;
use App\Models\SalesOrderProduct;
use App\Models\VendorReturnProduct;
use App\Http\Controllers\Controller;
use App\Models\ProductIssue;
use App\Services\NotificationService;

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
        $vendorOrders = ProductIssue::with(['order', 'product', 'purchaseOrder', 'tempOrder'])->get();
        // dd($vendorOrders);
        return view('exceed-shortage', compact('vendorOrders'));
    }

    public function returnAccept()
    {
        $vendorOrders = VendorReturnProduct::with('vendorPIProduct')->where('return_status', 'pending')->get();
        // $vendorOrders = VendorPIProduct::with(['order', 'product'])->where('issue_reason', 'Exceed')->where('issue_status', 'pending')->get();
        return view('return-or-accept', compact('vendorOrders'));
    }

    public function acceptVendorProducts($id)
    {
        $vendorReturnProduct = VendorReturnProduct::findOrFail($id);
        $vendorReturnProduct->return_status = 'accepted';
        $vendorReturnProduct->save();

        $warehouseStock = WarehouseStock::where('sku', $vendorReturnProduct->sku)->first();
        if ($warehouseStock) {
            $warehouseStock->available_quantity += $vendorReturnProduct->return_quantity;
            $warehouseStock->original_quantity += $vendorReturnProduct->return_quantity;
            $warehouseStock->save();

            // Create notification for accepted products
            NotificationService::warehouseProductAdded($vendorReturnProduct->sku, $vendorReturnProduct->return_quantity);
        }

        return back()->with('success', 'Products are accepted');
    }

    public function returnVendorProducts($id)
    {
        $vendorReturnProduct = VendorReturnProduct::findOrFail($id);
        $vendorReturnProduct->return_status = 'returned';
        $vendorReturnProduct->save();

        return back()->with('success', 'Products are returned');
    }

}
