<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceDetails;
use App\Models\SalesOrderProduct;

class InvoiceController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->get(),
        ];
        // dd($data['invoices'][0]->customer->client_name);   
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
        // dd($salesOrderProducts);
        $data = [
            'title' => 'Invoice',
            'invoice' => $invoice,
            'invoiceDetails' => InvoiceDetails::with('product')->where('invoice_id', $id)->get(),
            'salesOrderProducts' => $salesOrderProducts,
        ];
        // dd($data);
        // $data = [
        //     'title' => 'Invoice',
        //     'invoice' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id),
        //     'invoiceDetails' => InvoiceDetails::with('product')->where('invoice_id', $id)->get(),
        // ];
        // dd($data);
        // $pdf = PDF::loadView('invoice/invoice', $data);
        return  PDF::loadView('invoice/invoice-pdf', $data)->stream('invoice.pdf');
    }
}
