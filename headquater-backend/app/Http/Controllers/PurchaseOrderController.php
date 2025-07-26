<?php

namespace App\Http\Controllers;

use App\Models\VendorPI;
use App\Models\TempOrder;
use App\Models\PurchaseGrn;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class PurchaseOrderController extends Controller
{
    //
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')->get();
        // dd($purchaseOrders);
        $vendorCodes = $purchaseOrders->flatMap(function ($po) {
            return $po->purchaseOrderProducts->pluck('vendor_code');
        })->unique()->values();
        return view('purchaseOrder.index', compact('purchaseOrders', 'vendorCodes'));
    }

    public function view($id)
    {
        $tempOrder = TempOrder::get();
        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $id)->with('purchaseOrder', 'tempProduct')->get();
        $vendors = PurchaseOrderProduct::distinct()->pluck('vendor_code');
        $vendorPI = VendorPI::with('product')->where('purchase_order_id', $id)->get();
        $purchaseOrder = PurchaseOrder::with('vendorPI.product', 'purchaseInvoices')->where('status', 'pending')->get();
        $uploadedPIOfVendors = VendorPI::distinct()->pluck('vendor_code');
        $purchaseInvoice = PurchaseInvoice::where('purchase_order_id', $id)->get();
        $purchaseGrn = PurchaseGrn::where('purchase_order_id', $id)->get();
        // dd($vendorPI);
        return view('purchaseOrder.view', compact('purchaseOrderProducts', 'uploadedPIOfVendors', 'vendors', 'vendorPI', 'purchaseOrder', 'purchaseInvoice', 'purchaseGrn'));
    }

    public function store(Request $request)
    {
        $file = $request->file('pi_excel');
        if (!$file) {
            return redirect()->back()->withErrors(['pi_excel' => 'Please upload a CSV file.']);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('pi_excel')->getPathname();
            $file_extension = $request->file('pi_excel')->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($file, $file_extension);
            $insertCount = 0;

            foreach ($reader->getRows() as $record) {
                $vendor = VendorPI::create([
                    'purchase_order_id' => $request->purchase_order_id,
                    'vendor_code' => $request->vendor_code,
                    'vendor_sku_code' => $record['Vendor SKU Code'],
                    'mrp' => $record['MRP'],
                    'quantity_requirement' => $record['Quantity Requirement'],
                    'available_quantity' => $record['Available Quantity'],
                    'purchase_rate' => $record['Purchase Rate Basic'],
                    'gst' => $record['GST'],
                    'hsn' => $record['HSN'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['pi_excel' => 'No valid data found in the CSV file.']);
            }

            DB::commit();
            return redirect()->route('purchase.order.view')->with('success', 'CSV file imported successfully.');
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

        if($validated->fails()) {
            return redirect()->back()->withInput()->withErrors($validated)->with('error', $validated->failed());
        }
        
        // if($purchaseOrderInvoice?->invoice_file != null) {
            //     if(File::exists(public_path('uploads/invoices/' . $purchaseOrderInvoice->invoice_file))) {
        //         File::delete(public_path('uploads/invoices/' . $purchaseOrderInvoice->invoice_file));
        //     }
        // }

        $vendorPIStatus = VendorPI::where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

        if(!isset($vendorPIStatus)) {
            return redirect()->back()->with('error', 'Vendor PI Is Not Uploaded');
        }
        // dd($vendorPIStatus);

        $invoice_file = $request->file('invoice_file');
        $ext = $invoice_file->getClientOriginalExtension();
        $invoiceFileName = strtotime('now').'-'.$request->purchase_order_id.'.'.$ext;
        $invoice_file->move(public_path('uploads/invoices'), $invoiceFileName);

        $purchaseInvoice = new PurchaseInvoice();
        $purchaseInvoice->purchase_order_id = $request->purchase_order_id;
        $purchaseInvoice->vendor_code = $request->vendor_code;
        $purchaseInvoice->invoice_file = $invoiceFileName;
        $purchaseInvoice->save();
        
        if(!$purchaseInvoice) {
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

        if($validated->fails()) {
            return redirect()->back()->withInput()->withErrors($validated);
        }

        $grn_file = $request->file('grn_file');
        $ext = $grn_file->getClientOriginalExtension();
        $grnFileName = strtotime('now').'-'.$request->purchase_order_id.'.'.$ext;
        $grn_file->move(public_path('uploads/invoices'), $grnFileName);

        $purchaseGRN = new PurchaseGrn();
        $purchaseGRN->purchase_order_id = $request->purchase_order_id;
        $purchaseGRN->vendor_code = $request->vendor_code;
        $purchaseGRN->grn_file = $grnFileName;
        $purchaseGRN->save();
        
        return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'GRN imported successfully.');
    }
}
