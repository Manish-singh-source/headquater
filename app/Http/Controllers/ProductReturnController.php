<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CustomerReturn;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProductReturnController extends Controller
{
    //

    protected function checkDuplicateSkuInExcel($rows)
    {
        $seen = [];

        foreach ($rows as $record) {
            if (empty($record['SKU Code'])) {
                continue;
            }

            $key = strtolower(trim($record['SKU Code']));

            if (isset($seen[$key])) {
                return 'Please check excel file: duplicate SKU (' . $record['SKU Code'] . ') found in the file.';
            }

            $seen[$key] = true;
        }

        return null;
    }

    public function customerReturns()
    {
        $customerReturns = CustomerReturn::with('salesOrder', 'product')->get();
        // dd($customerReturns);
        return view('ReturnProducts.customer-returns', compact('customerReturns'));
    }

    public function createCustomerReturn()
    {
        $warehouses = Warehouse::all();
        $salesOrders = SalesOrder::all();
        return view('ReturnProducts.create-customer-returns', compact('warehouses', 'salesOrders'));
    }

    public function storeCustomerReturn(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'sales_order_id' => 'required',
            'warehouse_id' => 'required',
            'excel_file' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with($validated->errors());
        }

        $file = $request->file('excel_file');

        if (! $file) {
            return redirect()->back()->with(['excel_file' => 'Please upload a CSV file.']);
        }

        DB::beginTransaction();
        try {
            $file = $request->file('excel_file')->getPathname();
            $file_extension = $request->file('excel_file')->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($file, $file_extension);

            $rows = $reader->getRows()->toArray(); // convert to array so we can check duplicates easily

            // 🔹 Step 1: Check for duplicates (Customer + SKU)
            $duplicateCheck = $this->checkDuplicateSkuInExcel($rows);
            if ($duplicateCheck) {
                return redirect()->back()->with(['error' => $duplicateCheck]);
            }

            foreach ($reader->getRows() as $key => $record) {
                $sku = trim($record['SKU Code']);
                $returnQuantity = (int) $record['Return Quantity'];
                $warehouseId = $request->warehouse_id;

                CustomerReturn::create([
                    'sales_order_id' => $request->sales_order_id,
                    'warehouse_id' => $warehouseId,
                    'sku' => $sku,
                    'return_quantity' => $returnQuantity,
                    'return_reason' => $request->return_reason,
                    'return_description' => $request->return_description,
                ]);

                $warehouseStock = WarehouseStock::where('sku', $sku)->where('warehouse_id', $warehouseId)->first();
                if ($warehouseStock) {
                    $warehouseStock->available_quantity += $returnQuantity;
                    $warehouseStock->save();
                }

                if (!$warehouseStock) {
                    return redirect()->back()->with(['error' => 'Warehouse stock not found.']);
                }
            }
            DB::commit();

            return redirect()->route('customer.returns')->with('success', 'Customers Product Return created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function viewCustomerReturn($id)
    {
        $customerReturn = CustomerReturn::with('salesOrder', 'product')->findOrFail($id);
        return view('ReturnProducts.view-customer-returns', compact('customerReturn'));
    }

    public function editCustomerReturn($id)
    {
        $customerReturn = CustomerReturn::with('salesOrder', 'product')->findOrFail($id);
        return view('ReturnProducts.edit-customer-returns', compact('customerReturn'));
    }

    public function updateCustomerReturn(Request $request)
    {
        $customerReturn = CustomerReturn::findOrFail($request->customer_return_id);
        $customerReturn->return_reason = $request->return_reason;
        $customerReturn->return_description = $request->return_description;
        $customerReturn->save();

        return redirect()->route('customer.returns')->with('success', 'Customer Return updated successfully.');
    }

    public function deleteCustomerReturn($id)
    {
        $customerReturn = CustomerReturn::findOrFail($id);
        $customerReturn->delete();

        return redirect()->route('customer.returns')->with('success', 'Customer Return deleted successfully.');
    }
}
