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
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
        $userWarehouseId = $user->warehouse_id;

        // Fetch all invoices with relationships
        $query = Invoice::with(['warehouse', 'customer', 'salesOrder.customerGroup', 'appointment', 'dns', 'payments'])
            ->orderBy('created_at', 'desc');

        // Filter invoices based on user role
        if (!$isSuperAdmin && !$isAdmin && $userWarehouseId) {
            // Warehouse users can only see invoices for their warehouse
            $query->where('warehouse_id', $userWarehouseId);
        }

        $invoices = $query->get();

        // Separate manual and sales order invoices
        $manualInvoices = $invoices->where('invoice_type', 'manual');
        // $salesOrderInvoices = $invoices->where('invoice_type', 'sales_order');
        $salesOrderInvoices = SalesOrder::with(['customerGroup', 'invoices'])->whereHas('invoices')->get();
        // dd($salesOrderInvoices);
        return view('invoice.index', compact('invoices', 'manualInvoices', 'salesOrderInvoices'));
    }

    public function view($id)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
        $userWarehouseId = $user->warehouse_id;

        // Fetch invoices for this sales order
        $query = Invoice::with(['warehouse', 'customer', 'salesOrder', 'appointment', 'dns', 'payments'])
            ->where('sales_order_id', $id);

        // Filter invoices based on user role
        if (!$isSuperAdmin && !$isAdmin && $userWarehouseId) {
            // Warehouse users can only see invoices for their warehouse
            $query->where('warehouse_id', $userWarehouseId);
        }

        $data = [
            'title' => 'Invoices',
            'invoices' => $query->get(),
        ];

        // dd($data);
        return view('invoice.invoices', $data);
    }

    public function downloadPdf($id)
    {
        $igstStatus = false;
        $data = [
            'title' => 'Welcome to Headquaters',
            'date' => date('m/d/Y'),
        ];
        $invoice = Invoice::with(['warehouse', 'customer', 'salesOrder'])->findOrFail($id);
        $invoiceDetails = InvoiceDetails::with('product', 'tempOrder', 'salesOrderProduct')->where('invoice_id', $id)->get();

        // Check if it's a sales order invoice or manual invoice
        if ($invoice->sales_order_id) {
            // Sales Order Invoice
            $salesOrderProducts = SalesOrderProduct::with('product', 'tempOrder')
                ->where('sales_order_id', $invoice->sales_order_id)
                ->where('customer_id', $invoice->customer_id)
                ->get();

            $totalWeight = $salesOrderProducts->sum('weight');
            $totalBoxCount = $salesOrderProducts->sum('box_count');
        } else {
            // Manual Invoice - get totals from invoice_details
            $salesOrderProducts = collect();
            $totalWeight = $invoiceDetails->sum('weight');
            $totalBoxCount = $invoiceDetails->sum('box_count');
        }

        // if($invoice->warehouse->state && $invoice->customer->shipping_state) {
        //     $warehouseState = $invoice->warehouse->state->name;
        //     $customerState = $invoice->customer->shipping_state;
        //     // dd($warehouseState, $customerState);
        //     if($warehouseState == $customerState) {
        //         $igstStatus = true;
        //     } else {
        //         $igstStatus = false;
        //     }
        // }

        $data = [
            'title' => 'Invoice',
            'invoice' => $invoice,
            'invoiceDetails' => $invoiceDetails,
            'salesOrderProducts' => $salesOrderProducts,
            'TotalWeight' => $totalWeight,
            'TotalBoxCount' => $totalBoxCount,
            'igstStatus' => $igstStatus,
            'invoiceItemType' => $invoice->invoice_item_type ?? 'product',
        ];
        // dd($data);

        $pdf = \PDF::loadView('invoice/invoice-pdf', $data);
        $pdf->setPaper('a4');

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
                $podName = time() . '_pod.' . $ext;

                // Store original image
                $pod->move(public_path('uploads/pod'), $podName);
                $appointment->pod = $podName;
            }

            if ($request->hasFile('grn')) {
                $grn = $request->file('grn');
                $ext = $grn->getClientOriginalExtension();
                $grnName = time() . '_grn.' . $ext;

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
        $validator = Validator::make($request->all(), [
            'utr_no' => 'required|unique:payments,payment_utr_no',
            'pay_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $invoice = Invoice::with('payments')->findOrFail($id);

            // Calculate current paid amount and due amount
            $currentPaidAmount = $invoice->payments->sum('amount');
            $currentDueAmount = $invoice->total_amount - $currentPaidAmount;

            // Validate payment amount
            if ($currentDueAmount <= 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Invoice is already fully paid.')->withInput();
            }

            if ($request->input('pay_amount') > $currentDueAmount) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Payment amount (₹' . number_format($request->input('pay_amount'), 2) . ') is greater than due amount (₹' . number_format($currentDueAmount, 2) . ').')->withInput();
            }

            // Create payment record
            $payment = new Payment;
            $payment->invoice_id = $id;
            $payment->payment_utr_no = $request->input('utr_no');
            $payment->amount = $request->input('pay_amount');
            $payment->payment_method = $request->input('payment_method');
            $payment->payment_status = $request->input('payment_status');
            $payment->save();

            // Update invoice paid_amount and balance_due
            $newPaidAmount = $currentPaidAmount + $request->input('pay_amount');
            $newBalanceDue = $invoice->total_amount - $newPaidAmount;

            // Determine payment status
            if ($newBalanceDue <= 0) {
                $invoicePaymentStatus = 'paid';
            } elseif ($newPaidAmount > 0) {
                $invoicePaymentStatus = 'partial';
            } else {
                $invoicePaymentStatus = 'unpaid';
            }

            // Update invoice
            $invoice->paid_amount = $newPaidAmount;
            $invoice->balance_due = $newBalanceDue;
            $invoice->payment_status = $invoicePaymentStatus;
            $invoice->save();

            DB::commit();
            activity()->performedOn($invoice)->causedBy(Auth::user())->log('Payment added: ₹' . number_format($request->input('pay_amount'), 2));

            return redirect()->back()->with('success', 'Payment added successfully. Paid: ₹' . number_format($newPaidAmount, 2) . ', Due: ₹' . number_format($newBalanceDue, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice Payment Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
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
        $customerGroups = \App\Models\CustomerGroup::active()->get();

        return view('invoice.create-manual-invoice', compact('customers', 'warehouses', 'products', 'customerGroups'));
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
            'invoice_date' => 'required|date',
            'po_number' => 'nullable|string|max:255|unique:invoices,po_number',
            'po_date' => 'nullable|date',
            'invoice_item_type' => 'required|in:product,service',
            'products' => 'required_if:invoice_item_type,product|array',
            'products.*.warehouse_id' => 'required_with:products|exists:warehouses,id',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.hsn' => 'nullable|string|max:255',
            'products.*.quantity' => 'required_with:products|numeric',
            'products.*.box_count' => 'nullable|integer|min:0',
            'products.*.weight' => 'nullable|numeric|min:0',
            'products.*.unit_price' => 'required_with:products|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.tax' => 'nullable|numeric|min:0',
            'services' => 'required_if:invoice_item_type,service|array',
            'services.*.service_title' => 'nullable|string|max:255',
            'services.*.service_category' => 'nullable|string|max:255',
            'services.*.service_description' => 'nullable|string',
            'services.*.campaign_name' => 'nullable|string|max:255',
            'services.*.quantity' => 'required_with:services|integer|min:1',
            'services.*.unit_type' => 'nullable|string|max:255',
            'services.*.box_count' => 'nullable|integer|min:0',
            'services.*.weight' => 'nullable|numeric|min:0',
            'services.*.unit_price' => 'required_with:services|numeric|min:0',
            'services.*.discount' => 'nullable|numeric|min:0',
            'services.*.tax' => 'nullable|numeric|min:0',
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

            $timestamp = time();

            if ($lastInvoice) {
                $lastNumber = (int) substr($lastInvoice->invoice_number, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            // $invoiceNumber = "INV-{$yearMonth}-{$newNumber}";
            $invoiceNumber = 'INV-' . $timestamp . '-' . str_pad($newNumber + 1, 4, '0', STR_PAD_LEFT);

            // Calculate totals based on invoice type
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            if ($request->invoice_item_type === 'product') {
                foreach ($request->products as $item) {
                    $amount = $item['quantity'] * $item['unit_price'];
                    $discount = $item['discount'] ?? 0;
                    $tax = $item['tax'] ?? 0;

                    $subtotal += $amount;
                    $totalDiscount += $discount;
                    $totalTax += $tax;
                }
            } else {
                // Service invoice - calculation with discount and tax
                foreach ($request->services as $item) {
                    $amount = $item['quantity'] * $item['unit_price'];
                    $discount = $item['discount'] ?? 0;
                    $tax = $item['tax'] ?? 0;

                    $subtotal += $amount;
                    $totalDiscount += $discount;
                    $totalTax += $tax;
                }
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

            // Create invoice with warehouse_id = 0 (meaning "All Warehouses")
            // Individual warehouse info is stored in invoice_details table
            $invoice = Invoice::create([
                'warehouse_id' => 0,
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer_id,
                'sales_order_id' => null,
                'invoice_date' => $request->invoice_date,
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
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
                'invoice_item_type' => $request->invoice_item_type,
                'notes' => $request->notes,
            ]);

            // Create invoice details based on type
            if ($request->invoice_item_type === 'product') {
                // Create product invoice details and update stock
                foreach ($request->products as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $amount = $item['quantity'] * $item['unit_price'];
                    $discount = $item['discount'] ?? 0;
                    $tax = $item['tax'] ?? 0;
                    $totalPrice = $amount - $discount + $tax;

                    InvoiceDetails::create([
                        'invoice_id' => $invoice->id,
                        'warehouse_id' => $item['warehouse_id'],
                        'product_id' => $item['product_id'],
                        'hsn' => $item['hsn'] ?? null,
                        'quantity' => $item['quantity'],
                        'box_count' => $item['box_count'] ?? null,
                        'weight' => $item['weight'] ?? null,
                        'unit_price' => $item['unit_price'],
                        'discount' => $discount,
                        'amount' => $amount,
                        'tax' => $tax,
                        'total_price' => $totalPrice,
                        'description' => $item['description'] ?? null,
                    ]);

                    // Update warehouse stock
                    $stock = WarehouseStock::where('warehouse_id', $item['warehouse_id'])
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
                        $stock->original_quantity -= $item['quantity'];
                        $stock->save();
                    }
                }
            } else {
                // Create service invoice details (no stock management)
                foreach ($request->services as $item) {
                    $amount = $item['quantity'] * $item['unit_price'];
                    $discount = $item['discount'] ?? 0;
                    $tax = $item['tax'] ?? 0;
                    $totalPrice = $amount - $discount + $tax;

                    InvoiceDetails::create([
                        'invoice_id' => $invoice->id,
                        'warehouse_id' => null,
                        'product_id' => null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'amount' => $amount,
                        'total_price' => $totalPrice,
                        'discount' => $discount,
                        'tax' => $tax,
                        'service_title' => $item['service_title'] ?? null,
                        'service_category' => $item['service_category'] ?? null,
                        'service_description' => $item['service_description'] ?? null,
                        'campaign_name' => $item['campaign_name'] ?? null,
                        'unit_type' => $item['unit_type'] ?? null,
                        'box_count' => $item['box_count'] ?? null,
                        'weight' => $item['weight'] ?? null,
                    ]);
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

    // Check PO Number 
    // if po number is already exists then return cannot use this po number 
    // if po number is not exists then return you can use this po number 
    public function checkPoNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $poNumber = $request->po_number;
            $invoice = Invoice::where('po_number', $poNumber)->first();

            if ($invoice) {
                return response()->json(['success' => false, 'message' => 'PO Number already exists']);
            } else {
                return response()->json(['success' => true, 'message' => 'PO Number is available']);
            }
        } catch (\Exception $e) {
            Log::error('Check PO Number Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to check PO Number'], 500);
        }
    }
}
