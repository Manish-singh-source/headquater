<?php

namespace App\Http\Controllers;

use App\Models\Dn;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesOrder;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\InvoiceDetails;
use App\Models\SalesOrderProduct;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

    public function index()
    {
        $orders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->get();
        return view('invoice.index', compact('orders'));
    }

    public function view($id)
    {
        
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder', 'appointment', 'dns', 'payments'])->where('sales_order_id', $id)->get(),
        ];
        // dd($data);
        return view('invoice.invoices', $data);
    }

    public function downloadPdf($id)
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];
        $invoice = Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id);
        $salesOrderProducts = SalesOrderProduct::with('product', 'tempOrder')->where('sales_order_id', $invoice->sales_order_id)->where('customer_id', $invoice->customer_id)->get();

        $data = [
            'title' => 'Invoice',
            'invoice' => $invoice,
            'invoiceDetails' => InvoiceDetails::with('product.tempOrder')->where('invoice_id', $id)->get(),
            'salesOrderProducts' => $salesOrderProducts,
        ];
        // dd($data);
        $pdf = PDF::loadView('invoice/invoice-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('invoice.pdf');
    }


    public function invoiceAppointmentUpdate(Request $request, $id)
    {
        // Logic to update invoice appointment details
        $validated = Validator::make($request->all(), [
            'appointment_date' => 'required|date',
            'pod' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'grn' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($validated->fails()) {
            // If validation fails, redirect back with errors
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Handle file uploads and other logic here

        try {

            $appointment = new Appointment();
            $appointment->invoice_id = $id;
            $appointment->appointment_date = $request->input('appointment_date');

            if ($request->hasFile('pod')) {
                $pod = $request->file('pod');
                $ext = $pod->getClientOriginalExtension();
                $podName = time() . '.' . $ext;

                // Store original image
                $pod->move(public_path('uploads/pod'), $podName);
                $appointment->pod = $podName;
            }

            if ($request->hasFile('grn')) {
                $grn = $request->file('grn');
                $ext = $grn->getClientOriginalExtension();
                $grnName = time() . '.' . $ext;

                // Store original image
                $grn->move(public_path('uploads/grn'), $grnName);
                $appointment->grn = $grnName;
            }

            $appointment->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Invoice updated successfully.');
    }

    public function invoiceDnUpdate(Request $request, $id)
    {
        // Logic to update invoice DN details
        $validated = Validator::make($request->all(), [
            'dn_amount' => 'required|numeric|min:0',
            'dn_reason' => 'required|string|max:255',
            'dn_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if ($validated->fails()) {
            // If validation fails, redirect back with errors
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Handle file uploads and other logic here

        try {

            $dn = new Dn();
            $dn->invoice_id = $id;
            $dn->dn_amount = $request->input('dn_amount');
            $dn->dn_reason = $request->input('dn_reason');

            if ($request->hasFile('dn_receipt')) {
                $dnReceipt = $request->file('dn_receipt');
                $ext = $dnReceipt->getClientOriginalExtension();
                $dnReceiptName = time() . '.' . $ext;

                // Store original image
                $dnReceipt->move(public_path('uploads/dn_receipts'), $dnReceiptName);
                $dn->dn_receipt = $dnReceiptName;
            }

            $dn->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Invoice updated successfully.');
    }

    public function invoicePaymentUpdate(Request $request, $id)
    {
        // Logic to update invoice payment details
        $validated = Validator::make($request->all(), [
            'utr_no' => 'required',
            'pay_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        if ($validated->fails()) {
            // If validation fails, redirect back with errors
            return redirect()->back()->withErrors($validated)->withInput();
        }

        try {
            $payment = new Payment();
            $payment->invoice_id = $id;
            $payment->payment_utr_no = $request->input('utr_no');
            $payment->amount = $request->input('pay_amount');
            $payment->payment_method = $request->input('payment_method');
            $payment->payment_status = $request->input('payment_status');
            $payment->save();
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Invoice updated successfully.');
    }

    public function invoiceDetails($id)
    {
        $invoiceDetails = Invoice::with(['appointment', 'dns', 'payments', 'customer', 'warehouse'])->findOrFail($id);
        // dd($invoiceDetails);
        return view('invoice.invoice-details', compact('invoiceDetails'));
    }
}
