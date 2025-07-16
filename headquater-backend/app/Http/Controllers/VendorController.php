<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\TempOrder;
use App\Models\ManageOrder;
use Illuminate\Http\Request;
use App\Models\ManageCustomer;
use App\Models\TempOrderStatus;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    //
    public function index()
    {
        // $vendor = Vendor::all();
        $vendors = Vendor::get();
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'phone_number' => 'required|min:10',
            'email' => 'required|email|unique:vendors,email',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vendor = new Vendor();
        $vendor->first_name = $request->first_name;
        $vendor->last_name = $request->last_name;
        $vendor->phone_number = $request->phone_number;
        $vendor->email = $request->email;
        $vendor->gst_number = $request->gst_number;
        $vendor->pan_number = $request->pan_number;
        $vendor->address = $request->address;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->country = $request->country;
        $vendor->pin_code = $request->pin_code;
        $vendor->bank_account_number = $request->bank_account_number;
        $vendor->ifsc_number = $request->ifsc_number;
        $vendor->bank_number = $request->bank_number;
        $vendor->status = $request->status;
        $vendor->save();


        return redirect()->route('vendor.index')->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.edit',  compact('vendor'));
    }

    public function update(Request $request, $id)
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

        return redirect()->route('vendor.index')->with('success', 'Customer updated successfully.');
    }

    public function view($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.view', compact('vendor'));
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Customer deleted successfully.');
    }











    public function toggleStatus(Request $request)
    {
        $Vendor = Vendor::findOrFail($request->id);
        $Vendor->status = $request->status;
        $Vendor->save();

        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Vendor::destroy($ids);
        return redirect()->back()->with('success', 'Selected vendor deleted successfully.');
    }
    //
    public function vendorList()
    {
        // $vendor = Vendor::all();
        $vendors = Vendor::with('city')->get();
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
        $vendor = Vendor::with('orderDetail')->findOrFail($id);

        $orderIds = TempOrder::where('vendor_code', $vendor->vendor_code)
            ->select('order_id')
            ->distinct()
            ->pluck('order_id');

        $orderedProducts = TempOrder::where('vendor_code', $vendor->vendor_code)
            ->whereIn('order_id', $orderIds)
            ->get();

        $orders = ManageOrder::with('warehouse')->whereIn('id', $orderIds)->get();
        // dd($order);

        return view('vendor.vendor-details', compact('vendor', 'orders'));
    }

    public function vendorOrderView($id)
    {

        $order = ManageOrder::find($id);
        $vendorCodes = TempOrder::where('order_id', $id)
            ->select('vendor_code')
            ->distinct()
            ->pluck('vendor_code');
        $orders = TempOrder::with('vendorInfo')->where('order_id', $id)
            ->whereIn('vendor_code', $vendorCodes)
            ->get();
        $vendors = Vendor::whereIn('vendor_code', $vendorCodes)->get();
        // dd($orders);
        return view('vendor.vendor-order-view', compact('order', 'vendorCodes', 'orders', 'vendors'));
    }

    public function singleVendorOrderView($orderId, $vendorCode)
    {
        $vendor = Vendor::where('vendor_code', $vendorCode)->first();
        // dd($vendor);
        $order = ManageOrder::find($orderId);
        // $vendorCodes = TempOrder::where('order_id', $id)
        //     ->select('vendor_code')
        //     ->distinct()
        //     ->pluck('vendor_code');
        $orders = TempOrder::where('order_id', $orderId)
            ->where('vendor_code', $vendorCode)
            ->get();
        // dd($orders);
        // $vendors = Vendor::whereIn('vendor_code', $vendorCodes)->get();
        return view('vendor.single-vendor-order-view', compact('orders', 'vendor', 'order'));
    }
}
