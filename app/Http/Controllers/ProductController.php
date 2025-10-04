<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Services\NotificationService;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = WarehouseStock::with('product', 'warehouse')->get();
        return view('products.index', compact('products'));
    }

    // done
    public function create()
    {
        $warehouses = Warehouse::all();
        return view('products.create', ['warehouses' => $warehouses]);
    }

    // done
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

                if (empty($record['SKU Code'])) {
                    continue;
                }

                $existingProduct = Product::where('sku', $record['SKU Code'])->first();

                if ($existingProduct) {
                    $product = Product::upsert([
                        'sku' => $record['SKU Code'] ?? '',
                        'ean_code' => $record['EAN Code'] ?? '',
                        'brand' => $record['Brand'] ?? '',
                        'brand_title' => $record['Brand Title'] ?? '',
                        'mrp' => $record['MRP'] ?? '',
                        'category' => $record['Category'] ?? '',
                        'pcs_set' => $record['PCS/Set'] ?? '',
                        'sets_ctn' => $record['Sets/CTN'] ?? '',
                        'gst' => $record['GST'] ?? '',

                        'basic_rate' => $record['Basic Rate'] ?? '',
                        'net_landing_rate' => isset($record['Basic Rate'], $record['GST'])
                            ? number_format($record['Basic Rate'] + ($record['Basic Rate'] * $record['GST'] / 100), 2, '.', '')
                            : null,
                        'case_pack_quantity' => ($record['PCS/Set'] ?? 0) * ($record['Sets/CTN'] ?? 0),
                        
                        'vendor_code' => $record['Vendor Code'] ?? '',
                        'vendor_name' => $record['Vendor Name'] ?? '',
                        'vendor_purchase_rate' => $record['Vendor Purchase Rate'] ?? '',
                        'vendor_net_landing' => $record['Vendor Net Landing'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], ['sku']);

                    $warehouseStock = WarehouseStock::where('sku', $record['SKU Code'])->where('warehouse_id', $request->warehouse_id)->first();
                    isset($record['Stock']) ? $warehouseStock->original_quantity = $record['Stock'] : $warehouseStock->original_quantity = 0;
                    isset($record['Stock']) ? $warehouseStock->available_quantity = $record['Stock'] : $warehouseStock->available_quantity = 0;
                    $warehouseStock->save();
                } else {
                    $product = Product::create([
                        'sku' => $record['SKU Code'] ?? '',
                        'ean_code' => $record['EAN Code'] ?? '',
                        'brand' => $record['Brand'] ?? '',
                        'brand_title' => $record['Brand Title'] ?? '',
                        'mrp' => $record['MRP'] ?? '',
                        'category' => $record['Category'] ?? '',
                        'pcs_set' => $record['PCS/Set'] ?? '',
                        'sets_ctn' => $record['Sets/CTN'] ?? '',
                        'gst' => $record['GST'] ?? '',
                        'basic_rate' => $record['Basic Rate'] ?? '',
                        'net_landing_rate' => isset($record['Basic Rate'], $record['GST'])
                            ? number_format($record['Basic Rate'] + ($record['Basic Rate'] * $record['GST'] / 100), 2, '.', '')
                            : null,
                        'case_pack_quantity' => ($record['PCS/Set'] ?? 0) * ($record['Sets/CTN'] ?? 0),
                        'vendor_code' => $record['Vendor Code'] ?? '',
                        'vendor_name' => $record['Vendor Name'] ?? '',
                        'vendor_purchase_rate' => $record['Vendor Purchase Rate'] ?? '',
                        'vendor_net_landing' => $record['Vendor Net Landing'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $warehouseStock = new WarehouseStock();
                    $warehouseStock->warehouse_id = $request->warehouse_id;
                    $warehouseStock->sku = $record['SKU Code'];
                    isset($record['Stock']) ? $warehouseStock->original_quantity = $record['Stock'] : $warehouseStock->original_quantity = 0;
                    isset($record['Stock']) ? $warehouseStock->available_quantity = $record['Stock'] : $warehouseStock->available_quantity = 0;
                    $warehouseStock->save();
                }

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->with(['csv_file' => 'No valid data found in the CSV file.']);
            }

            DB::commit();

            // Create notification for warehouse products added
            NotificationService::warehouseProductAdded("Multiple products", $insertCount);

            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
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
                    'gst' => Arr::get($record, 'GST') ?? '',

                    'basic_rate' => $record['Basic Rate'] ?? '',
                    'net_landing_rate' => isset($record['Basic Rate'], $record['GST'])
                        ? number_format($record['Basic Rate'] + ($record['Basic Rate'] * $record['GST'] / 100), 2, '.', '')
                        : null,
                    'case_pack_quantity' => ($record['PCS/Set'] ?? 0) * ($record['Sets/CTN'] ?? 0),
                    
                    'vendor_name' => Arr::get($record, 'Vendor Name') ?? '',
                    'vendor_purchase_rate' => Arr::get($record, 'Vendor Purchase Rate') ?? '',
                    'vendor_net_landing' => Arr::get($record, 'Vendor Net Landing') ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $warehouseStockUpdate = WarehouseStock::where('sku', $record['SKU Code'])->first();
                if ($record['Stock'] < $warehouseStockUpdate->available_quantity) {
                    $warehouseStockUpdate->original_quantity -= $record['Stock'];
                    $warehouseStockUpdate->available_quantity = $record['Stock'];
                } else {
                    $warehouseStockUpdate->original_quantity += $record['Stock'];
                    $warehouseStockUpdate->available_quantity = $record['Stock'];
                }
                $warehouseStockUpdate->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            Product::upsert($products, ['sku']);

            DB::commit();

            // Create notification for warehouse products updated
            NotificationService::warehouseProductAdded("Product stock updated", $insertCount);

            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function editProduct($id)
    {
        $product = Product::with('warehouseStock')->findOrFail($id);

        if (!$product) {
            return redirect()->back()->withErrors(['error' => 'Product not found.']);
        }
        return response()->json($product); // send data to AJAX
    }

    public function updateProduct(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->ean_code = $request->ean_code;
        $product->brand = $request->brand;
        $product->brand_title = $request->brand_title;
        $product->mrp = $request->mrp;
        $product->category = $request->category;
        $product->pcs_set = $request->pcs_set;
        $product->sets_ctn = $request->sets_ctn;
        $product->basic_rate = $request->basic_rate;
        // $product->net_landing_rate = $request->net_landing_rate;
        // $product->case_pack_quantity = $request->case_pack_quantity;
        $net_landing_rate = $request->basic_rate + ($request->basic_rate * $product->gst / 100) ?? 0;
        $product->net_landing_rate = number_format($net_landing_rate, 2, '.', '');
        $product->save();

        // Update warehouse stock
        $warehouseStock = WarehouseStock::where('sku', $product->sku)->first();
        if ($warehouseStock) {
            if ($request->original_quantity !== null) {
                $warehouseStock->available_quantity = $request->original_quantity - $warehouseStock->block_quantity;
                $warehouseStock->original_quantity = $request->original_quantity;
            }
            $warehouseStock->save();
        }

        return response()->json(['success' => true]);
    }

    // done
    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        if ($product) {
            $warehouseStock = WarehouseStock::where('sku', $product->sku)->first();
            $warehouseStock->delete();
            $product->delete();
        }

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    // done
    public function deleteSelected(Request $request)
    {
        $request->validate([
            'ids' => 'required',
        ]);

        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        DB::transaction(function () use ($ids) {
            // Get product SKUs before deleting
            $skus = Product::whereIn('id', $ids)->pluck('sku');

            // Delete related warehouse stocks
            WarehouseStock::whereIn('sku', $skus)->delete();

            // Delete products
            Product::destroy($ids);
        });

        return redirect()->back()->with('success', 'Selected products deleted successfully.');
    }



    public function downloadProductSheet(Request $request, $id = null)
    {
        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/product_sheet_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $products = WarehouseStock::with('product', 'warehouse')->when($id, function ($query) use ($id) {
            $query->where('warehouse_id', $id);
        })->get();

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
                'Basic Rate' => $product->product->basic_rate,
                'Net Landing Rate' => $product->product->net_landing_rate,
                // 'Case Pack Quantity' => $product->product->case_pack_quantity,
                'Vendor Name' => $product->product->vendor_name,
                'Vendor Purchase Rate' => $product->product->vendor_purchase_rate,
                'GST' =>  $product->product->gst,
                'Vendor Net Landing' => $product->product->vendor_net_landing,
                'Stock' => $product->quantity ?? '',
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'products_sheet.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
