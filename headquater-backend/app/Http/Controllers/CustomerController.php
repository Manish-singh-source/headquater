<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\CustomerGroupMember;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function detail($id)
    {
        $customerDetails = Customer::with('groupInfo.customerGroup', 'orders.product')->where('id', $id)->first();
        // dd($customerDetails);
        // $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->findOrFail($id);
        return view('customer.detail-view', compact('customerDetails'));
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



    public function Customercount()
    {
        $customersCount = Customer::count();
        return view('index', compact('customersCount'));
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

    public function editCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit-customer', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'phone' => 'required|min:10',
            'email' => 'required|email|unique:customers,email,' . $id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::findOrFail($id);
        $customer->first_name = $request->firstName;
        $customer->last_name = $request->lastName;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->company_name = $request->companyName;
        $customer->gst_number = $request->gstNo;
        $customer->pan_number = $request->panNo;
        $customer->shipping_address = $request->shippingAddress;
        $customer->shipping_country = $request->shippingCountry;
        $customer->shipping_state = $request->shippingState;
        $customer->shipping_city = $request->shippingCity;
        $customer->shipping_pincode = $request->shippingPinCode;
        $customer->billing_address = $request->billingAddress;
        $customer->billing_country = $request->billingCountry;
        $customer->billing_state = $request->billingState;
        $customer->billing_city = $request->billingCity;
        $customer->billing_pincode = $request->billingPinCode;
        $customer->status = $request->status;
        $customer->save();

        return redirect()->route('customers')->with('success', 'Customer updated successfully.');
    }

    public function detailCustomer($id)
    {
        $customer = Customer::where('group_id', $id)->get();

        return view('customer.customer-detail', compact('customer'));
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }
}
