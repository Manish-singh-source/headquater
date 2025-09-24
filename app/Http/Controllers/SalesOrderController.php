<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TempOrder;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\SkuMapping;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\PurchaseOrder;
use App\Models\InvoiceDetails;
use App\Models\WarehouseStock;
use Illuminate\Support\Carbon;
use App\Models\NotFoundTempOrder;
use App\Models\SalesOrderProduct;
use App\Models\WarehouseStockLog;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SalesOrderController extends Controller
{
    //

    public function index()
    {
        $orders = SalesOrder::with('customerGroup')->get();
        return view('salesOrder.index', compact('orders'));
    }

    public function create()
    {
        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('salesOrder.create', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses]);
    }

    public function store(Request $request)
    {
        // get warehouse id, group id and po file 
        $warehouse_id = $request->warehouse_id;
        $customer_group_id = $request->customer_group_id;
        $file = $request->file('csv_file');

        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        DB::beginTransaction();
        try {
            $file = $request->file('csv_file')->getPathname();
            $file_extension = $request->file('csv_file')->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($file, $file_extension);

            $productStockCache = []; // Cache stock by SKU
            $insertedRows = [];
            $skuNotFoundRows = [];
            $insertCount = 0;

            // Not Found Temp Order
            $vendorsNotFound = [];

            // Creating a new Sales order for customer
            $salesOrder = new SalesOrder();
            $salesOrder->warehouse_id = $warehouse_id;
            $salesOrder->customer_group_id = $customer_group_id;
            $salesOrder->save();
            // sales order created

            // Iterate Excel file
            foreach ($reader->getRows() as $record) {
                $sku = trim($record['SKU Code']);
                // $poQty = (int)$record['PO Quantity'];
                $poQty = (int)$record['Purchase Order Quantity'];
                $warehouseId = $request->warehouse_id;
                $vendorCode = $record['Vendor Code'];

                // Default fallback
                $availableQty = 0;
                $shortQty = 0;
                $casePackQty = 0;
                $productStatus = '';
                $customerStatus = '';
                $vendorStatus = '';

                // SKU Mapping
                $skuMapping = SkuMapping::where('customer_sku', $sku)->first();

                if ($skuMapping) {
                    $product = WarehouseStock::with('product')->where('sku', $skuMapping->product_sku)->where('warehouse_id', $warehouseId)->first();
                    $sku = $product->sku;
                } else {
                    $product = WarehouseStock::with('product')->where('sku', $sku)->where('warehouse_id', $warehouseId)->first();
                }
                // sku mapping done

                // ------------ checking product/customer/vendor -----------------
                // after checking sku mapping check if product actual present or not in db
                // if no stock entry present in table
                if (!isset($product)) {
                    $productStatus = 'Not Found';
                }

                // check for customer and vendor available or not
                // customer availibility check
                // $keywords = preg_split('/[\s\-]+/', $record['Facility Location'], -1, PREG_SPLIT_NO_EMPTY);
                // $query = DB::table('customers'); // your table
                // $query->where(function ($q) use ($keywords) {
                //     foreach ($keywords as $word) {
                //         $q->orWhere('shipping_address', 'like', "%{$word}%");
                //     }
                // });
                // $customerInfo = $query->first();
                $customerInfo = Customer::where('facility_name', $record['Facility Name'])->first();
                // customer availibility check done

                if (!$customerInfo) {
                    $customerStatus = 'Not Found';
                }

                // vendor availibility check
                $vendorInfo = Vendor::where('vendor_code', $record['Vendor Code'])
                    ->first();
                // vendor availibility check done

                if (!$vendorInfo) {
                    $vendorsNotFound[] = $record['Vendor Code'];
                    $vendorStatus = 'Not Found';
                }

                if ($customerStatus == 'Not Found' || $vendorStatus == 'Not Found' || $productStatus == 'Not Found') {
                    NotFoundTempOrder::create([
                        'sales_order_id' => $salesOrder->id,
                        'customer_name' => $record['Customer Name'] ?? '',
                        'po_number' => $record['PO Number'] ?? '',
                        'sku' => $record['SKU Code'] ?? '',
                        'facility_name' => $record['Facility Name'] ?? '',
                        'facility_location' => $record['Facility Location'] ?? '',
                        'po_date' => $record['PO Date'] ?? '',
                        'po_expiry_date' => $record['PO Expiry Date'] ?? '',
                        'hsn' => $record['HSN'] ?? '',
                        'gst' => ($record['GST'] < 1 && $record['GST'] > 0)
                            ? intval(round($record['GST'] * 100))  // convert decimals (0.18 -> 18)
                            : intval($record['GST']),              // already integer (e.g., 18)

                        'item_code' => $record['Item Code'] ?? '',
                        'description' => $record['Description'] ?? '',

                        'basic_rate' => $record['Basic Rate'] ?? 0,
                        'product_basic_rate' => $record['Product Basic Rate'] ?? 0,
                        'rate_confirmation' => $record['Basic Rate Confirmation'] ?? 'Incorrect',

                        'net_landing_rate' => $record['Net Landing Rate'] ?? 0,
                        'product_net_landing_rate' => $record['Product Net Landing Rate'] ?? 0,
                        'net_landing_rate_confirmation' => $record['Net Landing Rate Confirmation'] ?? 'Incorrect',

                        'mrp' => $record['MRP'] ?? 0,
                        'product_mrp' => $record['Product MRP'] ?? 0,
                        'mrp_confirmation' => $record['MRP Confirmation'] ?? 'Incorrect',

                        'po_qty' => $record['PO Quantity'] ?? '',
                        'available_quantity' => $availableQty ?? 0,
                        'unavailable_quantity' => $shortQty ?? 0,
                        'block' => ($record['Block'] > $availableQty) ? $availableQty : $record['Block'],

                        'case_pack_quantity' => $casePackQty ?? '',
                        'purchase_order_quantity' => $record['Purchase Order Quantity'] ?? '',
                        'vendor_code' => $record['Vendor Code'] ?? '',
                        'customer_status' => $customerStatus ?? '',
                        'vendor_status' => $vendorStatus ?? '',
                        'product_status' => $productStatus ?? '',
                    ]);
                    continue;
                }
                // ------------ checking product/customer/vendor -----------------



                // 
                // check if product sku present in cache or not 
                if (!isset($productStockCache[$sku])) {
                    // Fetch stock if not already cached
                    // if stock entry present but quantity 0 
                    if (empty($product->available_quantity)) {
                        $productStockCache[$sku] = [
                            'available' => 0,
                        ];
                    } else {
                        // else store available quantity
                        $availableQty = $product->available_quantity;

                        if ($availableQty > 0) {
                            $productStockCache[$sku] = [
                                'available' => $availableQty,
                            ];
                        } else {
                            $productStockCache[$sku] = [
                                'available' => 0,
                            ];
                        }
                    }
                }
                // checked if product sku present in cache or not 


                // Use cached values
                $availableQty = $productStockCache[$sku]['available'];

                // Stock check
                if ($availableQty >= $poQty) {
                    // Sufficient stock
                    $productStockCache[$sku]['available'] -= $poQty;
                    $availableQty = $poQty;
                } else {
                    // Insufficient stock
                    $shortQty = $poQty - $availableQty;
                    $productStockCache[$sku]['available'] = 0;
                }

                if ($product) {
                    $casePackQty = (int)$product->product->pcs_set * (int)$product->product->sets_ctn;
                }

                $tempSalesOrder = TempOrder::create([
                    'customer_name' => $record['Customer Name'] ?? '',
                    'po_number' => $record['PO Number'] ?? '',
                    'sku' => $record['SKU Code'] ?? '',
                    'facility_name' => $record['Facility Name'] ?? '',
                    'facility_location' => $record['Facility Location'] ?? '',
                    'po_date' => $record['PO Date'] ?? '',
                    'po_expiry_date' => $record['PO Expiry Date'] ?? '',
                    'hsn' => $record['HSN'] ?? '',
                    'gst' => ($record['GST'] < 1 && $record['GST'] > 0)
                        ? intval(round($record['GST'] * 100))  // convert decimals (0.18 -> 18)
                        : intval($record['GST']),              // already integer (e.g., 18)
                    'item_code' => $record['Item Code'] ?? '',
                    'description' => $record['Description'] ?? '',

                    'basic_rate' => $record['Basic Rate'] ?? 0,
                    'product_basic_rate' => $record['Product Basic Rate'] ?? 0,
                    'rate_confirmation' => $record['Basic Rate Confirmation'] ?? '',

                    'net_landing_rate' => $record['Net Landing Rate'] ?? 0,
                    'product_net_landing_rate' => $record['Product Net Landing Rate'] ?? 0,
                    'net_landing_rate_confirmation' => $record['Net Landing Rate Confirmation'] ?? '',

                    'mrp' => $record['MRP'] ?? 0,
                    'product_mrp' => $record['Product MRP'] ?? 0,
                    'mrp_confirmation' => $record['MRP Confirmation'] ?? '',

                    'po_qty' => $record['PO Quantity'] ?? '',
                    'available_quantity' => $availableQty ?? 0,
                    'unavailable_quantity' => $shortQty ?? 0,
                    'block' => ($record['Block'] > $availableQty) ? $availableQty : $record['Block'],

                    'case_pack_quantity' => $casePackQty ?? '',
                    'purchase_order_quantity' => $record['Purchase Order Quantity'] ?? '',
                    'vendor_code' => $record['Vendor Code'] ?? '',
                    'customer_status' => 'Found',
                    'vendor_status' => 'Found',
                    'product_status' => 'Found',
                ]);

                // Block Quantity in WarehouseStock Table
                if ($product) {
                    if (intval($record['Block']) > intval($product->available_quantity)) {
                        $blockQuantity = $product->block_quantity + $product->available_quantity;
                    } else {
                        $blockQuantity = $product->block_quantity + $record['Block'];
                    }

                    // Block Quantity from WarehouseStock Table and Update WarehouseStockLog Table 
                    $product->available_quantity = intval($productStockCache[$sku]['available']) ?? 0;
                    $product->block_quantity = $blockQuantity;
                    $product->save();
                }


                $saveOrderProduct = new SalesOrderProduct();
                $saveOrderProduct->sales_order_id = $salesOrder->id;
                $saveOrderProduct->temp_order_id = $tempSalesOrder->id;
                $saveOrderProduct->customer_id = $customerInfo->id ?? null;
                $saveOrderProduct->vendor_code = $vendorInfo->id ?? null;
                $saveOrderProduct->ordered_quantity = $record['PO Quantity'];
                $saveOrderProduct->purchase_ordered_quantity = $record['Purchase Order Quantity'];
                $saveOrderProduct->product_id = $product->product->id ?? null;
                $saveOrderProduct->warehouse_stock_id = $product->id ?? null;
                $saveOrderProduct->sku = $sku;
                $saveOrderProduct->price = $record['Basic Rate'] ?? null;
                // what is exactly subtotal ?? 
                // basic rate * po quantity(customers quantity) or basic rate * purchase order quantity(vendors quantity)
                $saveOrderProduct->subtotal = ($record['Basic Rate'] ?? 0) * ($record['PO Quantity'] ?? 0);
                $saveOrderProduct->save();


                // Make a purchase order if one or more than one products have less quantity in warehouse
                if ($shortQty > 0) {
                    // change sales order status to blocked
                    $salesOrderStatus = SalesOrder::find($salesOrder->id);
                    $salesOrderStatus->status = 'blocked';
                    $salesOrderStatus->save();
                    // sales order status changed to blocked 

                    if (!isset($productStockCache[$vendorCode])) {
                        $productStockCache[$vendorCode] = [
                            'vendor_code' => $vendorCode,
                        ];

                        // Create a new purchase order for the vendor if not already created
                        $purchaseOrder = new PurchaseOrder();
                        $purchaseOrder->sales_order_id = $salesOrder->id;
                        $purchaseOrder->warehouse_id = $warehouse_id;
                        $purchaseOrder->customer_group_id = $customer_group_id;
                        $purchaseOrder->vendor_id = $vendorInfo->id ?? null;
                        $purchaseOrder->vendor_code = $vendorCode;
                        $purchaseOrder->status = 'pending';
                        $purchaseOrder->save();
                    }

                    $vendorCode = $productStockCache[$vendorCode]['vendor_code'];

                    // create purchase order product entry
                    $existingProduct = PurchaseOrderProduct::where('purchase_order_id', $purchaseOrder->id)
                        ->where('sku', $sku)
                        ->where('vendor_code', $vendorCode)
                        ->first();

                    if ($existingProduct) {
                        // Combine quantities if match found
                        $existingProduct->ordered_quantity += $shortQty;
                        $existingProduct->save();
                    } else {
                        // Create a new record
                        $purchaseOrderProduct = new PurchaseOrderProduct();
                        $purchaseOrderProduct->temp_order_id = $tempSalesOrder->id;
                        $purchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
                        $purchaseOrderProduct->sales_order_id = $salesOrder->id;
                        $purchaseOrderProduct->sales_order_product_id = $saveOrderProduct->id;
                        $purchaseOrderProduct->product_id = $product->product->id ?? null;
                        $purchaseOrderProduct->sku = $sku;
                        $purchaseOrderProduct->vendor_code = $vendorCode;
                        $purchaseOrderProduct->ordered_quantity = $shortQty;
                        $purchaseOrderProduct->save();
                    }
                }

                $insertCount++;
            }


            if ($insertCount === 0) {
                DB::rollBack();
                $uniqueString = implode(', ', array_unique($vendorsNotFound));
                return redirect()->back()->with(['error' => 'No valid data found in the CSV file. Please check Vendor Codes: ' . $uniqueString]);
            }

            DB::commit();
            return redirect()->route('sales.order.index')->with('success', 'Order Completed Successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder', 'orderedProducts.vendorPIProduct.order', 'vendorPIs.products')->findOrFail($id);
        foreach ($salesOrder->orderedProducts as $orderedProduct) {
            $orderedProduct->warehouseStockLog = WarehouseStockLog::where('sales_order_id', $orderedProduct->sales_order_id)
                ->where('sku', $orderedProduct->sku)
                ->first();
        }
        return view('salesOrder.edit', compact('salesOrder'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'products_excel' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $file = $request->file('products_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows()->toArray(); // convert to array so we can check duplicates easily

            // 🔹 Step 1: Check for duplicates (Customer + SKU)
            $seen = [];

            foreach ($rows as $record) {
                if (empty($record['SKU Code']) || empty($record['Facility Name'])) {
                    continue;
                }

                $key = strtolower(trim($record['Facility Name'])) . '|' . strtolower(trim($record['SKU Code']));

                if (isset($seen[$key])) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'products_excel' => 'Please check excel file: duplicate SKU (' . $record['SKU Code'] . ') found for same customer (' . $record['Facility Name'] . ').'
                    ]);
                }

                $seen[$key] = true;
            }
            $products = [];
            $insertCount = 0;


            // 🔹 Step 2: Process records if no duplicates
            foreach ($rows as $record) {
                if (empty($record['SKU Code'])) {
                    continue;
                }

                // Find customer
                $customerInfo = Customer::where('facility_name', $record['Facility Name'])->first();

                if (!$customerInfo) {
                    continue;
                }

                // Find sales order product
                $salesOrderProductUpdate = SalesOrderProduct::with('product', 'tempOrder.purchaseOrderProduct')
                    ->where('sku', $record['SKU Code'])
                    ->where('sales_order_id', $request->sales_order_id)
                    ->where('customer_id', $customerInfo->id)
                    ->first();

                if (!$salesOrderProductUpdate) {
                    continue;
                }


                // 3. Build products array for TempOrder::upsert()
                $products[] = [
                    'id' => $salesOrderProductUpdate->temp_order_id,
                    'item_code' => Arr::get($record, 'Item Code', ''),
                    'description' => Arr::get($record, 'Title', ''),
                    'basic_rate' => Arr::get($record, 'Basic Rate', 0),
                    'net_landing_rate' => Arr::get($record, 'Net Landing Rate', 0),
                    'mrp' => Arr::get($record, 'MRP', 0),
                    'rate_confirmation' => ($record['MRP'] == ($salesOrderProductUpdate->product->mrp ?? 0)) ? 'Correct' : 'Incorrect',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 4. Update SalesOrderProduct
                $salesOrderProductUpdate->price = $record['MRP'] ?? 0;
                $salesOrderProductUpdate->subtotal = ($record['Basic Rate'] ?? 0) * ($record['PO Quantity'] ?? 0);
                $salesOrderProductUpdate->ordered_quantity = $record['PO Quantity'] ?? 0;
                $salesOrderProductUpdate->purchase_ordered_quantity = $record['Purchase Order Quantity'] ?? 0;

                // 5. Update warehouse stock if PO quantity changed
                if ($salesOrderProductUpdate->tempOrder && $salesOrderProductUpdate->tempOrder->purchase_order_quantity != $record['Purchase Order Quantity']) {
                    $warehouseStockUpdate = WarehouseStock::find($salesOrderProductUpdate->warehouse_stock_id);
                    if ($warehouseStockUpdate) {
                        if ($salesOrderProductUpdate->tempOrder->purchase_order_quantity > $record['Purchase Order Quantity']) {
                            if ($salesOrderProductUpdate->tempOrder->block > $record['Purchase Order Quantity']) {
                                $extraBlockQuantity = $salesOrderProductUpdate->tempOrder->block - $record['Purchase Order Quantity'];

                                // Prevent negative values
                                $warehouseStockUpdate->block_quantity = max(0, $warehouseStockUpdate->block_quantity - $extraBlockQuantity);

                                // update tempOrder  
                                $salesOrderProductUpdate->tempOrder->po_qty = $record['PO Quantity'];
                                $salesOrderProductUpdate->tempOrder->purchase_order_quantity = $record['Purchase Order Quantity'];

                                $salesOrderProductUpdate->tempOrder->block = max(0, $salesOrderProductUpdate->tempOrder->block - $extraBlockQuantity);
                                $salesOrderProductUpdate->tempOrder->available_quantity = max(0, $salesOrderProductUpdate->tempOrder->block - $extraBlockQuantity);
                                $salesOrderProductUpdate->tempOrder->unavailable_quantity = max(0, ($salesOrderProductUpdate->tempOrder->block - $extraBlockQuantity) - $record['Purchase Order Quantity']);
                                $salesOrderProductUpdate->tempOrder->purchaseOrderProduct->ordered_quantity = max(0, ($salesOrderProductUpdate->tempOrder->block - $extraBlockQuantity) - $record['Purchase Order Quantity']);
                            } else if ($salesOrderProductUpdate->tempOrder->block < $record['Purchase Order Quantity']) {
                                $salesOrderProductUpdate->tempOrder->unavailable_quantity = $record['Purchase Order Quantity'];
                                $salesOrderProductUpdate->tempOrder->purchaseOrderProduct->ordered_quantity = $record['Purchase Order Quantity'];

                                $salesOrderProductUpdate->tempOrder->purchase_order_quantity = $record['Purchase Order Quantity'];
                                $salesOrderProductUpdate->tempOrder->po_qty = $record['PO Quantity'];
                            }

                            $warehouseStockUpdate->save();
                            $salesOrderProductUpdate->tempOrder->purchaseOrderProduct->save();
                            $salesOrderProductUpdate->tempOrder->save();
                        } else {
                            if ($salesOrderProductUpdate->tempOrder->block < $record['Purchase Order Quantity']) {
                                $extraBlockQuantity = $record['Purchase Order Quantity'] - $salesOrderProductUpdate->tempOrder->purchase_order_quantity;

                                // check if warehouse has available quantity so we will block for this product 
                                if ($warehouseStockUpdate->available_quantity >= $extraBlockQuantity) {
                                    $warehouseStockUpdate->available_quantity -= $extraBlockQuantity;
                                    $warehouseStockUpdate->block_quantity += $extraBlockQuantity;

                                    $salesOrderProductUpdate->tempOrder->block += $extraBlockQuantity;
                                    $salesOrderProductUpdate->tempOrder->available_quantity += $extraBlockQuantity;
                                    $salesOrderProductUpdate->tempOrder->unavailable_quantity -= $extraBlockQuantity;
                                    $salesOrderProductUpdate->tempOrder->purchaseOrderProduct->ordered_quantity -= $extraBlockQuantity;

                                    $salesOrderProductUpdate->tempOrder->purchase_order_quantity = $record['Purchase Order Quantity'];
                                    $salesOrderProductUpdate->tempOrder->po_qty = $record['PO Quantity'];
                                } else {

                                    $salesOrderProductUpdate->tempOrder->unavailable_quantity += $extraBlockQuantity;
                                    $salesOrderProductUpdate->tempOrder->purchaseOrderProduct->ordered_quantity += $extraBlockQuantity;
                                    $salesOrderProductUpdate->tempOrder->purchase_order_quantity = $record['Purchase Order Quantity'];
                                    $salesOrderProductUpdate->tempOrder->po_qty = $record['PO Quantity'];
                                }
                            }

                            $warehouseStockUpdate->save();
                            $salesOrderProductUpdate->tempOrder->purchaseOrderProduct->save();
                            $salesOrderProductUpdate->tempOrder->save();
                        }
                    }
                }
                $salesOrderProductUpdate->save();

                $insertCount++;
            }

            // Upsert TempOrder
            if (!empty($products)) {
                TempOrder::upsert(
                    $products,
                    ['id'],
                    ['item_code', 'description', 'basic_rate', 'net_landing_rate', 'mrp', 'rate_confirmation', 'updated_at']
                );
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            DB::commit();
            return redirect()->route('sales.order.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    public function view($id)
    {
        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.tempOrder.vendorPIProduct',
            'orderedProducts.warehouseStock',
        ])
            ->withSum('orderedProducts', 'purchase_ordered_quantity')
            ->withSum('orderedProducts', 'ordered_quantity')
            // ->withSum('orderedProducts.tempOrder', 'available_quantity')
            // ->withSum('tempOrders', 'available_quantity')
            // ->withSum('tempOrders', 'vendor_pi_fulfillment_quantity')
            ->withCount('notFoundTempOrderByProduct')
            ->withCount('notFoundTempOrderByCustomer')
            ->withCount('notFoundTempOrderByVendor')
            ->findOrFail($id);

        $vendorPiFulfillmentTotal = 0;
        $vendorPiReceivedTotal = 0;
        $availableQuantity = 0;
        $caching = [];

        foreach ($salesOrder->orderedProducts as $product) {
            if (isset($product->tempOrder)) {
                $vendorPiFulfillmentTotal += $product->tempOrder->vendor_pi_fulfillment_quantity;
                if ($product->tempOrder?->vendor_pi_received_quantity) {
                    // $product->tempOrder->vendor_pi_fulfillment_quantity = $product->tempOrder->vendor_pi_received_quantity;
                    $vendorPiReceivedTotal += $product->tempOrder->vendor_pi_received_quantity;
                }

                // if ($product->tempOrder->purchase_order_quantity <= ($product->tempOrder?->available_quantity ?? 0) + ($product->tempOrder?->vendor_pi_fulfillment_quantity ?? 0)) {
                //     $product->tempOrder->vendor_pi_fulfillment_quantity = $product->tempOrder->purchase_order_quantity;
                // }

                // $caching[] = $product->tempOrder->purchase_order_quantity . ' ' . $product->tempOrder?->vendor_pi_fulfillment_quantity;
                // $vendorPiFulfillmentTotal += $product->tempOrder->vendor_pi_fulfillment_quantity;
                // $availableQuantity += $product->tempOrder->available_quantity;
            }
        }

        // dd($salesOrder, $vendorPiFulfillmentTotal, $vendorPiReceivedTotal);

        return view('salesOrder.view', compact('salesOrder', 'vendorPiFulfillmentTotal', 'availableQuantity', 'vendorPiReceivedTotal'));
    }

    public function destroy($id)
    {
        try {
            $order = SalesOrder::findOrFail($id);

            $orderedProducts = SalesOrderProduct::with('tempOrder')->where('sales_order_id', $id)->get();

            foreach ($orderedProducts as $product) {
                $warehouseStock = WarehouseStock::where('id', $product->warehouse_stock_id)->first();

                if (isset($warehouseStock) && $warehouseStock->block_quantity > 0) {
                    $warehouseStock->block_quantity = $warehouseStock->block_quantity - $orderedProducts->tempOrder->block;
                    $warehouseStock->available_quantity = $warehouseStock->available_quantity + $orderedProducts->tempOrder->block;
                    $warehouseStock->save();
                }

                // Delete Temp Order Entry
                if (isset($product->tempOrder)) {
                    $product->tempOrder->delete();
                }
            }

            $order->delete();
            return redirect()->route('sales.order.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong: Please Try Again.']);
        }
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        try {
            foreach ($ids as $salesOrderProductId) {

                $salesOrderProduct = SalesOrderProduct::with('tempOrder')->find($salesOrderProductId);

                if (!$salesOrderProduct) {
                    continue; // Skip if not found
                }

                if ($salesOrderProduct->tempOrder && $salesOrderProduct->tempOrder->block > 0) {
                    $warehouseStock = WarehouseStock::find($salesOrderProduct->warehouse_stock_id);

                    if ($warehouseStock) {
                        $availableQty = $warehouseStock->available_quantity + $salesOrderProduct->tempOrder->block;
                        $blockQty = $warehouseStock->block_quantity - $salesOrderProduct->tempOrder->block;

                        while ($availableQty > 0) {
                            $checkProduct = SalesOrderProduct::where('id', '!=', $salesOrderProduct->id)
                                ->where('sales_order_id', $salesOrderProduct->sales_order_id)
                                ->where('sku', $salesOrderProduct->sku)
                                ->whereHas('tempOrder', function ($query) {
                                    $query->where('unavailable_quantity', '>', 0);
                                })
                                ->with('tempOrder')
                                ->first();

                            if (!$checkProduct || !$checkProduct->tempOrder) {
                                break; // No more matching products
                            }

                            $tempOrder = $checkProduct->tempOrder;
                            $wanted = $tempOrder->po_qty - $tempOrder->available_quantity;

                            if ($wanted <= 0) {
                                break; // No more quantity needed
                            }

                            $allocated = min($wanted, $availableQty);
                            $availableQty -= $allocated;
                            $blockQty += $allocated;

                            $tempOrder->available_quantity += $allocated;
                            $tempOrder->unavailable_quantity -= $allocated;
                            $tempOrder->block += $allocated;
                            $tempOrder->save();
                        }

                        $warehouseStock->block_quantity = $blockQty;
                        $warehouseStock->available_quantity = $availableQty;
                        $warehouseStock->save();
                    }
                }

                // Delete temp order if exists
                if ($salesOrderProduct->tempOrder) {
                    $salesOrderProduct->tempOrder->delete();
                }

                $salesOrderProduct->delete();
            }

            return redirect()->back()->with('success', 'Selected products deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }


    public function  changeStatus(Request $request)
    {
        try {

            $salesOrder = SalesOrder::findOrFail($request->order_id);
            $salesOrderDetails = SalesOrderProduct::with('tempOrder')->where('sales_order_id', $salesOrder->id)->get();

            if ($request->status == 'ready_to_ship') {
                $customerFacilityName = SalesOrderProduct::with('customer')
                    ->where('sales_order_id', $salesOrder->id)
                    ->get()
                    ->pluck('customer')
                    ->filter()
                    ->unique('client_name')
                    ->pluck('client_name', 'id',);

                foreach ($customerFacilityName as $customer_id => $facility_name) {
                    $invoice = new Invoice();
                    $invoice->warehouse_id = $salesOrder->warehouse_id;
                    $invoice->invoice_number = 'INV-' . time() . '-' . $customer_id;
                    $invoice->customer_id = $customer_id;
                    $invoice->sales_order_id = $salesOrder->id;
                    $invoice->invoice_date = now();
                    $invoice->round_off = 0;

                    $invoice->total_amount = $salesOrder->orderedProducts->sum(function ($product) {
                        return $product->ordered_quantity * $product->product->mrp; // Assuming 'price' is the field in Product model
                    });
                    $invoice->save();



                    $invoiceDetails = [];
                    foreach ($salesOrderDetails as $detail) {
                        if ($detail->customer_id == $customer_id) {
                            $product = Product::where('sku', $detail->sku)->first();
                            $invoiceDetails[] = [
                                'invoice_id' => $invoice->id,
                                'product_id' => $product->id,
                                'temp_order_id' => $detail->temp_order_id,
                                'quantity' => $detail->ordered_quantity,
                                'unit_price' => $product->mrp,
                                'discount' => 0, // Assuming no discount for simplicity
                                'amount' => $detail->ordered_quantity * $product->mrp,
                                'tax' => $product->gst ?? 0, // Assuming tax is a field in Product model
                                'total_price' => ($detail->ordered_quantity * $product->mrp) - 0, // Total price after discount
                                'description' => isset($detail->tempOrder) ? $detail->tempOrder->description : null, // Assuming description is in TempOrder
                            ];

                            $detail->status = 'dispatched';
                            $detail->save();
                        }
                    }
                    InvoiceDetails::insert($invoiceDetails);
                }

                $salesOrderUpdate = SalesOrder::with([
                    'customerGroup',
                    'warehouse',
                    'orderedProducts.product',
                    'orderedProducts.customer',
                    'orderedProducts.tempOrder',
                    'orderedProducts.warehouseStock',
                ])
                    ->findOrFail($request->order_id);

                foreach ($salesOrderUpdate->orderedProducts as $order) {
                    if ($order->tempOrder?->vendor_pi_received_quantity) {
                        $order->tempOrder->vendor_pi_fulfillment_quantity = $order->tempOrder->vendor_pi_received_quantity;
                    }
                    if ($order->ordered_quantity <= ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0)) {
                        if (!empty($order->final_dispatched_quantity)) {
                            $order->final_dispatched_quantity = $order->final_dispatched_quantity;
                        } else {
                            $order->final_dispatched_quantity = $order->ordered_quantity;
                        }
                    } else {
                        if (!empty($order->final_dispatched_quantity)) {
                            $order->final_dispatched_quantity = $order->final_dispatched_quantity;
                        } else {
                            $order->final_dispatched_quantity = ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                        }
                        $order->final_dispatched_quantity = ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                    }
                    $order->save();
                }

                $salesOrder->status = $request->status;
            } else if ($request->status == 'ready_to_package') {
                $salesOrderUpdate = SalesOrder::with([
                    'customerGroup',
                    'warehouse',
                    'orderedProducts.product',
                    'orderedProducts.customer',
                    'orderedProducts.tempOrder',
                    'orderedProducts.warehouseStock',
                ])
                    ->findOrFail($request->order_id);

                foreach ($salesOrderUpdate->orderedProducts as $order) {
                    if ($order->tempOrder?->vendor_pi_received_quantity) {
                        $order->tempOrder->vendor_pi_fulfillment_quantity = $order->tempOrder->vendor_pi_received_quantity;
                    }
                    if ($order->ordered_quantity <= ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0)) {
                        $order->dispatched_quantity = $order->ordered_quantity;
                    } else {
                        $order->dispatched_quantity = ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                    }
                    $order->status = 'packaging';
                    $order->save();
                }
                $salesOrderUpdate->save();

                $salesOrder->status = $request->status;
            } else if ($request->status == 'delivered') {
                $customerFacilityName = SalesOrderProduct::with('customer')
                    ->where('sales_order_id', $salesOrder->id)
                    ->get()
                    ->pluck('customer')
                    ->filter()
                    ->unique('client_name')
                    ->pluck('client_name', 'id',);

                foreach ($customerFacilityName as $customer_id => $facility_name) {
                    foreach ($salesOrderDetails as $detail) {
                        if ($detail->customer_id == $customer_id) {
                            $detail->status = 'completed';
                            $detail->save();
                        }
                    }
                }
                $salesOrder->status = $request->status;
            } else if ($request->status == 'completed') {
                $salesOrder->status = $request->status;
            }
            $salesOrder->save();

            if (!$salesOrder) {
                return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
            }
            if ($salesOrder->status == 'ready_to_package') {
                return redirect()->route('packing.products.view', $request->order_id)->with('success', 'Status has been changed.');
            } else if ($salesOrder->status == 'ready_to_ship') {
                return redirect()->route('readyToShip.view', $request->order_id)->with('success', 'Status has been changed.');
            } else if ($salesOrder->status == 'completed') {
                return redirect()->route('sales.order.index')->with('success', 'Status has been changed.');
            } else if ($salesOrder->status == 'delivered') {
                return redirect()->route('sales.order.index')->with('success', 'Status has been changed.');
            } else {
                return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.' . $e->getMessage());
        }
    }

    public function checkProductsStock(Request $request)
    {

        $file = $request->file('csv_file');
        if (!$file) {
            return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();

        $reader = SimpleExcelReader::create($file, $file_extension);

        $productStockCache = []; // Cache stock by SKU
        $insertedRows = [];

        try {

            foreach ($reader->getRows() as $record) {
                $sku = trim($record['SKU Code']);  // customer sku
                $poQty = (int)$record['PO Quantity'];
                $warehouseId = $request->warehouse_id;

                // Default fallback
                $availableQty = 0;
                $shortQty = 0;
                $casePackQty = 0;
                $reason = '';

                // map sku with product
                $skuMapping = SkuMapping::where('customer_sku', $sku)->first();

                if ($skuMapping) {
                    $product = Product::where('sku', $skuMapping->product_sku)->first();
                    $sku = $product->sku;
                } else {
                    $product = Product::where('sku', $sku)->first();
                }

                // check if product is present
                if (!$product) {
                    $reason = 'SKU Not Found';
                }

                $customer = Customer::where('facility_name', $record['Facility Name'])->first();

                // Check if customer is present
                if (!$customer) {
                    if (!empty($reason)) {
                        $reason .= ' | ';
                    }
                    $reason .= 'Customer Not Found';
                }

                if ($reason != '') {
                    $gst = ($record['GST'] < 1 && $record['GST'] > 0)
                        ? intval(round($record['GST'] * 100))  // convert decimals (0.18 -> 18)
                        : intval($record['GST']);
                    $netLandingRate = intval($record['Basic Rate']) + (intval($record['Basic Rate']) * $gst / 100);
                    $netLandingRate = number_format($netLandingRate, 2, '.', '');

                    $insertedRows[] = [
                        'Customer Name' => $record['Customer Name'] ?? '',
                        'PO Number' => $record['PO Number'] ?? '',
                        'SKU Code' => $sku ?? '',
                        'Facility Name' => $record['Facility Name'] ?? '',
                        'Facility Location' => $record['Facility Location'] ?? '',
                        'PO Date' => Carbon::parse($record['PO Date'])->format('d-m-Y'),
                        'PO Expiry Date' => Carbon::parse($record['PO Expiry Date'])->format('d-m-Y'),
                        'HSN' => $record['HSN'] ?? '',
                        'Item Code' => $record['Item Code'] ?? '',
                        'Description' => $record['Description'] ?? '',
                        'GST' => $gst,

                        'Basic Rate' => $record['Basic Rate'] ?? 0,
                        'Product Basic Rate' => 0,
                        'Basic Rate Confirmation' => 'Incorrect',

                        'Net Landing Rate' => $netLandingRate ?? 0,
                        'Product Net Landing Rate' => 0,
                        'Net Landing Rate Confirmation' => 'Incorrect',

                        'MRP' => $record['MRP'] ?? 0,
                        'Product MRP' =>  0,
                        'MRP Confirmation' => 'Incorrect',

                        'Case Pack Quantity' => 0,
                        'PO Quantity' => $poQty,
                        'Available Quantity' => 0,
                        'Unavailable Quantity' => 0,
                        'Reason' => $reason
                    ];
                    continue;
                }

                // Fetch stock if not already cached
                if (!isset($productStockCache[$sku])) {
                    $stockEntry = WarehouseStock::with('product')
                        ->where('sku', $sku)
                        ->where('warehouse_id', $warehouseId)
                        ->first();

                    if (!isset($stockEntry)) {
                        $productStockCache[$sku] = [
                            'available' => 0,
                        ];
                    } else {
                        $quantity = $stockEntry->available_quantity;
                        $productStockCache[$sku] = [
                            'available' => $quantity,
                        ];
                    }
                }

                // Use cached values
                $availableQty = $productStockCache[$sku]['available'];

                // Stock check
                if ($availableQty >= $poQty) {
                    // Sufficient stock
                    $productStockCache[$sku]['available'] -= $poQty;
                    $availableQty = $poQty;
                } else {
                    // Insufficient stock
                    $shortQty = $poQty - $availableQty;
                    $productStockCache[$sku]['available'] = 0;
                }

                if ($stockEntry) {
                    $casePackQty = (int)$stockEntry->product->pcs_set * (int)$stockEntry->product->sets_ctn;


                    $gst = ($record['GST'] < 1 && $record['GST'] > 0)
                        ? intval(round($record['GST'] * 100))  // convert decimals (0.18 -> 18)
                        : intval($record['GST']);
                    $netLandingRate = $record['Basic Rate'] + ($record['Basic Rate'] * $gst / 100);
                    $netLandingRate = number_format($netLandingRate, 2, '.', '');

                    $tolerance = 0.5; // define how close is acceptable
                    $isNearlyEqual = abs(intval($record['Basic Rate']) - intval($stockEntry->product->basic_rate)) <= $tolerance;
                    $rateConfirmation = ($isNearlyEqual) ? 'Correct' : 'Incorrect';
                    $netLandingRateConfirmation = ($isNearlyEqual) ? 'Correct' : 'Incorrect';

                    // $rateConfirmation = ($record['Basic Rate'] == $stockEntry->product->basic_rate) ? 'Correct' : 'Incorrect';
                    // $netLandingRateConfirmation = ($netLandingRate == $stockEntry->product->net_landing_rate) ? 'Correct' : 'Incorrect';
                    $mrpConfirmation = abs(intval($record['MRP']) - intval($stockEntry->product->mrp)) <= $tolerance ? 'Correct' : 'Incorrect';
                }

                $insertedRows[] = [
                    'Customer Name' => $record['Customer Name'],
                    'PO Number' => $record['PO Number'],
                    'SKU Code' => $sku,
                    'Facility Name' => $record['Facility Name'],
                    'Facility Location' => $record['Facility Location'],
                    'PO Date' => Carbon::parse($record['PO Date'])->format('d-m-Y'),
                    'PO Expiry Date' => Carbon::parse($record['PO Expiry Date'])->format('d-m-Y'),
                    'HSN' => $record['HSN'],
                    'Item Code' => $record['Item Code'],
                    'Description' => $record['Description'],
                    'GST' => $gst,

                    'Basic Rate' => $record['Basic Rate'],
                    'Product Basic Rate' => $stockEntry->product->basic_rate ?? 0,
                    'Basic Rate Confirmation' => $rateConfirmation,

                    'Net Landing Rate' => $netLandingRate,
                    'Product Net Landing Rate' => $stockEntry->product->net_landing_rate ?? 0,
                    'Net Landing Rate Confirmation' => $netLandingRateConfirmation,

                    'MRP' => $record['MRP'],
                    'Product MRP' => $stockEntry->product->mrp ?? 0,
                    'MRP Confirmation' => $mrpConfirmation,

                    'Case Pack Quantity' => $casePackQty ?? 0,
                    'PO Quantity' => $poQty,
                    'Available Quantity' => $availableQty,
                    'Unavailable Quantity' => $shortQty,
                    'Reason' => ''
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
            return view('salesOrder.process-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses, 'fileData' => $insertedRows]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing the CSV file. Please Check the file format and try again.');
        }
    }


    public function downloadBlockedCSV()
    {
        $originalPath = public_path(session('processed_csv_path'));

        if (!file_exists($originalPath)) {
            abort(404, 'CSV file not found.');
        }

        // Create temporary .xlsx file
        $tempXlsxPath = storage_path('app/blocked_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Add rows while transforming
        SimpleExcelReader::create($originalPath)->getRows()->each(function (array $row) use ($writer) {
            $product = Product::where('sku', $row['SKU Code'])->first();

            $writer->addRow([
                'Customer Name' => $row['Customer Name'] ?? '',
                'PO Number' => $row['PO Number'] ?? '',
                'SKU Code' => $row['SKU Code'] ?? '',
                'Facility Name' => $row['Facility Name'] ?? '',
                'Facility Location' => $row['Facility Location'] ?? '',
                'PO Date' => $row['PO Date'] ?? '',
                'PO Expiry Date' => $row['PO Expiry Date'] ?? '',
                'HSN' => $row['HSN'] ?? '',
                'GST' => $row['GST'] ?? '',
                'Item Code' => $row['Item Code'] ?? '',
                'Description' => $row['Description'] ?? '',

                'Basic Rate' => $row['Basic Rate'] ?? 0,
                'Product Basic Rate' => $row['Product Basic Rate'] ?? 0,
                'Basic Rate Confirmation' => $row['Basic Rate Confirmation'] ?? 'Incorrect',

                'Net Landing Rate' => $row['Net Landing Rate'] ?? 0,
                'Product Net Landing Rate' => $row['Product Net Landing Rate'] ?? 0,
                'Net Landing Rate Confirmation' => $row['Net Landing Rate Confirmation'] ?? 'Incorrect',

                'MRP' => $row['MRP'] ?? 0,
                'Product MRP' => $row['Product MRP'] ?? 0,
                'MRP Confirmation' => $row['MRP Confirmation'] ?? 'Incorrect',

                'PO Quantity' => $row['PO Quantity'] ?? 0,
                'Available Quantity' => $row['Available Quantity'] ?? '',
                'Unavailable Quantity' => $row['Unavailable Quantity'] ?? '',
                'Case Pack Quantity' => $row['Case Pack Quantity'] ?? '',
                'Purchase Order Quantity' => $row['Unavailable Quantity'] ?? '',
                'Block' => '',
                'Vendor Code' => $product->vendor_code ?? '',
                'Reason' => $row['Reason'] ?? '',
            ]);
        });

        $writer->close();

        // Return the XLSX as a download
        return response()->download($tempXlsxPath, 'blocked_orders.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadPoExcel(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'salesOrderId' => 'required',
        ]);

        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/order_po_update_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);


        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.tempOrder',
            'orderedProducts.warehouseStock',
        ])
            ->withSum('orderedProducts', 'ordered_quantity')
            ->findOrFail($request->salesOrderId);

        foreach ($salesOrder->orderedProducts as $order) {
            $fulfilledQuantity = 0;

            if ($order->ordered_quantity <= ($order->tempOrder->available_quantity ?? 0) + ($order->tempOrder->vendor_pi_fulfillment_quantity ?? 0)) {
                $fulfilledQuantity = ($order->tempOrder->available_quantity ?? 0) + ($order->tempOrder->vendor_pi_fulfillment_quantity ?? 0);
            } else {
                $fulfilledQuantity = ($order->tempOrder->available_quantity ?? 0) + ($order->tempOrder->vendor_pi_fulfillment_quantity ?? 0);
            }

            $writer->addRow([
                'Order No' => $salesOrder->id,
                'Customer Name' => $order->tempOrder->customer_name,
                'Facility Name' => $order->tempOrder->facility_name,
                'Facility Location' => $order->tempOrder->facility_location,
                'HSN' => $order->tempOrder->hsn,
                'GST' => $order->tempOrder->gst,
                'Item Code' =>  $order->tempOrder->item_code,
                'SKU Code' => $order->tempOrder->sku,
                'Title' => $order->tempOrder->description,
                'Basic Rate' => $order->tempOrder->basic_rate,
                'Net Landing Rate' => $order->tempOrder->net_landing_rate,
                'MRP' =>  $order->tempOrder->mrp,
                'Product MRP' => $order->tempOrder->product_mrp,
                'Rate Confirmation' => $order->tempOrder->rate_confirmation ?? 'Incorrect',
                'PO Quantity' => $order->ordered_quantity,
                'Purchase Order Quantity' => $order->purchase_ordered_quantity,
                'Qty Fullfilled' => $fulfilledQuantity,
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'vendor_po.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }


    public function downloadNotFoundSku($id)
    {
        if (!$id) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/not_found_sku_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $notFoundSku = NotFoundTempOrder::where('sales_order_id', $id)->where('product_status', 'Not Found')->get();

        // Add rows
        foreach ($notFoundSku as $product) {
            $writer->addRow([
                'Sales Order No' => $product->sales_order_id ?? '',
                'Customer Name' => $product->customer_name ?? '',
                'PO Number' => $product->po_number ?? '',
                'SKU Code' => $product->sku ?? '',
                'Facility Name' => $product->facility_name ?? '',
                'Facility Location' => $product->facility_location ?? '',
                'PO Date' => $product->po_date ?? '',
                'PO Expiry Date' => $product->po_expiry_date ?? '',
                'HSN' => $product->hsn ?? '',
                'Item Code' => $product->item_code ?? '',
                'Description' => $product->description ?? '',
                'Basic Rate' => $product->basic_rate ?? '',
                'GST' => $product->gst ?? '',
                'Net Landing Rate' => $product->net_landing_rate ?? '',
                'MRP' => $product->mrp ?? '',
                'PO Quantity' => $product->po_qty ?? '',
                'SKU Status' => $product->product_status,
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'Products-SKU-Not-Found.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadNotFoundCustomer($id)
    {
        if (!$id) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/not_found_customer_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $notFoundSku = NotFoundTempOrder::where('sales_order_id', $id)->where('customer_status', 'Not Found')->get();

        // Add rows
        foreach ($notFoundSku as $product) {
            $writer->addRow([
                'Sales Order No' => $product->sales_order_id ?? '',
                'Customer Name' => $product->customer_name ?? '',
                'PO Number' => $product->po_number ?? '',
                'SKU Code' => $product->sku ?? '',
                'Facility Name' => $product->facility_name ?? '',
                'Facility Location' => $product->facility_location ?? '',
                'PO Date' => $product->po_date ?? '',
                'PO Expiry Date' => $product->po_expiry_date ?? '',
                'HSN' => $product->hsn ?? '',
                'Item Code' => $product->item_code ?? '',
                'Description' => $product->description ?? '',
                'Basic Rate' => $product->basic_rate ?? '',
                'GST' => $product->gst ?? '',
                'Net Landing Rate' => $product->net_landing_rate ?? '',
                'MRP' => $product->mrp ?? '',
                'PO Quantity' => $product->po_qty ?? '',
                'Customer Status' => $product->customer_status,
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'Products-Customer-Not-Found.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function downloadNotFoundVendor($id)
    {
        if (!$id) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/not_found_vendor_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $notFoundSku = NotFoundTempOrder::where('sales_order_id', $id)->where('vendor_status', 'Not Found')->get();

        // Add rows
        foreach ($notFoundSku as $product) {
            $writer->addRow([
                'Sales Order No' => $product->sales_order_id ?? '',
                'Customer Name' => $product->customer_name ?? '',
                'PO Number' => $product->po_number ?? '',
                'SKU Code' => $product->sku ?? '',
                'Facility Name' => $product->facility_name ?? '',
                'Facility Location' => $product->facility_location ?? '',
                'PO Date' => $product->po_date ?? '',
                'PO Expiry Date' => $product->po_expiry_date ?? '',
                'HSN' => $product->hsn ?? '',
                'Item Code' => $product->item_code ?? '',
                'Description' => $product->description ?? '',
                'Basic Rate' => $product->basic_rate ?? '',
                'GST' => $product->gst ?? '',
                'Net Landing Rate' => $product->net_landing_rate ?? '',
                'MRP' => $product->mrp ?? '',
                'PO Quantity' => $product->po_qty ?? '',
                'Vendor Code' => $product->vendor_code ?? '',
                'Vendor Status' => $product->vendor_status,
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'Products-Vendor-Not-Found.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }


    public function generateInvoice(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'ids' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated->errors());
        }

        $ids = explode(',', $request->ids);

        try {
            // Fetch all selected sales order products with relations
            $salesOrderProducts = SalesOrderProduct::with(['tempOrder', 'product', 'customer'])
                ->whereIn('id', $ids)
                ->get();

            if ($salesOrderProducts->isEmpty()) {
                return redirect()->back()->with('error', 'No sales order products found.');
            }

            // Group by unique combination: customer_address + po_number
            $grouped = $salesOrderProducts->groupBy(function ($item) {
                $customerAddress = $item->customer->address ?? '';
                $poNumber = $item->po_number ?? '';
                return $customerAddress . '|' . $poNumber;
            });

            foreach ($grouped as $groupKey => $groupItems) {
                $firstItem = $groupItems->first();

                // Create Invoice
                $invoice = new Invoice();
                $invoice->warehouse_id   = $firstItem->warehouse_id ?? 1;
                $invoice->invoice_number = 'INV-' . time() . '-' . $firstItem->customer_id;
                $invoice->customer_id    = $firstItem->customer_id;
                $invoice->sales_order_id = $firstItem->sales_order_id;
                $invoice->invoice_date   = now();
                $invoice->round_off      = 0;

                // Calculate total amount of all grouped items
                $invoice->total_amount = $groupItems->sum(function ($detail) {
                    return $detail->ordered_quantity * $detail->product->mrp;
                });

                $invoice->save();

                // Create Invoice Details
                foreach ($groupItems as $salesOrderDetail) {
                    $invoiceDetail = new InvoiceDetails();
                    $invoiceDetail->invoice_id   = $invoice->id;
                    $invoiceDetail->product_id   = $salesOrderDetail->product_id;
                    $invoiceDetail->temp_order_id = $salesOrderDetail->temp_order_id;
                    $invoiceDetail->quantity     = $salesOrderDetail->ordered_quantity;
                    $invoiceDetail->unit_price   = $salesOrderDetail->product->mrp;
                    $invoiceDetail->discount     = 0;
                    $invoiceDetail->amount       = $salesOrderDetail->ordered_quantity * $salesOrderDetail->product->mrp;
                    $invoiceDetail->tax          = $salesOrderDetail->product->gst ?? 0;
                    $invoiceDetail->total_price  = $invoiceDetail->amount; // no discount applied
                    $invoiceDetail->description  = $salesOrderDetail->tempOrder->description ?? null;
                    $invoiceDetail->save();

                    // Optional: update product status
                    $salesOrderDetail->status = 'dispatched';
                    $salesOrderDetail->save();
                }
            }

            return redirect()->back()->with('success', 'Invoices generated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invoice generation failed. ' . $e->getMessage());
        }
    }
}
