<?php

namespace App\Http\Controllers;

use App\Models\SkuMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class SKUMappingController extends Controller
{
    /**
     * Display a listing of SKU mappings
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $skuMapping = SkuMapping::latest()
                ->paginate(15);

            return view('skuMapping.index', compact('skuMapping'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving SKU mappings: ' . $e->getMessage());
        }
    }

    /**
     * Store SKU mappings from uploaded Excel file
     *
     * @param Request $request
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

        if (!$file) {
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
                $key = strtolower($productSku . '|' . $vendorSku . '|' . $customerSku);

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
                ->log('SKU mappings imported: ' . $insertCount . ' records');

            $message = 'Successfully imported ' . $insertCount . ' SKU mapping(s).';
            if ($duplicateCount > 0) {
                $message .= ' (' . $duplicateCount . ' duplicate entries skipped)';
            }
            if ($errorCount > 0) {
                $message .= ' (' . $errorCount . ' errors encountered)';
            }

            return redirect()->route('sku.mapping')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing file: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a SKU mapping
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:sku_mappings,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
            }

            $skuMapping = SkuMapping::findOrFail($id);

            return view('skuMapping.edit', compact('skuMapping'));
        } catch (\Exception $e) {
            return redirect()->route('sku.mapping')
                ->with('error', 'Error loading SKU mapping: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified SKU mapping
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_id' => 'required|integer|exists:sku_mappings,id',
            'product_sku' => 'required|string|max:255|unique:sku_mappings,product_sku,' . $request->sku_id,
            'vendor_sku' => 'nullable|string|max:255',
            'customer_sku' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $skuMapping = SkuMapping::findOrFail($request->sku_id);

            if (!$skuMapping) {
                return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
            }

            // Check for duplicates with different record
            $existingMapping = SkuMapping::where('product_sku', trim($request->product_sku))
                ->where('vendor_sku', trim($request->vendor_sku ?? ''))
                ->where('customer_sku', trim($request->customer_sku ?? ''))
                ->where('id', '!=', $request->sku_id)
                ->first();

            if ($existingMapping) {
                return redirect()->back()
                    ->with('error', 'This SKU mapping combination already exists.')
                    ->withInput();
            }

            $oldAttributes = $skuMapping->getOriginal();

            $skuMapping->product_sku = trim($request->product_sku);
            $skuMapping->vendor_sku = trim($request->vendor_sku ?? '');
            $skuMapping->customer_sku = trim($request->customer_sku ?? '');
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
                ->with('error', 'Error updating SKU mapping: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a SKU mapping
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:sku_mappings,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
        }

        DB::beginTransaction();

        try {
            $skuMapping = SkuMapping::findOrFail($id);

            if (!$skuMapping) {
                return redirect()->route('sku.mapping')->with('error', 'SKU mapping not found.');
            }

            $mappingData = [
                'product_sku' => $skuMapping->product_sku,
                'vendor_sku' => $skuMapping->vendor_sku,
                'customer_sku' => $skuMapping->customer_sku,
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
                ->with('error', 'Error deleting SKU mapping: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple SKU mappings
     *
     * @param Request $request
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
                ->with('success', 'Successfully deleted ' . $deleted . ' SKU mapping(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting SKU mappings: ' . $e->getMessage());
        }
    }

    /**
     * Search SKU mappings
     *
     * @param Request $request
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
                'message' => 'Error searching SKU mappings: ' . $e->getMessage(),
            ], 500);
        }
    }
}
