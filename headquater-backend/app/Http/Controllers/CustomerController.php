<?php

namespace App\Http\Controllers;

use Log;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\CustomerGroups;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ProcessCustomerExcelJob;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //
    public function groupsList()
    {
        $customers = CustomerGroup::get();
        return view('customer.customers', compact('customers'));
    }

    public function customersList($id)
    {
        $group = CustomerGroup::findOrFail($id);
        $customers = Customer::where('group_id', $id)->get();
        return view('customer.customers-list', compact('customers', 'group'));
    }

    public function Customercount()
    {
        $customersCount = Customer::count();
        return view('index', compact('customersCount'));
    }


    public function addCustomer(Request $request)
    {
        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        return view('customer.add-customer', ['countries' => $countries, 'states' => $states, 'cities' => $cities]);
    }


    public function storeCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'phone' => 'required|digits:10',
            'email' => 'required|email|unique:customers,email',
            'companyName' => 'required',
            'gstNo' => 'required',
            'panNo' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = new Customer();
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

        return redirect()->route('customers')->with('success', 'Customer added successfully.');
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
        // dd($customer);
        return view('customer.customer-detail', compact('customer'));
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers')->with('success', 'Customer deleted successfully.');
    }
   
    public function deleteCustomerGroup($id)
    {
        $customer = CustomerGroup::findOrFail($id);
        $customer->delete();

        return redirect()->route('groups')->with('success', 'Customer deleted successfully.');
    }
}
