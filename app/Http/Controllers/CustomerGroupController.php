<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\CustomerGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerGroupController extends Controller
{
    /**
     * Display customer groups list with statistics
     */
    public function index(Request $request)
    {
        try {
            $status = $request->get('status', 'all');

            $query = CustomerGroup::with(['customerGroupMembers.customer']);

            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'inactive') {
                $query->inactive();
            }

            $customerGroups = $query->get();

            // Calculate statistics for each group
            $customerGroups->each(function ($group) {
                $customers = $group->customerGroupMembers->pluck('customer');
                $group->total_customers = $customers->count();
                $group->active_customers = $customers->where('status', '1')->count();
                $group->inactive_customers = $customers->where('status', '0')->count();
            });

            return view('customerGroups.index', compact('customerGroups', 'status'));
        } catch (\Exception $e) {
            Log::error('Error loading customer groups: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading customer groups: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('customerGroups.create');
    }

    /**
     * Store customer group with customers from Excel file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'csv_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->with('error', 'Please upload a file.')->withInput();
        }

        DB::beginTransaction();

        try {
            // Create the customer group
            $customerGroup = CustomerGroup::create([
                'name' => $request->name,
                'status' => '1',
            ]);

            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);

            $insertCount = 0;
            $existingCount = 0;

            foreach ($reader->getRows() as $record) {
                if (!isset($record['Facility Name']) || empty($record['Facility Name'])) {
                    continue; // Skip rows without facility name
                }

                $customer = Customer::where('facility_name', $record['Facility Name'])->first();

                if (!$customer) {
                    // Create new customer
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
                        'status'           => '1',
                    ]);
                } else {
                    $existingCount++;
                }

                // Check if customer is already in this group
                $existingMember = CustomerGroupMember::where('customer_id', $customer->id)
                    ->where('customer_group_id', $customerGroup->id)
                    ->first();

                if (!$existingMember) {
                    CustomerGroupMember::create([
                        'customer_id' => $customer->id,
                        'customer_group_id' => $customerGroup->id,
                    ]);
                    $insertCount++;
                }
            }

            if ($insertCount === 0 && $existingCount === 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No valid data found in the file.')->withInput();
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($customerGroup)
                ->causedBy(Auth::user())
                ->log('Customer Group created with ' . $insertCount . ' customers');

            $message = "Customer group created successfully. Added {$insertCount} customers.";
            if ($existingCount > 0) {
                $message .= " {$existingCount} customers already existed.";
            }

            return redirect()->route('customer.groups.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating customer group: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * View customer group with statistics
     */
    public function view($id)
    {
        try {
            $customerGroup = CustomerGroup::with('customerGroupMembers.customer')->findOrFail($id);

            // Calculate statistics
            $customers = $customerGroup->customerGroupMembers->pluck('customer');
            $customerGroup->total_customers = $customers->count();
            $customerGroup->active_customers = $customers->where('status', '1')->count();
            $customerGroup->inactive_customers = $customers->where('status', '0')->count();

            return view('customerGroups.view', compact('customerGroup'));
        } catch (\Exception $e) {
            Log::error('Error viewing customer group: ' . $e->getMessage());
            return redirect()->route('customer.groups.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $customerGroup = CustomerGroup::findOrFail($id);
            return view('customerGroups.edit', compact('customerGroup'));
        } catch (\Exception $e) {
            Log::error('Error loading customer group: ' . $e->getMessage());
            return redirect()->route('customer.groups.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update customer group
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $customerGroup = CustomerGroup::findOrFail($id);

            $customerGroup->name = $request->name;
            $customerGroup->save();

            DB::commit();

            activity()
                ->performedOn($customerGroup)
                ->causedBy(Auth::user())
                ->log('Customer Group updated');

            return redirect()->route('customer.groups.index')->with('success', 'Customer group updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating customer group: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete customer group
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:customer_groups,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('customer.groups.index')->with('error', 'Invalid customer group.');
        }

        DB::beginTransaction();

        try {
            $customerGroup = CustomerGroup::findOrFail($id);
            $customerGroup->delete();

            DB::commit();

            activity()
                ->performedOn($customerGroup)
                ->causedBy(Auth::user())
                ->log('Customer Group deleted');

            return redirect()->route('customer.groups.index')->with('success', 'Customer group deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting customer group: ' . $e->getMessage());
            return redirect()->route('customer.groups.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle customer group status
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:customer_groups,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 422);
        }

        DB::beginTransaction();

        try {
            $group = CustomerGroup::findOrFail($request->id);
            $group->status = $request->status;
            $group->save();

            DB::commit();

            activity()
                ->performedOn($group)
                ->causedBy(Auth::user())
                ->log('Customer Group status changed to ' . ($request->status == '1' ? 'Active' : 'Inactive'));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling customer group status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete multiple customer groups
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'No customer groups selected.');
        }

        DB::beginTransaction();

        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'No customer groups selected.');
            }

            $deleted = CustomerGroup::destroy($ids);

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->log('Deleted ' . $deleted . ' customer groups');

            if ($deleted > 0) {
                return redirect()->back()->with('success', "Successfully deleted {$deleted} customer group(s).");
            } else {
                return redirect()->back()->with('error', 'No groups deleted.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting customer groups: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Change status of multiple customer groups
     */
    public function bulkStatusChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid data provided.');
        }

        DB::beginTransaction();

        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'No customer groups selected.');
            }

            $updated = CustomerGroup::whereIn('id', $ids)->update(['status' => $request->status]);

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->log('Changed status of ' . $updated . ' customer groups to ' . ($request->status == '1' ? 'Active' : 'Inactive'));

            return redirect()->back()->with('success', "Successfully updated status of {$updated} customer group(s).");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing customer group status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
