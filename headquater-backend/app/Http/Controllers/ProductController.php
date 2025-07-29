<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TempOrder;
use App\Models\Warehouse;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;

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
                $product = Product::create([
                    'sku' => $record['SKU Code'],
                    'ean_code' => $record['EAN Code'],
                    'brand' => $record['Brand'],
                    'brand_title' => $record['Brand Title'],
                    'mrp' => $record['MRP'],
                    'category' => $record['Category'],
                    'pcs_set' => $record['PCS/Set'],
                    'sets_ctn' => $record['Sets/CTN'],
                    'vendor_name' => $record['Vendor Name'],
                    'vendor_purchase_rate' => $record['Vendor Purchase Rate'],
                    'gst' => $record['GST'],
                    'vendor_net_landing' => $record['Vendor Net Landing'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $warehouseStock = new WarehouseStock();
                $warehouseStock->warehouse_id = $request->warehouse_id;
                $warehouseStock->product_id = $product->id;
                $warehouseStock->sku = $record['SKU Code'];
                $warehouseStock->quantity = $record['Sets/CTN'];
                $warehouseStock->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
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
            $insertCount = 0;

            foreach ($rows as $record) {
                if (empty($record['SKU Code'])) continue;

                $products[] = [
                    'sku' => Arr::get($record, 'SKU Code'),
                    'ean_code' => Arr::get($record, 'EAN Code'),
                    'brand' => Arr::get($record, 'Brand'),
                    'brand_title' => Arr::get($record, 'Brand Title'),
                    'mrp' => Arr::get($record, 'MRP'),
                    'category' => Arr::get($record, 'Category'),
                    'pcs_set' => Arr::get($record, 'PCS/Set'),
                    'sets_ctn' => Arr::get($record, 'Sets/CTN'),
                    'vendor_name' => Arr::get($record, 'Vendor Name'),
                    'vendor_purchase_rate' => Arr::get($record, 'Vendor Purchase Rate'),
                    'gst' => Arr::get($record, 'GST'),
                    'vendor_net_landing' => Arr::get($record, 'Vendor Net Landing'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            Product::upsert($products, ['sku']);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }



    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Product::destroy($ids);
        return redirect()->back()->with('success', 'Selected customers deleted successfully.');
    }
    public function  productsList()
    {
        $products = TempOrder::with('warehouseStock.product')->get();
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

        $reader = SimpleExcelReader::create($file, $file_extension);
        $insertedRows = [];
        foreach ($reader->getRows() as $record) {

            $warehouseStock = new WarehouseStock();
            $warehouseStock->warehouse_id = $request->warehouse_id;
            $warehouseStock->product_id = $record['sku'];
            $warehouseStock->quantity = $record['units_ordered'];
            $warehouseStock->save();


            $insertedRows[] = [
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
