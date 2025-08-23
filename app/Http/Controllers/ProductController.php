<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TempOrder;
use App\Models\Warehouse;
use App\Models\SkuMapping;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockLog;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ProductController extends Controller
{
    //

    public function index()
    {
        $products = WarehouseStock::with('product', 'warehouse')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        return view('products.create', ['warehouses' => $warehouses]);
    }

    public function store(Request $request)
    {
        $file = $request->file('products_excel');
        if (!$file) {
            return redirect()->back()->withErrors(['products_excel' => 'Please upload a CSV file.']);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('products_excel')->getPathname();
            $file_extension = $request->file('products_excel')->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($file, $file_extension);
            $insertCount = 0;

            foreach ($reader->getRows() as $record) {
                // dd($record);
                $product = Product::create([
                    'sku' => $record['SKU Code'] ?? '',
                    'ean_code' => $record['EAN Code'] ?? '',
                    'brand' => $record['Brand'] ?? '',
                    'brand_title' => $record['Brand Title'] ?? '',
                    'mrp' => $record['MRP'] ?? '',
                    'category' => $record['Category'] ?? '',
                    'pcs_set' => $record['PCS/Set'] ?? '',
                    'sets_ctn' => $record['Sets/CTN'] ?? '',
                    'vendor_name' => $record['Vendor Name'] ?? '',
                    'vendor_purchase_rate' => $record['Vendor Purchase Rate'] ?? '',
                    'gst' => $record['GST'] ?? '',
                    'vendor_net_landing' => $record['Vendor Net Landing'] ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $warehouseStock = new WarehouseStock();
                $warehouseStock->warehouse_id = $request->warehouse_id;
                $warehouseStock->product_id = $product->id;
                $warehouseStock->sku = $record['SKU Code'];
                if(isset($record['Stock'])) {
                    $warehouseStock->quantity = $record['Stock'] ?? 0;
                }else {
                    $warehouseStock->quantity = 0;
                }
                $warehouseStock->save();

                // $warehouseStock = new WarehouseStockLog();
                // $warehouseStock->warehouse_id = $request->warehouse_id;
                // $warehouseStock->product_id = $product->id;
                // $warehouseStock->sku = $record['SKU Code'];
                // $warehouseStock->quantity = $record['Sets/CTN'];
                // $warehouseStock->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request)
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

                $warehouseStockUpdate = WarehouseStock::where('sku', $record['SKU Code'])->first();
                $warehouseStockUpdate->quantity = $record['Stock'];
                $warehouseStockUpdate->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            Product::upsert($products, ['sku']);

            DB::commit();

            // Create notification for products received
            notifyProductsReceived($insertCount);

            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product); // send data to AJAX
    }

    public function updateProduct(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->update([
            'sku' => $request->sku,
            'ean_code' => $request->ean_code,
            'brand' => $request->brand,
            'brand_title' => $request->brand_title,
            'mrp' => $request->mrp,
            'category' => $request->category,
            'pcs_set' => $request->pcs_set,
            'sets_ctn' => $request->sets_ctn,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Product::destroy($ids);
        return redirect()->back()->with('success', 'Selected customers deleted successfully.');
    }


    public function downloadProductSheet(Request $request)
    {
        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/product_sheet_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $products = WarehouseStock::with('product', 'warehouse')->get();

        // Add rows
        foreach ($products as $product) {
            $writer->addRow([
                'SKU Code' => $product->product->sku,
                'EAN Code' => $product->product->ean_code,
                'Brand' => $product->product->brand,
                'Brand Title' => $product->product->brand_title,
                'MRP' => $product->product->mrp,
                'Category' =>  $product->product->category,
                'PCS/Set' => $product->product->pcs_set,
                'Sets/CTN' => $product->product->sets_ctn,
                'Vendor Name' => $product->product->vendor_name,
                'Vendor Purchase Rate' => $product->product->vendor_purchase_rate,
                'GST' =>  $product->product->gst,
                'Vendor Net Landing' => $product->product->vendor_net_landing,
                'Stock' => $product->quantity ?? '',
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'vendor_po.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
