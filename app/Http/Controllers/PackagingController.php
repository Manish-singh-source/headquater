<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VendorPI;
use App\Models\SalesOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ProductIssue;
use Illuminate\Http\Request;
use App\Models\SalesOrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PackagingController extends Controller
{
    //
    public function index()
    {
        $orders = SalesOrder::with('customerGroup')->where('status', 'ready_to_package')->get();
        return view('packagingList.index', compact('orders'));
    }

    public function view($id)
    {

        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.product',
            'orderedProducts.customer',
            'orderedProducts.tempOrder',
            'orderedProducts.warehouseStock',
        ])
            ->findOrFail($id);

        $facilityNames = collect();
        foreach ($salesOrder->orderedProducts as $order) {
            $facilityNames->push($order->customer->facility_name);
        }
        $facilityNames = $facilityNames->filter()->unique()->values();

        return view('packagingList.view', compact('salesOrder', 'facilityNames'));
    }

    public function downloadPackagingProducts(Request $request)
    {
        if (!$request->id) {
            return back()->with('error', 'Please Try Again.');
        }

        $id = $request->id;

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/received_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.product',
            'orderedProducts.customer',
            'orderedProducts.tempOrder',
            'orderedProducts.warehouseStock',
        ])
            ->findOrFail($id);

        $facilityNames = collect();
        foreach ($salesOrder->orderedProducts as $order) {
            $facilityNames->push($order->customer->contact_name);
        }
        $facilityNames = $facilityNames->filter()->unique()->values();

        // Add rows
        foreach ($salesOrder->orderedProducts as $order) {

            $totalDispatchQty = 0;
            if ($order->tempOrder?->vendor_pi_received_quantity) {
                $order->tempOrder->vendor_pi_fulfillment_quantity = $order->tempOrder->vendor_pi_received_quantity;
            }

            if ($order->ordered_quantity <= ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0)) {
                $totalDispatchQty = $order->ordered_quantity;
            } else {
                $totalDispatchQty = ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
            }
            if (isset($request->facility_name) && $order->tempOrder->facility_name != $request->facility_name) {
                continue;
            }
            $writer->addRow([
                'Customer Name' => $order->customer->contact_name ?? '',
                // 'PO Number' => $order->tempOrder->po_number ?? '',
                'SKU Code' => $order->tempOrder->sku ?? '',
                'Facility Name' => $order->tempOrder->facility_name ?? '',
                'Facility Location' => $order->tempOrder->facility_location ?? '',
                'PO Date' => $order->tempOrder->po_date ?? '',
                'PO Expiry Date' => $order->tempOrder->po_expiry_date ?? '',
                'HSN' => $order->tempOrder->hsn ?? '',
                'Item Code' => $order->tempOrder->item_code ?? '',
                'Description' => $order->tempOrder->description ?? '',
                'GST' => $order->tempOrder->gst ?? '',
                'Basic Rate' => $order->tempOrder->basic_rate ?? '',
                'Net Landing Rate' => $order->tempOrder->net_landing_rate ?? '',
                'MRP' => $order->tempOrder->mrp ?? '',
                'PO Quantity' => $order->tempOrder->po_qty ?? '',
                'Purchase Order Quantity' => $order->tempOrder->purchase_order_quantity ?? '',
                'Warehouse Stock' => $order->warehouseStock->original_quantity ?? '',
                // 'PI Quantity' => $order->tempOrder?->vendor_pi_fulfillment_quantity,
                'Purchase Order No' => $order->tempOrder->po_number ?? '',
                'Total Dispatch Qty' => $totalDispatchQty ?? 0,
                'Final Dispatch Qty' => '',
                'Issue Units' => '',
                'Issue Reason' => ''
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'Packaging-Products.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function updatePackagingProducts(Request $request)
    {
        $request->validate([
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        if (!$request->salesOrderId) {
            return back()->with('error', 'Please Try Again.');
        }

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $insertCount = 0;

            foreach ($rows as $record) {
                if (empty($record['SKU Code'])) {
                    continue;
                }
                
                $customer = Customer::where('facility_name', $record['Facility Name'])->first();
                if (!$customer) {
                    continue;
                }
                
                // if final dispatch qty is empty or 0 then set it to dispatched quantity
                // if user wants to set dispatch quantity to 0 then set it to 0 but how?? 
                if (!empty($record['Final Dispatch Qty']) || $record['Final Dispatch Qty'] == 0) {
                    $order = SalesOrderProduct::with('tempOrder')->where('customer_id', $customer->id)->where('sales_order_id', $request->salesOrderId)->where('sku', $record['SKU Code'])->first();
                    if (!$order) {
                        continue;
                    }
                    
                    $tempOrder = $order->tempOrder;
                    
                    if ($order->dispatched_quantity > $record['Final Dispatch Qty']) {
                        $order->final_dispatched_quantity = $record['Final Dispatch Qty'];
                        $order->issue_item = $record['Issue Units'];
                        $order->issue_reason = 'Shortage';
                        $order->issue_description = $record['Issue Reason'];
                        $order->status = 'packaged';
                        $order->save();
                        
                        $lessQuantity = $order->dispatched_quantity - $record['Final Dispatch Qty'];
                        
                        // create entry in products issues table
                        // create entry in vendor return products issues table
                        ProductIssue::create([
                            'purchase_order_id' => $tempOrder->purchase_order_id,
                            'vendor_pi_id' => $tempOrder->vendor_pi_id,
                            'vendor_pi_product_id' => $tempOrder->vendorPIProduct->id,
                            'vendor_sku_code' => $tempOrder->vendor_sku_code,
                            'quantity_requirement' => $tempOrder->vendorPIProduct->quantity_requirement,
                            'available_quantity' => $tempOrder->available_quantity,
                            'quantity_received' => $tempOrder->vendorPIProduct->quantity_received,
                            'issue_item' => $lessQuantity,
                            'issue_reason' => 'Shortage',
                            'issue_description' => 'Shortage products',
                            'issue_from' => 'warehouse',
                            'issue_status' => 'pending',
                        ]);
                        
                    } elseif ($order->dispatched_quantity < $record['Final Dispatch Qty']) {
                        $order->final_dispatched_quantity = $order->dispatched_quantity;
                        $order->issue_item = 'Exceed';
                        $order->issue_description = $record['Issue Reason'];
                        $order->status = 'packaged';
                        $order->save();
                    } else {
                        $order->final_dispatched_quantity = $order->dispatched_quantity;
                        $order->status = 'packaged';
                        $order->save();
                    }
                } else {
                    $order = SalesOrderProduct::where('customer_id', $customer->id)->where('sales_order_id', $request->salesOrderId)->where('sku', $record['SKU Code'])->first();
                    if (!$order) {
                        continue;
                    }
                    if ($order->dispatched_quantity > $record['Final Dispatch Qty']) {
                        $order->final_dispatched_quantity = $record['Final Dispatch Qty'];
                        $order->issue_item = $record['Issue Units'];
                        $order->issue_reason = 'Shortage';
                        $order->issue_description = $record['Issue Reason'];
                        $order->status = 'packaged';
                        $order->save();
                    } elseif ($order->dispatched_quantity < $record['Final Dispatch Qty']) {
                        $order->final_dispatched_quantity = $order->dispatched_quantity;
                        $order->issue_item = 'Exceed';
                        $order->issue_description = $record['Issue Reason'];
                        $order->status = 'packaged';
                        $order->save();
                    } else {
                        $order->final_dispatched_quantity = $order->dispatched_quantity;
                        $order->status = 'packaged';
                        $order->save();
                    }
                }

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->with(['pi_excel' => 'No valid data found in the file.']);
            }

            DB::commit();
            return redirect()->route('packaging.list.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
