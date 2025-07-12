<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TempOrder;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\CustomerGroupMember;
use App\Models\ManageCustomer;
use App\Models\ManageOrder;
use App\Models\ManageProduct;
use App\Models\ManageVendor;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PurchaseOrder;
use App\Models\TempOrderStatus;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class OrderController extends Controller
{
    //
    public function orderList2()
    {
        $orders = ManageOrder::with(['warehouse', 'vendors.vendor', 'manageCustomer.customerGroup'])->get();
        return view('order', compact('orders'));
    }

    public function orderList()
    {
        $orders = Order::with(['group', 'items', 'purchaseOrders'])->get();
        // dd($orders);
        return view('order', compact('orders'));
    }

    public function viewOrder($id)
    {

        // Fetch customer related data 
        $customerGroupMember = CustomerGroupMember::with('customer.orders')->where('group_id', $id)->get();
        $order = Order::with(['group', 'items.products'])->find($id);

        // Fetch order related data 
        $orderItems = OrderItem::with('products')->where('order_id', $id)->get();


        $customerGroup = ManageCustomer::with('customerGroup.customerInfo')->where('order_id', $id)->find($id);
        $vendorCodes = TempOrder::where('order_id', $id)
            ->select('vendor_code')
            ->distinct()
            ->pluck('vendor_code');
        $orders = TempOrder::where('order_id', $id)
            ->whereIn('vendor_code', $vendorCodes)
            ->get();

        $vendors = Vendor::whereIn('vendor_code', $vendorCodes)->get();
        return view('customer.customer-order-view', compact('order', 'orders', 'vendors', 'customerGroupMember'));
    }

    public function addOrder()
    {
        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('add-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses]);
    }

    public function downloadBlockedCSV()
    {
        $filePath = public_path(session('processed_csv_path'));

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath), [
            'Content-Type' => 'text/csv',
            // 'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
        // return redirect()->route->('orders')->response()->download(public_path(session('processed_csv_path')));
    }

    public function processOrder(Request $request)
    {
        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();

        $reader = SimpleExcelReader::create($file, $file_extension);
        $insertedRows = [];

        foreach ($reader->getRows() as $record) {

            $product = Product::where('sku', $record['sku'])->first();
            if (!$product) {
                $unAvail = $record['po_qty'] . " Not Available";
                $availableQty = 0;
            } else {
                if ($product->units_ordered >= $record['po_qty']) {
                    $unAvail = 'All Available';
                } else {
                    $unavailableQuantity = $record['po_qty'] - $product->units_ordered;
                    $unAvail = $unavailableQuantity . " Not Available";
                }
                $availableQty = $product->units_ordered;
            }

            $insertedRows[] = [
                'customer_name' => $record['customer_name'],
                'po_number' => $record['po_number'],
                'sku' => $record['sku'],
                'facility_name' => $record['facility_name'],
                'facility_location' => $record['facility_location'],
                'po_date' => $record['po_date']->format('d-m-Y'),
                'po_expiry_date' => $record['po_expiry_date']->format('d-m-Y'),
                'hsn' => $record['hsn'],
                'item_code' => $record['item_code'],
                'description' => $record['description'],
                'basic_rate' => $record['basic_rate'],
                'gst' => $record['gst'],
                'net_landing_rate' => $record['net_landing_rate'],
                'mrp' => $record['mrp'],
                'po_qty' => $record['po_qty'],
                'available_quantity' => $availableQty,
                'unavailable_quantity' => $unAvail,
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
        }

        $filteredRows = collect($insertedRows)->map(function ($row) {
            unset($row['created_at']);
            return $row;
        });

        $fileName = 'processed_order_' . time() . '.csv';
        $csvPath = public_path("uploads/{$fileName}");

        SimpleExcelWriter::create($csvPath)->addRows($filteredRows->toArray());
        session(['processed_csv_path' => "uploads/{$fileName}"]);

        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('process-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses, 'fileData' => $insertedRows]);
    }


    public function processBlockOrder(Request $request)
    {

        // get warehouse id, group id and po file 
        $warehouse_id = $request->warehouse_id;
        $customer_group_id = $request->customer_group_id;
        $file = $request->file('csv_file');


        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();

        $reader = SimpleExcelReader::create($file, $file_extension);

        // A New order is created
        DB::beginTransaction();

        $saveOrder = new Order();
        $saveOrder->group_id = $customer_group_id;
        $saveOrder->save();

        // $manageOrder = new ManageOrder();
        // $manageOrder->order_id = '1000';
        // $manageOrder->warehouse_id = $request->warehouse_id;
        // $manageOrder->save();

        // $manageCustomer = new ManageCustomer();
        // $manageCustomer->order_id = $manageOrder->id;
        // $manageCustomer->customer_id = $request->customer_group_id;
        // $manageCustomer->save();

        $insertedRows = [];
        foreach ($reader->getRows() as $record) {

            // $manageVendor = new ManageVendor();
            // $manageVendor->order_id = $manageOrder->id;
            // $manageVendor->vendor_id = $record['vendor_code'];
            // $manageVendor->save();

            $purchaseOrder = new PurchaseOrder();
            $purchaseOrder->vendor_id = $record['vendor_code'];
            $purchaseOrder->order_id = $saveOrder->id;
            $purchaseOrder->save();
            
            $purchaseOrder = new OrderItem();
            $purchaseOrder->customer_id = $record['customer_name'];
            $purchaseOrder->order_id = $saveOrder->id;
            $purchaseOrder->product_id = $record['sku'];
            $purchaseOrder->quantity = $record['po_qty'];
            $purchaseOrder->save();
            
            $customerGroupMember = new CustomerGroupMember();
            $customerGroupMember->group_id = $customer_group_id;
            $customerGroupMember->customer_id = $record['customer_name'];
            $customerGroupMember->save();

            $insertedRows[] = [
                'order_id' => $saveOrder->id,
                'customer_name' => $record['customer_name'],
                'po_number' => $record['po_number'],
                'sku' => $record['sku'],
                'facility_name' => $record['facility_name'],
                'facility_location' => $record['facility_location'],
                'po_date' => $record['po_date']->format('d-m-Y'),
                'po_expiry_date' => $record['po_expiry_date']->format('d-m-Y'),
                'hsn' => $record['hsn'],
                'item_code' => $record['item_code'],
                'description' => $record['description'],
                'basic_rate' => $record['basic_rate'],
                'gst' => $record['gst'],
                'net_landing_rate' => $record['net_landing_rate'],
                'mrp' => $record['mrp'],
                'rate_confirmation' => $record['rate_confirmation'],
                'po_qty' => $record['po_qty'],
                'case_pack_quantity' => $record['case_pack_quantity'],
                'block' => $record['block'],
                'purchase_order_quantity' => $record['purchase_order_quantity'],
                'vendor_code' => $record['vendor_code'],
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
        }

        $insert = TempOrder::insert($insertedRows);
        if (!$insert) {
            DB::rollBack();
            return redirect()->back()->withErrors(['csv_file' => 'Failed to insert data into the database.']);
        }

        DB::commit();

        return redirect()->route('order')->with('success', 'Order Completed Successful.');
    }

    public function processBlockOrder2(Request $request)
    {
        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();

        $reader = SimpleExcelReader::create($file, $file_extension);

        DB::beginTransaction();

        $manageOrder = new ManageOrder();
        $manageOrder->order_id = '1000';
        $manageOrder->warehouse_id = $request->warehouse_id;
        $manageOrder->save();

        $manageCustomer = new ManageCustomer();
        $manageCustomer->order_id = $manageOrder->id;
        $manageCustomer->customer_id = $request->customer_group_id;
        $manageCustomer->save();

        $insertedRows = [];
        foreach ($reader->getRows() as $record) {

            $manageVendor = new ManageVendor();
            $manageVendor->order_id = $manageOrder->id;
            $manageVendor->vendor_id = $record['vendor_code'];
            $manageVendor->save();

            $insertedRows[] = [
                'order_id' => $manageOrder->id,
                'customer_name' => $record['customer_name'],
                'po_number' => $record['po_number'],
                'sku' => $record['sku'],
                'facility_name' => $record['facility_name'],
                'facility_location' => $record['facility_location'],
                'po_date' => $record['po_date']->format('d-m-Y'),
                'po_expiry_date' => $record['po_expiry_date']->format('d-m-Y'),
                'hsn' => $record['hsn'],
                'item_code' => $record['item_code'],
                'description' => $record['description'],
                'basic_rate' => $record['basic_rate'],
                'gst' => $record['gst'],
                'net_landing_rate' => $record['net_landing_rate'],
                'mrp' => $record['mrp'],
                'rate_confirmation' => $record['rate_confirmation'],
                'po_qty' => $record['po_qty'],
                'case_pack_quantity' => $record['case_pack_quantity'],
                'block' => $record['block'],
                'purchase_order_quantity' => $record['purchase_order_quantity'],
                'vendor_code' => $record['vendor_code'],
            ];
        }

        if (empty($insertedRows)) {
            return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
        }

        $insert = TempOrder::insert($insertedRows);
        if (!$insert) {
            DB::rollBack();
            return redirect()->back()->withErrors(['csv_file' => 'Failed to insert data into the database.']);
        }
        DB::commit();

        return redirect()->route('order')->with('success', 'Order Completed Successful.');
    }

    public function deleteOrder($id)
    {
        $order = TempOrderStatus::findOrFail($id);
        $order->delete();

        return redirect()->route('order')->with('success', 'Order deleted successfully.');
    }
}
