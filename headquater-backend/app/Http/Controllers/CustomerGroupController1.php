<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\customerGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerGroupController1 extends Controller
{
    public function importLargeCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|unique:customer_groups,group_name',
            'csv_file' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }


        DB::beginTransaction();
        $customerGroup = new customerGroup();
        $customerGroup->group_name = $request['group_name'];
        $customerGroup->save();


        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();
        // dd($request->file('csv_file'), $file_extension); // Debugging line to check file and mime type
        $reader = SimpleExcelReader::create($file, $file_extension);
        $insertedRows = [];
        foreach ($reader->getRows() as $record) {
            $insertedRows[] = [
                'group_id' => $customerGroup->id,
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
        $insert = Customer::insert($insertedRows);
        if (!$insert) {
            DB::rollBack();
            return redirect()->back()->withErrors(['csv_file' => 'Failed to insert data into the database.']);
        }


        DB::commit();
        return redirect('groups')->with('success', 'CSV file imported successfully.');
    }
}
