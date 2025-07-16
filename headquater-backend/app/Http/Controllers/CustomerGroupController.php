<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerGroupMember;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerGroupController extends Controller
{
    
    // List of Customer Groups
    public function index()
    {
        $customerGroups = CustomerGroup::with('customerGroupMembers')->get();
        return view('customerGroups.index', compact('customerGroups'));
    }

    // Creating a new Group of Customers
    public function create()
    {
        return view('customerGroups.create');
    }

    // Storing Group and it's customers
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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

        try {
            // 1. Create the customer group
            $customerGroup = new CustomerGroup();
            $customerGroup->name = $request['name'];
            $customerGroup->save();

            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);

            $insertCount = 0;

            foreach ($reader->getRows() as $record) {
                // 2. Insert individual customer
                $customer = Customer::create([
                    'client_name'       => $record['Client Name'],
                    'contact_name'       => $record['Contact Name'],
                    'email'      => $record['Email'],
                    'contact_no'      => $record['Contact No'],
                    'gstin'      => $record['GSTIN'],
                    'pan'      => $record['PAN'],
                ]);

                CustomerAddress::create([
                    'customer_id' => $customer->id,
                    'billing_address'      => $record['Billing Address'],
                    'billing_country'      => $record['Billing Country'],
                    'billing_state'      => $record['Billing State'],
                    'billing_city'      => $record['Billing City'],
                    'billing_zip'      => $record['Billing Zip'],
                    'shipping_address'      => $record['Shipping Address'],
                    'shipping_country'      => $record['Shipping Country'],
                    'shipping_state'      => $record['Shipping State'],
                    'shipping_city'      => $record['Shipping City'],
                    'shipping_zip'      => $record['Shipping Zip'],
                ]);

                // 3. Insert into customer_group_members
                CustomerGroupMember::create([
                    'customer_id' => $customer->id,
                    'customer_group_id' => $customerGroup->id,
                ]);

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
            }

            DB::commit();
            return redirect()->route('customer.groups.index')->with('success', 'CSV file imported successfully. Group and customers created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    // Editing Group information of existing
    public function edit($id)
    {
        return view('customerGroups.edit');
    }

    // View Customer Group Details
    public function view($id)
    {
        
        $customerGroup = CustomerGroup::with('customerGroupMembers.customer')->findOrFail($id);
        return view('customerGroups.view', compact('customerGroup'));
    }

    // Deleting Group of customers and it's related customers 
    public function destroy($id) {
        $customerGroup = CustomerGroup::findOrFail($id);
        $customerGroup->delete();
        
        return redirect()->route('customerGroups.index')->with('success', 'Successfully Deleted Group');
    }
}
