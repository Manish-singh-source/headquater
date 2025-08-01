<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;

class InvoiceController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->get(),
        ];
        // dd($data['invoices'][0]->customer->client_name);   
        return view('invoices', $data);
    }

    public function downloadPdf()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];
        // $data = [

        //     'title' => 'Invoice',
        //     'invoice' => Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id),
        //     'invoiceDetails' => InvoiceDetails::with('product')->where('invoice_id', $id)->get(),
        // ];
        // dd($data);
        // $pdf = PDF::loadView('invoice/invoice', $data);
        return  PDF::loadView('invoice/invoice', $data)->stream('invoice.pdf');
    }
}
