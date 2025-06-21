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
        $vendor = Vendor::all();
        return view('vendor', compact('vendor'));
    }

    public function createVendor()
    {
        return view('create-vendor');
    }

    public function addVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'phone' => 'required|min:10',
            'email' => 'required|email|unique:users,email',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vendor = new Vendor();
        $vendor->first_name = $request->firstName;
        $vendor->last_name = $request->lastName;
        $vendor->phone = $request->phone;
        $vendor->email = $request->email;
        $vendor->gst_no = $request->gst_no;
        $vendor->pan_no = $request->pan_no;
        $vendor->address = $request->address;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->pin_code = $request->pinCode;
        $vendor->account_no = $request->accountNo;
        $vendor->ifsc_code = $request->ifscCode;
        $vendor->bank_name = $request->bankName;
        $vendor->status = $request->status;
        $vendor->save();
        return redirect()->route('vendor')->with('success', 'Customer added successfully.');
    }

    public function editVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('edit-vendor', compact('vendor'));
    }

    public function updateVendor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->first_name = $request->firstName;
        $vendor->last_name = $request->lastName;
        $vendor->phone = $request->phone;
        $vendor->email = $request->email;
        $vendor->gst_no = $request->gst_no;
        $vendor->pan_no = $request->pan_no;
        $vendor->address = $request->address;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->pin_code = $request->pinCode;
        $vendor->account_no = $request->accountNo;
        $vendor->ifsc_code = $request->ifscCode;
        $vendor->bank_name = $request->bankName;
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
        return view('vendor-details', compact('vendor'));
    }

    public function vendorDetails()
    {
        return view('vendor-details');
    }

    public function vendorOrderView()
    {
        return view('vendor-order-view');
    }
}
