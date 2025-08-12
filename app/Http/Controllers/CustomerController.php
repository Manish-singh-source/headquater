<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Staff;
use App\Models\State;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\PurchaseOrder;
use App\Models\CustomerAddress;
use App\Models\SalesOrderProduct;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerGroupMember;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerController extends Controller
{

    public function index()
    {
        $customersCount = Customer::count();
        $vendorsCount = Vendor::count();
        $salesOrdersCount = SalesOrderProduct::count();
        $purchaseOrdersCount = PurchaseOrderProduct::count();
        $productsCount = Product::count();
        $warehouseCount = Warehouse::count();
        $readyToShipOrdersCount = SalesOrder::where('status', 'ready_to_ship')->count();
        $readyToPackageOrdersCount = SalesOrder::where('status', 'ready_to_package')->count();

        // Recent Purchase Orders (Vendor) 
        $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')->limit(4)->latest()->get();
        $vendorCodes = $purchaseOrders->flatMap(function ($po) {
            return $po->purchaseOrderProducts->pluck('vendor_code');
        })->unique()->values();

        // Recent Sales Orders (Cutomer)
        $orders = SalesOrder::with('customerGroup')->limit(4)->latest()->get();

        // Recent Packaging List
        $packagingOrders = SalesOrder::where('status', 'ready_to_package')->with('customerGroup')->limit(4)->latest()->get();

        // Recent Ready To Ship Orders
        $readyToShipOrders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->limit(4)->latest()->get();

        // Invoices Lists
        $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder'])->limit(4)->latest()->get();

        return view('index', compact('purchaseOrders', 'vendorCodes', 'orders', 'packagingOrders', 'readyToShipOrders', 'invoices', 'customersCount', 'vendorsCount', 'salesOrdersCount', 'purchaseOrdersCount', 'productsCount', 'warehouseCount', 'readyToShipOrdersCount', 'readyToPackageOrdersCount'));
    }


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
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        DB::beginTransaction();

        try {
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
                    'customer_group_id' => $g_id,
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|min:3',
            'contact_name' => 'required|min:3',
            'email' => 'required|email|unique:customers,email',
            'contact_no' => 'required|digits:10',
            'gstin' => 'required',
            'pan' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = new Customer();
        $customer->client_name = $request->client_name;
        $customer->contact_name = $request->contact_name;
        $customer->email = $request->email;
        $customer->contact_no = $request->contact_no;
        $customer->company_name = $request->companyName;
        $customer->gstin = $request->gstin;
        $customer->pan = $request->pan;
        $customer->status = $request->status;
        $customer->save();

        $customerAddress = new CustomerAddress();
        $customerAddress->customer_id = $customer->id;
        $customerAddress->shipping_address = $request->shippingAddress;
        $customerAddress->shipping_country = $request->shippingCountry;
        $customerAddress->shipping_state = $request->shippingState;
        $customerAddress->shipping_city = $request->shippingCity;
        $customerAddress->shipping_zip = $request->shippingPinCode;
        $customerAddress->billing_address = $request->billingAddress;
        $customerAddress->billing_country = $request->billingCountry;
        $customerAddress->billing_state = $request->billingState;
        $customerAddress->billing_city = $request->billingCity;
        $customerAddress->billing_zip = $request->billingPinCode;
        $customerAddress->save();

        $customerGrpMember = new CustomerGroupMember();
        $customerGrpMember->customer_group_id = $request->group_id;
        $customerGrpMember->customer_id  = $customer->id;
        $customerGrpMember->save();

        if ($customerAddress) {
            return redirect()->route('customer.groups.view', $request->group_id)->with('success', 'Customer added successfully.');
        }
        return back()->with('error', 'Something Went Wrong.');
    }

    public function edit($id, $group_id)
    {
        $customer = Customer::with('addresses')->findOrFail($id);
        return view('customer.edit', compact('customer', 'group_id'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'client_name' => 'required|min:3',
            'contact_name' => 'required|min:3',
            'email' => 'required|email|unique:customers,email,' . $id,
            'contact_no' => 'required|digits:10',
            'gstin' => 'required',
            'pan' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::findOrFail($id);
        $customer->client_name = $request->client_name;
        $customer->contact_name = $request->contact_name;
        $customer->email = $request->email;
        $customer->contact_no = $request->contact_no;
        $customer->company_name = $request->companyName;
        $customer->gstin = $request->gstin;
        $customer->pan = $request->pan;
        $customer->status = $request->status;
        $customer->save();

        $customerAddress = CustomerAddress::where('customer_id', $id)->first();
        $customerAddress->customer_id = $customer->id;
        $customerAddress->shipping_address = $request->shippingAddress;
        $customerAddress->shipping_country = $request->shippingCountry;
        $customerAddress->shipping_state = $request->shippingState;
        $customerAddress->shipping_city = $request->shippingCity;
        $customerAddress->shipping_zip = $request->shippingPinCode;
        $customerAddress->billing_address = $request->billingAddress;
        $customerAddress->billing_country = $request->billingCountry;
        $customerAddress->billing_state = $request->billingState;
        $customerAddress->billing_city = $request->billingCity;
        $customerAddress->billing_zip = $request->billingPinCode;
        $customerAddress->save();

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
        $customerDetails = Customer::with('groupInfo.customerGroup', 'orders.product', 'address')->where('id', $id)->first();
        // $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->findOrFail($id);
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
            return back()->withErrors($validator)->withInput();
        }

        $user = Staff::findOrFail($id);
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

    public function toggleStatus(Request $request)
    {
        $customer = CustomerGroup::findOrFail($request->id);
        $customer->status = $request->status;
        $customer->save();

        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Customer::destroy($ids);
        return redirect()->back()->with('success', 'Selected customers deleted successfully.');
    }




    // public function userview()
    // {
    //     $user = Auth::user();
    //     // $user = auth()->user();
    //     return view('user-profile', compact('user'));
    // }

    // public function Customercount()
    // {
    //     $customersCount = Customer::count();
    //     return view('index', compact('customersCount'));
    // }

    // public function detailCustomer($id)
    // {
    //     $customer = Customer::where('group_id', $id)->get();

    //     return view('customer.customer-detail', compact('customer'));
    // }
}
