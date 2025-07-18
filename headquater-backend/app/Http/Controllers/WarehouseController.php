<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseFormRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    //

    public function index()
    {
        $warehouses = Warehouse::with('country')->with('state')->with('cities')->get();
        return view('warehouse.index', compact('warehouses'));
    }


    public function create()
    {
        return view('warehouse.create');
    }

    public function store(WarehouseFormRequest $request)
    {
        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->type = $request->type;
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
        $warehouse->country_id = $request->country_id;
        $warehouse->state_id = $request->state_id;
        $warehouse->city_id = $request->city_id;
        $warehouse->pincode = $request->pincode;
        $warehouse->status = $request->status;
        $warehouse->save();

        if (!$warehouse) {
            return redirect()->back()->with('error', 'Something Went Wrong. Warehouse Not Created');
        }

        return redirect()->route('warehouse.index')->with('success', 'Warehouse Created Successfully');
    }

    public function view($id)
    {
        $warehouse = Warehouse::with('warehouseStock.product')->findOrFail($id);
        return view('warehouse.view', ['warehouse' => $warehouse]);
    }

    public function toggleStatus(Request $request)
    {
        $warehouse = Warehouse::findOrFail($request->id);
        $warehouse->status = $request->status;
        $warehouse->save();

        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Warehouse::destroy($ids);
        return redirect()->back()->with('success', 'Selected customers deleted successfully.');
    }


    public function warehouseDetail($id)
    {
        $warehouse = Warehouse::with('stocks.product')->findOrFail($id);
        return view('warehouse.warehouse-detail', ['warehouse' => $warehouse]);
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouse.edit', ['warehouse' => $warehouse]);
    }

    public function update(WarehouseFormRequest $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->name = $request->name;
        $warehouse->type = $request->type;
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
        $warehouse->country_id = $request->country_id;
        $warehouse->state_id = $request->state_id;
        $warehouse->city_id = $request->city_id;
        $warehouse->pincode = $request->pincode;
        $warehouse->status = $request->status;
        $warehouse->save();

        if (!$warehouse) {
            return redirect()->back()->with('error', 'Something Went Wrong. Warehouse Not Created');
        }

        return redirect()->route('warehouse.index')->with('success', 'Warehouse Created Successfully');
    }


    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return redirect()->route('warehouse.index')->with('success', 'Customer deleted successfully.');
    }
}
