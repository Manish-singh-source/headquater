<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $status = $request->query('status');

            $vendors = Vendor::query();

            if (!is_null($status)) {
                $status = (int)$status;

                if ($status === 1) {
                    $vendors->active();
                } elseif ($status === 0) {
                    $vendors->inactive();
                }
            }

            $vendors = $vendors->latest()
                ->withCount('orders')
                ->with('shippingState', 'shippingCity', 'billingCountry', 'billingState', 'billingCity')
                ->paginate(15);

            return view('vendor.index', compact('vendors', 'status'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving vendors: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new vendor
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vendor.create');
    }

    /**
     * Store a newly created vendor
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_code' => 'required|string|max:255|unique:vendors,vendor_code',
            'client_name' => 'required|string|min:3|max:255',
            'contact_name' => 'required|string|min:3|max:255',
            'phone_number' => 'required|regex:/^[0-9]{10,}$/',
            'email' => 'required|string|email|max:255|unique:vendors,email',
            'gst_number' => 'nullable|string|max:15',
            'gst_treatment' => 'nullable|string|max:255',
            'pan_number' => 'nullable|string|max:10',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_zip' => 'nullable|string|max:10',
            'billing_address' => 'nullable|string|max:500',
            'billing_country' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'billing_zip' => 'nullable|string|max:10',
            'status' => 'nullable|in:0,1',
        ], [
            'phone_number.regex' => 'Phone number must be at least 10 digits.',
            'vendor_code.unique' => 'This vendor code already exists.',
            'email.unique' => 'This email is already registered.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $vendor = Vendor::create([
                'vendor_code' => trim($request->vendor_code),
                'client_name' => trim($request->client_name),
                'contact_name' => trim($request->contact_name),
                'phone_number' => trim($request->phone_number),
                'email' => strtolower(trim($request->email)),
                'gst_number' => trim($request->gst_number ?? ''),
                'gst_treatment' => trim($request->gst_treatment ?? ''),
                'pan_number' => trim($request->pan_number ?? ''),
                'shipping_address' => trim($request->shipping_address ?? ''),
                'shipping_country' => trim($request->shipping_country ?? ''),
                'shipping_state' => trim($request->shipping_state ?? ''),
                'shipping_city' => trim($request->shipping_city ?? ''),
                'shipping_zip' => trim($request->shipping_zip ?? ''),
                'billing_address' => trim($request->billing_address ?? ''),
                'billing_country' => trim($request->billing_country ?? ''),
                'billing_state' => trim($request->billing_state ?? ''),
                'billing_city' => trim($request->billing_city ?? ''),
                'billing_zip' => trim($request->billing_zip ?? ''),
                'status' => $request->status ?? '1', // Active by default
            ]);

            DB::commit();

            // Log activity
            activity()
                ->performedOn($vendor)
                ->causedBy(Auth::user())
                ->withProperties(['vendor_code' => $vendor->vendor_code])
                ->event('created')
                ->log('Vendor created: ' . $vendor->client_name);

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor "' . $vendor->client_name . '" added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error creating vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a vendor
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:vendors,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('vendor.index')->with('error', 'Vendor not found.');
            }

            $vendor = Vendor::findOrFail($id);

            return view('vendor.edit', compact('vendor'));
        } catch (\Exception $e) {
            return redirect()->route('vendor.index')
                ->with('error', 'Error loading vendor: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified vendor
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vendor_code' => 'required|string|max:255|unique:vendors,vendor_code,' . $id,
            'client_name' => 'required|string|min:3|max:255',
            'contact_name' => 'required|string|min:3|max:255',
            'phone_number' => 'required|regex:/^[0-9]{10,}$/',
            'email' => 'required|string|email|max:255|unique:vendors,email,' . $id,
            'gst_number' => 'nullable|string|max:15',
            'gst_treatment' => 'nullable|string|max:255',
            'pan_number' => 'nullable|string|max:10',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_zip' => 'nullable|string|max:10',
            'billing_address' => 'nullable|string|max:500',
            'billing_country' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'billing_zip' => 'nullable|string|max:10',
            'status' => 'nullable|in:0,1',
        ], [
            'phone_number.regex' => 'Phone number must be at least 10 digits.',
            'vendor_code.unique' => 'This vendor code already exists.',
            'email.unique' => 'This email is already registered.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $vendor = Vendor::findOrFail($id);

            if (!$vendor) {
                return redirect()->route('vendor.index')->with('error', 'Vendor not found.');
            }

            $oldAttributes = $vendor->getOriginal();

            $vendor->update([
                'vendor_code' => trim($request->vendor_code),
                'client_name' => trim($request->client_name),
                'contact_name' => trim($request->contact_name),
                'phone_number' => trim($request->phone_number),
                'email' => strtolower(trim($request->email)),
                'gst_number' => trim($request->gst_number ?? ''),
                'gst_treatment' => trim($request->gst_treatment ?? ''),
                'pan_number' => trim($request->pan_number ?? ''),
                'shipping_address' => trim($request->shipping_address ?? ''),
                'shipping_country' => trim($request->shipping_country ?? ''),
                'shipping_state' => trim($request->shipping_state ?? ''),
                'shipping_city' => trim($request->shipping_city ?? ''),
                'shipping_zip' => trim($request->shipping_zip ?? ''),
                'billing_address' => trim($request->billing_address ?? ''),
                'billing_country' => trim($request->billing_country ?? ''),
                'billing_state' => trim($request->billing_state ?? ''),
                'billing_city' => trim($request->billing_city ?? ''),
                'billing_zip' => trim($request->billing_zip ?? ''),
                'status' => $request->status ?? '1',
            ]);

            DB::commit();

            // Log activity
            activity()
                ->performedOn($vendor)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $oldAttributes,
                    'new' => $vendor->getChanges(),
                ])
                ->event('updated')
                ->log('Vendor updated: ' . $vendor->client_name);

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor "' . $vendor->client_name . '" updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified vendor
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:vendors,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('vendor.index')->with('error', 'Vendor not found.');
            }

            $vendor = Vendor::with('orders.purchaseOrderProducts')
                ->withCount('orders')
                ->with('shippingState', 'shippingCity', 'billingCountry', 'billingState', 'billingCity')
                ->findOrFail($id);

            return view('vendor.view', compact('vendor'));
        } catch (\Exception $e) {
            return redirect()->route('vendor.index')
                ->with('error', 'Error loading vendor details: ' . $e->getMessage());
        }
    }

    /**
     * View a single vendor order
     *
     * @param int $purchaseOrderId
     * @param string $vendorCode
     * @return \Illuminate\View\View
     */
    public function singleVendorOrderView($purchaseOrderId, $vendorCode)
    {
        try {
            $validator = Validator::make([
                'purchase_order_id' => $purchaseOrderId,
                'vendor_code' => $vendorCode,
            ], [
                'purchase_order_id' => 'required|integer|min:1',
                'vendor_code' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid purchase order or vendor code.');
            }

            $vendor = Vendor::where('vendor_code', trim($vendorCode))
                ->firstOrFail();

            $orders = $vendor->orders()
                ->where('id', $purchaseOrderId)
                ->with('purchaseOrderProducts')
                ->get();

            if ($orders->isEmpty()) {
                return redirect()->back()->with('error', 'No orders found for this vendor.');
            }

            return view('vendor.single-vendor-order-view', compact('orders', 'vendor'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading order: ' . $e->getMessage());
        }
    }

    /**
     * Delete a vendor
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:vendors,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('vendor.index')->with('error', 'Vendor not found.');
        }

        DB::beginTransaction();

        try {
            $vendor = Vendor::findOrFail($id);

            if (!$vendor) {
                return redirect()->route('vendor.index')->with('error', 'Vendor not found.');
            }

            // Check if vendor has orders
            $ordersCount = $vendor->orders()->count();
            if ($ordersCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete vendor with ' . $ordersCount . ' associated order(s).');
            }

            $vendorName = $vendor->client_name;

            // Log activity before deletion
            activity()
                ->performedOn($vendor)
                ->causedBy(Auth::user())
                ->withProperties(['vendor_code' => $vendor->vendor_code])
                ->event('deleted')
                ->log('Vendor deleted: ' . $vendorName);

            $vendor->delete();

            DB::commit();

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor "' . $vendorName . '" deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting vendor: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple vendors
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        // Normalize ids: accept either array (ids[]=1&ids[]=2) or comma-separated string (ids=1,2,3)
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

        // Re-validate normalized ids
        $validator = Validator::make(['ids' => $ids], [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:vendors,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid vendor IDs selected.');
        }

        DB::beginTransaction();

        try {
            $vendors = Vendor::whereIn('id', $ids)->get();

            $deletedCount = 0;
            $skippedCount = 0;

            foreach ($vendors as $vendor) {
                $ordersCount = $vendor->orders()->count();

                if ($ordersCount > 0) {
                    $skippedCount++;
                    continue;
                }

                activity()
                    ->performedOn($vendor)
                    ->causedBy(Auth::user())
                    ->withProperties(['vendor_code' => $vendor->vendor_code])
                    ->event('deleted')
                    ->log('Vendor deleted (bulk): ' . $vendor->client_name);

                $vendor->delete();
                $deletedCount++;
            }

            DB::commit();

            $message = 'Successfully deleted ' . $deletedCount . ' vendor(s).';
            if ($skippedCount > 0) {
                $message .= ' (' . $skippedCount . ' vendor(s) with orders were skipped)';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting vendors: ' . $e->getMessage());
        }
    }

    /**
     * Change status for multiple selected vendors
     *
     * Accepts `ids` as array or comma-separated string and `status` as 0 or 1
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeSelectedStatus(Request $request)
    {
        // Normalize ids similar to deleteSelected
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

        $validator = Validator::make(array_merge(['ids' => $ids], $request->only('status')), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:vendors,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid request for changing status.');
        }

        DB::beginTransaction();

        try {
            $status = $request->input('status');
            $vendors = Vendor::whereIn('id', $ids)->get();

            $changed = 0;

            foreach ($vendors as $vendor) {
                $old = $vendor->status;
                if ((string)$old === (string)$status) {
                    continue;
                }
                $vendor->status = $status;
                $vendor->save();

                activity()
                    ->performedOn($vendor)
                    ->causedBy(Auth::user())
                    ->withProperties(['old_status' => $old, 'new_status' => $status, 'vendor_code' => $vendor->vendor_code])
                    ->event('status_changed')
                    ->log('Vendor status changed (bulk): ' . $vendor->client_name);

                $changed++;
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status updated for ' . $changed . ' vendor(s).');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Toggle vendor status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vendors,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $vendor = Vendor::findOrFail($request->id);

            $oldStatus = $vendor->status;
            $vendor->status = $request->status;
            $vendor->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($vendor)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $vendor->status,
                ])
                ->event('status_changed')
                ->log('Vendor status changed: ' . ($vendor->status === '1' ? 'Active' : 'Inactive'));

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
