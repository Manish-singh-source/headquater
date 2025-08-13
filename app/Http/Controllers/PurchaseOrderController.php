<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\VendorPI;
use App\Models\TempOrder;
use App\Models\PurchaseGrn;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\WarehouseStock;
use App\Models\PurchaseInvoice;
use App\Models\VendorPIProduct;
use App\Models\WarehouseStockLog;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PurchaseOrderController extends Controller
{
    //
    public function purchesstore()
    {
        return view('purchaseOrder.create');
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')->get();

        $vendorCodes = $purchaseOrders->flatMap(function ($po) {
            return $po->purchaseOrderProducts->pluck('vendor_code');
        })->unique()->values();
        return view('purchaseOrder.index', compact('purchaseOrders', 'vendorCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
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

            $vendorPi = VendorPI::create([
                'purchase_order_id' => $request->purchase_order_id,
                'vendor_code' => $request->vendor_code,
            ]);

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) continue;

                $vendorProducts[] = [
                    'vendor_pi_id' => $vendorPi->id,
                    'vendor_sku_code' => Arr::get($record, 'Vendor SKU Code'),
                    'mrp' => Arr::get($record, 'MRP'),
                    'quantity_requirement' => Arr::get($record, 'Quantity Requirement'),
                    'available_quantity' => Arr::get($record, 'Available Quantity'),
                    'purchase_rate' => Arr::get($record, 'Purchase Rate Basic'),
                    'gst' => Arr::get($record, 'GST'),
                    'hsn' => Arr::get($record, 'HSN'),
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
            return redirect()->route('purchase.order.view')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function view($id)
    {
        $tempOrder = TempOrder::get();
        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $id)->with('purchaseOrder', 'tempProduct')->get();
        // $vendors = PurchaseOrderProduct::distinct()->pluck('vendor_code');
        $vendorPI = VendorPI::with('product')->where('purchase_order_id', $id)->get();

        $facilityNames = VendorPI::with('product')->where('purchase_order_id', $id)
            ->where('status', '!=', 'completed')
            ->get()
            ->pluck('vendor_code')
            ->filter()
            ->unique()
            ->values();

        $purchaseOrder = PurchaseOrder::with('vendorPI.product', 'purchaseInvoices')->get();
        $uploadedPIOfVendors = VendorPI::distinct()->pluck('vendor_code');
        $purchaseInvoice = PurchaseInvoice::where('purchase_order_id', $id)->get();
        $purchaseGrn = PurchaseGrn::where('purchase_order_id', $id)->get();
        $vendorPIs = VendorPI::with('products.product')->where('purchase_order_id', $id)->where('status', '!=', 'completed')->get();

        $vendorPIid = VendorPI::where('purchase_order_id', $id)->get();

        // dd($vendorPIs);
        return view('purchaseOrder.view', compact('vendorPIid', 'facilityNames', 'purchaseOrderProducts', 'uploadedPIOfVendors',  'vendorPIs', 'purchaseOrder', 'purchaseInvoice', 'purchaseGrn'));
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
            dd($e->getMessage());
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

    public function updateStatus(Request $request)
    {
        $vendorPI = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
        $vendorPI->status = 'approve';
        $vendorPI->save();

        if ($vendorPI) {
            return redirect()->back()->with('success', 'Successfully Sent For Approval.');
        }
    }

    public function approveRequest(Request $request)
    {
        DB::beginTransaction();

        try {

            $vendorPI = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            $vendorPI->status = 'completed';
            $vendorPI->save();

            // update warehouse stock 
            // find products 
            $vendorPIProducts = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

            foreach ($vendorPIProducts->products as $product) {
                $updateStock = WarehouseStock::where('sku', $product->vendor_sku_code)->first();
                if (isset($updateStock)) {
                    $updateStock->quantity = $updateStock->quantity + $product->available_quantity;
                    $updateStock->block_quantity = $updateStock->block_quantity + $product->available_quantity;
                    $updateStock->save();
                } else {
                    $storeStock = WarehouseStock::create([
                        'warehouse_id' => '0',
                        'product_id' => '0',
                        'sku' => $product->vendor_sku_code,
                        'quantity' => '0',
                        'block_quantity' => '0',
                    ]);
                }

                $warehouseStockBlockLogs = WarehouseStockLog::where('sku', $product->vendor_sku_code)->first();
                $warehouseStockBlockLogs->block_quantity = $warehouseStockBlockLogs->block_quantity + $product->available_quantity;
                $warehouseStockBlockLogs->reason = "Block Quantity Removed - " . $warehouseStockBlockLogs->block_quantity;
                $warehouseStockBlockLogs->save();

                $updateProductStock = Product::where('sku', $product->vendor_sku_code)->first();
                $updateProductStock->sets_ctn = $updateProductStock->sets_ctn + $product->available_quantity;
                $updateProductStock->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Successfully Approved Received Products');
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
        $query = PurchaseOrderProduct::where('purchase_order_id', $request->purchaseOrderId);
        if ($request->filled('vendorCode')) {
            $query->where('vendor_code', '=', $request->vendorCode);
        }
        $purchaseOrderProducts = $query->with('purchaseOrder', 'tempProduct')->get();

        // Add rows
        foreach ($purchaseOrderProducts as $order) {
            if ($order->ordered_quantity > 0) {
                $writer->addRow([
                    'Order No' => $order->id,
                    'Purchase Order No' => 'PO-' . $order->id,
                    'Portal'            => $order->tempProduct->item_code ?? '',
                    'Vendor SKU Code'   => $order->tempProduct->sku ?? '',
                    'Title'             => $order->tempProduct->description ?? '',
                    'MRP'               => $order->tempProduct->mrp ?? '',
                    'Quantity Requirement' => $order->ordered_quantity ?? '',
                    'Available Quantity' => '',
                    'Purchase Rate Basic' => '',
                    'GST' => '',
                    'HSN' => '',
                ]);
            }
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, $request->vendorCode . '_Vendor_PO.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
