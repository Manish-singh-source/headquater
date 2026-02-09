<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\NotFoundTempOrder;
use App\Models\Product;
use App\Models\ProductMapping;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Models\SkuMapping;
use App\Models\TempOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\Models\WarehouseAllocation;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockLog;
use App\Services\NotificationService;
use App\Services\WarehouseAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SalesOrderController extends Controller
{
    //

    /**
     * Check SKU Mapping
     *
     * @param [type] $customerSku
     * @return [type] $productSku|null
     */
    protected function mapSku($customerSku)
    {
        $productSku = SkuMapping::where('customer_sku', $customerSku)->first();
        if (! $productSku) {
            return null;
        }

        return $productSku;
    }

    /**
     * Check Customer Existence
     *
     * @param [type] $customerName
     * @return [type] $customerInfo|null
     */
    protected function checkCustomerExistence(string $facilityName)
    {
        return Customer::where('facility_name', $facilityName)->first();
    }

    protected function checkVendorExistence(string $vendorCode)
    {
        return Vendor::where('vendor_code', $vendorCode)->first();
    }

    protected function checkDuplicateSkuInExcel($rows)
    {
        $seen = [];

        foreach ($rows as $record) {
            if (empty($record['SKU Code']) || empty($record['PO Number']) || empty($record['Item Code'])) {
                continue;
            }

            $key = strtolower(trim($record['PO Number'])) . '|' . strtolower(trim($record['SKU Code'])) . '|' . strtolower(trim($record['Item Code']));

            if (isset($seen[$key])) {
                return 'Please check excel file: duplicate SKU (' . $record['SKU Code'] . ') found for same customer (' . $record['PO Number'] . ').';
            }

            $seen[$key] = true;
        }

        return null;
    }

    /**
     * Display a listing of sales orders with the customers groups.
     *
     * @param None
     * @return view
     */
    public function index()
    {
        $orders = SalesOrder::with('customerGroup')->get();

        return view('salesOrder.index', compact('orders'));
    }

    /**
     * Show the form for creating a new sales order.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customerGroup = CustomerGroup::active()->get();
        $warehouses = Warehouse::where('status', '1')->get();

        return view('salesOrder.create', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses]);
    }

    /**
     * Store a newly created sales order in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get warehouse id, group id and po file
        $warehouse_id = $request->warehouse_id;
        $customer_group_id = $request->customer_group_id;
        $file = $request->file('csv_file');

        if (! $file) {
            return redirect()->back()->with(['csv_file' => 'Please upload a CSV file.']);
        }

        // Check if auto allocation is selected
        $isAutoAllocation = ($warehouse_id === 'auto');

        DB::beginTransaction();
        try {
            $file = $request->file('csv_file')->getPathname();
            $file_extension = $request->file('csv_file')->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($file, $file_extension);

            $rows = $reader->getRows()->toArray(); // convert to array so we can check duplicates easily

            // 🔹 Step 1: Check for duplicates (Customer + SKU)
            $duplicateCheck = $this->checkDuplicateSkuInExcel($rows);
            if ($duplicateCheck) {
                return redirect()->back()->with(['error' => $duplicateCheck]);
            }

            $productStockCache = [];
            $insertedRows = [];
            $skuNotFoundRows = [];
            $insertCount = 0;

            // Not Found Temp Order
            $vendorsNotFound = [];

            // prefix for sales order using last order
            $lastSalesOrder = SalesOrder::orderBy('id', 'desc')->first();
            if ($lastSalesOrder && $lastSalesOrder->order_number) {
                $prefix = $lastSalesOrder ? 'SO-' . date('Ym', strtotime($lastSalesOrder->created_at)) . '-' : 'SO-' . date('Ym') . '-';
                $lastSalesOrderNumber = $lastSalesOrder ? intval(explode('-', $lastSalesOrder->order_number)[2]) : 0;
            } else {
                $prefix = 'SO-' . date('Ym') . '-';
                $lastSalesOrderNumber = 0;
            }
            $nextSalesOrderNumber = $lastSalesOrderNumber + 1;
            $nextSalesOrderNumber = str_pad($nextSalesOrderNumber, 4, '0', STR_PAD_LEFT);
            $nextSalesOrderNumber = $prefix . $nextSalesOrderNumber;

            // Creating a new Sales order for customer
            $salesOrder = new SalesOrder;
            $salesOrder->order_number = $nextSalesOrderNumber;
            $salesOrder->warehouse_id = $isAutoAllocation ? null : $warehouse_id;
            $salesOrder->customer_group_id = $customer_group_id;
            $salesOrder->order_date = now();
            $salesOrder->status = 'blocked';
            $salesOrder->save();
            // sales order created

            // Iterate Excel file
            foreach ($reader->getRows() as $key => $record) {
                $sku = trim($record['SKU Code']);
                $poQty = (int) $record['PO Quantity'];
                $purchaseQty = (int) $record['Purchase Order Quantity'];
                $warehouseId = $request->warehouse_id;
                // $vendorCode = $record['Vendor Code'];
                $vendorCode = trim($record['Vendor Code']);

                // Default fallback
                $availableQty = 0;
                $shortQty = 0;
                $casePackQty = 0;
                $productStatus = '';
                $customerStatus = '';
                $vendorStatus = '';

                // SKU Mapping
                $skuMapping = $this->mapSku($sku);

                // If auto allocation, get product from any warehouse
                if ($isAutoAllocation) {
                    if ($skuMapping) {
                        $product = WarehouseStock::with('product')->where('sku', $skuMapping->product_sku)->first();
                        $sku = $product ? $product->sku : $skuMapping->product_sku;
                    } else {
                        $product = WarehouseStock::with('product')->where('sku', $sku)->first();
                    }

                    // If not found in warehouse_stocks, check in products table
                    if (! $product) {
                        $productMaster = Product::where('sku', $sku)->first();
                        if ($productMaster) {
                            // Create a pseudo product object for consistency
                            $product = (object) [
                                'sku' => $productMaster->sku,
                                'product' => $productMaster,
                                'available_quantity' => 0, // Will be calculated from all warehouses
                            ];
                        }
                    }
                } else {
                    // Single warehouse selection
                    if ($skuMapping) {
                        $product = WarehouseStock::with('product')->where('sku', $skuMapping->product_sku)->where('warehouse_id', $warehouseId)->first();
                        $sku = $product ? $product->sku : $skuMapping->product_sku;
                    } else {
                        $product = WarehouseStock::with('product')->where('sku', $sku)->where('warehouse_id', $warehouseId)->first();
                    }
                }
                // sku mapping done

                // ------------ checking product/customer/vendor -----------------
                // after checking sku mapping check if product actual present or not in db
                // if no stock entry present in table
                if (! isset($product)) {
                    $productStatus = 'Not Found';
                }

                // check for customer and vendor available or not
                // customer availibility check
                $customerInfo = $this->checkCustomerExistence($record['Facility Name']);
                // customer availibility check done

                if (! $customerInfo) {
                    $customerStatus = 'Not Found';
                }

                // vendor availibility check
                $vendorInfo = $this->checkVendorExistence($record['Vendor Code']);
                // vendor availibility check done

                if (! $vendorInfo) {
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

                        'portal_code' => $record['Portal Code'] ?? '',
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
                if ($isAutoAllocation) {
                    // For auto allocation, get total stock from all warehouses
                    if (! isset($productStockCache[$sku])) {
                        $totalAvailable = WarehouseStock::where('sku', $sku)
                            ->whereHas('warehouse', function ($q) {
                                $q->where('status', '1'); // Only active warehouses
                            })
                            ->sum('available_quantity');

                        $productStockCache[$sku] = [
                            'available' => $totalAvailable,
                        ];
                    }
                } else {
                    // Single warehouse logic (existing)
                    if (! isset($productStockCache[$sku])) {
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
                }
                // checked if product sku present in cache or not

                // Use cached values
                $availableQty = $productStockCache[$sku]['available'];

                // Stock check
                // 100 >= 36
                if ($availableQty >= $poQty) {
                    // Sufficient stock
                    // 100 - 36 = 64
                    $productStockCache[$sku]['available'] -= $poQty;
                    // 36
                    $availableQty = $poQty;
                } else {
                    // Insufficient stock
                    $shortQty = $poQty - $availableQty;
                    $productStockCache[$sku]['available'] = 0;
                }

                if ($product) {
                    $casePackQty = (int) $product->product->pcs_set * (int) $product->product->sets_ctn;
                }

                $tempSalesOrder = TempOrder::create([
                    'customer_name' => $record['Customer Name'] ?? '',
                    'po_number' => $record['PO Number'] ?? '',
                    'sku' => $record['SKU Code'] ?? '',
                    'facility_name' => $record['Facility Name'] ?? '',
                    'facility_location' => $record['Facility Location'] ?? '',
                    'po_date' => $record['PO Date'] ?? '',
                    'po_expiry_date' => $record['PO Expiry Date'] ?? '',
                    'hsn' => $product->product->hsn ?? $record['HSN'],
                    'gst' => ($record['GST'] < 1 && $record['GST'] > 0)
                        ? intval(round($record['GST'] * 100))  // convert decimals (0.18 -> 18)
                        : intval($record['GST']),              // already integer (e.g., 18)
                    'portal_code' => $record['Portal Code'] ?? '',
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
                    'available_quantity_track' => $availableQty ?? 0,
                    'unavailable_quantity' => $shortQty ?? 0,
                    'unavailable_quantity_track' => $shortQty ?? 0,
                    'block' => ($record['Block'] > $availableQty) ? $availableQty : $record['Block'],

                    'case_pack_quantity' => $casePackQty ?? '',
                    'purchase_order_quantity' => $record['Purchase Order Quantity'] ?? '',
                    'vendor_code' => $record['Vendor Code'] ?? '',
                    'customer_status' => 'Found',
                    'vendor_status' => 'Found',
                    'product_status' => 'Found',
                ]);

                // Block Quantity in WarehouseStock Table
                // Skip this for auto allocation - WarehouseAllocationService will handle it
                if ($product && ! $isAutoAllocation) {
                    if (intval($record['Block']) > intval($product->available_quantity)) {
                        $blockQuantity = $product->block_quantity + $product->available_quantity;
                    } else {
                        $blockQuantity = $product->block_quantity + intval($record['Block']);
                    }

                    // Block Quantity from WarehouseStock Table and Update WarehouseStockLog Table
                    $product->available_quantity = intval($productStockCache[$sku]['available']) ?? 0;
                    $product->block_quantity = $blockQuantity;
                    $product->save();
                }

                $saveOrderProduct = new SalesOrderProduct;
                $saveOrderProduct->sales_order_id = $salesOrder->id;
                $saveOrderProduct->temp_order_id = $tempSalesOrder->id;
                $saveOrderProduct->customer_id = $customerInfo->id ?? null;
                $saveOrderProduct->vendor_code = $vendorInfo->id ?? null;
                $saveOrderProduct->ordered_quantity = $record['PO Quantity'] ?? 0;
                // For auto-allocation, set purchase_ordered_quantity to 0 initially (will be updated later)
                // For single warehouse, use Excel value
                $saveOrderProduct->purchase_ordered_quantity = $isAutoAllocation ? 0 : ($record['Purchase Order Quantity'] ?? 0);
                $saveOrderProduct->product_id = $product->product->id ?? null;
                // For auto allocation, warehouse_stock_id is null (multiple warehouses)
                $saveOrderProduct->warehouse_stock_id = $isAutoAllocation ? null : ($product->id ?? null);
                $saveOrderProduct->sku = $sku;
                $saveOrderProduct->price = $record['Basic Rate'] ?? null;
                // what is exactly subtotal ??
                // basic rate * po quantity(customers quantity) or basic rate * purchase order quantity(vendors quantity)
                if ($casePackQty > 0) {
                    $saveOrderProduct->box_count = ceil($poQty / $casePackQty);
                } else {
                    $saveOrderProduct->box_count = 0;
                }
                $saveOrderProduct->subtotal = ($record['Basic Rate'] ?? 0) * ($record['PO Quantity'] ?? 0);
                $saveOrderProduct->save();

                // Create warehouse allocation record for single warehouse orders
                if (! $isAutoAllocation && $product && $product->warehouse_id) {
                    // Use the block quantity from TempOrder (already calculated correctly)
                    $allocatedQty = intval($tempSalesOrder->block);

                    if ($allocatedQty > 0) {
                        WarehouseAllocation::create([
                            'sales_order_id' => $salesOrder->id,
                            'sales_order_product_id' => $saveOrderProduct->id,
                            'warehouse_id' => $product->warehouse_id,
                            'sku' => $sku,
                            'allocated_quantity' => $allocatedQty,
                            'sequence' => 1,
                            'box_count' => $saveOrderProduct->box_count,
                            'status' => 'allocated',
                            'notes' => "Allocated from warehouse {$product->warehouse->name}",
                        ]);

                        activity()
                            ->performedOn($saveOrderProduct)
                            ->causedBy(Auth::user())
                            ->withProperties([
                                'warehouse_id' => $product->warehouse_id,
                                'warehouse_name' => $product->warehouse->name,
                                'sku' => $sku,
                                'allocated_quantity' => $allocatedQty,
                            ])
                            ->log("Stock allocated from warehouse {$product->warehouse->name}");
                    }
                }

                // Make a purchase order if one or more than one products have less quantity in warehouse
                if ($shortQty > 0) {
                    if (! isset($productStockCache[$vendorCode])) {
                        $productStockCache[$vendorCode] = [
                            'vendor_code' => $vendorCode,
                        ];

                        // handle if null
                        $lastPurchaseOrder = PurchaseOrder::orderBy('id', 'desc')->first();
                        if ($lastPurchaseOrder && $lastPurchaseOrder->order_number) {
                            $prefix = $lastPurchaseOrder ? 'PO-' . date('Ym', strtotime($lastPurchaseOrder->created_at)) . '-' : 'PO-' . date('Ym') . '-';
                            $lastPurchaseOrderNumber = $lastPurchaseOrder ? intval(explode('-', $lastPurchaseOrder->order_number)[2]) : 0;
                        } else {
                            $prefix = 'PO-' . date('Ym') . '-';
                            $lastPurchaseOrderNumber = 0;
                        }
                        $nextPurchaseOrderNumber = $lastPurchaseOrderNumber + 1;
                        $nextPurchaseOrderNumber = str_pad($nextPurchaseOrderNumber, 4, '0', STR_PAD_LEFT);
                        $nextPurchaseOrderNumber = $prefix . $nextPurchaseOrderNumber;

                        // Create a new purchase order for the vendor if not already created
                        $purchaseOrder = new PurchaseOrder;
                        $purchaseOrder->order_number = $nextPurchaseOrderNumber;
                        $purchaseOrder->sales_order_id = $salesOrder->id;
                        $purchaseOrder->warehouse_id = $warehouse_id;
                        $purchaseOrder->customer_group_id = $customer_group_id;
                        $purchaseOrder->vendor_id = $vendorInfo->id ?? null;
                        $purchaseOrder->vendor_code = $vendorCode;
                        $purchaseOrder->status = 'pending';
                        $purchaseOrder->save();

                        $productStockCache[$vendorCode]['purchase_order_id'] = $purchaseOrder->id;
                    } else {
                        $purchaseOrder = PurchaseOrder::find($productStockCache[$vendorCode]['purchase_order_id']);
                    }

                    $vendorCode = $productStockCache[$vendorCode]['vendor_code'];

                    // create purchase order product entry
                    $existingProduct = PurchaseOrderProduct::where('purchase_order_id', $purchaseOrder->id)
                        ->where('sku', $sku)
                        ->where('vendor_code', $vendorCode)
                        ->first();

                    if ($existingProduct) {
                        // Combine quantities if match found
                        if ($shortQty != $record['Purchase Order Quantity']) {
                            $existingProduct->ordered_quantity += $record['Purchase Order Quantity'];
                            $existingProduct->save();
                        } else {
                            $existingProduct->ordered_quantity += $shortQty;
                            $existingProduct->save();
                        }
                    } else {
                        // Create a new record
                        $purchaseOrderProduct = new PurchaseOrderProduct;
                        $purchaseOrderProduct->temp_order_id = $tempSalesOrder->id;
                        $purchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
                        $purchaseOrderProduct->sales_order_id = $salesOrder->id;
                        $purchaseOrderProduct->sales_order_product_id = $saveOrderProduct->id;
                        $purchaseOrderProduct->product_id = $product->product->id ?? null;
                        $purchaseOrderProduct->sku = $sku;
                        $purchaseOrderProduct->vendor_code = $vendorCode;
                        if ($shortQty != $record['Purchase Order Quantity']) {
                            $purchaseOrderProduct->ordered_quantity = $record['Purchase Order Quantity'] ?? 0;
                        } else {
                            $purchaseOrderProduct->ordered_quantity = $shortQty;
                        }
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

            // If auto allocation is selected, trigger warehouse allocation
            if ($isAutoAllocation) {
                $allocationService = new \App\Services\WarehouseAllocationService;

                // Get all sales order products
                $salesOrderProducts = SalesOrderProduct::where('sales_order_id', $salesOrder->id)->get();

                foreach ($salesOrderProducts as $orderProduct) {
                    // Auto allocate stock for each product
                    $allocationResult = $allocationService->autoAllocateStock(
                        $orderProduct->sku,
                        $orderProduct->ordered_quantity,
                        $salesOrder->id,
                        $orderProduct->id
                    );

                    // If allocation failed, log it
                    if (! $allocationResult['success']) {
                        Log::warning('Auto allocation failed for SKU: ' . $orderProduct->sku, [
                            'sales_order_id' => $salesOrder->id,
                            'error' => $allocationResult['error'] ?? 'Unknown error',
                        ]);
                    }

                    // If purchase order needed, it's already created in the loop above
                    // Just update the purchase_ordered_quantity
                    if (isset($allocationResult['need_purchase']) && $allocationResult['need_purchase']) {
                        $orderProduct->purchase_ordered_quantity = $allocationResult['pending_quantity'] ?? 0;
                        $orderProduct->save();
                    }
                }

                activity()
                    ->performedOn($salesOrder)
                    ->causedBy(Auth::user())
                    ->log('Auto-allocated stock from multiple warehouses');
            }

            DB::commit();

            // Create notification
            NotificationService::orderCreated('sales', $salesOrder->id);

            $successMessage = 'Sales Order created successfully! Order ID: ' . $salesOrder->id;
            if ($isAutoAllocation) {
                $successMessage .= ' (Stock auto-allocated from multiple warehouses)';
            }

            return redirect()->route('sales.order.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            // dd($e);
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
                if (empty($record['SKU Code']) || empty($record['PO Number'])) {
                    continue;
                }

                $key = strtolower(trim($record['PO Number'])) . '|' . strtolower(trim($record['SKU Code']));

                if (isset($seen[$key])) {
                    DB::rollBack();

                    return redirect()->back()->with([
                        'error' => 'Please check excel file: duplicate SKU (' . $record['SKU Code'] . ') found for same customer (' . $record['PO Number'] . ').',
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

                if (! $customerInfo) {
                    continue;
                }

                // Find sales order product
                $salesOrderProductUpdate = SalesOrderProduct::with('product', 'productMapping', 'tempOrder.purchaseOrderProduct')
                    ->where('sku', $record['SKU Code'])
                    ->where('sales_order_id', $request->sales_order_id)
                    ->where('customer_id', $customerInfo->id)
                    ->whereHas('tempOrder', function ($query) use ($record) {
                        $query->where('po_number', $record['PO Number']);
                    })
                    ->first();

                if (! $salesOrderProductUpdate) {
                    continue;
                }

                // 3. Build products array for TempOrder::upsert()
                // Quantity Fullfilled	Warehouse Allocation

                // item_code   = Item Code
                // po_date
                // po_expiry_date
                // basic_rate  = Basic Rate
                // product_basic_rate  = Product Basic Rate
                // rate_confirmation  = Basic Rate Confirmation
                // net_landing_rate  = Net Landing Rate
                // product_net_landing_rate  = Product Net Landing Rate
                // net_landing_rate_confirmation  = Net Landing Rate Confirmation
                // mrp  = PO MRP
                // product_mrp  = Product MRP
                // mrp_confirmation  = MRP Confirmation
                // po_qty  = PO Quantity
                // available_quantity  =
                // unavailable_quantity  =
                // block  = Block Quantity
                // case_pack_quantity  =
                // purchase_order_quantity  = Purchase Order Quantity

                $products[] = [
                    'id' => $salesOrderProductUpdate->temp_order_id,
                    'item_code' => Arr::get($record, 'Item Code', ''),
                    'description' => Arr::get($record, 'Title', ''),
                    'basic_rate' => Arr::get($record, 'Basic Rate', 0),
                    'product_basic_rate' => Arr::get($record, 'Product Basic Rate', 0),
                    'rate_confirmation' => ($record['Basic Rate'] == ($salesOrderProductUpdate->productMapping->basic_rate ?? 0)) ? 'Correct' : 'Incorrect',
                    'net_landing_rate' => Arr::get($record, 'Net Landing Rate', 0),
                    'product_net_landing_rate' => Arr::get($record, 'Product Net Landing Rate', 0),
                    'net_landing_rate_confirmation' => ($record['Net Landing Rate'] == ($salesOrderProductUpdate->productMapping->net_landing_rate ?? 0)) ? 'Correct' : 'Incorrect',
                    'mrp' => Arr::get($record, 'PO MRP', 0),
                    'product_mrp' => Arr::get($record, 'Product MRP', 0),
                    'mrp_confirmation' => ($record['PO MRP'] == ($salesOrderProductUpdate->product->mrp ?? 0)) ? 'Correct' : 'Incorrect',
                    'po_qty' => Arr::get($record, 'PO Quantity', 0),
                    'block' => Arr::get($record, 'Block Quantity', 0),
                    'purchase_order_quantity' => Arr::get($record, 'Purchase Order Quantity', 0),
                    'updated_at' => now(),
                ];

                // 4. Update SalesOrderProduct
                $salesOrderProductUpdate->price = $record['PO MRP'] ?? 0;
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
                            } elseif ($salesOrderProductUpdate->tempOrder->block < $record['Purchase Order Quantity']) {
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

                if ($record['Final Fulfilled Quantity'] > 0) {
                    $salesOrderProductUpdate->final_dispatched_quantity = $record['Final Fulfilled Quantity'] ?? 0;

                    if ($salesOrderProductUpdate->warehouseAllocations()->count() > 0) {
                        foreach ($salesOrderProductUpdate->warehouseAllocations as $allocation) {
                            // Proportionally distribute dispatched quantity
                            $allocation->final_dispatched_quantity = $record['Final Fulfilled Quantity'] ?? 0;
                            $allocation->save();
                        }
                    }
                }
                $salesOrderProductUpdate->save();

                $insertCount++;
            }

            // Upsert TempOrder
            if (! empty($products)) {
                TempOrder::upsert(
                    $products,
                    ['id'],
                    ['portal_code', 'item_code', 'description', 'basic_rate', 'product_basic_rate', 'rate_confirmation', 'net_landing_rate', 'product_net_landing_rate', 'net_landing_rate_confirmation', 'mrp', 'product_mrp', 'mrp_confirmation', 'po_qty', 'block', 'purchase_order_quantity', 'updated_at']
                );
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->with(['products_excel' => 'No valid data found in the file.']);
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
            'warehouseAllocations.warehouse',
            'warehouseAllocations.product',
        ])
            ->withSum('orderedProducts', 'purchase_ordered_quantity')
            ->withSum('orderedProducts', 'ordered_quantity')
            ->withCount('notFoundTempOrderByProduct')
            ->withCount('notFoundTempOrderByCustomer')
            ->withCount('notFoundTempOrderByVendor')
            ->findOrFail($id);

        $blockQuantity = 0;
        $vendorPiFulfillmentTotal = 0;
        $vendorPiReceivedTotal = 0;
        $availableQuantity = 0;
        $unavailableQuantity = 0;
        $orderedQuantity = 0;

        foreach ($salesOrder->orderedProducts as $product) {
            if (isset($product->tempOrder)) {
                $blockQuantity += $product->tempOrder->block;
                $vendorPiFulfillmentTotal += $product->tempOrder->vendor_pi_fulfillment_quantity;
                $vendorPiReceivedTotal += $product->tempOrder->vendor_pi_received_quantity;
                $availableQuantity += $product->tempOrder->available_quantity;
                $unavailableQuantity += $product->tempOrder->unavailable_quantity;
                $orderedQuantity += $product->ordered_quantity;
            }
        }

        $remainingQuantity = $orderedQuantity - ($blockQuantity);
        // Unique brand names (non-null)
        $uniqueBrands = $salesOrder->orderedProducts
            ->pluck('product.brand')
            ->filter()
            ->unique()
            ->values();

        // Unique PO numbers (non-null, nested relationship)
        $uniquePONumbers = $salesOrder->orderedProducts
            ->map(function ($orderedProduct) {
                return optional($orderedProduct->tempOrder)->po_number;
            })
            ->filter()
            ->unique()
            ->values();

        // Get warehouse allocation breakdown
        $warehouseAllocations = \App\Models\WarehouseAllocation::where('sales_order_id', $id)
            ->with('warehouse', 'product')
            ->orderBy('sku')
            ->orderBy('sequence')
            ->get()
            ->groupBy('sku');

        // Check if user is super admin
        $isSuperAdmin = Auth::user() && Auth::user()->roles->contains('name', 'Super Admin');

        $displayProducts = [];
        $facilityNames = [];

        if ($isSuperAdmin) {
            // For super admin, create separate rows for each warehouse allocation
            foreach ($salesOrder->orderedProducts as $order) {
                $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                if ($hasAllocations) {
                    // Has warehouse allocations (both single and multi-warehouse)
                    foreach ($order->warehouseAllocations as $allocation) {
                        $displayProducts[] = [
                            'order' => $order,
                            'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                            'allocated_quantity' => $allocation->allocated_quantity,
                            'warehouse_allocation_display' => $allocation->warehouse->name . ': ' . $allocation->allocated_quantity,
                            'allocation_id' => $allocation->id,
                        ];
                        $facilityNames[] = $order->tempOrder->facility_name;
                    }
                } else {
                    // Fallback: No allocation record (legacy data)
                    $warehouseName = $order->warehouseStock ? $order->warehouseStock->warehouse->name : 'N/A';
                    $allocatedQty = $order->tempOrder->block ?? 0;
                    $displayProducts[] = [
                        'order' => $order,
                        'warehouse_name' => $warehouseName,
                        'allocated_quantity' => $allocatedQty,
                        'warehouse_allocation_display' => $warehouseName . ': ' . $allocatedQty,
                        'allocation_id' => null,
                    ];
                    $facilityNames[] = $order->tempOrder->facility_name;
                }
            }
        } else {
            // For non-super admin, show warehouse allocation info if available
            foreach ($salesOrder->orderedProducts as $order) {
                $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                if ($hasAllocations) {
                    // Build warehouse allocation display string
                    $allocationDisplay = [];
                    foreach ($order->warehouseAllocations as $allocation) {
                        $allocationDisplay[] = $allocation->warehouse->name . ': ' . $allocation->allocated_quantity;
                    }
                    $displayProducts[] = [
                        'order' => $order,
                        'warehouse_name' => null, // Not shown separately
                        'allocated_quantity' => null,
                        'warehouse_allocation_display' => implode(', ', $allocationDisplay),
                        'allocation_id' => null,
                    ];
                } else {
                    // Fallback: No allocation record
                    $displayProducts[] = [
                        'order' => $order,
                        'warehouse_name' => null,
                        'allocated_quantity' => null,
                        'warehouse_allocation_display' => '',
                        'allocation_id' => null,
                    ];
                }
                $facilityNames[] = $order->tempOrder->facility_name;
            }
        }

        $facilityNames = array_unique($facilityNames);

        return view('salesOrder.view', compact('uniqueBrands', 'uniquePONumbers', 'remainingQuantity', 'blockQuantity', 'salesOrder', 'vendorPiFulfillmentTotal', 'availableQuantity', 'orderedQuantity', 'unavailableQuantity', 'vendorPiReceivedTotal', 'warehouseAllocations', 'displayProducts', 'facilityNames', 'isSuperAdmin'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $order = SalesOrder::with(['orderedProducts.tempOrder', 'orderedProducts.warehouseAllocations'])->findOrFail($id);

            foreach ($order->orderedProducts as $product) {
                // Check if this is auto-allocation (has warehouse allocations) or single warehouse
                $hasAutoAllocation = $product->warehouseAllocations && $product->warehouseAllocations->count() > 0;

                if ($hasAutoAllocation) {
                    // Handle auto-allocation case: Release blocked quantity from all allocated warehouses
                    foreach ($product->warehouseAllocations as $allocation) {
                        $warehouseStock = WarehouseStock::where('warehouse_id', $allocation->warehouse_id)
                            ->where('sku', $allocation->sku)
                            ->first();

                        if ($warehouseStock) {
                            // Release blocked quantity back to available
                            $warehouseStock->block_quantity = max(0, $warehouseStock->block_quantity - $allocation->allocated_quantity);
                            $warehouseStock->available_quantity = $warehouseStock->available_quantity + $allocation->allocated_quantity;
                            $warehouseStock->save();
                        }

                        // Delete allocation record
                        $allocation->delete();
                    }
                } else {
                    // Handle single warehouse case: Release blocked quantity from single warehouse
                    $warehouseStock = WarehouseStock::where('id', $product->warehouse_stock_id)->first();

                    if (isset($warehouseStock) && $warehouseStock->block_quantity > 0 && isset($product->tempOrder)) {
                        $warehouseStock->block_quantity = max(0, $warehouseStock->block_quantity - $product->tempOrder->block);
                        $warehouseStock->available_quantity = $warehouseStock->available_quantity + $product->tempOrder->block;
                        $warehouseStock->save();
                    }
                }

                // Delete Temp Order Entry
                if (isset($product->tempOrder)) {
                    $product->tempOrder->delete();
                }
            }

            $order->delete();

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->withProperties(['sales_order_id' => $id])
                ->log('Sales order deleted and blocked quantities released');

            return redirect()->route('sales.order.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting sales order: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong: Please Try Again.');
        }
    }

    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        DB::beginTransaction();
        try {
            foreach ($ids as $salesOrderProductId) {

                $salesOrderProduct = SalesOrderProduct::with(['tempOrder', 'warehouseAllocations'])->find($salesOrderProductId);

                if (! $salesOrderProduct) {
                    continue; // Skip if not found
                }

                // Check if this is auto-allocation (has warehouse allocations) or single warehouse
                $hasAutoAllocation = $salesOrderProduct->warehouseAllocations && $salesOrderProduct->warehouseAllocations->count() > 0;

                if ($hasAutoAllocation) {
                    // Handle auto-allocation case: Release blocked quantity from all allocated warehouses
                    foreach ($salesOrderProduct->warehouseAllocations as $allocation) {
                        $warehouseStock = WarehouseStock::where('warehouse_id', $allocation->warehouse_id)
                            ->where('sku', $allocation->sku)
                            ->first();

                        if ($warehouseStock) {
                            // Release blocked quantity back to available
                            $warehouseStock->block_quantity = max(0, $warehouseStock->block_quantity - $allocation->allocated_quantity);
                            $warehouseStock->available_quantity = $warehouseStock->available_quantity + $allocation->allocated_quantity;
                            $warehouseStock->save();

                            // Log activity
                            activity()
                                ->performedOn($warehouseStock)
                                ->causedBy(Auth::user())
                                ->withProperties([
                                    'warehouse_id' => $warehouseStock->warehouse_id,
                                    'sku' => $allocation->sku,
                                    'released_quantity' => $allocation->allocated_quantity,
                                    'sales_order_product_id' => $salesOrderProductId,
                                ])
                                ->log('Blocked quantity released from warehouse on product deletion');
                        }

                        // Delete allocation record
                        $allocation->delete();
                    }
                } else {
                    // Handle single warehouse case: Release blocked quantity from single warehouse
                    if ($salesOrderProduct->tempOrder && $salesOrderProduct->tempOrder->block > 0) {
                        $warehouseStock = WarehouseStock::find($salesOrderProduct->warehouse_stock_id);

                        if ($warehouseStock) {
                            // Release blocked quantity back to available
                            $warehouseStock->block_quantity = max(0, $warehouseStock->block_quantity - $salesOrderProduct->tempOrder->block);
                            $warehouseStock->available_quantity = $warehouseStock->available_quantity + $salesOrderProduct->tempOrder->block;
                            $warehouseStock->save();

                            // Log activity
                            activity()
                                ->performedOn($warehouseStock)
                                ->causedBy(Auth::user())
                                ->withProperties([
                                    'warehouse_stock_id' => $warehouseStock->id,
                                    'sku' => $salesOrderProduct->sku,
                                    'released_quantity' => $salesOrderProduct->tempOrder->block,
                                    'sales_order_product_id' => $salesOrderProductId,
                                ])
                                ->log('Blocked quantity released from warehouse on product deletion');
                        }
                    }
                }

                // Delete temp order if exists
                if ($salesOrderProduct->tempOrder) {
                    $salesOrderProduct->tempOrder->delete();
                }

                // Delete sales order product
                $salesOrderProduct->delete();

                // Log activity
                activity()
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'sales_order_product_id' => $salesOrderProductId,
                        'sku' => $salesOrderProduct->sku,
                    ])
                    ->log('Sales order product deleted');
            }

            DB::commit();

            return redirect()->back()->with('success', 'Selected products deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting sales order products: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function changeStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|integer|exists:sales_orders,id',
                'status' => 'required|in:shipped,delivered,completed,ready_to_ship,ready_to_package,pending,blocked',
                'customer_id' => 'nullable|integer|exists:customers,id',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Invalid request data.');
            }

            $salesOrder = SalesOrder::findOrFail($request->order_id);
            $salesOrderDetails = SalesOrderProduct::with('tempOrder')->where('sales_order_id', $salesOrder->id)->get();

            // Get user information for role-based updates
            if ($request->filled('user_id')) {
                $user = User::findOrFail($request->user_id);
            } else {
                $user = Auth::user();
            }
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;

            // Handle warehouse-specific status updates for shipped, delivered, completed
            if (in_array($request->status, ['shipped', 'delivered', 'completed'])) {
                // Update warehouse allocations based on user role
                $allocationsQuery = WarehouseAllocation::where('sales_order_id', $request->order_id);
                // If warehouse user, only update their warehouse allocations
                if ($request->status == 'delivered') {
                    if (! $isAdmin && $userWarehouseId) {
                        $allocationsQuery->whereHas('salesOrderProduct', function ($q) use ($request) {
                            $q->where('customer_id', $request->customer_id);
                        });
                    }
                } else {
                    $allocationsQuery->whereHas('salesOrderProduct', function ($q) use ($request) {
                        $q->where('customer_id', $request->customer_id);
                    });
                    if (! $isAdmin && $userWarehouseId) {
                        $allocationsQuery->where('warehouse_id', $userWarehouseId);
                    }
                }

                $allocations = $allocationsQuery->get();

                // dd($allocations);
                if ($allocations->isEmpty()) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', 'No warehouse allocations found for this order and customer.');
                }

                // Update shipping_status for the allocations
                foreach ($allocations as $allocation) {
                    $allocation->shipping_status = $request->status;
                    $allocation->save();

                    activity()
                        ->performedOn($allocation)
                        ->causedBy($user)
                        ->withProperties([
                            'old_status' => $allocation->getOriginal('shipping_status'),
                            'new_status' => $request->status,
                            'warehouse_id' => $allocation->warehouse_id,
                            'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                        ])
                        ->log('Warehouse allocation shipping status changed');
                }

                // Update sales order products status
                $salesOrderProducts = SalesOrderProduct::where('sales_order_id', $request->order_id)
                    ->where('customer_id', $request->customer_id)
                    ->get();

                foreach ($salesOrderProducts as $product) {
                    $product->status = $request->status;
                    $product->save();
                }

                // Check if all warehouse allocations for this order have reached the same status
                // If yes, update the main sales order status
                if ($isAdmin) {
                    // Admin updates all, so update main order status immediately
                    $oldStatus = $salesOrder->status;
                    $salesOrder->status = $request->status;
                    $salesOrder->save();

                    NotificationService::statusChanged('sales', $salesOrder->id, $oldStatus, $salesOrder->status);

                    activity()
                        ->performedOn($salesOrder)
                        ->causedBy($user)
                        ->withProperties([
                            'old_status' => $oldStatus,
                            'new_status' => $request->status,
                        ])
                        ->log('Sales order status changed by Admin');
                } else {
                    // Warehouse user: Check if all allocations are at the same status
                    $allAllocations = WarehouseAllocation::where('sales_order_id', $request->order_id)->get();
                    $allAtSameStatus = $allAllocations->every(function ($allocation) use ($request) {
                        return $allocation->shipping_status === $request->status;
                    });

                    if ($allAtSameStatus) {
                        $oldStatus = $salesOrder->status;
                        $salesOrder->status = $request->status;
                        $salesOrder->save();

                        NotificationService::statusChanged('sales', $salesOrder->id, $oldStatus, $salesOrder->status);

                        activity()
                            ->performedOn($salesOrder)
                            ->causedBy($user)
                            ->withProperties([
                                'old_status' => $oldStatus,
                                'new_status' => $request->status,
                            ])
                            ->log('Sales order status changed (all warehouses at same status)');
                    }
                }

                DB::commit();

                $warehouseName = $isAdmin ? 'all warehouses' : ($user->warehouse->name ?? 'your warehouse');

                return redirect()->back()
                    ->with('success', "Status updated to '{$request->status}' for {$warehouseName} successfully!");
            }

            if ($request->status == 'ready_to_ship') {
                // Check if all products are ready_to_ship
                $totalProducts = $salesOrderDetails->count();
                $readyToShipProducts = $salesOrderDetails->where('status', 'ready_to_ship')->count();

                if ($totalProducts != $readyToShipProducts) {
                    $pendingProducts = $totalProducts - $readyToShipProducts;

                    return redirect()->back()
                        ->with('error', 'Cannot change order status to Ready to Ship. ' . $pendingProducts . ' product(s) are still in packaging. Please ensure all warehouse persons have marked their products as ready to ship.');
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
                        if (! empty($order->final_dispatched_quantity)) {
                            $order->final_dispatched_quantity = $order->final_dispatched_quantity;
                        } else {
                            $order->final_dispatched_quantity = $order->ordered_quantity;
                        }
                    } else {
                        if (! empty($order->final_dispatched_quantity)) {
                            $order->final_dispatched_quantity = $order->final_dispatched_quantity;
                        } else {
                            $order->final_dispatched_quantity = ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                        }
                        $order->final_dispatched_quantity = ($order->tempOrder?->available_quantity ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                    }

                    $order->save();
                }

                $oldStatus = $salesOrderUpdate->status;
                $salesOrderUpdate->status = $request->status;
                $salesOrderUpdate->save();
            } elseif ($request->status == 'ready_to_package') {
                $salesOrderUpdate = SalesOrder::with([
                    'customerGroup',
                    'warehouse',
                    'orderedProducts.product',
                    'orderedProducts.customer',
                    'orderedProducts.tempOrder',
                    'orderedProducts.warehouseStock',
                ])
                    ->find($request->order_id);
                foreach ($salesOrderUpdate->orderedProducts as $order) {

                    // if ($order->tempOrder?->vendor_pi_received_quantity > 0) {
                    //     if ($order->tempOrder?->po_qty <= ($order->tempOrder?->block ?? 0)) {
                    //         $order->dispatched_quantity = $order->tempOrder->po_qty;
                    //     } else {
                    //         $order->dispatched_quantity = $order->tempOrder->block ?? 0;
                    //     }
                    // } elseif ($order->tempOrder?->vendor_pi_fulfillment_quantity > 0) {
                    //     if ($order->tempOrder?->po_qty <= ($order->tempOrder?->block ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0)) {
                    //         $order->dispatched_quantity = $order->tempOrder->po_qty;
                    //     } else {
                    //         $order->dispatched_quantity = ($order->tempOrder?->block ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                    //     }
                    // } else {
                    //     if ($order->tempOrder?->po_qty <= ($order->tempOrder?->block ?? 0)) {
                    //         $order->dispatched_quantity = $order->tempOrder->po_qty;
                    //     } else {
                    //         $order->dispatched_quantity = $order->tempOrder->block ?? 0;
                    //     }
                    // }
                    $order->status = 'packaging';
                    $order->product_status = 'packaging';

                    if ($order->warehouseAllocations->count() > 0) {
                        foreach ($order->warehouseAllocations as $allocation) {
                            $allocation->product_status = 'packaging';
                            $allocation->save();
                        }
                    }
                    $order->save();
                }
                $oldStatus = $salesOrderUpdate->status;
                $salesOrderUpdate->status = $request->status;
                $salesOrderUpdate->save();

                if (! $salesOrderUpdate) {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Status Not Changed. Please Try Again.');
                }

                // Create status change notification
                NotificationService::statusChanged('sales', $salesOrderUpdate->id, $oldStatus, $salesOrderUpdate->status);

                activity()
                    ->performedOn($salesOrderUpdate)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => $salesOrderUpdate->status,
                    ])
                    ->log('Sales order status changed');

                DB::commit();

                if ($salesOrderUpdate->status == 'ready_to_package') {
                    return redirect()->route('packing.products.view', $request->order_id)->with('success', 'Order status changed to "Ready to Package" successfully! Order ID: ' . $salesOrderUpdate->id);
                }
            }

            // $oldStatus = $salesOrder->getOriginal('status');
            // $salesOrder->save();

            DB::commit();

            if ($salesOrder->status == 'ready_to_package') {
                return redirect()->route('packing.products.view', $request->order_id)->with('success', 'Order status changed to "Ready to Package" successfully! Order ID: ' . $salesOrder->id);
            } elseif ($salesOrder->status == 'ready_to_ship') {
                return redirect()->route('readyToShip.view', $request->order_id)->with('success', 'Order status changed to "Ready to Ship" successfully! Order ID: ' . $salesOrder->id);
            } elseif ($salesOrder->status == 'completed') {
                return redirect()->route('sales.order.index')->with('success', 'Order marked as "Completed" successfully! Order ID: ' . $salesOrder->id);
            } elseif ($salesOrder->status == 'shipped') {
                return redirect()->route('sales.order.index')->with('success', 'Order marked as "Shipped" successfully! Order ID: ' . $salesOrder->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing sales order status: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Status Not Changed. Please Try again. Error: ' . $e->getMessage());
        }
    }

    // send to packaging
    public function sendToPackaging(Request $request)
    {

        DB::beginTransaction();

        try {
            $salesOrder = SalesOrder::with('orderedProducts.warehouseAllocations')->findOrFail($request->order_id);
            $oldStatus = $salesOrder->status;
            $salesOrder->status = 'ready_to_package';
            $salesOrder->save();

            // only update ordered products and their allocations for selected ids if provided
            if ($request->filled('ids')) {
                $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
                $salesOrderProducts = SalesOrderProduct::where('sales_order_id', $salesOrder->id)
                    ->whereIn('id', $ids)
                    ->get();
            } else {
                $salesOrderProducts = $salesOrder->orderedProducts;
            }

            foreach ($salesOrderProducts as $order) {
                $order->status = 'packaging';
                $order->product_status = 'packaging';

                if ($order->warehouseAllocations->count() > 0) {
                    foreach ($order->warehouseAllocations as $allocation) {
                        $allocation->product_status = 'packaging';
                        $allocation->save();
                    }
                }
                $order->save();
            }

            // Create status change notification
            NotificationService::statusChanged('sales', $salesOrder->id, $oldStatus, $salesOrder->status);

            activity()
                ->performedOn($salesOrder)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $salesOrder->status,
                ])
                ->log('Sales order status changed to Ready to Package');

            DB::commit();

            return redirect()->route('packing.products.view', $request->order_id)->with('success', 'Order sent to packaging successfully! Order ID: ' . $salesOrder->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending sales order to packaging: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to send order to packaging. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function generateInvoice(Request $request)
    {
        try {
            // Parse IDs
            if ($request->filled('ids')) {
                $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            } else {
                $ids = [];
            }

            // Validate sales order exists
            $salesOrder = SalesOrder::findOrFail($request->order_id);

            // Build query with eager loading (including warehouseAllocations for grouping)
            $salesOrderDetails = SalesOrderProduct::with(['tempOrder', 'customer', 'product', 'warehouseAllocations.warehouse'])
                ->where('sales_order_id', $salesOrder->id);

            // Apply filters
            if (! empty($ids)) {
                $salesOrderDetails->whereIn('id', $ids);
            }

            if ($request->filled('brand')) {
                $salesOrderDetails->whereHas('product', function ($query) use ($request) {
                    $query->where('brand', $request->brand);
                });
            }

            if ($request->filled('shipping_status')) {
                $salesOrderDetails->whereHas('warehouseAllocations', function ($query) use ($request) {
                    $query->where('shipping_status', $request->shipping_status);
                });
            }

            if ($request->filled('po_number')) {
                $salesOrderDetails->whereHas('tempOrder', function ($query) use ($request) {
                    $query->where('po_number', $request->po_number);
                });
            }

            // Execute query
            $salesOrderDetails = $salesOrderDetails->get();

            // Validate we have records to process
            if ($salesOrderDetails->isEmpty()) {
                return redirect()->back()->with('error', 'No sales order details found matching the criteria.');
            }

            // Group by: warehouse_id + po_number + facility_name + optional(brand) + optional(client_name)
            $invoicesGroup = [];

            foreach ($salesOrderDetails as $detail) {
                // Validate required relationships
                if (! $detail->customer || ! $detail->tempOrder || ! $detail->product) {
                    continue; // Skip invalid records
                }

                $facilityName = $detail->customer->facility_name ?? '';
                $poNumber = $detail->tempOrder->po_number ?? '';

                // Get warehouse allocations for this product
                $allocations = $detail->warehouseAllocations;

                // If no allocations, skip this detail
                if ($allocations->isEmpty()) {
                    continue;
                }

                // Group by each warehouse allocation
                foreach ($allocations as $allocation) {
                    $warehouseId = $allocation->warehouse_id;

                    // Build dynamic grouping key: warehouse_id + po_number + facility_name
                    $groupKey = $warehouseId . '|' . $poNumber . '|' . $facilityName;

                    if ($request->filled('brand')) {
                        $brand = $detail->product->brand ?? '';
                        $groupKey .= '|' . $brand;
                    }

                    if ($request->filled('client_name')) {
                        $clientName = $detail->customer->client_name ?? '';
                        $groupKey .= '|' . $clientName;
                    }

                    // Store detail with allocation info
                    $invoicesGroup[$groupKey][] = [
                        'detail' => $detail,
                        'allocation' => $allocation,
                    ];
                }
            }

            // Validate we have groups to process
            if (empty($invoicesGroup)) {
                return redirect()->back()->with('error', 'No valid records found to generate invoices.');
            }

            DB::beginTransaction();

            $timestamp = time();
            $invoiceCounter = 0;
            $generatedInvoices = [];

            foreach ($invoicesGroup as $groupKey => $invoiceData) {
                // Reset total for this invoice
                $invoiceTotal = 0;

                // Extract warehouse_id from groupKey (first part before |)
                $groupParts = explode('|', $groupKey);
                $warehouseId = (int) $groupParts[0];

                // Get customer_id and po_number from first item
                $firstItem = $invoiceData[0];
                $customerId = $firstItem['detail']->customer_id;
                $poNumber = $firstItem['detail']->tempOrder->po_number ?? '';

                $yearMonth = date('Ym');
                $lastInvoice = Invoice::where('invoice_number', 'LIKE', "INV-{$yearMonth}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                $timestamp = date('Ym');

                if ($lastInvoice) {
                    $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
                    $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '0001';
                }

                // $invoiceNumber = "INV-{$yearMonth}-{$newNumber}";
                $invoiceNumber = 'INV-' . $timestamp . '-' . $newNumber;

                // Create invoice with unique number
                $invoice = new Invoice;
                $invoice->warehouse_id = $warehouseId;
                $invoice->invoice_number = $invoiceNumber;
                $invoice->customer_id = $customerId;
                $invoice->sales_order_id = $salesOrder->id;
                $invoice->invoice_date = now();
                $invoice->round_off = 0;
                $invoice->taxable_amount = 0;
                $invoice->total_amount = 0; // Will update after calculating
                $invoice->po_number = $poNumber;
                $invoice->save();

                // Process invoice details
                $taxable_amount = 0;
                foreach ($invoiceData as $item) {
                    $detail = $item['detail'];
                    $allocation = $item['allocation'];

                    // Use allocated quantity instead of ordered quantity
                    $quantity = (int) $allocation->final_final_dispatched_quantity;
                    $unitPrice = (float) $detail->tempOrder->basic_rate;
                    $lineTotal = $quantity * $unitPrice;

                    $invoiceTotal += $lineTotal;

                    $invoiceDetail = new InvoiceDetails;
                    $invoiceDetail->sales_order_product_id = $detail->id;
                    $invoiceDetail->invoice_id = $invoice->id;
                    $invoiceDetail->product_id = $detail->product_id;
                    $invoiceDetail->temp_order_id = $detail->temp_order_id;
                    $invoiceDetail->warehouse_id = $warehouseId;
                    $invoiceDetail->quantity = $quantity;
                    $invoiceDetail->unit_price = $unitPrice;
                    $invoiceDetail->box_count = ceil($allocation->box_count);
                    $invoiceDetail->weight = ceil($allocation->weight);
                    $invoiceDetail->discount = 0;
                    $invoiceDetail->hsn = $detail->hsn;
                    $invoiceDetail->amount = $lineTotal;
                    $invoiceDetail->tax = $detail->product->gst ?? 0;
                    $invoiceDetail->total_price = $lineTotal + (($detail->product->gst / 100) * $lineTotal); // After discount (currently 0)
                    $invoiceDetail->description = $detail->tempOrder->description ?? null;
                    $invoiceDetail->po_number = $detail->tempOrder->po_number ?? null;
                    $invoiceDetail->save();

                    // Update sales order product status only if all allocations are processed
                    // We'll handle this separately after all invoices are created

                    $taxable_amount += (($unitPrice * $quantity) * ($detail->product->gst / 100));
                }

                // Update invoice with calculated total
                $invoice->taxable_amount = $invoiceTotal;
                $invoice->tax_amount = $taxable_amount;
                $invoice->total_amount = $invoiceTotal + $taxable_amount;
                $invoice->balance_due = $invoiceTotal + $taxable_amount;
                $invoice->save();

                $generatedInvoices[] = $invoice->id;
                $invoiceCounter++;
            }

            // Update sales order product status after all invoices are created
            foreach ($salesOrderDetails as $detail) {
                // Check if all allocations for this product have been invoiced
                $allAllocationsInvoiced = true;
                foreach ($detail->warehouseAllocations as $allocation) {
                    // Check if this allocation was included in any generated invoice
                    $found = false;
                    foreach ($invoicesGroup as $groupKey => $items) {
                        foreach ($items as $item) {
                            if ($item['detail']->id === $detail->id && $item['allocation']->id === $allocation->id) {
                                $found = true;
                                break 2;
                            }
                        }
                    }
                    if (! $found) {
                        $allAllocationsInvoiced = false;
                        break;
                    }
                }

                // Update status only if all allocations are invoiced
                if ($allAllocationsInvoiced) {
                    $detail->status = 'dispatched';
                    $detail->invoice_status = 'completed';
                    $detail->save();
                }
            }

            DB::commit();

            // Create notifications for all generated invoices
            foreach ($generatedInvoices as $invoiceId) {
                NotificationService::invoiceGenerated($invoiceId, $salesOrder->id);
            }

            $invoiceCount = count($generatedInvoices);
            $invoiceIds = implode(', ', $generatedInvoices);

            return redirect()->back()->with(
                'success',
                "Successfully generated {$invoiceCount} invoice(s) for Order ID: {$salesOrder->id}. Invoice IDs: {$invoiceIds}"
            );
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invoice Generation Failed: ' . $e->getMessage(), [
                'order_id' => $request->order_id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with(
                'error',
                'Invoice generation failed: ' . $e->getMessage()
            );
        }
    }

    public function checkProductsStock(Request $request)
    {
        $file = $request->file('csv_file');
        if (! $file) {
            return redirect()->back()->with(['csv_file' => 'Please upload a CSV file.']);
        }

        $file = $request->file('csv_file')->getPathname();
        $file_extension = $request->file('csv_file')->getClientOriginalExtension();

        $reader = SimpleExcelReader::create($file, $file_extension);

        $productStockCache = []; // Cache stock by SKU
        $insertedRows = [];

        $rows = $reader->getRows()->toArray(); // convert to array so we can check duplicates easily

        // 🔹 Step 1: Check for duplicates (Customer + SKU)
        $duplicateCheck = $this->checkDuplicateSkuInExcel($rows);
        if ($duplicateCheck) {
            return redirect()->back()->with(['error' => $duplicateCheck]);
        }

        // Check if auto allocation is selected
        $isAutoAllocation = ($request->warehouse_id === 'auto');

        try {

            foreach ($reader->getRows() as $record) {
                $sku = trim($record['SKU Code']);  // customer sku
                $poQty = (int) $record['PO Quantity'];
                $warehouseId = $request->warehouse_id;

                // Default fallback
                $availableQty = 0;
                $shortQty = 0;
                $casePackQty = 0;
                $reason = '';

                // map sku with product
                $skuMapping = $this->mapSku($sku);

                // If auto allocation, get product from any warehouse
                if ($isAutoAllocation) {
                    if ($skuMapping) {
                        $product = WarehouseStock::with('product')->where('sku', $skuMapping->product_sku)->first();
                        $sku = $product ? $product->sku : $skuMapping->product_sku;
                    } else {
                        $product = WarehouseStock::with('product')->where('sku', $sku)->first();
                    }

                    // If not found in warehouse_stocks, check in products table
                    if (! $product) {
                        $productMaster = Product::where('sku', $sku)->first();
                        if ($productMaster) {
                            // Create a pseudo product object for consistency
                            $product = (object) [
                                'sku' => $productMaster->sku,
                                'product' => $productMaster,
                                'available_quantity' => 0, // Will be calculated from all warehouses
                            ];
                        }
                    }
                } else {
                    // Single warehouse selection
                    if ($skuMapping) {
                        $product = WarehouseStock::with('product')->where('sku', $skuMapping->product_sku)->where('warehouse_id', $warehouseId)->first();
                        $sku = $product ? $product->sku : $skuMapping->product_sku;
                    } else {
                        $product = WarehouseStock::with('product')->where('sku', $sku)->where('warehouse_id', $warehouseId)->first();
                    }
                }
                // sku mapping done
                // check if product is present
                if (! $product) {
                    $reason = 'SKU Not Found';
                }

                $customer = $this->checkCustomerExistence($record['Facility Name']);

                // Check if customer is present
                if (! $customer) {
                    if (! empty($reason)) {
                        $reason .= ' | ';
                    }
                    $reason .= 'Customer Not Found';
                }

                if ($reason != '') {
                    $productMapping = ProductMapping::where('sku', $sku)->where('item_code', $record['Item Code'])->first();
                    // dd($productMapping);
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
                        'Portal Code' => $record['Portal Code'] ?? '',
                        'Item Code' => $record['Item Code'] ?? '',
                        'Description' => $record['Description'] ?? '',
                        'GST' => $gst,

                        'Basic Rate' => $record['Basic Rate'] ?? 0,
                        'Product Basic Rate' => $productMapping->basic_rate ?? 0,
                        'Basic Rate Confirmation' => 'Incorrect',

                        'Net Landing Rate' => $netLandingRate ?? 0,
                        'Product Net Landing Rate' => $productMapping->net_landing_rate ?? 0,
                        'Net Landing Rate Confirmation' => 'Incorrect',

                        'MRP' => $record['MRP'] ?? 0,
                        'Product MRP' => 0,
                        'MRP Confirmation' => 'Incorrect',

                        'Case Pack Quantity' => 0,
                        'PO Quantity' => $poQty,
                        'Available Quantity' => 0,
                        'Unavailable Quantity' => 0,
                        'Warehouse Allocation' => '',
                        'Reason' => $reason,
                    ];

                    continue;
                }

                // Initialize stockEntry variable
                $stockEntry = null;

                // Fetch stock if not already cached
                if (! isset($productStockCache[$sku])) {
                    if ($isAutoAllocation) {
                        // For auto allocation, get total stock from all warehouses
                        $totalAvailable = WarehouseStock::where('sku', $sku)
                            ->whereHas('warehouse', function ($q) {
                                $q->where('status', '1'); // Only active warehouses
                            })
                            ->sum('available_quantity');

                        $productStockCache[$sku] = [
                            'available' => $totalAvailable,
                        ];

                        // Get first warehouse stock entry for product details
                        $stockEntry = WarehouseStock::with('product')
                            ->where('sku', $sku)
                            ->first();
                    } else {
                        // Single warehouse logic
                        $stockEntry = WarehouseStock::with('product')
                            ->where('sku', $sku)
                            ->where('warehouse_id', $warehouseId)
                            ->first();

                        if (! isset($stockEntry)) {
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
                }

                // Use cached values
                $availableQty = $productStockCache[$sku]['available'];

                // Calculate warehouse-wise allocation for both auto and single warehouse
                $warehouseBreakdown = '';
                if ($isAutoAllocation) {
                    // Auto allocation: show breakdown from all active warehouses
                    $warehouseStocks = WarehouseStock::with('warehouse')
                        ->where('sku', $sku)
                        ->whereHas('warehouse', function ($q) {
                            $q->where('status', '1');
                        })
                        ->where('available_quantity', '>', 0)
                        ->orderBy('warehouse_id', 'asc')
                        ->get();

                    $remainingQty = $poQty;
                    $allocations = [];

                    foreach ($warehouseStocks as $stock) {
                        if ($remainingQty <= 0) {
                            break;
                        }

                        $allocateQty = min($stock->available_quantity, $remainingQty);
                        $currentUnavailable = max(0, $remainingQty - $allocateQty);
                        $allocations[] = $stock->warehouse->name . ' - PO Qty: ' . $remainingQty . ', Available: ' . $allocateQty . ', Unavailable: ' . $currentUnavailable;
                        $remainingQty -= $allocateQty;
                    }

                    if (! empty($allocations)) {
                        $warehouseBreakdown = implode('<br>', $allocations);
                    }

                    if ($remainingQty > 0) {
                        $warehouseBreakdown .= ($warehouseBreakdown ? '<br>' : '') . 'PO Required: ' . $remainingQty;
                    }
                } else {
                    // Single warehouse: show breakdown for selected warehouse
                    $warehouseStock = WarehouseStock::with('warehouse')
                        ->where('sku', $sku)
                        ->where('warehouse_id', $warehouseId)
                        ->first();

                    if ($warehouseStock) {
                        $allocateQty = min($warehouseStock->available_quantity, $poQty);
                        $unavailableQty = max(0, $poQty - $allocateQty);
                        $warehouseBreakdown = $warehouseStock->warehouse->name . ' - PO Qty: ' . $poQty . ', Available: ' . $allocateQty . ', Unavailable: ' . $unavailableQty;

                        if ($unavailableQty > 0) {
                            $warehouseBreakdown .= '<br>PO Required: ' . $unavailableQty;
                        }
                    }
                }

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
                    $productObj = $stockEntry->product;
                } else {
                    $productObj = $product->product;
                }

                // dd($sku);
                $productMapping = ProductMapping::where('sku', $sku)->where('item_code', $record['Item Code'])->first();

                // dd($productMapping);
                // Case pack quantity
                $casePackQty = (int) $productObj->pcs_set * (int) $productObj->sets_ctn;

                // GST handling (0.18 → 18, 18 → 18)
                $gst = ($record['GST'] > 0 && $record['GST'] < 1)
                    ? (int) round($record['GST'] * 100)
                    : (int) $record['GST'];

                // Net landing rate calculation
                $basicRate = floatval($record['Basic Rate']);
                $netLandingRate = $basicRate + ($basicRate * $gst / 100);
                $netLandingRate = round($netLandingRate, 2);

                // Tolerance for comparison
                $tolerance = 0.5;

                // Basic Rate confirmation
                $isBasicRateCorrect = abs(
                    $basicRate - floatval($productMapping->basic_rate ?? $productObj->basic_rate)
                ) <= $tolerance;

                $rateConfirmation = $isBasicRateCorrect ? 'Correct' : 'Incorrect';

                // Net Landing Rate confirmation
                $isNetLandingRateCorrect = abs(
                    $netLandingRate - floatval($productMapping->net_landing_rate ?? $productObj->net_landing_rate)
                ) <= $tolerance;

                $netLandingRateConfirmation = $isNetLandingRateCorrect ? 'Correct' : 'Incorrect';

                // MRP confirmation
                $mrpConfirmation = abs(
                    floatval($record['MRP']) - floatval($productObj->mrp)
                ) <= $tolerance ? 'Correct' : 'Incorrect';

                $insertedRows[] = [
                    'Customer Name' => $record['Customer Name'] ?? '',
                    'PO Number' => $record['PO Number'] ?? '',
                    'SKU Code' => $sku ?? '',
                    'Facility Name' => $record['Facility Name'] ?? '',
                    'Facility Location' => $record['Facility Location'] ?? '',
                    'PO Date' => Carbon::parse($record['PO Date'])->format('d-m-Y') ?? '',
                    'PO Expiry Date' => Carbon::parse($record['PO Expiry Date'])->format('d-m-Y') ?? '',
                    'HSN' => $record['HSN'] ?? '',
                    'Portal Code' => $productMapping->portal_code ?? $record['Portal Code'] ?? '',
                    'Item Code' => $record['Item Code'] ?? '',
                    'Description' => $record['Description'] ?? '',
                    'GST' => $gst ?? 0,

                    'Basic Rate' => $record['Basic Rate'] ?? 0,
                    'Product Basic Rate' => $productMapping->basic_rate ?? $productObj->basic_rate ?? 0,
                    'Basic Rate Confirmation' => $rateConfirmation ?? 'Incorrect',

                    'Net Landing Rate' => $netLandingRate ?? 0,
                    'Product Net Landing Rate' => $productMapping->net_landing_rate ?? $productObj->net_landing_rate ?? 0,
                    'Net Landing Rate Confirmation' => $netLandingRateConfirmation ?? 'Incorrect',

                    'MRP' => $record['MRP'] ?? 0,
                    'Product MRP' => $productObj->mrp ?? 0,
                    'MRP Confirmation' => $mrpConfirmation ?? 'Incorrect',

                    'Case Pack Quantity' => $casePackQty ?? 0,
                    'PO Quantity' => $poQty ?? 0,
                    'Available Quantity' => $availableQty ?? 0,
                    'Unavailable Quantity' => $shortQty ?? 0,
                    'Warehouse Allocation' => $warehouseBreakdown ?? '',
                    'Reason' => '',
                ];
            }

            if (empty($insertedRows)) {
                return redirect()->back()->with(['csv_file' => 'No valid data found in the CSV file.']);
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
            // dd($e);

            return redirect()->back()->with('error', 'An error occurred while processing the CSV file. Please Check the file format and try again.');
        }
    }

    public function downloadBlockedCSV()
    {
        $originalPath = public_path(session('processed_csv_path'));

        if (! file_exists($originalPath)) {
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
                'Portal Code' => $row['Portal Code'] ?? '',
                'Item Code' => $row['Item Code'] ?? '',
                'Description' => $row['Description'] ?? '',

                'Basic Rate' => $row['Basic Rate'] ?? 0,
                'Product Basic Rate' => ($row['Product Basic Rate'] != '' && $row['Product Basic Rate'] != null) ? intval($row['Product Basic Rate']) : 0,
                'Basic Rate Confirmation' => $row['Basic Rate Confirmation'] ?? 'Incorrect',

                'Net Landing Rate' => $row['Net Landing Rate'] ?? 0,
                'Product Net Landing Rate' => intval($row['Product Net Landing Rate']) ?? 0,
                'Net Landing Rate Confirmation' => $row['Net Landing Rate Confirmation'] ?? 'Incorrect',

                'MRP' => $row['MRP'] ?? 0,
                'Product MRP' => $row['Product MRP'] ?? 0,
                'MRP Confirmation' => $row['MRP Confirmation'] ?? 'Incorrect',

                'PO Quantity' => $row['PO Quantity'] ?? 0,
                'Available Quantity' => $row['Available Quantity'] ?? '',
                'Unavailable Quantity' => $row['Unavailable Quantity'] ?? '',
                'Case Pack Quantity' => $row['Case Pack Quantity'] ?? '',
                'Warehouse Allocation' => strip_tags($row['Warehouse Allocation'] ?? ''),
                'Purchase Order Quantity' => $row['Unavailable Quantity'] ?? '',
                'Block' => '0',
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

    // Change this method as required
    public function downloadPoExcel(Request $request)
    {
        // Parse IDs
        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        } else {
            $ids = [];
        }

        // Validate sales order exists
        $salesOrder = SalesOrder::findOrFail($request->order_id);

        // Build query with eager loading
        $salesOrderDetails = SalesOrderProduct::with(['tempOrder', 'customer', 'product', 'warehouseAllocations.warehouse'])
            ->where('sales_order_id', $salesOrder->id);

        // Apply filters
        if (! empty($ids)) {
            $salesOrderDetails->whereIn('id', $ids);
        }

        if ($request->filled('brand') && $request->brand !== '') {
            $salesOrderDetails->whereHas('product', function ($query) use ($request) {
                $query->where('brand', $request->brand);
            });
        }

        // if ($request->filled('shipping_status') && $request->shipping_status !== '') {
        //     $salesOrderDetails->whereHas('warehouseAllocations', function ($query) use ($request) {
        //         $query->where('shipping_status', strtolower($request->shipping_status));
        //     });
        // }

        if ($request->filled('po_number') && $request->po_number !== '') {
            $salesOrderDetails->whereHas('tempOrder', function ($query) use ($request) {
                $query->where('po_number', $request->po_number);
            });
        }

        // Apply quantity fulfilled filter
        // if ($request->filled('quantity_fulfilled_filter') && $request->quantity_fulfilled_filter !== 'all') {
        //     $qtyFilter = $request->quantity_fulfilled_filter;
        //     if ($qtyFilter == '0') {
        //         // Filter for not fulfilled (qty = 0)
        //         $salesOrderDetails->where(function ($query) {
        //             $query->whereHas('tempOrder', function ($subQuery) {
        //                 $subQuery->where(function ($innerQuery) {
        //                     $innerQuery->where('block', 0)
        //                         ->orWhereNull('block');
        //                 });
        //             });
        //         });
        //     } elseif ($qtyFilter == 'greater_than_0') {
        //         // Filter for fulfilled (qty > 0)
        //         $salesOrderDetails->whereHas('tempOrder', function ($subQuery) {
        //             $subQuery->where('block', '>', 0);
        //         });
        //     }
        // }

        // Apply dispatched_quantity filter
        if (
            $request->filled('quantity_fulfilled_filter') &&
            $request->quantity_fulfilled_filter !== 'all'
        ) {
            $quantifyFilter = $request->quantity_fulfilled_filter;

            if ($quantifyFilter == '0') {
                // Not fulfilled: no warehouse allocation with quantity > 0
                $salesOrderDetails->where('dispatched_quantity', 0)->orWhereNull('dispatched_quantity');
            }

            if ($quantifyFilter == 'greater_than_0') {
                // Fulfilled: has warehouse allocation with quantity > 0
                $salesOrderDetails->where('dispatched_quantity', '>', 0);
            }
        }

        // Apply final quantity fulfilled filter
        if (
            $request->filled('final_quantity_fulfilled_filter') &&
            $request->final_quantity_fulfilled_filter !== 'all'
        ) {
            $finalQtyFilter = $request->final_quantity_fulfilled_filter;

            if ($finalQtyFilter == '0') {
                // Not fulfilled: no warehouse allocation with quantity > 0
                $salesOrderDetails->where('final_dispatched_quantity', 0)->orWhereNull('final_dispatched_quantity');
            }

            if ($finalQtyFilter == 'greater_than_0') {
                // Fulfilled: has warehouse allocation with quantity > 0
                $salesOrderDetails->where('final_dispatched_quantity', '>', 0);
            }
        }


        // Execute query
        $filteredOrders = $salesOrderDetails->with([
            // 'customerGroup',
            // 'warehouse',
            // 'warehouseStock',
        ])
            ->get();

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/order_po_update_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        foreach ($filteredOrders as $order) {
            $fulfilledQuantity = 0;

            if ($order->ordered_quantity <= ($order->tempOrder->available_quantity ?? 0) + ($order->tempOrder->vendor_pi_fulfillment_quantity ?? 0)) {
                $fulfilledQuantity = ($order->tempOrder->available_quantity ?? 0) + ($order->tempOrder->vendor_pi_fulfillment_quantity ?? 0);
            } else {
                $fulfilledQuantity = ($order->tempOrder->available_quantity ?? 0) + ($order->tempOrder->vendor_pi_fulfillment_quantity ?? 0);
            }

            $qtyFullfilled = 0;

            if ($order->tempOrder?->vendor_pi_received_quantity > 0) {
                if ($order->tempOrder->po_qty <= ($order->tempOrder?->block ?? 0)) {
                    $qtyFullfilled = $order->tempOrder->po_qty ?? 0;
                } else {
                    $qtyFullfilled = $order->tempOrder?->block ?? 0;
                }
            } elseif ($order->tempOrder?->vendor_pi_fulfillment_quantity > 0) {
                if (
                    $order->tempOrder->po_qty <=
                    ($order->tempOrder?->block ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0)
                ) {
                    $qtyFullfilled = $order->tempOrder->po_qty;
                } else {
                    $qtyFullfilled = ($order->tempOrder?->block ?? 0) + ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0);
                }
            } else {
                if ($order->tempOrder->po_qty <= ($order->tempOrder?->block ?? 0)) {
                    $qtyFullfilled = $order->tempOrder->po_qty;
                } else {
                    $qtyFullfilled = $order->tempOrder?->block ?? 0;
                }
            }

            $warehouseAllocation = '';

            // Check if product has warehouse allocations (auto-allocation)
            $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

            if ($hasAllocations) {
                if ($order->warehouseAllocations->count() > 0) {
                    $iteration = 0;
                    foreach ($order->warehouseAllocations->sortBy('sequence') as $allocation) {
                        if ($iteration > 0) {
                            $warehouseAllocation .= ', ' . ($allocation->warehouse->name ?? 'N/A') . ': ' . $allocation->allocated_quantity;
                        } else {
                            $warehouseAllocation .= ($allocation->warehouse->name ?? 'N/A') . ': ' . $allocation->allocated_quantity;
                            $iteration++;
                        }
                    }
                } else {
                    $warehouseAllocation = 'N/A';
                }
            } else {

                if ($order->warehouseStock) {
                    $warehouseAllocation = ($order->warehouseStock->warehouse->name ?? 'N/A') . ': ' . ($order->tempOrder->block ?? 0);
                } elseif ($order->tempOrder && $order->tempOrder->block > 0) {
                    $fallbackWarehouseName = 'N/A';
                    $fallbackQuantity = $order->tempOrder->block ?? 0;
                    $warehouseStock = \App\Models\WarehouseStock::where(
                        'sku',
                        $order->sku,
                    )
                        ->where('block_quantity', '>', 0)
                        ->first();
                    if ($warehouseStock) {
                        $fallbackWarehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                    } else {
                        if ($salesOrder->warehouse) {
                            $fallbackWarehouseName = $salesOrder->warehouse->name;
                        }
                    }
                    $warehouseAllocation = $fallbackWarehouseName . ': ' . $fallbackQuantity;
                } else {
                    $warehouseAllocation = 'N/A';
                }
            }

            if ($request->filled('quantity_fulfilled_filter') && $request->quantity_fulfilled_filter !== 'all') {
                $qtyFilter = $request->quantity_fulfilled_filter;
                if ($qtyFilter == '0') {
                    if ($order->dispatched_quantity > 0) {
                        continue;
                    }
                } elseif ($qtyFilter == 'greater_than_0') {
                    if ($order->dispatched_quantity <= 0) {
                        continue;
                    }
                }
            }

            if ($request->filled('final_quantity_fulfilled_filter') && $request->final_quantity_fulfilled_filter !== 'all') {
                $finalQtyFilter = $request->final_quantity_fulfilled_filter;
                if ($finalQtyFilter == '0') {
                    if ($order->final_dispatched_quantity > 0) {
                        continue;
                    }
                } elseif ($finalQtyFilter == 'greater_than_0') {
                    if ($order->final_dispatched_quantity <= 0) {
                        continue;
                    }
                }
            }

            // Sanitize and convert data for Excel
            $rowData = [
                'Order No' => $this->sanitizeExcelValue($salesOrder->order_number ?? ''),
                'Customer Name' => $this->sanitizeExcelValue($order->tempOrder?->customer_name ?? ''),
                'Facility Name' => $this->sanitizeExcelValue($order->tempOrder?->facility_name ?? ''),
                'Facility Location' => $this->sanitizeExcelValue($order->tempOrder?->facility_location ?? ''),
                'HSN' => $this->sanitizeExcelValue($order->tempOrder?->hsn ?? ''),
                'GST' => $this->sanitizeExcelValue($order->tempOrder?->gst ?? ''),
                'Item Code' => $this->sanitizeExcelValue($order->tempOrder?->item_code ?? ''),
                'SKU Code' => $this->sanitizeExcelValue($order->tempOrder?->sku ?? ''),
                'Brand' => $this->sanitizeExcelValue($order->product?->brand ?? ''),
                'Title' => $this->sanitizeExcelValue($order->tempOrder?->description ?? ''),
                'Basic Rate' => (float) ($order->tempOrder?->basic_rate ?? 0),
                'Product Basic Rate' => (float) ($order->tempOrder?->product_basic_rate ?? 0),
                'Basic Rate Confirmation' => $this->sanitizeExcelValue($order->tempOrder?->rate_confirmation ?? 'Incorrect'),
                'Net Landing Rate' => (float) ($order->tempOrder?->net_landing_rate ?? 0),
                'Product Net Landing Rate' => (float) ($order->tempOrder?->product_net_landing_rate ?? 0),
                'Net Landing Rate Confirmation' => $this->sanitizeExcelValue($order->tempOrder?->net_landing_rate_confirmation ?? 'Incorrect'),
                'PO MRP' => (float) ($order->tempOrder?->mrp ?? 0),
                'Product MRP' => (float) ($order->tempOrder?->product_mrp ?? 0),
                'MRP Confirmation' => $this->sanitizeExcelValue($order->tempOrder?->mrp_confirmation ?? 'Incorrect'),
                'PO Number' => $this->sanitizeExcelValue($order->tempOrder?->po_number ?? ''),
                'PO Quantity' => (int) ($order->ordered_quantity ?? 0),
                'Purchase Order Quantity' => (int) ($order->tempOrder?->purchase_order_quantity ?? 0),  
                'Vendor PI Fulfillment Quantity' => (int) ($order->tempOrder?->vendor_pi_fulfillment_quantity ?? 0),
                'Vendor PI Received Quantity' => (int) ($order->tempOrder?->vendor_pi_received_quantity ?? 0),
                'Block Quantity' => (int) ($order->tempOrder?->block ?? 0),
                'Quantity Fulfilled' => $order->dispatched_quantity ??  (int) $qtyFullfilled ?? 0,
                'Final Fulfilled Quantity' => (int) ($order->final_dispatched_quantity ?? 0),
                'Warehouse Allocation' => $this->sanitizeExcelValue($warehouseAllocation),
            ];

            $writer->addRow($rowData);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'update_sales_order_po_' . $salesOrder->id . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Sanitize values for Excel to prevent corruption
     */
    private function sanitizeExcelValue($value): string
    {
        if (is_null($value)) {
            return '';
        }

        // Convert to string
        $value = (string) $value;

        // Remove control characters and invalid XML characters
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);

        // Escape formula injection attempts
        if (in_array(substr($value, 0, 1), ['=', '+', '-', '@'])) {
            $value = "'" . $value;
        }

        // Trim whitespace
        $value = trim($value);

        return $value;
    }

    public function downloadNotFoundSku($id)
    {
        if (! $id) {
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
        if (! $id) {
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
        if (! $id) {
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

    /**
     * Auto-allocate stock from multiple warehouses for a sales order
     *
     * @param  int  $id  Sales Order ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoAllocateStock($id)
    {
        DB::beginTransaction();
        try {
            $salesOrder = SalesOrder::with('orderedProducts')->findOrFail($id);
            $allocationService = new WarehouseAllocationService;

            // Allocate stock for entire sales order
            $result = $allocationService->allocateSalesOrder($id);

            if (! $result['success']) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Allocation failed: ' . $result['error'],
                ], 500);
            }

            // If purchase order is needed, create it
            if ($result['needs_purchase_order'] && ! empty($result['purchase_order_items'])) {
                // Get vendor from first ordered product (you can modify this logic)
                $firstProduct = $salesOrder->orderedProducts->first();
                $vendorId = $firstProduct->vendor_code ?? null;

                if ($vendorId) {
                    $purchaseResult = $allocationService->createPurchaseOrderForShortage(
                        $id,
                        $result['purchase_order_items'],
                        $vendorId
                    );

                    $result['purchase_order'] = $purchaseResult;
                }
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($salesOrder)
                ->causedBy(Auth::user())
                ->withProperties($result)
                ->log("Multi-warehouse auto-allocation completed for Sales Order #{$id}");

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Auto allocation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get allocation breakdown for a sales order
     *
     * @param  int  $id  Sales Order ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllocationBreakdown($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|exists:sales_orders,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $allocationService = new WarehouseAllocationService;
            $breakdown = $allocationService->getAllocationBreakdown($id);

            return response()->json([
                'success' => true,
                'sales_order_id' => $id,
                'allocations' => $breakdown,
            ]);
        } catch (\Exception $e) {
            Log::error('Get allocation breakdown failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Manual allocation - allocate specific SKU from specific warehouse
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function manualAllocate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_order_id' => 'required|exists:sales_orders,id',
            'sales_order_product_id' => 'required|exists:sales_order_products,id',
            'sku' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Check warehouse stock availability
            $warehouseStock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
                ->where('sku', $request->sku)
                ->first();

            if (! $warehouseStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'SKU not found in selected warehouse',
                ], 404);
            }

            if ($warehouseStock->available_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$warehouseStock->available_quantity}, Requested: {$request->quantity}",
                ], 400);
            }

            // Get next sequence number
            $lastSequence = WarehouseAllocation::where('sales_order_id', $request->sales_order_id)
                ->where('sku', $request->sku)
                ->max('sequence') ?? 0;

            // Create allocation
            $allocation = WarehouseAllocation::create([
                'sales_order_id' => $request->sales_order_id,
                'sales_order_product_id' => $request->sales_order_product_id,
                'warehouse_id' => $request->warehouse_id,
                'sku' => $request->sku,
                'allocated_quantity' => $request->quantity,
                'sequence' => $lastSequence + 1,
                'status' => 'allocated',
                'notes' => 'Manual allocation',
            ]);

            // Update warehouse stock
            $warehouseStock->available_quantity -= $request->quantity;
            $warehouseStock->block_quantity += $request->quantity;
            $warehouseStock->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($allocation)
                ->causedBy(Auth::user())
                ->withProperties([
                    'warehouse_id' => $request->warehouse_id,
                    'sku' => $request->sku,
                    'quantity' => $request->quantity,
                ])
                ->log('Manual stock allocation created');

            return response()->json([
                'success' => true,
                'message' => 'Stock allocated successfully',
                'allocation' => $allocation,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manual allocation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
