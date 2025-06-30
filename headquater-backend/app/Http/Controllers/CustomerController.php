<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //
    public function customerList()
    {
        $customers = Customer::all();
        return view('customer.customers', compact('customers'));
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
            'phone' => 'required|min:10',
            'email' => 'required|email|unique:customers,email',
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
        $customer = Customer::with('shippingCountry')->with('billingCountry')->findOrFail($id);
        // dd($customer->country);
        return view('customer.customer-detail', compact('customer'));
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers')->with('success', 'Customer deleted successfully.');
    }
}
