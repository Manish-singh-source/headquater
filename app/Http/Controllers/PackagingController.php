<?php

namespace App\Http\Controllers;

use App\Models\VendorPI;
use App\Models\SalesOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
        $orders = SalesOrder::with('customerGroup')->get();
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
                'Customer Name' => $order->customer->contact_name,
                'PO Number' => $order->tempOrder->po_number,
                'SKU Code' => $order->tempOrder->sku,
                'Facility Name' => $order->tempOrder->facility_name,
                'Facility Location' => $order->tempOrder->facility_location,
                'PO Date' => $order->tempOrder->po_date,
                'PO Expiry Date' => $order->tempOrder->po_expiry_date,
                'HSN' => $order->tempOrder->hsn,
                'Item Code' => $order->tempOrder->item_code,
                'Description' => $order->tempOrder->description,
                'Basic Rate' => $order->tempOrder->basic_rate,
                'GST' => $order->tempOrder->gst,
                'Net Landing Rate' => $order->tempOrder->net_landing_rate,
                'MRP' => $order->tempOrder->mrp,
                'PO Quantity' => $order->tempOrder->po_qty,
                'Warehouse Stock' => $order->warehouseStock->original_quantity,
                // 'PI Quantity' => $order->tempOrder?->vendor_pi_fulfillment_quantity,
                'Purchase Order No' => $order->tempOrder->po_number,
                'Total Dispatch Qty' => $totalDispatchQty
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
            'products_excel' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $file = $request->file('products_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $products = [];
            $warehouseStockUpdate = [];
            $insertCount = 0;

            foreach ($rows as $record) {
                if (empty($record['SKU Code'])) continue;

                $products[] = [
                    'sku' => Arr::get($record, 'SKU Code') ?? '',
                    'ean_code' => Arr::get($record, 'EAN Code') ?? '',
                    'brand' => Arr::get($record, 'Brand') ?? '',
                    'brand_title' => Arr::get($record, 'Brand Title') ?? '',
                    'mrp' => Arr::get($record, 'MRP') ?? '',
                    'category' => Arr::get($record, 'Category') ?? '',
                    'pcs_set' => Arr::get($record, 'PCS/Set') ?? '',
                    'sets_ctn' => Arr::get($record, 'Sets/CTN') ?? '',
                    'vendor_name' => Arr::get($record, 'Vendor Name') ?? '',
                    'vendor_purchase_rate' => Arr::get($record, 'Vendor Purchase Rate') ?? '',
                    'gst' => Arr::get($record, 'GST') ?? '',
                    'vendor_net_landing' => Arr::get($record, 'Vendor Net Landing') ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];


                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            // Product::upsert($products, ['sku']);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
