<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Staff;
use App\Models\State;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\CustomerAddress;
use App\Models\SalesOrderProduct;
use App\Models\CustomerGroupMember;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        return view('index', compact('customersCount', 'vendorsCount', 'salesOrdersCount', 'purchaseOrdersCount', 'productsCount', 'warehouseCount', 'readyToShipOrdersCount', 'readyToPackageOrdersCount'));
    }


    public function create($g_id)
    {
        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        return view('customer.create', ['group_id' => $g_id, 'countries' => $countries, 'states' => $states, 'cities' => $cities]);
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
        // dd($customer);
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
        $customerDetails = Customer::with('groupInfo.customerGroup', 'orders.product')->where('id', $id)->first();
        // dd($customerDetails);
        // $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->findOrFail($id);
        return view('customer.detail-view', compact('customerDetails'));
    }

    public function profile()
    {
        $user = Auth::user();
        // $user = auth()->user();
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
        $user->current_address = $request->current_address;
        // Handle profile image upload if needed
        if ($request->hasFile('profile_image')) {
            // Save the profile image logic here
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/images/profile'), $imageName);
            $user->profile_image = '/uploads/images/profile/' . $imageName; // Save the 
            dd($user->profile_image);
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
        CustomerGroup::destroy($ids);
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
