<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\customerGroup;

class CustomerGroupController extends Controller
{
    public function importLargeCsv(Request $request)
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
                'group_name' => $request['group_name'],
                'client_name' => $record['client_name'],
                'contact_name' => $record['contact_name'],
                'contact_email' => $record['email'],
                'contact_phone' => $record['phone'],
                'billing_address' => $record['billing_address'],
                'billing_zip' => $record['billing_zip'],
                'billing_city' => $record['billing_city'],
                'billing_state' => $record['billing_state'],
                'billing_country' => $record['billing_country'],
                'shipping_address' => $record['shipping_address'],
                'shipping_zip' => $record['shipping_zip'],
                'shipping_city' => $record['shipping_city'],
                'shipping_state' => $record['shipping_state'],
                'shipping_country' => $record['shipping_country'],
                'gstin' => $record['gstin'],
                'pan' => $record['pan'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
        }
        // Insert the data into the database
        $insert = customerGroup::insert($insertedRows);
        if (!$insert) {
            return redirect()->back()->withErrors(['csv_file' => 'Failed to insert data into the database.']);
        }



        return redirect('customers')->with('success', 'CSV file imported successfully.');
    }
}
