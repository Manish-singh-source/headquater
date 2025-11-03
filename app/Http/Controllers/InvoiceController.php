<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Dn;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        // Fetch all invoices with relationships
        $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder', 'appointment', 'dns', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Separate manual and sales order invoices
        $manualInvoices = $invoices->where('invoice_type', 'manual');
        $salesOrderInvoices = $invoices->where('invoice_type', 'sales_order');

        return view('invoice.index', compact('invoices', 'manualInvoices', 'salesOrderInvoices'));
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
            'title' => 'Welcome to Headquaters',
            'date' => date('m/d/Y'),
        ];
        $invoice = Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id);
        $salesOrderProducts = SalesOrderProduct::with('product', 'tempOrder')->where('sales_order_id', $invoice->sales_order_id)->where('customer_id', $invoice->customer_id)->get();

        $data = [
            'title' => 'Invoice',
            'invoice' => $invoice,
            'invoiceDetails' => InvoiceDetails::with('product', 'tempOrder', 'salesOrderProduct')->where('invoice_id', $id)->get(),
            'salesOrderProducts' => $salesOrderProducts,
            'TotalWeight' => $salesOrderProducts->sum('weight'),
            'TotalBoxCount' => $salesOrderProducts->sum('box_count'),
        ];

        $pdf = \PDF::loadView('invoice/invoice-pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('invoice.pdf');
    }

    public function invoiceAppointmentUpdate(Request $request, $id)
    {
        // Logic to update invoice appointment details

        $validated = Validator::make($request->all(), [
            'appointment_date' => 'nullable|date',
            'pod' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'grn' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        if (
            ! $request->filled('appointment_date') &&
            ! $request->hasFile('pod') &&
            ! $request->hasFile('grn')
        ) {
            return redirect()->back()->with('error', 'Please provide at least one field to update.')->withInput();
        }

        if ($validated->fails()) {
            return redirect()->back()->with('error', $validated->errors()->first())->withInput();
        }

        // Handle file uploads and other logic here

        try {
            // Ensure $id is scalar to avoid Illegal offset type error
            if (! is_scalar($id)) {
                return redirect()->back()->with('error', 'Invalid invoice ID.');
            }
            $appointment = Appointment::firstOrNew(['invoice_id' => (int) $id]);

            if ($request->filled('appointment_date')) {
                $appointment->appointment_date = $request->input('appointment_date');
            }

            if ($request->hasFile('pod')) {
                $pod = $request->file('pod');
                $ext = $pod->getClientOriginalExtension();
                $podName = time().'_pod.'.$ext;

                // Store original image
                $pod->move(public_path('uploads/pod'), $podName);
                $appointment->pod = $podName;
            }

            if ($request->hasFile('grn')) {
                $grn = $request->file('grn');
                $ext = $grn->getClientOriginalExtension();
                $grnName = time().'_grn.'.$ext;

                // Store original image
                $grn->move(public_path('uploads/grn'), $grnName);
                $appointment->grn = $grnName;
            }

            $appointment->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update invoice: '.$e->getMessage());
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
            return redirect()->back()->with('error', $validated->errors()->first())->withInput();
        }

        // Handle file uploads and other logic here

        try {

            $dn = new Dn;
            $dn->invoice_id = $id;
            $dn->dn_amount = $request->input('dn_amount');
            $dn->dn_reason = $request->input('dn_reason');

            if ($request->hasFile('dn_receipt')) {
                $dnReceipt = $request->file('dn_receipt');
                $ext = $dnReceipt->getClientOriginalExtension();
                $dnReceiptName = time().'.'.$ext;

                // Store original image
                $dnReceipt->move(public_path('uploads/dn_receipts'), $dnReceiptName);
                $dn->dn_receipt = $dnReceiptName;
            }

            $dn->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update invoice: '.$e->getMessage());
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
            return redirect()->back()->with($validated)->withInput();
        }

        try {
            $payment = new Payment;
            $payment->invoice_id = $id;
            $payment->payment_utr_no = $request->input('utr_no');
            $payment->amount = $request->input('pay_amount');
            $payment->payment_method = $request->input('payment_method');
            $payment->payment_status = $request->input('payment_status');
            $payment->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update invoice: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Invoice updated successfully.');
    }

    public function invoiceDetails($id)
    {
        $invoiceDetails = Invoice::with(['appointment', 'dns', 'payments', 'customer', 'warehouse'])->findOrFail($id);

        return view('invoice.invoice-details', compact('invoiceDetails'));
    }

    // Manual Invoice Methods
    public function createManualInvoice()
    {
        $customers = Customer::active()->get();
        $warehouses = Warehouse::where('status', '1')->get();
        $products = Product::all();

        return view('invoice.create-manual-invoice', compact('customers', 'warehouses', 'products'));
    }

    public function getProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $search = $request->input('search', '');
            $products = Product::where('product_name', 'LIKE', "%{$search}%")
                ->orWhere('sku', 'LIKE', "%{$search}%")
                ->limit(20)
                ->get(['id', 'product_name', 'sku', 'mrp', 'hsn_code']);

            return response()->json(['success' => true, 'products' => $products]);
        } catch (\Exception $e) {
            Log::error('Get Products Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch products'], 500);
        }
    }

    public function checkStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $product = Product::findOrFail($request->product_id);
            $stock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
                ->where('sku', $product->sku)
                ->first();

            $availableQty = $stock ? $stock->available_quantity : 0;

            return response()->json([
                'success' => true,
                'available_quantity' => $availableQty,
                'product' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Check Stock Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to check stock'], 500);
        }
    }

    public function storeManualInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'invoice_date' => 'required|date',
            'po_number' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.tax' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|string|max:255',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Generate invoice number
            $yearMonth = date('Ym');
            $lastInvoice = Invoice::where('invoice_number', 'LIKE', "INV-{$yearMonth}-%")
                ->orderBy('id', 'desc')
                ->first();

            if ($lastInvoice) {
                $lastNumber = (int) substr($lastInvoice->invoice_number, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $invoiceNumber = "INV-{$yearMonth}-{$newNumber}";

            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($request->products as $item) {
                $amount = $item['quantity'] * $item['unit_price'];
                $discount = $item['discount'] ?? 0;
                $tax = $item['tax'] ?? 0;

                $subtotal += $amount;
                $totalDiscount += $discount;
                $totalTax += $tax;
            }

            $totalAmount = $subtotal - $totalDiscount + $totalTax;
            $paidAmount = $request->paid_amount ?? 0;
            $balanceDue = $totalAmount - $paidAmount;

            // Determine payment status
            if ($paidAmount >= $totalAmount) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            } else {
                $paymentStatus = 'unpaid';
            }

            // Create invoice
            $invoice = Invoice::create([
                'warehouse_id' => $request->warehouse_id,
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer_id,
                'sales_order_id' => null,
                'invoice_date' => $request->invoice_date,
                'po_number' => $request->po_number,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'round_off' => 0,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance_due' => $balanceDue,
                'payment_mode' => $request->payment_mode,
                'payment_status' => $paymentStatus,
                'invoice_type' => 'manual',
                'notes' => $request->notes,
            ]);

            // Create invoice details and update stock
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $amount = $item['quantity'] * $item['unit_price'];
                $discount = $item['discount'] ?? 0;
                $tax = $item['tax'] ?? 0;
                $totalPrice = $amount - $discount + $tax;

                InvoiceDetails::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $discount,
                    'amount' => $amount,
                    'tax' => $tax,
                    'total_price' => $totalPrice,
                    'description' => $item['description'] ?? null,
                ]);

                // Update warehouse stock
                $stock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
                    ->where('sku', $product->sku)
                    ->first();

                if ($stock) {
                    if ($stock->available_quantity < $item['quantity']) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', "Insufficient stock for product: {$product->product_name}. Available: {$stock->available_quantity}")
                            ->withInput();
                    }

                    $stock->available_quantity -= $item['quantity'];
                    $stock->save();
                }
            }

            // Create payment record if paid amount > 0
            if ($paidAmount > 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $paidAmount,
                    'payment_method' => $request->payment_mode,
                    'payment_status' => 'completed',
                    'payment_utr_no' => null,
                ]);
            }

            DB::commit();
            activity()->performedOn($invoice)->causedBy(Auth::user())->log('Manual invoice created');

            return redirect()->route('invoices-details', $invoice->id)->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manual Invoice Creation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
}
