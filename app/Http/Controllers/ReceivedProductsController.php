<?php

namespace App\Http\Controllers;

use App\Models\VendorPI;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\VendorPIProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReceivedProductsController extends Controller
{

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['purchaseOrderProducts', 'vendorPI'])
            ->where('status', 'pending')
            ->withCount('purchaseOrderProducts')
            ->whereHas('vendorPI', function ($query) {
                $query->where('status', 'pending');
            }) 
            ->get();
        // dd($purchaseOrders);
        return view('receivedProducts.index', compact('purchaseOrders'));
    }

    public function view($id, $vendorCode)
    {
        $vendorPIs = VendorPI::with('products.product', 'purchaseOrder', 'vendor')
            ->where('purchase_order_id', $id)
            ->where('vendor_code', $vendorCode)
            // ->where('status', 'pending')
            ->first();
        // dd($vendorPIs);
        if ($vendorPIs) {
            return view('receivedProducts.view', compact('vendorPIs'));
        }

        return back()->with('error', 'Vendor PI not found.');
    }


    public function updateStatus(Request $request)
    {
        $vendorPI = VendorPI::with('products')->where('id', $request->vendor_pi_id)->first();
        $vendorPI->status = 'approve';
        $vendorPI->save();

        if ($vendorPI) {
            return redirect()->route('received-products.index')->with('success', 'Successfully Sent For Approval.');
        }

        return back()->with('error', 'Please Try Again.');
    }

    public function downloadReceivedProductsFile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchaseOrderId' => 'required',
            'vendorCode' => 'required',
        ]);

        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/received_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $vendorPIs = VendorPI::with('products.product')->where('purchase_order_id', $request->purchaseOrderId)->where('vendor_code', $request->vendorCode)->first();

        // Add rows
        foreach ($vendorPIs->products as $product) {
            $writer->addRow([
                'Order No' => $vendorPIs->id,
                'Purchase Order No' => $vendorPIs->purchase_order_id ?? '',
                // 'Vendor Code' => $vendorPIs->vendor_code ?? '',
                'Vendor SKU Code'   => $product->vendor_sku_code ?? '',
                'Title'             => $product->product->brand_title ?? '',
                'MRP'               => $product->mrp ?? '',
                'PO Quantity' => $product->quantity_requirement ?? '',
                'PI Quantity' => $product->available_quantity ?? '',
                // 'Purchase Rate Basic' => $product->purchase_rate ?? '',
                // 'GST' => $product->gst ?? '',
                // 'HSN' => $product->hsn ?? '',
                'Quantity Received' => '',
                'Issue Units' => '',
                'Issue Description' => '',
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'Received-Products-' . $request->vendorCode . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function update(Request $request)
    {
        $purchaseOrder = PurchaseOrder::where('id', $request->purchase_order_id)->first();
        $purchaseOrder->status = 'completed';
        $purchaseOrder->save();

        if ($purchaseOrder) {
            return back()->with('success', 'Order Saved Successfully.');
        }

        return back()->with('error', 'Something Went Wrong.');
    }

    public function getVendors(Request $request)
    {
        $vendorsList = VendorPI::where('purchase_order_id', $request->id)->where('status', '!=', 'completed')->get();

        return response()->json([
            'success' => true,
            'message' => 'Vendors retrieved successfully',
            'data' => $vendorsList
        ], 200);
    }

    public function updateRecievedProduct(Request $request)
    {
        $request->validate([
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'vendor_pi_id' => 'required',
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

            $vendorPIid = VendorPI::with('products')->where('id', $request->vendor_pi_id)->first();

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) {
                    continue;
                }
                
                $productData = VendorPIProduct::where('vendor_sku_code', Arr::get($record, 'Vendor SKU Code'))->where('vendor_pi_id', $vendorPIid->id)->first();

                if (Arr::get($record, 'PI Quantity')) {
                    $productData->quantity_requirement = Arr::get($record, 'PI Quantity');
                }
                if (Arr::get($record, 'Quantity Received')) {
                    $productData->quantity_received = Arr::get($record, 'Quantity Received');
                }
                if ($issueItem = Arr::get($record, 'Issue Units')) {
                    $productData->issue_item = $issueItem ?? '';
                    $productData->issue_reason = (Arr::get($record, 'PI Quantity') < Arr::get($record, 'Quantity Received') ? 'Exceed' : 'Shortage');
                    $productData->issue_description = Arr::get($record, 'Issue Description') ?? '';
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

            DB::commit();
            return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
