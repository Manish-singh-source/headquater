<?php

namespace App\Http\Controllers;

use App\Models\ProductMapping;
use App\Models\SkuMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ProductMappingController extends Controller
{
    /**
     * Display a listing of SKU mappings
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $productMapping = ProductMapping::orderBy('created_at', 'desc')
                ->get();

            return view('skuMapping.index', compact('productMapping'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving SKU mappings: '.$e->getMessage());
        }
    }

    /**
     * Store SKU mappings from uploaded Excel file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_mapping' => 'required|file|mimes:xlsx,csv,xls|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('sku_mapping');

        if (! $file) {
            return redirect()->back()->withErrors(['sku_mapping' => 'Please upload a valid file.']);
        }

        DB::beginTransaction();

        try {
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);
            $rows = $reader->getRows();
            $insertCount = 0;
            $duplicateCount = 0;
            $errorCount = 0;

            // Check for duplicates in the file first
            $seenSkus = [];

            foreach ($rows as $record) {
                if (empty($record['Product SKU'] ?? null)) {
                    continue;
                }

                $productSku = trim($record['Product SKU']);
                $vendorSku = trim($record['Vendor SKU'] ?? '');
                $customerSku = trim($record['Customer SKU'] ?? '');

                // Check for duplicate in current file
                $key = strtolower($productSku.'|'.$vendorSku.'|'.$customerSku);

                if (isset($seenSkus[$key])) {
                    $duplicateCount++;

                    continue;
                }

                $seenSkus[$key] = true;

                // Check for duplicate in database
                $existingMapping = SkuMapping::where('product_sku', $productSku)
                    ->where('vendor_sku', $vendorSku)
                    ->where('customer_sku', $customerSku)
                    ->first();

                if ($existingMapping) {
                    $duplicateCount++;

                    continue;
                }

                try {
                    SkuMapping::create([
                        'product_sku' => $productSku,
                        'vendor_sku' => $vendorSku,
                        'customer_sku' => $customerSku,
                    ]);

                    $insertCount++;
                } catch (\Exception $e) {
                    $errorCount++;

                    continue;
                }
            }

            if ($insertCount === 0) {
                DB::rollBack();

                $message = 'No valid data found to import.';
                if ($duplicateCount > 0) {
                    $message .= " ({$duplicateCount} duplicate entries skipped)";
                }

                return redirect()->back()->withErrors(['sku_mapping' => $message]);
            }

            DB::commit();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'inserted' => $insertCount,
                    'duplicates' => $duplicateCount,
                    'errors' => $errorCount,
                ])
                ->event('bulk_import')
                ->log('SKU mappings imported: '.$insertCount.' records');

            $message = 'Successfully imported '.$insertCount.' SKU mapping(s).';
            if ($duplicateCount > 0) {
                $message .= ' ('.$duplicateCount.' duplicate entries skipped)';
            }
            if ($errorCount > 0) {
                $message .= ' ('.$errorCount.' errors encountered)';
            }

            return redirect()->route('sku.mapping')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing file: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a SKU mapping
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:product_mappings,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
            }

            $productMapping = ProductMapping::findOrFail($id);

            return view('skuMapping.edit', compact('productMapping'));
        } catch (\Exception $e) {
            return redirect()->route('sku.mapping')
                ->with('error', 'Error loading SKU mapping: '.$e->getMessage());
        }
    }

    /**
     * Update the specified SKU mapping
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_id' => 'required|integer|exists:product_mappings,id',
            'portal_code' => 'nullable|string|max:255',
            'item_code' => 'nullable|string|max:255',
            'basic_rate' => 'nullable|string|max:255',
            'net_landing_rate' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $skuMapping = ProductMapping::findOrFail($request->sku_id);

            if (! $skuMapping) {
                return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
            }

            // Check for duplicates with different record
            $existingMapping = ProductMapping::where('sku', $request->sku)
                ->where('portal_code', $request->portal_code)
                ->where('item_code', $request->item_code)
                ->where('id', '!=', $request->sku_id)
                ->first();

            if ($existingMapping) {
                return redirect()->back()
                    ->with('error', 'This SKU mapping combination already exists.')
                    ->withInput();
            }

            $oldAttributes = $skuMapping->getOriginal();

            $skuMapping->portal_code = $request->portal_code;
            $skuMapping->item_code = $request->item_code;
            $skuMapping->basic_rate = $request->basic_rate;
            $skuMapping->net_landing_rate = $request->net_landing_rate;
            $skuMapping->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($skuMapping)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $oldAttributes,
                    'new' => $skuMapping->getChanges(),
                ])
                ->event('updated')
                ->log('SKU mapping updated');

            return redirect()->route('sku.mapping')
                ->with('success', 'SKU mapping updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating SKU mapping: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a SKU mapping
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:product_mappings,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
        }

        DB::beginTransaction();

        try {
            $skuMapping = ProductMapping::findOrFail($id);

            if (! $skuMapping) {
                return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
            }

            $mappingData = [
                'sku' => $skuMapping->sku,
                'portal_code' => $skuMapping->portal_code,
                'item_code' => $skuMapping->item_code,
                'basic_rate' => $skuMapping->basic_rate,
                'net_landing_rate' => $skuMapping->net_landing_rate,
            ];

            // Log activity before deletion
            activity()
                ->performedOn($skuMapping)
                ->causedBy(Auth::user())
                ->withProperties($mappingData)
                ->event('deleted')
                ->log('SKU mapping deleted');

            $skuMapping->delete();

            DB::commit();

            return redirect()->route('sku.mapping')
                ->with('success', 'SKU mapping deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('sku.mapping')
                ->with('error', 'Error deleting SKU mapping: '.$e->getMessage());
        }
    }

    /**
     * Delete multiple SKU mappings
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:sku_mappings,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid SKU mapping IDs selected.');
        }

        DB::beginTransaction();

        try {
            $ids = $request->ids;
            $mappings = SkuMapping::whereIn('id', $ids)->get();

            foreach ($mappings as $mapping) {
                activity()
                    ->performedOn($mapping)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'product_sku' => $mapping->product_sku,
                        'vendor_sku' => $mapping->vendor_sku,
                        'customer_sku' => $mapping->customer_sku,
                    ])
                    ->event('deleted')
                    ->log('SKU mapping deleted (bulk)');
            }

            $deleted = SkuMapping::destroy($ids);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Successfully deleted '.$deleted.' SKU mapping(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting SKU mappings: '.$e->getMessage());
        }
    }

    /**
     * Search SKU mappings
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid search query',
            ], 422);
        }

        try {
            $query = trim($request->query);

            $mappings = SkuMapping::where('product_sku', 'LIKE', "%{$query}%")
                ->orWhere('vendor_sku', 'LIKE', "%{$query}%")
                ->orWhere('customer_sku', 'LIKE', "%{$query}%")
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $mappings,
                'count' => $mappings->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching SKU mappings: '.$e->getMessage(),
            ], 500);
        }
    }

    // download excel file with product mapping data
    public function downloadSkuMappingExcel()
    {
        try {
            $tempXlsxPath = storage_path('app/sku_mapping_'.Str::random(8).'.xlsx');
            $writer = SimpleExcelWriter::create($tempXlsxPath);

            $productMappings = ProductMapping::orderBy('id')->get();

            if ($productMappings->isEmpty()) {
                return redirect()->back()->with('info', 'No SKU mappings found to download.');
            }

            foreach ($productMappings as $mapping) {
                $writer->addRow([
                    'SKU' => $mapping->sku ?? '',
                    'Portal Code' => $mapping->portal_code ?? '',
                    'Item Code' => $mapping->item_code ?? '',
                    'Basic Rate' => $mapping->basic_rate ?? '',
                    'Net Landing Rate' => $mapping->net_landing_rate ?? '',
                ]);
            }

            $writer->close();

            return response()->download($tempXlsxPath, 'sku-mapping.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error downloading SKU mappings: '.$e->getMessage());
        }
    }

    // update data using excel file upload same format as download file
    public function uploadSkuMappingExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_mapping_excel' => 'required|file|mimes:xlsx,csv,xls|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('sku_mapping_excel');

        if (! $file) {
            return redirect()->back()->withErrors(['sku_mapping_excel' => 'Please upload a valid file.']);
        }

        DB::beginTransaction();

        try {
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);
            $rows = $reader->getRows();

            $insertCount = 0;
            $updateCount = 0;
            $skipCount = 0;

            foreach ($rows as $record) {
                $sku = trim((string) ($record['SKU'] ?? $record['sku'] ?? $record['Product SKU'] ?? ''));
                $portalCode = trim((string) ($record['Portal Code'] ?? $record['portal_code'] ?? ''));
                $itemCode = trim((string) ($record['Item Code'] ?? $record['item_code'] ?? ''));
                $basicRate = trim((string) ($record['Basic Rate'] ?? $record['basic_rate'] ?? ''));
                $netLandingRate = trim((string) ($record['Net Landing Rate'] ?? $record['net_landing_rate'] ?? ''));

                if ($sku === '') {
                    $skipCount++;

                    continue;
                }

                $existingMapping = ProductMapping::where('sku', $sku)
                    ->where('portal_code', $portalCode)
                    ->where('item_code', $itemCode)
                    ->first();

                if ($existingMapping) {
                    $existingMapping->basic_rate = $basicRate;
                    $existingMapping->net_landing_rate = $netLandingRate;
                    $existingMapping->save();

                    $updateCount++;

                    continue;
                }

                ProductMapping::create([
                    'sku' => $sku,
                    'portal_code' => $portalCode,
                    'item_code' => $itemCode,
                    'basic_rate' => $basicRate,
                    'net_landing_rate' => $netLandingRate,
                ]);

                $insertCount++;
            }

            if ($insertCount === 0 && $updateCount === 0) {
                DB::rollBack();

                return redirect()->back()->with('error', 'No valid data found to import.');
            }

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'inserted' => $insertCount,
                    'updated' => $updateCount,
                    'skipped' => $skipCount,
                ])
                ->event('bulk_import')
                ->log('SKU mappings imported/updated: '.$insertCount.' inserted, '.$updateCount.' updated');

            $message = 'SKU mapping import complete. Inserted: '.$insertCount.', Updated: '.$updateCount.'.';
            if ($skipCount > 0) {
                $message .= ' Skipped: '.$skipCount.'.';
            }

            return redirect()->route('sku.mapping')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing file: '.$e->getMessage())
                ->withInput();
        }
    }
}
