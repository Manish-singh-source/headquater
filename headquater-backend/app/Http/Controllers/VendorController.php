<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    //
    public function vendorList()
    {
        // $vendor = Vendor::all();
        $vendors = Vendor::paginate(10);
        return view('vendor.vendor', compact('vendors'));
    }

    public function createVendor()
    {
        return view('vendor.create-vendor');
    }

    public function addVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'phone' => 'required|min:10',
            'email' => 'required|email|unique:vendors,email',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vendor = new Vendor();
        $vendor->first_name = $request->firstName;
        $vendor->last_name = $request->lastName;
        $vendor->phone_number = $request->phone;
        $vendor->email = $request->email;
        $vendor->gst_number = $request->gstNo;
        $vendor->pan_number = $request->panNo;
        $vendor->address = $request->address;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->country = $request->country;
        $vendor->pin_code = $request->pinCode;
        $vendor->bank_account_number = $request->accountNo;
        $vendor->ifsc_number = $request->ifscCode;
        $vendor->bank_number = $request->bankName;
        $vendor->status = $request->status;
        $vendor->save();
        return redirect()->route('vendor')->with('success', 'Customer added successfully.');
    }

    public function editVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.edit-vendor', compact('vendor'));
    }

    public function updateVendor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'email' => 'required|email|unique:vendors,email,' . $id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->first_name = $request->firstName;
        $vendor->last_name = $request->lastName;
        $vendor->phone_number = $request->phone;
        $vendor->email = $request->email;
        $vendor->gst_number = $request->gstNo;
        $vendor->pan_number = $request->panNo;
        $vendor->address = $request->address;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->country = $request->country;
        $vendor->pin_code = $request->pinCode;
        $vendor->bank_account_number = $request->accountNo;
        $vendor->ifsc_number = $request->ifscCode;
        $vendor->bank_number = $request->bankName;
        $vendor->status = $request->status;
        $vendor->save();

        return redirect()->route('vendor')->with('success', 'Customer updated successfully.');
    }

    public function deleteVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor')->with('success', 'Customer deleted successfully.');
    }

    public function detailVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.vendor-details', compact('vendor'));
    }

    public function vendorOrderView()
    {
        return view('vendor.vendor-order-view');
    }
}
