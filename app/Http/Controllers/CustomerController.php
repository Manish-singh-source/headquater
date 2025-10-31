<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerGroupMember;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerController extends Controller
{
    //

    public function create($g_id)
    {
        $countries = Country::get();
        $states = State::get();
        $cities = City::get();

        return view('customer.create', ['group_id' => $g_id, 'countries' => $countries, 'states' => $states, 'cities' => $cities]);
    }

    public function storeBulk(Request $request, $g_id)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with($validator)->withInput();
        }

        $file = $request->file('csv_file');
        if (! $file) {
            return redirect()->back()->with('error', 'Please upload a CSV file.');
        }

        DB::beginTransaction();

        try {
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);

            $insertCount = 0;

            foreach ($reader->getRows() as $record) {
                // 1. Check if customer already exists
                // $existingCustomer = Customer::where('email', $record['Email'] ?? '')->first();
                // $keywords = preg_split('/[\s\-]+/', $record['Shipping Address'], -1, PREG_SPLIT_NO_EMPTY);
                // $query = DB::table('customers'); // your table

                // $query->where(function ($q) use ($keywords) {
                //     foreach ($keywords as $word) {
                //         $q->orWhere('shipping_address', 'like', "%{$word}%");
                //     }
                // });

                if (! isset($record['Facility Name']) || empty($record['Facility Name'])) {
                    throw new \Exception('Facility Name is required');
                }

                // $existingCustomer = $query->first();
                $existingCustomer = Customer::where('facility_name', $record['Facility Name'])->first();

                if ($existingCustomer) {
                    // Customer exists, you can choose to update or skip
                    CustomerGroupMember::create([
                        'customer_id' => $existingCustomer->id,
                        'customer_group_id' => $g_id,
                    ]);
                } else {
                    // 2. Insert individual customer
                    $customer = Customer::create([
                        'facility_name' => $record['Facility Name'] ?? '',
                        'client_name' => $record['Client Name'] ?? '',
                        'contact_name' => $record['Contact Name'] ?? '',
                        'email' => $record['Email'] ?? '',
                        'contact_no' => $record['Contact No'] ?? '',
                        'gstin' => $record['GSTIN'] ?? '',
                        'pan' => $record['PAN'] ?? '',
                        'company_name' => $record['Company Name'] ?? '',
                        'billing_address' => $record['Billing Address'] ?? '',
                        'billing_country' => $record['Billing Country'] ?? '',
                        'billing_state' => $record['Billing State'] ?? '',
                        'billing_city' => $record['Billing City'] ?? '',
                        'billing_zip' => $record['Billing Zip'] ?? '',
                        'shipping_address' => $record['Shipping Address'] ?? '',
                        'shipping_country' => $record['Shipping Country'] ?? '',
                        'shipping_state' => $record['Shipping State'] ?? '',
                        'shipping_city' => $record['Shipping City'] ?? '',
                        'shipping_zip' => $record['Shipping Zip'] ?? '',
                    ]);

                    // 3. Insert into customer_group_members
                    CustomerGroupMember::create([
                        'customer_id' => $customer->id,
                        'customer_group_id' => $g_id,
                    ]);
                }

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->with(['csv_file' => 'No valid data found in the CSV file.']);
            }

            DB::commit();
            activity()->log('Customer Group Created' . $g_id . ' with ' . $insertCount . ' customers.' . ' by ' . Auth::user()->name);

            return redirect()->route('customer.groups.index')->with('success', 'CSV file imported successfully. Group and customers created.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facility_name' => 'required|min:3',
            'client_name' => 'required|min:3',
            'contact_name' => 'required|min:3',
            'email' => 'required|email|unique:customers,email',
            'contact_no' => 'required|digits:10',
            'gstin' => 'required',
            'pan' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with($validator)->withInput();
        }

        $customer = new Customer;
        $customer->facility_name = $request->facility_name;
        $customer->client_name = $request->client_name;
        $customer->contact_name = $request->contact_name;
        $customer->email = $request->email;
        $customer->contact_no = $request->contact_no;
        $customer->company_name = $request->companyName;
        $customer->gstin = $request->gstin;
        $customer->pan = $request->pan;
        $customer->status = $request->status;
        $customer->shipping_address = $request->shippingAddress;
        $customer->shipping_country = $request->shippingCountry;
        $customer->shipping_state = $request->shippingState;
        $customer->shipping_city = $request->shippingCity;
        $customer->shipping_zip = $request->shippingPinCode;
        $customer->billing_address = $request->billingAddress;
        $customer->billing_country = $request->billingCountry;
        $customer->billing_state = $request->billingState;
        $customer->billing_city = $request->billingCity;
        $customer->billing_zip = $request->billingPinCode;
        $customer->save();

        $customerGrpMember = new CustomerGroupMember;
        $customerGrpMember->customer_group_id = $request->group_id;
        $customerGrpMember->customer_id = $customer->id;
        $customerGrpMember->save();

        if ($customer && $customerGrpMember) {
            return redirect()->route('customer.groups.view', $request->group_id)->with('success', 'Customer added successfully.');
        }

        return back()->with('error', 'Something Went Wrong.');
    }

    public function edit($id, $group_id)
    {
        $customer = Customer::findOrFail($id);

        return view('customer.edit', compact('customer', 'group_id'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'facility_name' => 'required',
            'client_name' => 'required|min:3',
            'contact_name' => 'required|min:3',
            'email' => 'required|email|unique:customers,email,' . $id,
            'contact_no' => 'required|digits:10',
            'gstin' => 'required',
            'pan' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with($validator)->withInput();
        }

        $customer = Customer::findOrFail($id);
        $customer->facility_name = $request->facility_name;
        $customer->client_name = $request->client_name;
        $customer->contact_name = $request->contact_name;
        $customer->email = $request->email;
        $customer->contact_no = $request->contact_no;
        $customer->company_name = $request->companyName;
        $customer->gstin = $request->gstin;
        $customer->pan = $request->pan;
        $customer->status = $request->status;
        $customer->shipping_address = $request->shippingAddress;
        $customer->shipping_country = $request->shippingCountry;
        $customer->shipping_state = $request->shippingState;
        $customer->shipping_city = $request->shippingCity;
        $customer->shipping_zip = $request->shippingPinCode;
        $customer->billing_address = $request->billingAddress;
        $customer->billing_country = $request->billingCountry;
        $customer->billing_state = $request->billingState;
        $customer->billing_city = $request->billingCity;
        $customer->billing_zip = $request->billingPinCode;
        $customer->save();

        // Update customer group membership
        return redirect()->route('customer.groups.view', $request->group_id)->with('success', 'Customer updated successfully.');
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }

    public function detail($id)
    {
        $customerDetails = Customer::with('groupInfo.customerGroup', 'orders.product', 'orders.tempOrder')->where('id', $id)->first();

        return view('customer.detail-view', compact('customerDetails'));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('user-profile', compact('user'));
    }

    public function updateuser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|min:3',
            'lname' => 'required|min:3',
            'email' => 'required|email|unique:staff,email,' . $id,
            'phone' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return back()->with($validator)->withInput();
        }

        $user = User::findOrFail($id);
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->pincode = $request->zip;

        $user->current_address = $request->current_address;
        // Handle profile image upload if needed
        if ($request->hasFile('profile_image')) {
            // Save the profile image logic here
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/images/profile'), $imageName);
            $user->profile_image = '/uploads/images/profile/' . $imageName; // Save the
        }
        $user->save();

        return redirect()->route('user-profile')->with('success', 'Profile updated successfully.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        $customerGroupMember = CustomerGroupMember::where('customer_group_id', $request->groupId)->whereIn('id', $ids)->delete();

        if ($customerGroupMember) {
            return redirect()->back()->with('success', 'Selected customers deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete selected customers.');
    }
}
