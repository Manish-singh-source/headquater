<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\Product;
use Spatie\SimpleExcel\SimpleExcelReader;

class OrderController extends Controller
{
    //
    public function orderList()
    {
        return view('order');
    }

    public function addOrder()
    {
        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('add-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses]);
    }

    public function processOrder(Request $request)
    {
        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();
        // dd($request->file('csv_file'), $file_extension); // Debugging line to check file and mime type
        $reader = SimpleExcelReader::create($file, $file_extension);
        $insertedRows = [];
        foreach ($reader->getRows() as $record) {

            $product = Product::where('sku', $record['sku'])->first();

            if ($product->units_ordered > $record['po_qty']) {
                $unAvail = 'All Available';
            } else {
                $unavailableQuantity = $product->units_ordered - $record['po_qty'];
                $unAvail = abs($unavailableQuantity) . " Not Available";
            }

            $insertedRows[] = [
                'Customer' => $record['Customer'],
                'po_number' => $record['po_number'],
                'facility_name' => $record['facility_name'],
                'facility_Location' => $record['facility_Location'],
                'po_date' => $record['po_date']->format('d-m-Y'),
                'po_expiry_date' => $record['po_expiry_date']->format('d-m-Y'),
                'HSN' => $record['HSN'],
                'Item_Code' => $record['Item_Code'],
                'Description' => $record['Description'],
                'po_qty' => $record['po_qty'],
                'Basic_rate' => $record['Basic_rate'],
                'GST' => $record['GST'],
                'Net_Landing_rate' => $record['Net_Landing_rate'],
                'MRP' => $record['MRP'],
                'po_qty' => $record['po_qty'],
                'available_quantity' => $product->units_ordered,
                'unavailable_quantity' => $unAvail,
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
        }

        // dd($insertedRows);
        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('add-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses, 'fileData' => $insertedRows]);
    }


    public function processBlockOrder(Request $request)
    {
        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();
        // dd($request->file('csv_file'), $file_extension); // Debugging line to check file and mime type
        $reader = SimpleExcelReader::create($file, $file_extension);
        $insertedRows = [];
        foreach ($reader->getRows() as $record) {
            $insertedRows[] = [
                'Order_No' => $record['Order_No'],
                'Customer' => $record['Customer'],
                'po_number' => $record['po_number'],
                'facility_name' => $record['facility_name'],
                'facility_Location' => $record['facility_Location'],
                'po_date' => $record['po_date']->format('d-m-Y'),
                'po_expiry_date' => $record['po_expiry_date']->format('d-m-Y'),
                'HSN' => $record['HSN'],
                'Item_Code' => $record['Item_Code'],
                'Description' => $record['Description'],
                'po_qty' => $record['po_qty'],
                'Basic_rate' => $record['Basic_rate'],
                'GST' => $record['GST'],
                'Net_Landing_rate' => $record['Net_Landing_rate'],
                'MRP' => $record['MRP'],
                'Rate_Confirmation' => $record['Rate_Confirmation'],
                'po_qty' => $record['po_qty'],
                'Case_pack_Qty' => $record['Case_pack_Qty'],
                'Available' => $record['Available'],
                'Unavailable_qty' => $record['Unavailable_qty'],
                'Block' => $record['Block'],
                'Purchase_Order_Qty' => $record['Purchase_Order_Qty'],
                'Vendor_Code' => $record['Vendor_Code'],
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
        }

        // dd($insertedRows);
        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('add-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses, 'blockedData' => $insertedRows]);

        if (!$insert) {
            return redirect()->back()->withErrors(['csv_file' => 'Failed to insert data into the database.']);
        }

        return redirect('groups')->with('success', 'CSV file imported successfully.');
    }
}
