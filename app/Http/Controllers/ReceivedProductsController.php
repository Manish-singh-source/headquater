<?php

namespace App\Http\Controllers;

use App\Models\VendorPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReceivedProductsController extends Controller
{

    public function index()
    {
        $purchaseOrders = PurchaseOrder::where('status', 'pending')->get();
        return view('receivedProducts.index', compact('purchaseOrders'));
    }

    public function view(Request $request)
    {
        $vendorPIs = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
        return view('receivedProducts.view', compact('vendorPIs'));
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
        $vendorPIs = VendorPI::with('products')->where('purchase_order_id', $request->purchaseOrderId)->where('vendor_code', $request->vendorCode)->first();

        // Add rows
        foreach ($vendorPIs->products as $product) {
            $writer->addRow([
                'Order No' => $product->id,
                'Vendor Code' => $vendorPIs->vendor_code,
                'Purchase Order No' => $vendorPIs->purchase_order_id ?? '',
                'Vendor SKU Code'   => $product->vendor_sku_code ?? '',
                'Title'             => $product->vendor_sku_code ?? '',
                'MRP'               => $product->mrp ?? '',
                'Quantity Requirement' => $product->quantity_requirement ?? '',
                'Available Quantity' => $product->available_quantity ?? '',
                'Purchase Rate Basic' => $product->purchase_rate ?? '',
                'GST' => $product->gst ?? '',
                'HSN' => $product->hsn ?? '',
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'update_vendor_pi.xlsx', [
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
}
