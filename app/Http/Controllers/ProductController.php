<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ProductController extends Controller
{
    /**
     * Display a listing of products with warehouse stock
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $products = WarehouseStock::with('product', 'warehouse')
                ->get();

            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving products: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new product
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $warehouses = Warehouse::where('status', '1')->get();
        return view('products.create', ['warehouses' => $warehouses]);
    }

    /**
     * Store products from uploaded Excel file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products_excel' => 'required|file|mimes:xlsx,csv,xls',
            'warehouse_id' => 'required|integer|exists:warehouses,id,status,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $file = $request->file('products_excel');
        if (!$file) {
            return redirect()->back()->withErrors(['products_excel' => 'Please upload a valid file.']);
        }
        
        DB::beginTransaction();
        
        try {
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();
            
            $reader = SimpleExcelReader::create($filePath, $fileExtension);
            $rows = $reader->getRows()->toArray();
            
            // store duplicate check from database 
            $duplicatesInDb = [];

            // Check for duplicates
            $seen = [];
            foreach ($rows as $record) {
                if (empty($record['SKU Code'] ?? null)) {
                    continue;
                }

                $key = strtolower(trim($record['SKU Code'] ?? ''));

                if (isset($seen[$key])) {
                    DB::rollBack();

                    return redirect()->back()->with([
                        'error' => 'Please check excel file: duplicate SKU ('.$record['SKU Code'].') found in the file.',
                    ]);
                }

                $seen[$key] = true;
            }
            
            // Process records
            $insertCount = 0;
            
            foreach ($reader->getRows() as $record) {
                if (empty($record['SKU Code'] ?? null)) {
                    continue;
                }
                
                $sku = trim($record['SKU Code']);
                $basicRate = (int)($record['Basic Rate'] ?? 0);
                $gst = (int)($record['GST'] ?? 0);
                $netLandingRate = $this->calculateNetLandingRate($basicRate, $gst);
                $casePackQuantity = ((int)($record['PCS/Set'] ?? 0)) * ((int)($record['Sets/CTN'] ?? 0));

                $existingProduct = Product::where('sku', $sku)->where('warehouse_id', $request->warehouse_id)->first();

                if ($existingProduct) {
                    // Update existing product
                    $duplicatesInDb[] = $sku;
                } else {
                    // Create new product
                    Product::create([
                        'warehouse_id' => $request->warehouse_id,
                        'sku' => $sku,
                        'ean_code' => $record['EAN Code'] ?? '',
                        'brand' => $record['Brand'] ?? '',
                        'brand_title' => $record['Brand Title'] ?? '',
                        'mrp' => $record['MRP'] ?? '',
                        'category' => $record['Category'] ?? '',
                        'pcs_set' => $record['PCS/Set'] ?? '',
                        'sets_ctn' => $record['Sets/CTN'] ?? '',
                        'gst' => $record['GST'] ?? '',
                        'basic_rate' => isset($record['Basic Rate']) ? intval($record['Basic Rate']) : '',
                        'net_landing_rate' => $netLandingRate,
                        'case_pack_quantity' => $casePackQuantity,
                        'vendor_code' => $record['Vendor Code'] ?? '',
                        'vendor_name' => $record['Vendor Name'] ?? '',
                        'vendor_purchase_rate' => $record['Vendor Purchase Rate'] ?? '',
                        'vendor_net_landing' => $record['Vendor Net Landing'] ?? '',
                    ]);

                    // Create warehouse stock entry
                    $stock = (int)($record['Stock'] ?? 0);
                    
                    WarehouseStock::create([
                        'warehouse_id' => $request->warehouse_id,
                        'sku' => $sku,
                        'original_quantity' => $stock,
                        'available_quantity' => $stock ,
                    ]);
                }

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            
            if (!empty($duplicatesInDb)) {
                DB::rollBack();
                return redirect()->back()->with([
                    'error' => 'Duplicate SKU(s) found in the database: ' . implode(', ', $duplicatesInDb),
                ]);
            }

            DB::commit();
            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['count' => $insertCount])
                ->event('bulk_import')
                ->log('Products imported: ' . $insertCount . ' records');

            // Create notification
            NotificationService::warehouseProductAdded('Multiple products', $insertCount);

            return redirect()->route('products.index')
                ->with('success', 'Successfully imported ' . $insertCount . ' products.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Update products from uploaded Excel file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products_excel' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('products_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $products = [];
            $insertCount = 0;

            foreach ($rows as $record) {
                if (empty($record['SKU Code'] ?? null) || empty($record['Warehouse Id'] ?? null)) {
                    continue;
                }

                $sku = trim($record['SKU Code']);
                $basicRate = (int)($record['Basic Rate'] ?? 0);
                $gst = (int)($record['GST'] ?? 0);
                $netLandingRate = $this->calculateNetLandingRate($basicRate, $gst);
                $casePackQuantity = ((int)($record['PCS/Set'] ?? 0)) * ((int)($record['Sets/CTN'] ?? 0));

                $products[] = [
                    'warehouse_id' => Arr::get($record, 'Warehouse Id') ?? '',
                    'sku' => Arr::get($record, 'SKU Code') ?? '',
                    'ean_code' => Arr::get($record, 'EAN Code') ?? '',
                    'brand' => Arr::get($record, 'Brand') ?? '',
                    'brand_title' => Arr::get($record, 'Brand Title') ?? '',
                    'mrp' => Arr::get($record, 'MRP') ?? '',
                    'category' => Arr::get($record, 'Category') ?? '',
                    'pcs_set' => Arr::get($record, 'PCS/Set') ?? '',
                    'sets_ctn' => Arr::get($record, 'Sets/CTN') ?? '',
                    'gst' => Arr::get($record, 'GST') ?? '',

                    'basic_rate' => isset($record['Basic Rate']) ? $record['Basic Rate'] : '',
                    'net_landing_rate' => $netLandingRate,
                    'case_pack_quantity' => $casePackQuantity,

                    'vendor_code' => $record['Vendor Code'] ?? '',
                    'vendor_name' => Arr::get($record, 'Vendor Name') ?? '',
                    'vendor_purchase_rate' => Arr::get($record, 'Vendor Purchase Rate') ?? '',
                    'vendor_net_landing' => Arr::get($record, 'Vendor Net Landing') ?? '',
                    'updated_at' => now(),
                ];

                // Update warehouse stock
                $warehouseStock = WarehouseStock::where('sku', $sku)->where('warehouse_id', $record['Warehouse Id'])->first();
                
                if ($warehouseStock) {
                    $stock = (int)($record['Stock'] ?? 0);

                    if ($stock < $warehouseStock->available_quantity) {
                        $warehouseStock->original_quantity -= ($warehouseStock->available_quantity - $stock);
                    } else {
                        $warehouseStock->original_quantity += ($stock - $warehouseStock->available_quantity);
                    }

                    $warehouseStock->available_quantity = $stock;
                    $warehouseStock->save();
                }

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            Product::upsert($products, ['sku', 'warehouse_id']);

            DB::commit();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['count' => $insertCount])
                ->event('bulk_update')
                ->log('Products updated: ' . $insertCount . ' records');

            // Create notification
            NotificationService::warehouseProductAdded('Product stock updated', $insertCount);

            return redirect()->route('products.index')
                ->with('success', 'Successfully updated ' . $insertCount . ' products.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Get product details for editing via AJAX
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProduct($id)
    {
        try {
            $product = Product::with('warehouseStock')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
    }

    /**
     * Update a single product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products,id',
            'ean_code' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:255',
            'brand_title' => 'nullable|string|max:255',
            'mrp' => 'nullable|numeric',
            'category' => 'nullable|string|max:255',
            'pcs_set' => 'nullable|integer|min:0',
            'sets_ctn' => 'nullable|integer|min:0',
            'basic_rate' => 'nullable|numeric|min:0',
            'original_quantity' => 'nullable|integer|min:0',
            'available_quantity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->id);

            // Only update basic_rate and net_landing_rate if basic_rate is provided
            $updateData = [
                'ean_code' => $request->ean_code,
                'brand' => $request->brand,
                'brand_title' => $request->brand_title,
                'mrp' => $request->mrp,
                'category' => $request->category,
                'pcs_set' => (int)($request->pcs_set ?? 0),
                'sets_ctn' => (int)($request->sets_ctn ?? 0),
            ];

            if ($request->has('basic_rate') && $request->basic_rate !== null && $request->basic_rate !== '') {
                $basicRate = (float)$request->basic_rate;
                $netLandingRate = $this->calculateNetLandingRate((int)$basicRate, (int)($product->gst ?? 0));
                $updateData['basic_rate'] = $basicRate;
                $updateData['net_landing_rate'] = $netLandingRate;
            }

            $product->update($updateData);

            // Update warehouse stock if provided
            if ($request->has('original_quantity') || $request->has('available_quantity')) {
                $warehouseStock = WarehouseStock::where('sku', $product->sku)->first();

                if ($warehouseStock) {
                    $updateStockData = [];

                    // Update original quantity if provided
                    if ($request->has('original_quantity')) {
                        $updateStockData['original_quantity'] = (int)$request->original_quantity;
                    }

                    // Update available quantity if provided
                    if ($request->has('available_quantity')) {
                        $updateStockData['available_quantity'] = (int)$request->available_quantity;
                    }

                    $warehouseStock->update($updateStockData);
                }
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($product)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $product->getOriginal(),
                    'new' => $product->getChanges(),
                ])
                ->event('updated')
                ->log('Product updated');

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a single product
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            DB::beginTransaction();

            // Delete warehouse stock
            WarehouseStock::where('sku', $product->sku)->delete();

            // Log activity
            activity()
                ->performedOn($product)
                ->causedBy(Auth::user())
                ->withProperties(['sku' => $product->sku])
                ->event('deleted')
                ->log('Product deleted');

            // Delete product
            $product->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple selected products
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        // Normalize ids: accept array or comma-separated string
        $rawIds = $request->input('ids');

        if (is_string($rawIds)) {
            $ids = array_filter(array_map('trim', explode(',', $rawIds)), function ($v) {
                return $v !== '';
            });
        } elseif (is_array($rawIds)) {
            $ids = $rawIds;
        } else {
            $ids = [];
        }

        // Cast to integers
        $ids = array_map('intval', $ids);

        $validator = Validator::make(['ids' => $ids], [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid product IDs selected.');
        }

        DB::beginTransaction();

        try {
            $skus = Product::whereIn('id', $ids)->pluck('sku')->toArray();

            // Delete related warehouse stocks
            if (!empty($skus)) {
                WarehouseStock::whereIn('sku', $skus)->delete();
            }

            // Delete products
            $deleted = Product::destroy($ids);

            DB::commit();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['count' => $deleted, 'ids' => $ids])
                ->event('bulk_delete')
                ->log('Products deleted: ' . $deleted . ' records');

            return redirect()->back()->with('success', 'Successfully deleted ' . $deleted . ' product(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error deleting products: ' . $e->getMessage());
        }
    }

    /**
     * Download product sheet as Excel file
     *
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Http\Response
     */
    public function downloadProductSheet(Request $request, $id = null)
    {
        try {
            $tempXlsxPath = storage_path('app/product_sheet_' . Str::random(8) . '.xlsx');
            $writer = SimpleExcelWriter::create($tempXlsxPath);

            $products = WarehouseStock::with('product', 'warehouse')
                ->when($id, function ($query) use ($id) {
                    $query->where('warehouse_id', (int)$id);
                })
                ->get();

            if ($products->isEmpty()) {
                return redirect()->back()->with('info', 'No products found to download.');
            }

            // Add data rows
            foreach ($products as $stock) {
                $writer->addRow([
                    'Warehouse Id' => $stock->warehouse?->id ?? '',
                    'Warehouse Name' => $stock->warehouse?->name ?? '',
                    'SKU Code' => $stock->product?->sku ?? '',
                    'EAN Code' => $stock->product?->ean_code ?? '',
                    'Brand' => $stock->product?->brand ?? '',
                    'Brand Title' => $stock->product?->brand_title ?? '',
                    'MRP' => $stock->product?->mrp ?? '',
                    'Category' => $stock->product?->category ?? '',
                    'PCS/Set' => $stock->product?->pcs_set ?? '',
                    'Sets/CTN' => $stock->product?->sets_ctn ?? '',
                    'Basic Rate' => $stock->product?->basic_rate ?? '',
                    'Net Landing Rate' => $stock->product?->net_landing_rate ?? '',
                    'Vendor Code' => $stock->product?->vendor_code ?? '',
                    'Vendor Name' => $stock->product?->vendor_name ?? '',
                    'Vendor Purchase Rate' => $stock->product?->vendor_purchase_rate ?? '',
                    'GST' => $stock->product?->gst ?? '',
                    'Vendor Net Landing' => $stock->product?->vendor_net_landing ?? '',
                    'Stock' => $stock->available_quantity ?? 0,
                ]);
            }

            $writer->close();

            return response()->download($tempXlsxPath, 'products_sheet.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error downloading products: ' . $e->getMessage());
        }
    }

    /**
     * Calculate net landing rate
     *
     * @param int $basicRate
     * @param int $gst
     * @return string
     */
    private function calculateNetLandingRate($basicRate, $gst)
    {
        if ($basicRate <= 0) {
            return '0.00';
        }

        $netRate = $basicRate + ($basicRate * $gst / 100);

        return number_format($netRate, 2, '.', '');
    }
}
