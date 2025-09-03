<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerGroupMember;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerGroupController extends Controller
{
    //
    public function index()
    {
        $customerGroups = CustomerGroup::with('customerGroupMembers')->get();
        return view('customerGroups.index', compact('customerGroups'));
    }

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
                $customer = Customer::where('client_name', $record['Client Name'])->first();

                if (!$customer) {
                    // 2. Insert individual customer
                    $customer = Customer::create([
                        'client_name'       => $record['Client Name'] ?? '',
                        'contact_name'       => $record['Contact Name'] ?? '',
                        'email'      => $record['Email'] ?? '',
                        'contact_no'      => $record['Contact No'] ?? '',
                        'company_name'      => $record['Company Name'] ?? '',
                        'gstin'      => $record['GSTIN'] ?? '',
                        'pan'      => $record['PAN'] ?? '',
                        'gst_treatment'      => $record['GST Treatment'] ?? '',
                        'private_details'      => $record['Private Details'] ?? '',
                        'billing_address'      => $record['Billing Address'] ?? '',
                        'billing_country'      => $record['Billing Country'] ?? '',
                        'billing_state'      => $record['Billing State'] ?? '',
                        'billing_city'      => $record['Billing City'] ?? '',
                        'billing_zip'      => $record['Billing Zip'] ?? '',
                        'shipping_address'      => $record['Shipping Address'] ?? '',
                        'shipping_country'      => $record['Shipping Country'] ?? '',
                        'shipping_state'      => $record['Shipping State'] ?? '',
                        'shipping_city'      => $record['Shipping City'] ?? '',
                        'shipping_zip'      => $record['Shipping Zip'] ?? '',
                    ]);
                }

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
            // dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function view($id)
    {
        $customerGroup = CustomerGroup::with('customerGroupMembers.customer')->findOrFail($id);
        return view('customerGroups.view', compact('customerGroup'));
    }

    public function edit($id)
    {
        $customerGroup = CustomerGroup::findOrFail($id);
        return view('customerGroups.edit', compact('customerGroup'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customerGroup = CustomerGroup::find($id);
        $customerGroup->name = $request->name;
        $customerGroup->save();

        if ($customerGroup) {
            return redirect()->route('customer.groups.index')->with('success', 'Group Name Updated Successfully.');
        }

        return redirect()->route('customer.groups.index')->with('info', 'No changes made to Group Name.');
    }

    // Deleting Group of customers and it's related customers 
    public function destroy($id)
    {
        if (!$id) {
            return redirect()->route('customer.groups.index')->with('error', 'Invalid Group ID.');
        }

        $customerGroup = CustomerGroup::findOrFail($id);
        $customerGroup->delete();

        if ($customerGroup) {
            return redirect()->route('customer.groups.index')->with('success', 'Successfully Deleted Group');
        }

        return redirect()->route('customer.groups.index')->with('info', 'No changes made to Group Name.');
    }

    public function toggleStatus(Request $request)
    {
        $customer = CustomerGroup::findOrFail($request->id);
        $customer->status = $request->status;
        $customer->save();

        if ($customer) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

     public function deleteSelected(Request $request)
    {
        if (!$request->ids) {
            return redirect()->back()->with('error', 'No customer groups selected for deletion.');
        }

        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        $group = CustomerGroup::destroy($ids);

        if ($group) {
            return redirect()->back()->with('success', 'Selected customer groups deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete selected customer groups.');
    }
}
