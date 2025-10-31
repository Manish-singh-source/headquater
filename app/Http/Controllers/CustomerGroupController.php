<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\CustomerGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerGroupController extends Controller
{
    public function index()
    {
        $customerGroups = CustomerGroup::with('customerGroupMembers')->get();

        return view('customerGroups.index', compact('customerGroups'));
    }

    public function create()
    {
        return view('customerGroups.create');
    }

    // Storing Group and its customers
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'csv_file' => 'required|file|mimes:csv,txt',
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
                if (!isset($record['Facility Name']) || empty($record['Facility Name'])) {
                    throw new \Exception('Facility Name is required');
                }

                $customer = Customer::where('facility_name', $record['Facility Name'])->first();

                if (!$customer) {
                    // 2. Insert individual customer
                    $customer = Customer::create([
                        'facility_name'    => $record['Facility Name'] ?? '',
                        'client_name'      => $record['Client Name'] ?? '',
                        'contact_name'     => $record['Contact Name'] ?? '',
                        'email'            => $record['Email'] ?? '',
                        'contact_no'       => $record['Contact No'] ?? '',
                        'company_name'     => $record['Company Name'] ?? '',
                        'gstin'            => $record['GSTIN'] ?? '',
                        'pan'              => $record['PAN'] ?? '',
                        'gst_treatment'    => $record['GST Treatment'] ?? '',
                        'private_details'  => $record['Private Details'] ?? '',
                        'billing_address'  => $record['Billing Address'] ?? '',
                        'billing_country'  => $record['Billing Country'] ?? '',
                        'billing_state'    => $record['Billing State'] ?? '',
                        'billing_city'     => $record['Billing City'] ?? '',
                        'billing_zip'      => $record['Billing Zip'] ?? '',
                        'shipping_address' => $record['Shipping Address'] ?? '',
                        'shipping_country' => $record['Shipping Country'] ?? '',
                        'shipping_state'   => $record['Shipping State'] ?? '',
                        'shipping_city'    => $record['Shipping City'] ?? '',
                        'shipping_zip'     => $record['Shipping Zip'] ?? '',
                    ]);
                } else {
                    $customer->update([
                        'facility_name'    => $record['Facility Name'] ?? $customer->facility_name,
                        'client_name'      => $record['Client Name'] ?? $customer->client_name,
                        'contact_name'     => $record['Contact Name'] ?? $customer->contact_name,
                        'email'            => $record['Email'] ?? $customer->email,
                        'contact_no'       => $record['Contact No'] ?? $customer->contact_no,
                        'company_name'     => $record['Company Name'] ?? $customer->company_name,
                        'gstin'            => $record['GSTIN'] ?? $customer->gstin,
                        'pan'              => $record['PAN'] ?? $customer->pan,
                        'gst_treatment'    => $record['GST Treatment'] ?? $customer->gst_treatment,
                        'private_details'  => $record['Private Details'] ?? $customer->private_details,
                        'billing_address'  => $record['Billing Address'] ?? $customer->billing_address,
                        'billing_country'  => $record['Billing Country'] ?? $customer->billing_country,
                        'billing_state'    => $record['Billing State'] ?? $customer->billing_state,
                        'billing_city'     => $record['Billing City'] ?? $customer->billing_city,
                        'billing_zip'      => $record['Billing Zip'] ?? $customer->billing_zip,
                        'shipping_address' => $record['Shipping Address'] ?? $customer->shipping_address,
                        'shipping_country' => $record['Shipping Country'] ?? $customer->shipping_country,
                        'shipping_state'   => $record['Shipping State'] ?? $customer->shipping_state,
                        'shipping_city'    => $record['Shipping City'] ?? $customer->shipping_city,
                        'shipping_zip'     => $record['Shipping Zip'] ?? $customer->shipping_zip,
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

            // Log activity
            activity()
                ->performedOn($customerGroup)
                ->causedBy(Auth::user())
                ->withProperties(['attributes' => $customerGroup->toArray()])
                ->event('created')
                ->log('Customer Group created');

            return redirect()->route('customer.groups.index')->with('success', 'CSV file imported successfully. Group and customers created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: Please make sure the file has Facility Name column filled. ' . $e->getMessage()]);
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

        if ($customerGroup) {
            $oldAttributes = $customerGroup->getOriginal();

            $customerGroup->name = $request->name;
            $customerGroup->save();

            activity()
                ->performedOn($customerGroup)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $oldAttributes,
                    'new' => $customerGroup->getChanges(),
                ])
                ->event('updated')
                ->log('Customer Group updated');

            return redirect()->route('customer.groups.index')->with('success', 'Group Name Updated Successfully.');
        }

        return redirect()->route('customer.groups.index')->with('info', 'No changes made to Group Name.');
    }

    // Deleting Group of customers and its related customers
    public function destroy($id)
    {
        if (!$id) {
            return redirect()->route('customer.groups.index')->with('error', 'Invalid Group ID.');
        }
        try {
            $customerGroup = CustomerGroup::findOrFail($id);
            $customerGroup->delete();

            activity()
                ->performedOn($customerGroup)
                ->causedBy(Auth::user())
                ->event('deleted')
                ->log('Customer Group deleted');

            return redirect()->route('customer.groups.index')->with('success', 'Successfully Deleted Group');
        } catch (\Exception $e) {
            return redirect()->route('customer.groups.index')->with('error', 'You cannot delete this group. You have to delete sales orders first.');
        }
    }

    public function toggleStatus(Request $request)
    {
        $group = CustomerGroup::findOrFail($request->id);
        $group->status = $request->status;
        $group->save();

        if ($group) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->ids) {
            return redirect()->back()->with('error', 'No customer groups selected for deletion.');
        }

        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $deleted = CustomerGroup::destroy($ids);

            if ($deleted) {
                return redirect()->back()->with('success', 'Selected customer groups deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'No groups deleted.');
            }
        } catch (\Exception $e) {
            return redirect()->route('customer.groups.index')->with('error', 'You cannot delete this group. You have to delete sales orders first.');
        }
    }
}
