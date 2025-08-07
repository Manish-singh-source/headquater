<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Models\InvoiceDetails;
use App\Models\SalesOrderProduct;

class InvoiceController extends Controller
{

    public function index()
    {
        $orders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->get();
        return view('invoice.index', compact('orders'));
    }
    
    public function view($id)
    {
        // $order = SalesOrder::where('status', 'ready_to_ship')->find($id);
        
        // $facilityNames = SalesOrderProduct::with('customer')
        //     ->where('sales_order_id', $id)
        //     ->get()
        //     ->pluck('customer')
        //     ->filter()
        //     ->unique('client_name')
        //     ->pluck('id');
        // $customerInfo = Customer::with('groupInfo.customerGroup')->withCount('orders')->whereIn('id', $facilityNames)->get();
        
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->where('sales_order_id', $id)->get(),
        ];
        return view('invoice.invoices', $data);
    }

    public function downloadPdf($id)
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];  
        // $salesOrder = SalesOrder::with([
        //     'customerGroup',
        //     'warehouse',
        //     'orderedProducts.product',
        //     'orderedProducts.tempOrder',
        //     'orderedProducts' => function ($query) use ($c_id) {
        //         $query->where('customer_id', $c_id);
        //     }
        // ])->findOrFail($id);

        // $customerInfo = Customer::with('addresses')->find($c_id);
        $invoice = Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id);
        $salesOrderProducts = SalesOrderProduct::with('product')->where('sales_order_id', $invoice->sales_order_id)->where('customer_id', $invoice->customer_id)->get();

        $data = [
            'title' => 'Invoice',
            'invoice' => $invoice,
            'invoiceDetails' => InvoiceDetails::with('product')->where('invoice_id', $id)->get(),
            'salesOrderProducts' => $salesOrderProducts,
        ];
        // $data = [
        //     'title' => 'Invoice',
        //     'invoice' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id),
        //     'invoiceDetails' => InvoiceDetails::with('product')->where('invoice_id', $id)->get(),
        // ];
        // $pdf = PDF::loadView('invoice/invoice', $data);
        return  PDF::loadView('invoice/invoice-pdf', $data)->stream('invoice.pdf');
    }
}
