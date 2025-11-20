<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductIssue;
use App\Models\PurchaseGrn;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SalesOrderProduct;
use App\Models\SkuMapping;
use App\Models\TempOrder;
use App\Models\Vendor;
use App\Models\VendorPayment;
use App\Models\VendorPI;
use App\Models\VendorPIProduct;
use App\Models\Warehouse;
use App\Models\WarehouseAllocation;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PurchaseOrderController extends Controller
{
    //
    protected function checkDuplicateSkuInExcel($rows)
    {
        $seen = [];
        $duplicates = [];

        foreach ($rows as $record) {
            // Normalize the key
            $skuKey = isset($record['SKU Code']) ? strtolower(trim($record['SKU Code'])) : null;
            if (empty($skuKey)) {
                continue;
            }
            if (isset($seen[$skuKey])) {
                $duplicates[] = $record['SKU Code'];
            }
            $seen[$skuKey] = true;
        }
        if (! empty($duplicates)) {
            return 'Please check excel file: duplicate SKUs found: ' . implode(', ', $duplicates);
        }

        return null;
    }

    public function customPurchaseCreate($purchaseId = null)
    {
        $warehouses = Warehouse::all();
        $vendors = Vendor::all();
        if ($purchaseId) {
            return view('purchaseOrder.create', compact('purchaseId', 'warehouses', 'vendors'));
        }

        return view('purchaseOrder.create', compact('warehouses', 'vendors'));
    }

    /**
     * Create custom purchase order
     *
     * @return void
     */
    public function customPurchaseStore(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchase_excel' => 'required|mimes:xlsx,csv,xls',
            'warehouse_id' => 'required|exists:warehouses,id',
            'vendor_id' => 'required|exists:vendors,id',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', $validated->errors()->first());
        }

        // get warehouse id
        $warehouse_id = $request->warehouse_id;
        $vendor_id = $request->vendor_id;

        $file = $request->file('purchase_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $vendorProducts = [];
            $insertCount = 0;
            $total_amount = 0;

            // check for duplicate sku
            $rows = $reader->getRows()->toArray(); // convert to array so we can check duplicates easily

            // 🔹 Step 1: Check for duplicates (Customer + SKU)
            $duplicateCheck = $this->checkDuplicateSkuInExcel($rows);
            if ($duplicateCheck) {
                DB::rollBack();

                return redirect()->back()->with(['error' => $duplicateCheck]);
            }

            // check for existing vendor
            $vendor = Vendor::where('id', $vendor_id)->first();
            if (! $vendor) {
                return redirect()->back()->with(['error' => 'Vendor not found']);
            }

            if (! $request->has('purchaseId')) {

                // add prefix for purchase order 
                $lastPurchaseOrder = PurchaseOrder::orderBy('id', 'desc')->first();
                if ($lastPurchaseOrder->order_number) {
                    $prefix = $lastPurchaseOrder ? 'PO-' . date('Ym', strtotime($lastPurchaseOrder->created_at)) . '-' : 'PO-' . date('Ym') . '-';
                    $lastPurchaseOrderNumber = $lastPurchaseOrder ? intval(explode('-', $lastPurchaseOrder->order_number)[2]) : 0;
                } else {
                    $prefix = 'PO-' . date('Ym') . '-';
                    $lastPurchaseOrderNumber = 0;
                }
                $nextPurchaseOrderNumber = $lastPurchaseOrderNumber + 1;
                $nextPurchaseOrderNumber = str_pad($nextPurchaseOrderNumber, 4, '0', STR_PAD_LEFT);
                $nextPurchaseOrderNumber = $prefix . $nextPurchaseOrderNumber;

                $purchaseOrder = new PurchaseOrder;
                $purchaseOrder->order_number = $nextPurchaseOrderNumber;
                $purchaseOrder->warehouse_id = $warehouse_id ?? null;
                $purchaseOrder->vendor_id = $vendor->id ?? null;
                $purchaseOrder->vendor_code = $vendor->vendor_code ?? null;
                $purchaseOrder->status = 'pending';
                $purchaseOrder->save();
            }

            foreach ($reader->getRows() as $record) {
                if (empty($record['SKU Code'])) {
                    continue;
                }

                // check if product is already added in purchase order
                $product = Product::where('sku', $record['SKU Code'])->first();
                if (! $product) {
                    $vendorProducts[] = [
                        'purchase_order_id' => $purchaseOrder->id,
                        'vendor_code' => $vendor->vendor_code ?? null,
                        'sku' => $record['SKU Code'],
                        'ordered_quantity' => $record['PO Quantity'] ?? 0,
                        'product_status' => 'not_found',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    continue;
                }

                $tempSalesOrder = TempOrder::create([
                    'po_number' => $record['PO Number'] ?? '',
                    'sku' => $record['SKU Code'] ?? '',
                    'hsn' => $record['HSN'] ?? '',
                    'gst' => ($record['GST'] < 1 && $record['GST'] > 0)
                        ? intval(round($record['GST'] * 100))  // convert decimals (0.18 -> 18)
                        : intval($record['GST']),              // already integer (e.g., 18)
                    'item_code' => $record['Item Code'] ?? '',
                    'description' => $record['Title'] ?? '',

                    'basic_rate' => $record['Basic Rate'] ?? 0,
                    // 'product_basic_rate' => $record['Product Basic Rate'] ?? 0,
                    // 'rate_confirmation' => $record['Basic Rate Confirmation'] ?? '',

                    'net_landing_rate' => $record['Net Landing Rate'] ?? 0,
                    // 'product_net_landing_rate' => $record['Product Net Landing Rate'] ?? 0,
                    // 'net_landing_rate_confirmation' => $record['Net Landing Rate Confirmation'] ?? '',

                    'mrp' => $record['MRP'] ?? 0,
                    // 'product_mrp' => $record['Product MRP'] ?? 0,
                    // 'mrp_confirmation' => $record['MRP Confirmation'] ?? '',

                    'purchase_order_quantity' => $record['PO Quantity'] ?? 0,
                    'vendor_code' => $vendor->vendor_code ?? '',
                    'customer_status' => 'Found',
                    'vendor_status' => 'Found',
                    'product_status' => 'Found',
                ]);

                $purchaseOrderProduct = new PurchaseOrderProduct;
                $purchaseOrderProduct->temp_order_id = $tempSalesOrder->id;
                if (isset($purchaseOrder->id)) {
                    $purchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
                } else {
                    $purchaseOrderProduct->purchase_order_id = $request->purchaseId;
                }
                $purchaseOrderProduct->ordered_quantity = $record['PO Quantity'] ?? 0;
                $purchaseOrderProduct->sku = $record['SKU Code'];
                $purchaseOrderProduct->product_id = $product->id;
                $purchaseOrderProduct->vendor_code = $vendor->vendor_code;
                $purchaseOrderProduct->save();

                // calculate total amount and insert in vendor pi
                $total_amount += $record['PO Quantity'] * $record['MRP'];
                $purchaseOrder->total_amount = $total_amount;
                $purchaseOrder->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->with(['purchase_excel' => 'No valid data found in the CSV file.']);
            }

            if (! empty($vendorProducts)) {
                DB::rollBack();

                return redirect()->back()->with(['error' => $vendorProducts[0]['sku'] . ' Product not found in database.']);
            }

            DB::commit();

            // Create notification
            NotificationService::orderCreated('purchase', $purchaseOrder->id);

            return redirect()->route('purchase.order.index')->with('success', 'Purchase Order created successfully! Order ID: ' . $purchaseOrder->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function storeCustomPurchaseOrder(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'vendor_code' => 'required|string|max:255',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', $validated->errors()->first())->withInput();
        }

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $vendorProducts = [];
            $insertCount = 0;

            // update fulfillment quantity in temp order
            $vendorPi = VendorPI::create([
                'purchase_order_id' => $request->purchase_order_id,
                'vendor_code' => $request->vendor_code,
                'warehouse_id' => $request->warehouse_id,
            ]);

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) {
                    continue;
                }

                // check if vendor code of request and excel file is same
                if ($request->vendor_code != $record['Vendor Code']) {
                    throw new \Exception('Vendor Code is not same. Please check the file.');
                }

                // map sku with product
                $sku = SkuMapping::where('vendor_sku', Arr::get($record, 'Vendor SKU Code'))->first();

                if ($sku) {
                    $newSku = $sku->product_sku;
                } else {
                    $newSku = Arr::get($record, 'Vendor SKU Code');
                }

                $salesOrderFulfillment[$newSku] = [
                    'quantity' => $record['PI Quantity'],
                ];

                $vendorProducts[] = [
                    'purchase_order_id' => $request->purchase_order_id,
                    'vendor_pi_id' => $vendorPi->id,
                    'vendor_sku_code' => $newSku ?? Arr::get($record, 'Vendor SKU Code'),
                    'title' => Arr::get($record, 'Title'),
                    'mrp' => Arr::get($record, 'MRP') ?? 0,
                    'quantity_requirement' => Arr::get($record, 'PO Quantity') ?? 0,
                    'available_quantity' => Arr::get($record, 'PI Quantity') ?? 0,
                    'purchase_rate' => Arr::get($record, 'Purchase Rate Basic') ?? 0,
                    'gst' => Arr::get($record, 'GST') ?? 0,
                    'hsn' => Arr::get($record, 'HSN') ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->with(['pi_excel' => 'No valid data found in the CSV file.']);
            }

            VendorPIProduct::insert($vendorProducts);
            DB::commit();

            return redirect()->back()->with('success', 'Purchase Order products imported successfully! Vendor PI ID: ' . $vendorPi->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['purchaseOrderProducts', 'vendorPI', 'salesOrder'])
        ->withSum('purchaseOrderProducts', 'ordered_quantity')
        ->withCount('purchaseOrderProducts')->get();

        return view('purchaseOrder.index', compact('purchaseOrders'));
    }

    // Storing Vendor PI Products from CSV
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'sales_order_id' => 'nullable|exists:sales_orders,id',
            'vendor_code' => 'required|string|max:255',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', $validated->errors()->first())->withInput();
        }

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $vendorProducts = [];
            $insertCount = 0;

            $purchaseOrder = PurchaseOrder::find($request->purchase_order_id);
            $purchaseOrder->received_warehouse_id = $request->warehouse_id;
            $purchaseOrder->save();

            if (!$purchaseOrder) {
                return redirect()->back()->with('error', 'Please Select Warehouse Name First.');
            }

            // update fulfillment quantity in temp order
            $vendorPi = VendorPI::create([
                'purchase_order_id' => $request->purchase_order_id,
                'vendor_code' => $request->vendor_code,
                'sales_order_id' => $request->sales_order_id,
                'warehouse_id' => $request->warehouse_id,
            ]);

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) {
                    continue;
                }

                // check if vendor code of request and excel file is same
                if ($request->vendor_code != $record['Vendor Code']) {
                    throw new \Exception('Vendor Code is not same. Please check the file.');
                }

                // map sku with product
                $sku = SkuMapping::where('vendor_sku', Arr::get($record, 'Vendor SKU Code'))->first();

                if ($sku) {
                    $newSku = $sku->product_sku;
                } else {
                    $newSku = Arr::get($record, 'Vendor SKU Code');
                }

                $salesOrderFulfillment[$newSku] = [
                    'quantity' => $record['PI Quantity'],
                ];

                // Get Temp Order ID
                $salesOrderProduct = SalesOrderProduct::where('sales_order_id', $request->sales_order_id)
                    ->where('sku', $newSku)
                    ->get();

                foreach ($salesOrderProduct as $item) {
                    $tempProduct = TempOrder::where('id', $item->temp_order_id)->where('vendor_code', $request->vendor_code)->first();
                    if (! $tempProduct) {
                        continue;
                    }
                    if ($tempProduct->po_qty >= $salesOrderFulfillment[$newSku]['quantity']) {
                        if ($tempProduct->po_qty > $tempProduct->available_quantity) {
                            $tempProduct->vendor_pi_fulfillment_quantity = $salesOrderFulfillment[$newSku]['quantity'];
                            $tempProduct->vendor_pi_id = $vendorPi->id;
                            WarehouseAllocation::create([
                                'sales_order_id' => $item->sales_order_id,
                                'sales_order_product_id' => $item->id,
                                'warehouse_id' => $request->warehouse_id,
                                'sku' => $newSku,
                                'allocated_quantity' => $salesOrderFulfillment[$newSku]['quantity'],
                                'sequence' => 1,
                                'status' => 'allocated',
                                'notes' => "Allocated from warehouse {$request->warehouse_id}",
                            ]);
                            $salesOrderFulfillment[$newSku]['quantity'] = 0;
                        }
                    } else {
                        if ($tempProduct->po_qty > $tempProduct->available_quantity) {
                            $tempProduct->vendor_pi_fulfillment_quantity = $tempProduct->po_qty;
                            $tempProduct->vendor_pi_id = $vendorPi->id;
                            WarehouseAllocation::create([
                                'sales_order_id' => $item->sales_order_id,
                                'sales_order_product_id' => $item->id,
                                'warehouse_id' => $request->warehouse_id,
                                'sku' => $newSku,
                                'allocated_quantity' => $tempProduct->po_qty,
                                'sequence' => 1,
                                'status' => 'allocated',
                                'notes' => "Allocated from warehouse {$request->warehouse_id}",
                            ]);
                            $salesOrderFulfillment[$newSku]['quantity'] = $salesOrderFulfillment[$newSku]['quantity'] - $tempProduct->po_qty;
                        }
                    }
                    $tempProduct->save();

                    $salesOrderFulfillment[] = $item->temp_order_id;
                }

                $vendorProducts[] = [
                    'purchase_order_id' => $request->purchase_order_id,
                    'vendor_pi_id' => $vendorPi->id,
                    'vendor_sku_code' => $newSku ?? Arr::get($record, 'Vendor SKU Code'),
                    'title' => Arr::get($record, 'Title'),
                    'mrp' => Arr::get($record, 'MRP') ?? 0,
                    'quantity_requirement' => Arr::get($record, 'PO Quantity') ?? 0,
                    'available_quantity' => Arr::get($record, 'PI Quantity') ?? 0,
                    'purchase_rate' => Arr::get($record, 'Purchase Rate Basic') ?? 0,
                    'gst' => Arr::get($record, 'GST') ?? 0,
                    'hsn' => Arr::get($record, 'HSN') ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->with(['pi_excel' => 'No valid data found in the CSV file.']);
            }

            VendorPIProduct::insert($vendorProducts);
            DB::commit();

            return redirect()->back()->with('success', 'Purchase Order products imported successfully! Vendor PI ID: ' . $vendorPi->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function view($id)
    {
        $purchaseOrder = PurchaseOrder::with('vendor', 'purchaseOrderProducts.tempOrder', 'vendorPI.products.purchaseOrder.purchaseOrderProducts.tempOrder', 'vendorPI.products.product', 'vendorPI.products.tempOrder',  'vendorPI.purchaseOrder', 'vendorPI.salesOrder')
            ->withCount('purchaseOrderProducts')
            ->findOrFail($id);

        $purchaseOrderProducts = PurchaseOrderProduct::where('purchase_order_id', $id)->with('purchaseOrder', 'tempOrder')->get();

        $facilityNames = VendorPI::with('product')->where('purchase_order_id', $id)
            ->where('status', '!=', 'completed')
            ->get()
            ->pluck('vendor_code')
            ->filter()
            ->unique()
            ->values();

        $uploadedPIOfVendors = VendorPI::distinct()->pluck('vendor_code');
        $purchaseInvoice = PurchaseInvoice::where('purchase_order_id', $id)->get();
        $purchaseGrn = PurchaseGrn::where('purchase_order_id', $id)->get();
        $vendorPIs = VendorPI::with('products.product')->where('purchase_order_id', $id)->where('status', '!=', 'completed')->get();

        // Get only active warehouses for warehouse selection
        $warehouses = Warehouse::where('status', '1')
            ->orderBy('name')
            ->get();

        return view('purchaseOrder.view', compact('purchaseOrder', 'facilityNames', 'purchaseOrderProducts', 'uploadedPIOfVendors', 'vendorPIs', 'purchaseInvoice', 'purchaseGrn', 'warehouses'));
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'purchase_order_id' => 'required',
            'vendor_code' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', $validated->errors()->first());
        }

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $insertCount = 0;

            $vendorPIid = VendorPI::where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'])) {
                    continue;
                }

                // check if vendor code of request and excel file is same
                if ($request->vendor_code != $record['Vendor Code']) {
                    throw new \Exception('Vendor Code is not same. Please check the file.');
                }

                $productData = VendorPIProduct::where('vendor_sku_code', Arr::get($record, 'Vendor SKU Code'))->where('vendor_pi_id', $vendorPIid->id)->first();
                if (Arr::get($record, 'MRP')) {
                    $productData->mrp = Arr::get($record, 'MRP');
                }
                if (Arr::get($record, 'Quantity Ordered')) {
                    $productData->quantity_requirement = Arr::get($record, 'Quantity Ordered');
                }
                // $productData->purchase_rate = Arr::get($record, 'Purchase Rate Basic');
                if (Arr::get($record, 'Issue Units')) {
                    $productData->quantity_received = Arr::get($record, 'Quantity Received');
                }
                // $productData->gst = Arr::get($record, 'GST');
                // $productData->hsn = Arr::get($record, 'HSN');

                if ($issueItem = Arr::get($record, 'Issue Units')) {
                    $productData->issue_item = $issueItem ?? '';
                    $productData->issue_reason = Arr::get($record, 'Issue Reason') ?? '';
                } else {
                    $productData->issue_item = 0;
                    $productData->issue_reason = '';
                }
                $productData->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->with(['pi_excel' => 'No valid data found in the CSV file.']);
            }
            DB::commit();

            return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase.order.index')->with('success', 'Purchase Order deleted successfully.');
    }

    public function multiDelete(Request $request)
    {
        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            PurchaseOrder::destroy($ids);

            return redirect()->back()->with('success', 'Purchase Orders deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function SingleProductdelete($id)
    {
        $purchaseOrderProduct = PurchaseOrderProduct::findOrFail($id);
        $purchaseOrderProduct->delete();

        return redirect()->back()->with('success', 'Purchase Order deleted successfully.');
    }

    public function multiProductdelete(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        PurchaseOrderProduct::destroy($ids);

        return redirect()->back()->with('success', 'Purchase Order deleted successfully.');
    }

    public function approveRequest(Request $request)
    {
        DB::beginTransaction();

        try {

            $total_amount = 0;

            $vendorPI = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            $vendorPI->status = 'completed';
            $vendorPI->approve_or_reject_reason = $request->approve_or_reject_reason ?? null;

            $purchaseOrder = PurchaseOrder::where('id', $request->purchase_order_id)->first();
            $purchaseOrder->status = 'received';
            $purchaseOrder->save();

            // find products
            $vendorPIProducts = VendorPI::with('products')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();

            foreach ($vendorPIProducts->products as $product) {

                $productIssueUpdate = ProductIssue::where('vendor_pi_product_id', $product->id)->first();
                if ($productIssueUpdate) {
                    $productIssueUpdate->issue_status = 'accept';
                    $productIssueUpdate->save();
                }

                $total_amount += $product->mrp * $product->quantity_received;

                // Get warehouse_id from VendorPI (selected during Excel upload)
                $warehouseId = $vendorPI->warehouse_id;

                // Find stock in the selected warehouse
                $updateStock = WarehouseStock::where('sku', $product->vendor_sku_code)
                    ->where('warehouse_id', $warehouseId)
                    ->first();

                // Calculate actual sales order requirement from temp_orders
                $tempOrderProducts = TempOrder::where('vendor_pi_id', $product->vendor_pi_id)
                    ->where('vendor_code', $request->vendor_code)
                    ->where('sku', $product->vendor_sku_code)
                    ->get();

                // Calculate total unavailable quantity (what was actually needed from sales orders)
                $totalUnavailableQty = $tempOrderProducts->sum('unavailable_quantity');

                // Get received quantity
                $receivedQuantity = $product->quantity_received ?? 0;

                // Calculate extra quantity (if vendor sent more than what was needed)
                // Extra = received - what was actually needed for sales orders
                $extraQuantity = max(0, $receivedQuantity - $totalUnavailableQty);
                $blockQuantity = min($receivedQuantity, $totalUnavailableQty);

                if (isset($updateStock)) {
                    // Update stock - block only what was needed, make extra quantity available
                    $updateStock->block_quantity = $updateStock->block_quantity + $blockQuantity;
                    $updateStock->original_quantity = $updateStock->original_quantity + $receivedQuantity;
                    $updateStock->available_quantity = $updateStock->available_quantity + $extraQuantity;
                    $updateStock->save();
                } else {
                    // If stock doesn't exist, create new entry in the selected warehouse
                    WarehouseStock::create([
                        'warehouse_id' => $warehouseId,
                        'sku' => $product->vendor_sku_code,
                        'original_quantity' => $receivedQuantity,
                        'available_quantity' => $extraQuantity,
                        'block_quantity' => $blockQuantity,
                    ]);
                }

                // Update temp order vendor_pi_received_quantity
                // Only allocate what was needed (unavailable_quantity), not extra quantity
                // We already fetched tempOrderProducts above, so reuse it
                $quantityToAllocate = $receivedQuantity; // Start with total received

                foreach ($tempOrderProducts as $tempOrderproduct) {
                    $allocatedQty = 0; // Track how much was allocated to this temp order

                    if ($tempOrderproduct->unavailable_quantity <= $quantityToAllocate && $tempOrderproduct->unavailable_quantity > 0) {
                        // This temp order needs less than or equal to what we have
                        $allocatedQty = $tempOrderproduct->unavailable_quantity;
                        $tempOrderproduct->available_quantity += $tempOrderproduct->unavailable_quantity;
                        $tempOrderproduct->block += $tempOrderproduct->unavailable_quantity;
                        $tempOrderproduct->vendor_pi_received_quantity += $tempOrderproduct->unavailable_quantity;
                        $quantityToAllocate -= $tempOrderproduct->unavailable_quantity;
                        $tempOrderproduct->unavailable_quantity = 0;
                    } else {
                        // This temp order needs more than what we have left
                        $allocatedQty = $quantityToAllocate;
                        $tempOrderproduct->available_quantity += $quantityToAllocate;
                        $tempOrderproduct->block += $quantityToAllocate;
                        $tempOrderproduct->vendor_pi_received_quantity += $quantityToAllocate;
                        $tempOrderproduct->unavailable_quantity -= $quantityToAllocate;
                        $quantityToAllocate = 0;
                    }
                    $tempOrderproduct->save();

                    // Update WarehouseAllocation if this is an auto-allocation order
                    if ($allocatedQty > 0) {
                        // Find the sales order product associated with this temp order
                        $salesOrderProduct = SalesOrderProduct::where('temp_order_id', $tempOrderproduct->id)->first();

                        if ($salesOrderProduct) {
                            // Check if this sales order uses auto-allocation (has warehouse allocations)
                            $existingAllocations = WarehouseAllocation::where('sales_order_product_id', $salesOrderProduct->id)
                                ->where('warehouse_id', $warehouseId)
                                ->where('sku', $product->vendor_sku_code)
                                ->first();

                            if ($existingAllocations) {
                                // Update existing allocation
                                $existingAllocations->allocated_quantity = $allocatedQty;
                                $existingAllocations->save();

                                Log::info('Updated WarehouseAllocation', [
                                    'allocation_id' => $existingAllocations->id,
                                    'warehouse_id' => $warehouseId,
                                    'sku' => $product->vendor_sku_code,
                                    'added_quantity' => $allocatedQty,
                                    'new_total' => $existingAllocations->allocated_quantity,
                                ]);
                            } else {
                                // Check if there are any allocations for this product (to determine if it's auto-allocation)
                                $hasAllocations = WarehouseAllocation::where('sales_order_product_id', $salesOrderProduct->id)->exists();

                                if ($hasAllocations) {
                                    // This is an auto-allocation order, create new allocation for this warehouse
                                    $maxSequence = WarehouseAllocation::where('sales_order_product_id', $salesOrderProduct->id)
                                        ->max('sequence') ?? 0;

                                    $newAllocation = WarehouseAllocation::create([
                                        'sales_order_id' => $salesOrderProduct->sales_order_id,
                                        'sales_order_product_id' => $salesOrderProduct->id,
                                        'warehouse_id' => $warehouseId,
                                        'sku' => $product->vendor_sku_code,
                                        'allocated_quantity' => $allocatedQty,
                                        'sequence' => $maxSequence + 1,
                                        'status' => 'allocated',
                                        'notes' => 'Allocated from received products (Vendor PI: ' . $vendorPI->id . ')',
                                    ]);

                                    Log::info('Created new WarehouseAllocation', [
                                        'allocation_id' => $newAllocation->id,
                                        'warehouse_id' => $warehouseId,
                                        'sku' => $product->vendor_sku_code,
                                        'allocated_quantity' => $allocatedQty,
                                        'sequence' => $maxSequence + 1,
                                    ]);
                                }
                            }
                        }
                    }

                    if ($quantityToAllocate <= 0) {
                        break; // Stop if we've allocated all received quantity to sales orders
                    }
                }

                // Any extra quantity (receivedQuantity - totalUnavailableQty) remains in warehouse as available_quantity
                // This was already handled in the warehouse stock update above
            }

            $vendorPI->total_amount = $vendorPI->total_amount ?? $total_amount;
            $vendorPI->total_due_amount = $vendorPI->total_amount ?? $total_amount;
            $vendorPI->save();

            DB::commit();

            return redirect()->back()->with('success', 'Successfully Approved Received Products');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function rejectRequest(Request $request)
    {
        DB::beginTransaction();

        try {

            $vendorPI = VendorPI::with('products', 'purchaseOrder')->where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            $vendorPI->status = 'reject';
            $vendorPI->approve_or_reject_reason = $request->approve_or_reject_reason ?? null;
            $vendorPI->purchaseOrder->status = 'rejected';
            $vendorPI->purchaseOrder->save();
            $vendorPI->save();

            DB::commit();

            return redirect()->back()->with('success', 'Successfully Rejected Received Products');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function invoiceStore(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'invoice_file' => 'required|mimes:pdf',
            'purchase_order_id' => 'required',
            'vendor_code' => 'required',
            'invoice_no' => 'required|unique:purchase_invoices,invoice_no',
            'invoice_amount' => 'required',
        ]);

        if ($validated->fails()) {
            // Redirect back with the first validation error as a flash message
            return redirect()->back()->with('error', $validated->errors()->first());
        }

        try {

            $vendorPIStatus = VendorPI::where('purchase_order_id', $request->purchase_order_id)->where('vendor_code', $request->vendor_code)->first();
            $vendorPIStatus->total_amount = $request->invoice_amount;
            $vendorPIStatus->total_due_amount = $request->invoice_amount;
            $vendorPIStatus->save();

            if (! isset($vendorPIStatus)) {
                return redirect()->back()->with('error', 'Vendor PI Is Not Uploaded');
            }

            $invoice_file = $request->file('invoice_file');
            $ext = $invoice_file->getClientOriginalExtension();
            $invoiceFileName = strtotime('now') . '-' . $request->purchase_order_id . '.' . $ext;
            $invoice_file->move(public_path('uploads/invoices'), $invoiceFileName);

            $purchaseInvoice = new PurchaseInvoice;
            $purchaseInvoice->purchase_order_id = $request->purchase_order_id;
            $purchaseInvoice->vendor_code = $request->vendor_code;
            $purchaseInvoice->invoice_file = $invoiceFileName;
            $purchaseInvoice->invoice_no = $request->invoice_no;
            $purchaseInvoice->invoice_amount = $request->invoice_amount;
            $purchaseInvoice->save();

            if (! $purchaseInvoice) {
                return back()->with('error', 'Something went wrong');
            }

            return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'Invoice imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function grnStore(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'grn_file' => 'required|mimes:pdf',
            'purchase_order_id' => 'required',
            'vendor_code' => 'required',
        ]);

        if ($validated->fails()) {
            // Redirect back with the first validation error as a flash message
            return redirect()->back()->with('error', $validated->errors()->first());
        }

        $grn_file = $request->file('grn_file');
        $ext = $grn_file->getClientOriginalExtension();
        $grnFileName = strtotime('now') . '-' . $request->purchase_order_id . '.' . $ext;
        $grn_file->move(public_path('uploads/invoices'), $grnFileName);

        $purchaseGRN = new PurchaseGrn;
        $purchaseGRN->purchase_order_id = $request->purchase_order_id;
        $purchaseGRN->vendor_code = $request->vendor_code;
        $purchaseGRN->grn_file = $grnFileName;
        $purchaseGRN->save();

        return redirect()->route('purchase.order.view', $request->purchase_order_id)->with('success', 'GRN imported successfully.');
    }

    public function downloadVendorPO(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchaseOrderId' => 'required|integer|exists:purchase_orders,id',
        ]);

        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/blocked_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $purchaseOrderProducts = PurchaseOrderProduct::with('product', 'salesOrder')->where('purchase_order_id', $request->purchaseOrderId)
            ->where('vendor_code', $request->vendorCode)
            ->with('tempOrderFetch')->get();
            // dd($purchaseOrderProducts);

        // Add rows
        foreach ($purchaseOrderProducts as $order) {
            if ($order->ordered_quantity > 0) {
                $rowsData = [];
                if ($order->sales_order_id) {
                    $rowsData['Sales Order No'] = $order->salesOrder->order_number ?? '';
                }
                $writer->addRow(
                    array_merge($rowsData, [
                        'Purchase Order No' => $order->purchaseOrder->order_number ?? '',
                        'Vendor Code' => $order->vendor_code ?? '',
                        'Portal Code' => $order->tempOrderFetch->item_code ?? '',
                        'Vendor SKU Code' => $order->tempOrderFetch->sku ?? '',
                        'Title' => $order->tempOrderFetch->description ?? '',
                        'MRP' => $order->tempOrderFetch->mrp ?? '',
                        'GST' => $order->tempOrderFetch->gst ?? '',
                        'HSN' => $order->tempOrderFetch->hsn ?? '',
                        'PO Quantity' => $order->ordered_quantity ?? '',
                        'PI Quantity' => '',
                        'Purchase Rate Basic' => $order->product->vendor_purchase_rate ?? '',
                    ])
                );
            }
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, $request->vendorCode . '_PO.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // Return or Accept vendor exceeded products
    public function vendorProductReturn($id)
    {
        $vendorPIProduct = VendorPIProduct::findOrFail($id);
        $vendorPIProduct->issue_status = 'return';
        $vendorPIProduct->save();

        if ($vendorPIProduct) {
            return back()->with('success', 'Products are returned');
        }

        return back()->with('error', 'Something went wrong.');
    }

    // Return or Accept vendor exceeded products
    public function vendorProductAccept($id)
    {
        DB::beginTransaction();

        try {
            $vendorPIProduct = VendorPIProduct::with('order')->findOrFail($id);
            $vendorPIProduct->issue_status = 'accept';

            // Get warehouse_id from VendorPI
            $warehouseId = $vendorPIProduct->order->warehouse_id;

            // Find stock in the selected warehouse
            $product = WarehouseStock::where('sku', $vendorPIProduct->vendor_sku_code)
                ->where('warehouse_id', $warehouseId)
                ->first();

            if ($product) {
                $product->original_quantity += $vendorPIProduct->issue_item;
                $product->available_quantity += $vendorPIProduct->issue_item;
                $product->save();
            }

            $vendorPIProduct->save();

            DB::commit();

            activity()
                ->performedOn($vendorPIProduct)
                ->causedBy(Auth::user())
                ->log('Vendor exceeded products accepted');

            return back()->with('success', 'Products are accepted');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function changeStatus(Request $request)
    {
        try {

            $purchaseOrder = PurchaseOrder::findOrFail($request->order_id);
            $oldStatus = $purchaseOrder->status;
            $purchaseOrder->status = $request->status;
            $purchaseOrder->save();

            if (! $purchaseOrder) {
                return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
            }

            // Create status change notification
            NotificationService::statusChanged('purchase', $purchaseOrder->id, $oldStatus, $purchaseOrder->status);

            return redirect()->back()->with('success', 'Purchase Order status changed to "' . ucfirst(str_replace('_', ' ', $request->status)) . '" successfully! Order ID: ' . $purchaseOrder->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
        }
    }

    public function vendorInvoicePaymentStore(Request $request)
    {
        // Logic to add invoice payment details
        $validated = Validator::make($request->all(), [
            'vendor_pi_id' => 'required',
            'utr_no' => 'required|unique:vendor_payments,payment_utr_no',
            'pay_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        if ($validated->fails()) {
            // If validation fails, redirect back with errors
            return back()->with('error', $validated->errors()->first())->withInput();
        }

        if ($request->input('pay_amount') == 0) {
            return back()->with('error', 'Payment amount is required.');
        }

        DB::beginTransaction();
        try {
            $payment = new VendorPayment;
            $payment->vendor_pi_id = $request->vendor_pi_id;
            $payment->payment_utr_no = $request->input('utr_no');
            $payment->amount = $request->input('pay_amount');
            $payment->payment_method = $request->input('payment_method');

            $vendorPI = VendorPI::findOrFail($request->vendor_pi_id);
            if ($vendorPI->total_due_amount == 0) {
                DB::rollBack();

                return back()->with('error', 'Payment amount is already paid.');
            }

            if ($vendorPI->total_due_amount < $request->input('pay_amount')) {
                DB::rollBack();

                return back()->with('error', 'Payment amount is greater than due amount.');
            }

            if ($vendorPI->total_due_amount > 0 && $vendorPI->total_due_amount <= $vendorPI->total_amount) {
                $vendorPI->total_due_amount -= $request->input('pay_amount');
                $vendorPI->total_paid_amount += $request->input('pay_amount');
                if ($vendorPI->total_due_amount == 0) {
                    $vendorPI->payment_status = 'paid';
                } else {
                    $vendorPI->payment_status = 'partial_paid';
                }
                $vendorPI->save();
            }

            if ($vendorPI->total_due_amount == $request->input('pay_amount')) {
                $payment->payment_status = 'completed';
            } else {
                $payment->payment_status = 'partial';
            }
            $payment->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to add payment: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment added successfully.');
    }
}
