<?php

namespace App\Http\Controllers;

use App\Models\NotFoundTempOrder;
use App\Models\Product;
use App\Models\VendorPI;
use App\Models\TempOrder;
use App\Models\SalesOrder;
use App\Models\SkuMapping;
use App\Models\PurchaseGrn;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\WarehouseStock;
use App\Models\PurchaseInvoice;
use App\Models\VendorPIProduct;
use App\Models\SalesOrderProduct;
use App\Models\WarehouseStockLog;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use App\Models\VendorPayment;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Services\NotificationService;

class PurchaseOrderController extends Controller
{
    //
    public function customPurchaseCreate($purchaseId = null)
    {
        if ($purchaseId) {
            return view('purchaseOrder.create', compact('purchaseId'));
        }
        return view('purchaseOrder.create');
    }

    public function customPurchaseStore(Request $request)
    {
        $request->validate([
            'purchase_excel' => 'required|mimes:xlsx,csv,xls',
        ]);

        $file = $request->file('purchase_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $vendorProducts = [];
            $insertCount = 0;

            if (!isset($request->purchaseId)) {
                $purchaseOrder = new PurchaseOrder();
                $purchaseOrder->save();
            }

            foreach ($rows as $record) {
                if (empty($record['SKU Code'])) continue;

                $purchaseOrderProduct = new PurchaseOrderProduct();
                if (isset($purchaseOrder->id)) {
                    $purchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
                } else {
                    $purchaseOrderProduct->purchase_order_id = $request->purchaseId;
                }
                $purchaseOrderProduct->ordered_quantity = $record['Purchase Order Quantity'];
                $purchaseOrderProduct->sku = $record['SKU Code'];
                $purchaseOrderProduct->vendor_code = $record['Vendor Code'];
                $purchaseOrderProduct->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['purchase_excel' => 'No valid data found in the CSV file.']);
            }

            DB::commit();

            // Create notification
            NotificationService::orderCreated('purchase', $purchaseOrder->id);

            return redirect()->route('purchase.order.index')->with('success', 'Purchase Order created successfully! Order ID: ' . $purchaseOrder->id);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')
            ->withSum('purchaseOrderProducts', 'ordered_quantity')
            ->withCount('purchaseOrderProducts')->get();
        return view('purchaseOrder.index', compact('purchaseOrders'));
    }

    // Storing Vendor PI Products from CSV
    public function store(Request $request)
    {
        $request->validate([
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'sales_order_id' => 'required|exists:sales_orders,id',
            'vendor_code' => 'required|string|max:255',
        ]);

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $vendorProducts = [];
            $insertCount = 0;

            // update fulfillment quantity in temp order 
            $vendorPi = VendorPI::create([
                'purchase_order_id' => $request->purchase_order_id,
                'vendor_code' => $request->vendor_code,
                'sales_order_id' => $request->sales_order_id,
            ]);

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) continue;

                // check if vendor code of request and excel file is same 
                if ($request->vendor_code != $record['Vendor Code']) {
                    throw new \Exception('Vendor Code is not same. Please check the file.');
                }

                // map sku with product 
                $sku = SkuMapping::where('vendor_sku', Arr::get($record, 'Vendor SKU Code'))->first();

                if ($sku) {
                    $newSku = $sku->product_sku;
                } else {
                    $newSku = Arr::get($record, 'Vendor SKU Code');
                }

                $salesOrderFulfillment[$newSku] = [
                    'quantity' => $record['PI Quantity']
                ];

                // Get Temp Order ID
                $salesOrderProduct = SalesOrderProduct::where('sales_order_id', $request->sales_order_id)
                    ->where('sku', $newSku)
                    ->get();


                foreach ($salesOrderProduct as $item) {
                    $tempProduct = TempOrder::where('id', $item->temp_order_id)->first();

                    if ($tempProduct->po_qty >= $salesOrderFulfillment[$newSku]['quantity']) {
                        if ($tempProduct->po_qty > $tempProduct->available_quantity) {
                            $tempProduct->vendor_pi_fulfillment_quantity = $salesOrderFulfillment[$newSku]['quantity'];
                            $tempProduct->vendor_pi_id = $vendorPi->id;
                            $salesOrderFulfillment[$newSku]['quantity'] = 0;
                        }
                    } else {
                        if ($tempProduct->po_qty > $tempProduct->available_quantity) {
                            $tempProduct->vendor_pi_fulfillment_quantity = $tempProduct->po_qty;
                            $tempProduct->vendor_pi_id = $vendorPi->id;
                            $salesOrderFulfillment[$newSku]['quantity'] = $salesOrderFulfillment[$newSku]['quantity'] - $tempProduct->po_qty;
                        }
                    }
                    $tempProduct->save();

                    $salesOrderFulfillment[] = $item->temp_order_id;
                }


                $vendorProducts[] = [
                    'purchase_order_id' => $request->purchase_order_id,
                    'vendor_pi_id' => $vendorPi->id,
                    'vendor_sku_code' => $newSku ?? Arr::get($record, 'Vendor SKU Code'),
                    'title' => Arr::get($record, 'Title'),
                    'mrp' => Arr::get($record, 'MRP') ?? 0,
                    'quantity_requirement' => Arr::get($record, 'PO Quantity') ?? 0,
                    'available_quantity' => Arr::get($record, 'PI Quantity') ?? 0,
                    'purchase_rate' => Arr::get($record, 'Purchase Rate Basic') ?? 0,
                    'gst' => Arr::get($record, 'GST') ?? 0,
                    'hsn' => Arr::get($record, 'HSN') ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['pi_excel' => 'No valid data found in the CSV file.']);
            }

            VendorPIProduct::insert($vendorProducts);
            DB::commit();
            return redirect()->back()->with('success', 'Purchase Order products imported successfully! Vendor PI ID: ' . $vendorPi->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function view($id)
    {
        $purchaseOrder = PurchaseOrder::with('vendor', 'purchaseOrderProducts.tempOrder', 'vendorPI.products.purchaseOrder.purchaseOrderProducts.tempOrder', 'vendorPI.products.product',  'vendorPI.products.tempOrder')
            ->withCount('purchaseOrderProducts')
            ->findOrFail($id);

        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $id)->with('purchaseOrder', 'tempOrder')->get();

        $facilityNames = VendorPI::with('product')->where('purchase_order_id', $id)
            ->where('status', '!=', 'completed')
            ->get()
            ->pluck('vendor_code')
            ->filter()
            ->unique()
            ->values();

        $uploadedPIOfVendors = VendorPI::distinct()->pluck('vendor_code');
        $purchaseInvoice = PurchaseInvoice::where('purchase_order_id', $id)->get();
        $purchaseGrn = PurchaseGrn::where('purchase_order_id', $id)->get();
        $vendorPIs = VendorPI::with('products.product')->where('purchase_order_id', $id)->where('status', '!=', 'completed')->get();

        return view('purchaseOrder.view', compact('purchaseOrder', 'facilityNames', 'purchaseOrderProducts', 'uploadedPIOfVendors',  'vendorPIs', 'purchaseInvoice', 'purchaseGrn'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'purchase_order_id' => 'required',
            'vendor_code' => 'required',
        ]);

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            // $products = [];
            $insertCount = 0;

            $vendorPIid = VendorPI::where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) {
                    continue;
                }

                // check if vendor code of request and excel file is same  
                if ($request->vendor_code != $record['Vendor Code']) {
                    throw new \Exception('Vendor Code is not same. Please check the file.');
                }

                $productData = VendorPIProduct::where('vendor_sku_code', Arr::get($record, 'Vendor SKU Code'))->where('vendor_pi_id', $vendorPIid->id)->first();
                if (Arr::get($record, 'MRP')) {
                    $productData->mrp = Arr::get($record, 'MRP');
                }
                if (Arr::get($record, 'Quantity Ordered')) {
                    $productData->quantity_requirement = Arr::get($record, 'Quantity Ordered');
                }
                // $productData->purchase_rate = Arr::get($record, 'Purchase Rate Basic');
                if (Arr::get($record, 'Issue Units')) {
                    $productData->quantity_received = Arr::get($record, 'Quantity Received');
                }
                // $productData->gst = Arr::get($record, 'GST');
                // $productData->hsn = Arr::get($record, 'HSN');

                if ($issueItem = Arr::get($record, 'Issue Units')) {
                    $productData->issue_item = $issueItem ?? '';
                    $productData->issue_reason = Arr::get($record, 'Issue Reason') ?? '';
                } else {
                    $productData->issue_item = 0;
                    $productData->issue_reason = '';
                }
                $productData->save();

                $insertCount++;
            }


            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['pi_excel' => 'No valid data found in the CSV file.']);
            }

            // VendorPIProduct::upsert($products, ['vendor_sku_code', 'vendor_pi_id']);

            DB::commit();
            return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase.order.index')->with('success', 'Purchase Order deleted successfully.');
    }

    public function multiDelete(Request $request)
    {
        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            PurchaseOrder::destroy($ids);
            return redirect()->back()->with('success', 'Purchase Orders deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function SingleProductdelete($id)
    {
        $purchaseOrderProduct = PurchaseOrderProduct::findOrFail($id);
        $purchaseOrderProduct->delete();

        return redirect()->back()->with('success', 'Purchase Order deleted successfully.');
    }

    public function multiProductdelete(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        PurchaseOrderProduct::destroy($ids);
        return redirect()->back()->with('success', 'Purchase Order deleted successfully.');
    }


    public function approveRequest(Request $request)
    {
        DB::beginTransaction();

        try {

            $total_amount = 0;

            $vendorPI = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            $vendorPI->status = 'completed';
            $vendorPI->approve_or_reject_reason = $request->approve_or_reject_reason ?? null;

            $purchaseOrder = PurchaseOrder::where('id', $request->purchase_order_id)->first();
            $purchaseOrder->status = 'received';
            $purchaseOrder->save();

            // find products 
            $vendorPIProducts = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

            foreach ($vendorPIProducts->products as $product) {
                $total_amount += $product->mrp * $product->quantity_received;
                $updateStock = WarehouseStock::where('sku', $product->vendor_sku_code)->first();
                if (isset($updateStock)) {
                    // logic for updating warehouse stock and block quantity 
                    $updateStock->block_quantity = $updateStock->block_quantity + $product->quantity_received;
                    $updateStock->original_quantity = $updateStock->original_quantity + $product->quantity_received;
                    $updateStock->save();
                } else {
                    $storeStock = WarehouseStock::create([
                        'warehouse_id' => '0',
                        'product_id' => '0',
                        'sku' => $product->vendor_sku_code,
                        'original_quantity' => '0',
                        'available_quantity' => '0',
                        'block_quantity' => '0',
                    ]);
                }

                // need of foreach ?.....
                // update temp order vendor_pi_received_quantity
                $receivedQuantity = $product->quantity_received ?? 0;

                $tempOrderProducts = TempOrder::where('vendor_pi_id', $product->vendor_pi_id)->where('sku', $product->vendor_sku_code)->get();
                foreach ($tempOrderProducts as $tempOrderproduct) {
                    if ($tempOrderproduct->unavailable_quantity <= $receivedQuantity) {
                        $tempOrderproduct->available_quantity += $tempOrderproduct->unavailable_quantity;
                        $tempOrderproduct->block += $tempOrderproduct->unavailable_quantity; 
                        $tempOrderproduct->vendor_pi_received_quantity = $tempOrderproduct->unavailable_quantity; 
                        $tempOrderproduct->unavailable_quantity = 0; 
                        $receivedQuantity -= $tempOrderproduct->unavailable_quantity;
                    } else {
                        $required = $tempOrderproduct->unavailable_quantity - $receivedQuantity;
                        $tempOrderproduct->available_quantity += $required;
                        $tempOrderproduct->block += $required;
                        $tempOrderproduct->vendor_pi_received_quantity = $required;
                        $tempOrderproduct->unavailable_quantity -= $required;
                        $receivedQuantity = 0;
                    }
                    // $requiredQuantity = $receivedQuantity - $tempOrderproduct->unavailable_quantity;
                    $tempOrderproduct->save();
                }
            }

            $vendorPI->total_amount = $total_amount;
            $vendorPI->total_due_amount = $total_amount;
            $vendorPI->save();

            DB::commit();
            return redirect()->back()->with('success', 'Successfully Approved Received Products');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function rejectRequest(Request $request)
    {
        DB::beginTransaction();

        try {

            $vendorPI = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            $vendorPI->status = 'rejected';
            $vendorPI->approve_or_reject_reason = $request->approve_or_reject_reason ?? null;
            $vendorPI->save();

            // update warehouse stock 
            // find products 
            // $vendorPIProducts = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

            // foreach ($vendorPIProducts->products as $product) {
            //     // update temp order vendor_pi_fulfillment_quantity
            //     $salesOrderProduct = SalesOrderProduct::where('sales_order_id', $vendorPI->sales_order_id)
            //         ->where('sku', $product->vendor_sku_code)
            //         ->get();

            //     foreach ($salesOrderProduct as $item) {
            //         $tempProduct = TempOrder::where('id', $item->temp_order_id)->first();
            //         if ($tempProduct) {
            //             $tempProduct->vendor_pi_fulfillment_quantity = 0;
            //             $tempProduct->save();
            //         }
            //     }
            // }

            DB::commit();
            return redirect()->back()->with('success', 'Successfully Rejected Received Products');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function invoiceStore(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchase_order_id' => 'required',
            'vendor_code' => 'required',
            'invoice_file' => 'required|mimes:pdf',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withInput()->withErrors($validated)->with('error', $validated->failed());
        }

        // if($purchaseOrderInvoice?->invoice_file != null) {
        //     if(File::exists(public_path('uploads/invoices/' . $purchaseOrderInvoice->invoice_file))) {
        //         File::delete(public_path('uploads/invoices/' . $purchaseOrderInvoice->invoice_file));
        //     }
        // }

        $vendorPIStatus = VendorPI::where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

        if (!isset($vendorPIStatus)) {
            return redirect()->back()->with('error', 'Vendor PI Is Not Uploaded');
        }

        $invoice_file = $request->file('invoice_file');
        $ext = $invoice_file->getClientOriginalExtension();
        $invoiceFileName = strtotime('now') . '-' . $request->purchase_order_id . '.' . $ext;
        $invoice_file->move(public_path('uploads/invoices'), $invoiceFileName);

        $purchaseInvoice = new PurchaseInvoice();
        $purchaseInvoice->purchase_order_id = $request->purchase_order_id;
        $purchaseInvoice->vendor_code = $request->vendor_code;
        $purchaseInvoice->invoice_file = $invoiceFileName;
        $purchaseInvoice->save();

        if (!$purchaseInvoice) {
            return back()->with('error', 'Something went wrong');
        }
        return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'Invoice imported successfully.');
    }

    public function grnStore(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchase_order_id' => 'required',
            'vendor_code' => 'required',
            'grn_file' => 'required|mimes:pdf',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withInput()->withErrors($validated);
        }

        $grn_file = $request->file('grn_file');
        $ext = $grn_file->getClientOriginalExtension();
        $grnFileName = strtotime('now') . '-' . $request->purchase_order_id . '.' . $ext;
        $grn_file->move(public_path('uploads/invoices'), $grnFileName);

        $purchaseGRN = new PurchaseGrn();
        $purchaseGRN->purchase_order_id = $request->purchase_order_id;
        $purchaseGRN->vendor_code = $request->vendor_code;
        $purchaseGRN->grn_file = $grnFileName;
        $purchaseGRN->save();

        return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'GRN imported successfully.');
    }


    public function downloadVendorPO(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchaseOrderId' => 'required',
        ]);

        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/blocked_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $request->purchaseOrderId)
            ->where('vendor_code', $request->vendorCode)
            ->with('tempOrderThrough')->get();

        // dd($purchaseOrderProducts);
        // Add rows
        foreach ($purchaseOrderProducts as $order) {
            if ($order->ordered_quantity > 0) {
                $writer->addRow([
                    'Sales Order No' => $order->sales_order_id ?? '',
                    'Purchase Order No' => $order->purchase_order_id ?? '',
                    'Vendor Code'            => $order->vendor_code ?? '',
                    'Portal Code'            => $order->tempOrderThrough->item_code ?? '',
                    'Vendor SKU Code'   => $order->tempOrderThrough->sku ?? '',
                    'Title'             => $order->tempOrderThrough->description ?? '',
                    'MRP'               => $order->tempOrderThrough->mrp ?? '',
                    'GST' => $order->tempOrderThrough->gst ?? '',
                    'HSN' => $order->tempOrderThrough->hsn ?? '',
                    'PO Quantity' => $order->ordered_quantity ?? '',
                    'PI Quantity' => '',
                    'Purchase Rate Basic' => '',
                ]);
            }
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, $request->vendorCode . '_Vendor_PO.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // Return or Accept vendor exceeded products 
    public function  vendorProductReturn($id)
    {
        $vendorPIProduct = VendorPIProduct::findOrFail($id);
        $vendorPIProduct->issue_status = 'return';
        $vendorPIProduct->save();

        if ($vendorPIProduct) {
            return back()->with('success', 'Products are returned');
        }
        return back()->with('error', 'Something went wrong.');
    }

    // Return or Accept vendor exceeded products 
    public function  vendorProductAccept($id)
    {
        $vendorPIProduct = VendorPIProduct::findOrFail($id);
        $vendorPIProduct->issue_status = 'accept';


        $product = WarehouseStock::where('sku', $vendorPIProduct->vendor_sku_code)->first();
        if ($product) {
            $product->original_quantity += $vendorPIProduct->issue_item;
            $product->available_quantity += $vendorPIProduct->issue_item;
            $product->save();
        }
        $vendorPIProduct->save();
        if ($vendorPIProduct) {
            return back()->with('success', 'Products are accepted');
        }

        return back()->with('error', 'Something went wrong.');
    }


    public function  changeStatus(Request $request)
    {
        try {

            $purchaseOrder = PurchaseOrder::findOrFail($request->order_id);
            $oldStatus = $purchaseOrder->status;
            $purchaseOrder->status = $request->status;
            $purchaseOrder->save();

            if (!$purchaseOrder) {
                return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
            }

            // Create status change notification
            NotificationService::statusChanged('purchase', $purchaseOrder->id, $oldStatus, $purchaseOrder->status);

            return redirect()->back()->with('success', 'Purchase Order status changed to "' . ucfirst(str_replace('_', ' ', $request->status)) . '" successfully! Order ID: ' . $purchaseOrder->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
        }
    }

    public function vendorInvoicePaymentStore(Request $request)
    {
        // Logic to add invoice payment details
        $validated = Validator::make($request->all(), [
            'vendor_pi_id' => 'required',
            'utr_no' => 'required',
            'pay_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        if ($validated->fails()) {
            // If validation fails, redirect back with errors
            return back()->withErrors($validated)->withInput();
        }

        if ($request->input('pay_amount') == 0) {
            return back()->with('error', 'Payment amount is required.');
        }

        DB::beginTransaction();
        try {
            $payment = new VendorPayment();
            $payment->vendor_pi_id = $request->vendor_pi_id;
            $payment->payment_utr_no = $request->input('utr_no');
            $payment->amount = $request->input('pay_amount');
            $payment->payment_method = $request->input('payment_method');

            $vendorPI = VendorPI::findOrFail($request->vendor_pi_id);
            if ($vendorPI->total_due_amount == 0) {
                DB::rollBack();
                return back()->with('error', 'Payment amount is already paid.');
            }

            if ($vendorPI->total_due_amount < $request->input('pay_amount')) {
                DB::rollBack();
                return back()->with('error', 'Payment amount is greater than due amount.');
                dd($vendorPI->total_due_amount, $request->input('pay_amount'));
            }

            if ($vendorPI->total_due_amount > 0 && $vendorPI->total_due_amount <= $vendorPI->total_amount) {
                $vendorPI->total_due_amount -= $request->input('pay_amount');
                $vendorPI->total_paid_amount += $request->input('pay_amount');
                if ($vendorPI->total_due_amount == 0) {
                    $vendorPI->payment_status = 'paid';
                } else {
                    $vendorPI->payment_status = 'partial_paid';
                }
                $vendorPI->save();
            }

            if ($vendorPI->total_due_amount == $request->input('pay_amount')) {
                $payment->payment_status = 'completed';
            } else {
                $payment->payment_status = 'partial';
            }
            $payment->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add payment: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment added successfully.');
    }
}
