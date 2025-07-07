<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProductController extends Controller
{
    //
    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Product::destroy($ids);
        return redirect()->back()->with('success', 'Selected customers deleted successfully.');
    }
    public function  productsList()
    {
        $products = Product::with('warehouse')->get();
        return view('products', ['products' => $products]);
    }

    public function addProductPage()
    {
        $warehouses = Warehouse::all();
        return view('add-product', ['warehouses' => $warehouses]);
    }

    public function  storeProducts(Request $request)
    {
        $file = $request->file('products_excel');
        if (!$file) {
            return redirect()->back()->withErrors(['products_excel' => 'Please upload a CSV file.']);
        }

        $file = $request->file('products_excel')->getPathname();
        $file_extension = $request->file('products_excel')->getClientOriginalExtension();
        // dd($request->file('products_excel'), $file_extension); // Debugging line to check file and mime type
        $reader = SimpleExcelReader::create($file, $file_extension);
        $insertedRows = [];
        foreach ($reader->getRows() as $record) {
            $insertedRows[] = [
                'warehouse_id' => $request->warehouse_id,
                'name' => $record['name'],
                'sku' => $record['sku'],
                'item_id' => $record['item_id'],
                'vendor_name' => $record['vendor_name'],
                'entity_vendor_legal_name' => $record['entity_vendor_legal_name'],
                'manufacturer_name' => $record['manufacturer_name'],
                'facility_name' => $record['facility_name'],
                'units' => $record['units'],
                'units_ordered' => $record['units_ordered'],
                'landing_rate' => $record['landing_rate'],
                'cost_price' => $record['cost_price'],
                'total_amount' => $record['total_amount'],
                'mrp' => $record['mrp'],
                'po_status' => $record['po_status'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the CSV file.']);
        }
        // Insert the data into the database
        $insert = Product::insert($insertedRows);
        if (!$insert) {
            return redirect()->back()->withErrors(['products_excel' => 'Failed to insert data into the database.']);
        }
        return redirect('products')->with('success', 'CSV file imported successfully.');
    }
}
