<?php

namespace App\Http\Controllers;

use App\Models\SkuMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;

class SKUMappingController extends Controller
{
    //
    public function index() {
        $skuMapping = SkuMapping::get();
        return view('skuMapping.index', compact('skuMapping'));
    }

    public function store(Request $request)
    {
        $file = $request->file('sku_mapping');
        if (!$file) {
            return redirect()->back()->withErrors(['sku_mapping' => 'Please upload a CSV file.']);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('sku_mapping')->getPathname();
            $file_extension = $request->file('sku_mapping')->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($file, $file_extension);
            $insertCount = 0;

            foreach ($reader->getRows() as $record) {
                SkuMapping::create([
                    'product_sku' => $record['Product SKU'],
                    'vendor_sku' => $record['Vendor SKU'],
                    'customer_sku' => $record['Customer SKU'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['sku_mapping' => 'No valid data found in the CSV file.']);
            }

            DB::commit();
            return redirect()->route('sku.mapping')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $skuMapping  = SkuMapping::findOrFail($id);
        return view('skuMapping.edit', compact('skuMapping'));
    }
    
    public function update(Request $request) {
        $skuMapping  = SkuMapping::findOrFail($request->sku_id);
        $skuMapping->product_sku = $request->product_sku;
        $skuMapping->customer_sku = $request->customer_sku;
        $skuMapping->vendor_sku = $request->vendor_sku;
        $skuMapping->save();

        return redirect()->route('sku.mapping')->with('success', 'SKU Mapping Updated Successfully.');
    }

    public function delete($id) {
        $skuMapping = SkuMapping::findOrFail($id);
        $skuMapping->delete();

        return redirect()->route('sku.mapping')->with('success', 'SKU Mapping deleted successfully.');
    }

}
