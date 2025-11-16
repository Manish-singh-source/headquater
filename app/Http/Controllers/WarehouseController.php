<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseFormRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('country', 'state', 'cities')->latest()->paginate(15);

        return view('warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();

        return view('warehouse.create', compact('countries', 'states', 'cities'));
    }

    public function store(WarehouseFormRequest $request)
    {
        $attributes = $this->warehouseAttributes($request);

        try {
            $warehouse = Warehouse::create($attributes);

            // Log activity
            activity()
                ->performedOn($warehouse)
                ->causedBy(Auth::user())
                ->withProperties($attributes)
                ->event('created')
                ->log('Warehouse created: '.$warehouse->name);

            return redirect()->route('warehouse.index')->with('success', 'Warehouse created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Warehouse not created.')->withInput();
        }
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();

        return view('warehouse.edit', compact('warehouse', 'countries', 'states', 'cities'));
    }

    public function update(WarehouseFormRequest $request, $id)
    {
        $attributes = $this->warehouseAttributes($request);

        try {
            $warehouse = Warehouse::findOrFail($id);
            $oldAttributes = $warehouse->getOriginal();
            $warehouse->update($attributes);

            // Log activity
            activity()
                ->performedOn($warehouse)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $oldAttributes,
                    'new' => $warehouse->getChanges(),
                ])
                ->event('updated')
                ->log('Warehouse updated: '.$warehouse->name);

            return redirect()->route('warehouse.index')->with('success', 'Warehouse updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Warehouse not updated.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $warehouse = Warehouse::findOrFail($id);

            // Optionally check for related products/stock before deletion
            if ($warehouse->warehouseStock()->exists()) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Warehouse not deleted. Please delete related products/stock first.');
            }

            $warehouse->delete();

            // Log activity
            activity()
                ->performedOn($warehouse)
                ->causedBy(Auth::user())
                ->event('deleted')
                ->log('Warehouse deleted: '.$warehouse->name);

            DB::commit();

            return redirect()->route('warehouse.index')->with('success', 'Warehouse deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong. Warehouse not deleted. Please delete related Products/Stock first.');
        }
    }

    public function view($id)
    {
        $warehouse = Warehouse::with('warehouseStock.product')->findOrFail($id);

        return view('warehouse.view', compact('warehouse'));
    }

    public function toggleStatus(Request $request)
    {
        $warehouse = Warehouse::findOrFail($request->id);
        $oldStatus = $warehouse->status;
        $warehouse->status = $request->status;
        $warehouse->save();

        // Log activity
        activity()
            ->performedOn($warehouse)
            ->causedBy(Auth::user())
            ->withProperties(['old_status' => $oldStatus, 'new_status' => $warehouse->status])
            ->event('status_changed')
            ->log('Warehouse status changed: '.$warehouse->name);

        return response()->json(['success' => true, 'status' => $warehouse->status]);
    }

    public function deleteSelected(Request $request)
    {
        // Normalize ids: accept array or comma-separated string
        $rawIds = $request->input('ids');
        if (is_string($rawIds)) {
            $ids = array_filter(array_map('trim', explode(',', $rawIds)), function ($v) {
                return $v !== '';
            });
        } elseif (is_array($rawIds)) {
            $ids = $rawIds;
        } else {
            $ids = [];
        }

        // Cast to integers and validate
        $ids = array_map('intval', $ids);
        $validator = \Illuminate\Support\Facades\Validator::make(['ids' => $ids], [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:warehouses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid warehouse IDs selected.');
        }

        try {
            DB::beginTransaction();

            // Get warehouses to check stock and log names
            $warehouses = Warehouse::whereIn('id', $ids)->get();
            $deleted = 0;
            $skipped = [];

            foreach ($warehouses as $warehouse) {
                if ($warehouse->warehouseStock()->exists()) {
                    $skipped[] = $warehouse->name;

                    continue;
                }

                // Log activity before deletion
                activity()
                    ->performedOn($warehouse)
                    ->causedBy(Auth::user())
                    ->withProperties(['id' => $warehouse->id, 'name' => $warehouse->name])
                    ->event('deleted')
                    ->log('Warehouse deleted in bulk operation: '.$warehouse->name);

                $warehouse->delete();
                $deleted++;
            }

            DB::commit();

            $message = 'Successfully deleted '.$deleted.' warehouse(s).';
            if (! empty($skipped)) {
                $message .= ' Skipped '.count($skipped).' warehouse(s) with existing stock: '.implode(', ', $skipped);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error deleting warehouses. Please ensure warehouses have no stock and try again.');
        }
    }

    /**
     * Helper to get warehouse attributes for store and update
     *
     * @param  Request|WarehouseFormRequest  $request
     * @return array
     */
    private function warehouseAttributes($request)
    {
        return [
            'name' => trim($request->name),
            'type' => $request->type,
            'contact_person_name' => trim($request->contact_person_name),
            'phone' => trim($request->contact_person_phone_no),
            'alt_phone' => trim($request->contact_person_alt_phone_no),
            'email' => strtolower(trim($request->contact_person_email)),
            'gst_number' => trim($request->gst_no),
            'pan_number' => trim($request->pan_no),
            'address_line_1' => trim($request->address_line_1),
            'address_line_2' => trim($request->address_line_2),
            'licence_doc' => $request->licence_doc,
            'max_storage_capacity' => $request->max_storage_capacity,
            'operations' => $request->supported_operations,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => trim($request->pincode),
            'status' => $request->status,
        ];
    }
}
