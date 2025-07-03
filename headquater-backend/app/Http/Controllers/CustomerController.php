<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerGroups;
use App\Imports\CustomersImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //
    public function customerList()
    {
        $customerGroups = CustomerGroups::paginate(10);
        return view('customer.customers', compact('customerGroups'));
    }

    public function customerGroupDetail($id)
    {
        $customers = Customer::where('group_id', $id)->get();
        return view('customer.customer-group-detail', compact('customers'));
    }

    public function  downloadCustomersCSV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required',
            'customer_po_excel' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        DB::beginTransaction();
        $customerGroup = new CustomerGroups();
        $customerGroup->group_name = $request->group_name;
        $customerGroup->save();

        $file = $request->file('customer_po_excel');

        if ($file) {
            // Get the original file name
            $fileName = $file->getClientOriginalName();
            // Import the file
            Excel::queueImport(new CustomersImport, $file);
            DB::commit();
            
            return redirect()->route('customers')->with('success', 'All good!');
        }

        DB::rollBack();
        return redirect()->back()->with('error', 'No file uploaded.');
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
