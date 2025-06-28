<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    //
    public function warehouseList()
    {
        $warehouses = Warehouse::with('country')->with('state')->with('cities')->get();
        return view('warehouse.warehouse', ['warehouses' => $warehouses]);
    }

    public function createWarehouse()
    {
        return view('warehouse.create-warehouse');
    }

    public function storeWarehouse(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'warehouse_name' => 'required|min:3',
                'warehouse_type' => 'required',
                'contact_person_name' => 'required|min:3',
                'contact_person_phone_no' => 'required|digits:10',
                'contact_person_alt_phone_no' => 'required|digits:10',
                'contact_person_email' => 'required',
                'address_line_1' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $validator->failed();
            return redirect()->route('register')->withErrors($validator);
        }

        $warehouse = new Warehouse();
        $warehouse->name = $request->warehouse_name;
        $warehouse->type = $request->warehouse_type;
        $warehouse->contact_person_name = $request->contact_person_name;
        $warehouse->phone = $request->contact_person_phone_no;
        $warehouse->alt_phone = $request->contact_person_alt_phone_no;
        $warehouse->email = $request->contact_person_email;
        $warehouse->gst_number = $request->gst_no;
        $warehouse->pan_number = $request->pan_no;
        $warehouse->address_line_1 = $request->address_line_1;
        $warehouse->address_line_2 = $request->address_line_2;
        $warehouse->licence_doc = $request->licence_doc;
        $warehouse->max_storage_capacity = $request->max_storage_capacity;
        $warehouse->operations = $request->supported_operations;
        $warehouse->city = $request->city;
        $warehouse->state = $request->state;
        $warehouse->country = $request->country;
        $warehouse->pincode = $request->pincode;
        $warehouse->status = $request->status;
        // $warehouse->default_warehouse = $request->default_warehouse == 'on' ? 'yes' : 'no';
        $warehouse->save();

        return redirect()->route('warehouse');
    }


    public function warehouseDetail($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouse.warehouse-detail', ['warehouse' => $warehouse]);
    }

    public function warehouseEdit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouse.edit-warehouse', ['warehouse' => $warehouse]);
    }

    public function warehouseUpdate(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'warehouse_name' => 'required|min:3',
                'warehouse_type' => 'required',
                'contact_person_name' => 'required|min:3',
                'contact_person_phone_no' => 'required|digits:10',
                'contact_person_alt_phone_no' => 'required|digits:10',
                'contact_person_email' => 'required',
                'address_line_1' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $validator->failed();
            return redirect()->route('register')->withErrors($validator);
        }

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->name = $request->warehouse_name;
        $warehouse->type = $request->warehouse_type;
        $warehouse->contact_person_name = $request->contact_person_name;
        $warehouse->phone = $request->contact_person_phone_no;
        $warehouse->alt_phone = $request->contact_person_alt_phone_no;
        $warehouse->email = $request->contact_person_email;
        $warehouse->gst_number = $request->gst_no;
        $warehouse->pan_number = $request->pan_no;
        $warehouse->address_line_1 = $request->address_line_1;
        $warehouse->address_line_2 = $request->address_line_2;
        $warehouse->licence_doc = $request->licence_doc;
        $warehouse->max_storage_capacity = $request->max_storage_capacity;
        $warehouse->operations = $request->supported_operations;
        $warehouse->city = $request->city;
        $warehouse->state = $request->state;
        $warehouse->country = $request->country;
        $warehouse->pincode = $request->pincode;
        $warehouse->status = $request->status;
        // $warehouse->default_warehouse = $request->default_warehouse ? 'yes' : 'no';
        $warehouse->save();

        return redirect()->route('warehouse');
    }


    public function deleteWarehouse($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return redirect()->route('warehouse')->with('success', 'Customer deleted successfully.');
    }
}
