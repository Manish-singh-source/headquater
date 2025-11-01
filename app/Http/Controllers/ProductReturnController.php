<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\CustomerReturn;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProductReturnController extends Controller
{
    /**
     * Check for duplicate SKU codes in Excel file
     *
     * @param array $rows
     * @return string|null
     */
    protected function checkDuplicateSkuInExcel($rows)
    {
        $seen = [];

        foreach ($rows as $record) {
            if (empty($record['SKU Code'] ?? null)) {
                continue;
            }

            $key = strtolower(trim($record['SKU Code']));

            if (isset($seen[$key])) {
                return 'Duplicate SKU found in file: ' . $record['SKU Code'];
            }

            $seen[$key] = true;
        }

        return null;
    }

    /**
     * Display list of customer returns
     *
     * @return \Illuminate\View\View
     */
    public function customerReturns()
    {
        try {
            $customerReturns = CustomerReturn::with('salesOrder', 'product')
                ->latest()
                ->paginate(15);

            return view('ReturnProducts.customer-returns', compact('customerReturns'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving customer returns: ' . $e->getMessage());
        }
    }

    /**
     * Show form for creating customer return
     *
     * @return \Illuminate\View\View
     */
    public function createCustomerReturn()
    {
        try {
            $warehouses = Warehouse::all();
            $salesOrders = SalesOrder::where('status', 'packaged')
                ->orWhere('status', 'shipped')
                ->orWhere('status', 'delivered')
                ->get();

            return view('ReturnProducts.create-customer-returns', compact('warehouses', 'salesOrders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    /**
     * Store customer return from Excel file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCustomerReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_order_id' => 'required|integer|exists:sales_orders,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'excel_file' => 'required|file|mimes:xlsx,csv,xls',
            'return_reason' => 'required|string|max:255',
            'return_description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('excel_file');

        if (!$file) {
            return redirect()->back()->withErrors(['excel_file' => 'Please upload a valid file.']);
        }

        DB::beginTransaction();

        try {
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);
            $rows = $reader->getRows()->toArray();

            // Check for duplicates
            $duplicateCheck = $this->checkDuplicateSkuInExcel($rows);
            if ($duplicateCheck) {
                DB::rollBack();

                return redirect()->back()->withErrors(['error' => $duplicateCheck]);
            }

            $insertCount = 0;
            $salesOrderId = (int)$request->sales_order_id;
            $warehouseId = (int)$request->warehouse_id;
            $returnReason = trim($request->return_reason);
            $returnDescription = trim($request->return_description ?? '');

            // Verify sales order exists
            $salesOrder = SalesOrder::findOrFail($salesOrderId);

            foreach ($reader->getRows() as $record) {
                if (empty($record['SKU Code'] ?? null)) {
                    continue;
                }

                $sku = trim($record['SKU Code']);
                $returnQuantity = (int)($record['Return Quantity'] ?? 0);

                if ($returnQuantity <= 0) {
                    continue;
                }

                // Verify product exists
                $product = Product::where('sku', $sku)->first();
                if (!$product) {
                    DB::rollBack();

                    return redirect()->back()->withErrors(['error' => 'Product with SKU ' . $sku . ' not found.']);
                }

                // Create customer return record
                $customerReturn = CustomerReturn::create([
                    'sales_order_id' => $salesOrderId,
                    'warehouse_id' => $warehouseId,
                    'sku' => $sku,
                    'product_id' => $product->id,
                    'return_quantity' => $returnQuantity,
                    'return_reason' => $returnReason,
                    'return_description' => $returnDescription,
                    'status' => 'pending',
                ]);

                // Update warehouse stock
                $warehouseStock = WarehouseStock::where('sku', $sku)
                    ->where('warehouse_id', $warehouseId)
                    ->first();

                if (!$warehouseStock) {
                    DB::rollBack();

                    return redirect()->back()->withErrors([
                        'error' => 'Warehouse stock record not found for SKU: ' . $sku,
                    ]);
                }

                // Add returned quantity back to available stock
                $warehouseStock->available_quantity += $returnQuantity;
                $warehouseStock->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->withErrors(['excel_file' => 'No valid return data found in the file.']);
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($salesOrder)
                ->causedBy(Auth::user())
                ->withProperties(['returns_count' => $insertCount])
                ->event('product_return_created')
                ->log('Customer product returns created: ' . $insertCount . ' items');

            return redirect()->route('customer.returns')
                ->with('success', 'Successfully created ' . $insertCount . ' product return(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing returns: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * View customer return details
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewCustomerReturn($id)
    {
        try {
            $customerReturn = CustomerReturn::with('salesOrder', 'product', 'warehouse')
                ->findOrFail($id);

            return view('ReturnProducts.view-customer-returns', compact('customerReturn'));
        } catch (\Exception $e) {
            return redirect()->route('customer.returns')
                ->with('error', 'Customer return not found.');
        }
    }

    /**
     * Show edit form for customer return
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editCustomerReturn($id)
    {
        try {
            $customerReturn = CustomerReturn::with('salesOrder', 'product')
                ->findOrFail($id);

            return view('ReturnProducts.edit-customer-returns', compact('customerReturn'));
        } catch (\Exception $e) {
            return redirect()->route('customer.returns')
                ->with('error', 'Customer return not found.');
        }
    }

    /**
     * Update customer return record
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCustomerReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_return_id' => 'required|integer|exists:customer_returns,id',
            'return_reason' => 'required|string|max:255',
            'return_description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:pending,approved,rejected,processed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $customerReturn = CustomerReturn::findOrFail($request->customer_return_id);

            $oldAttributes = $customerReturn->getOriginal();

            $customerReturn->return_reason = $request->return_reason;
            $customerReturn->return_description = $request->return_description ?? '';

            if ($request->has('status')) {
                $customerReturn->status = $request->status;
            }

            $customerReturn->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($customerReturn)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $oldAttributes,
                    'new' => $customerReturn->getChanges(),
                ])
                ->event('updated')
                ->log('Customer return updated');

            return redirect()->route('customer.returns')
                ->with('success', 'Customer return updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating customer return: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete customer return record
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCustomerReturn($id)
    {
        DB::beginTransaction();

        try {
            $customerReturn = CustomerReturn::findOrFail($id);

            // Reverse the warehouse stock adjustment
            $warehouseStock = WarehouseStock::where('sku', $customerReturn->sku)
                ->where('warehouse_id', $customerReturn->warehouse_id)
                ->first();

            if ($warehouseStock) {
                $warehouseStock->available_quantity -= $customerReturn->return_quantity;
                $warehouseStock->save();
            }

            // Log activity
            activity()
                ->performedOn($customerReturn)
                ->causedBy(Auth::user())
                ->withProperties(['sku' => $customerReturn->sku, 'quantity' => $customerReturn->return_quantity])
                ->event('deleted')
                ->log('Customer return deleted');

            $customerReturn->delete();

            DB::commit();

            return redirect()->route('customer.returns')
                ->with('success', 'Customer return deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('customer.returns')
                ->with('error', 'Error deleting customer return: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple customer returns
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:customer_returns,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid return IDs selected.');
        }

        DB::beginTransaction();

        try {
            $ids = $request->ids;
            $returns = CustomerReturn::whereIn('id', $ids)->get();

            foreach ($returns as $return) {
                // Reverse the warehouse stock adjustment
                $warehouseStock = WarehouseStock::where('sku', $return->sku)
                    ->where('warehouse_id', $return->warehouse_id)
                    ->first();

                if ($warehouseStock) {
                    $warehouseStock->available_quantity -= $return->return_quantity;
                    $warehouseStock->save();
                }
            }

            $deleted = CustomerReturn::destroy($ids);

            DB::commit();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['count' => $deleted, 'ids' => $ids])
                ->event('bulk_delete')
                ->log('Customer returns deleted: ' . $deleted . ' records');

            return redirect()->back()
                ->with('success', 'Successfully deleted ' . $deleted . ' customer return(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error deleting returns: ' . $e->getMessage());
        }
    }
}
