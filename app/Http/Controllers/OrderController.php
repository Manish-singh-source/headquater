<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TempOrder;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\ManageOrder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ManageVendor;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\PurchaseOrder;
use App\Models\InvoiceDetails;
use App\Models\ManageCustomer;
use App\Models\WarehouseStock;
use Illuminate\Support\Carbon;
use App\Models\SalesOrderProduct;
use App\Models\WarehouseStockLog;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class OrderController extends Controller
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
            $insertCount = 0;

            $saveOrder = new SalesOrder();
            $saveOrder->warehouse_id = $warehouse_id;
            $saveOrder->customer_group_id = $customer_group_id;
            $saveOrder->save();

            $purchaseOrder = new PurchaseOrder();
            $purchaseOrder->sales_order_id = $saveOrder->id;
            $purchaseOrder->warehouse_id = $warehouse_id;
            $purchaseOrder->customer_group_id = $customer_group_id;
            $purchaseOrder->save();

            foreach ($reader->getRows() as $record) {
                $sku = trim($record['SKU Code']);
                $poQty = (int)$record['PO Quantity'];
                $warehouseId = $request->warehouse_id;

                $availableQty = 0;
                $unavailableStatus = 0;

                // Fetch stock if not already cached
                if (!isset($productStockCache[$sku])) {
                    $stockEntry = WarehouseStock::with('product')
                        ->where('sku', $sku)
                        ->where('warehouse_id', $warehouseId)
                        ->first();

                    if (!isset($stockEntry)) {
                        $productStockCache[$sku] = [
                            'remaining' => 0,
                            'ordered' => 0,
                        ];

                        // Store Product & Stock Quantity as well
                        $newProduct = Product::create([
                            'sku' => $record['SKU Code'] ?? '',
                            'ean_code' => $record['EAN Code'] ?? '',
                            'brand' => $record['Description'] ?? '',
                            'brand_title' => $record['Description'] ?? '',
                            'mrp' => $record['MRP'] ?? '0',
                            'category' => $record['Category'] ?? '',
                            'pcs_set' => $record['PCS/Set'] ?? '0',
                            'sets_ctn' => $record['Sets/CTN'] ?? '0',
                            'vendor_name' => $record['Vendor Name'] ?? '',
                            'vendor_purchase_rate' => $record['Vendor Purchase Rate'] ?? '',
                            'gst' => $record['GST'] ?? '0',
                            'vendor_net_landing' => $record['Net Landing Rate'] ?? '',
                            'created_at' => now() ?? '',
                            'updated_at' => now() ?? '',
                        ]);

                        WarehouseStock::create([
                            'warehouse_id' => $warehouseId,
                            'product_id' => $newProduct->id,
                            'sku' => $record['SKU Code'],
                        ]);
                    } else {
                        if (empty($stockEntry->quantity)) {
                            $quantity = 0;
                            $block_quantity = 0;
                        } else {
                            $quantity = $stockEntry->quantity;
                            $block_quantity = $stockEntry->block_quantity;
                        }

                        if (isset($block_quantity) && $block_quantity >= $quantity) {
                            $productStockCache[$sku] = [
                                'remaining' => 0,
                                'ordered' => 0,
                            ];
                        } elseif (isset($block_quantity) && $block_quantity > 0 && $block_quantity < $quantity) {
                            $productStockCache[$sku] = [
                                'remaining' => $quantity - $block_quantity,
                                'ordered' => 0,
                            ];
                        } else {
                            if ($stockEntry) {
                                $productStockCache[$sku] = [
                                    'remaining' => $quantity,
                                    'ordered' => $stockEntry->product->sets_ctn ?? 0,
                                ];
                            } else {
                                $productStockCache[$sku] = [
                                    'remaining' => 0,
                                    'ordered' => 0,
                                ];
                            }
                        }
                    }
                }

                // Use cached values
                $remaining = $productStockCache[$sku]['remaining'];
                $availableQty = $productStockCache[$sku]['ordered'];

                // Stock check
                if ($remaining >= $poQty) {
                    // Sufficient stock  
                    $remaining = $poQty;
                    $unavailableStatus = 0;
                    $productStockCache[$sku]['remaining'] -= $poQty;
                } else {
                    // Insufficient stock
                    $shortage = $poQty - $remaining;
                    $unavailableStatus = $shortage;
                    $productStockCache[$sku]['remaining'] = 0;
                }


                $tempOrder = TempOrder::create([
                    'customer_name' => $record['Customer Name'],
                    'po_number' => $record['PO Number'],
                    'sku' => $record['SKU Code'],
                    'facility_name' => $record['Facility Name'],
                    'facility_location' => $record['Facility Location'],
                    'po_date' => $record['PO Date'],
                    'po_expiry_date' => $record['PO Expiry Date'],
                    'hsn' => $record['HSN'],
                    'gst' => $record['GST'],
                    'item_code' => $record['Item Code'],
                    'description' => $record['Description'],
                    'basic_rate' => $record['Basic Rate'],
                    'net_landing_rate' => $record['Net Landing Rate'],
                    'mrp' => $record['PO MRP'],
                    'product_mrp' => $record['Product MRP'],
                    // rate confirmation ?? want to store -- first create rate_confirmation column in db 
                    // 'rate_confirmation' => $record['Rate Confirmation'],
                    'po_qty' => $record['PO Quantity'],
                    'available_quantity' => $remaining,
                    'unavailable_quantity' => $unavailableStatus,
                    'block' => $record['Block'],
                    'rate_confirmation' => $record['Rate Confirmation'],
                    'case_pack_quantity' => $record['Case Pack Quantity'],
                    'purchase_order_quantity' => $record['Purchase Order Quantity'] ?? '',
                    'vendor_code' => $record['Vendor Code'],
                ]);

                $customerInfo = Customer::where('client_name', $record['Facility Name'])
                    ->first();

                $saveOrderProduct = new SalesOrderProduct();
                $saveOrderProduct->sales_order_id = $saveOrder->id;
                $saveOrderProduct->temp_order_id = $tempOrder->id;
                $saveOrderProduct->ordered_quantity = $record['PO Quantity'];
                $saveOrderProduct->sku = $record['SKU Code'];
                if (isset($customerInfo)) {
                    $saveOrderProduct->customer_id = $customerInfo->id;
                }
                $saveOrderProduct->vendor_code = $record['Vendor Code'];
                $saveOrderProduct->save();

                if ($unavailableStatus > 0) {
                    // Check if an existing product for the same purchase order, SKU, and vendor exists
                    $existingProduct = PurchaseOrderProduct::where('purchase_order_id', $purchaseOrder->id)
                        ->where('sku', $record['SKU Code'])
                        ->where('vendor_code', $record['Vendor Code'])
                        ->first();

                    if ($existingProduct) {
                        // Combine quantities if match found
                        $existingProduct->ordered_quantity += $unavailableStatus;
                        $existingProduct->save();
                    } else {
                        // Create a new record
                        $purchaseOrderProduct = new PurchaseOrderProduct();
                        $purchaseOrderProduct->sales_order_id = $saveOrder->id;
                        $purchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
                        $purchaseOrderProduct->ordered_quantity = $unavailableStatus;
                        $purchaseOrderProduct->sku = $record['SKU Code'];
                        $purchaseOrderProduct->vendor_code = $record['Vendor Code'];
                        $purchaseOrderProduct->save();
                    }
                }


                $blockQuantity = WarehouseStock::where('sku', $sku)->first();
                $WarehouseblockQuantity = WarehouseStock::where('sku', $sku)->first(); // For Updating WarehouseStockLog Table
                // Update WarehouseStock Table
                if (isset($blockQuantity)) {
                    if (empty($blockQuantity->quantity)) {
                        $quantity1 = 0;
                        $block_quantity1 = 0;
                    } else {
                        $quantity1 = $blockQuantity->quantity;
                        $block_quantity1 = $blockQuantity->block_quantity;
                    }

                    if ($record['PO Quantity'] > ((int)$quantity1 - (int)$block_quantity1)) {
                        $blockQuantity->block_quantity = (int)$block_quantity1 + ((int)$quantity1 - (int)$block_quantity1);
                    } else {
                        $blockQuantity->block_quantity = (int)$block_quantity1 + (int)$record['PO Quantity'];
                    }
                    $blockQuantity->save();
                }

                // Update WarehouseStockLog Table
                $warehouseStockBlockLogs = new WarehouseStockLog();
                $warehouseStockBlockLogs->warehouse_id = $warehouse_id;
                $warehouseStockBlockLogs->sales_order_id = $saveOrder->id;
                if (isset($customerInfo)) {
                    $warehouseStockBlockLogs->customer_id = $customerInfo->id;
                }
                $warehouseStockBlockLogs->sku = $record['SKU Code'];
                if (isset($WarehouseblockQuantity)) {
                    if ($record['PO Quantity'] > ((int)$WarehouseblockQuantity->quantity - (int)$WarehouseblockQuantity->block_quantity)) {
                        $warehouseStockBlockLogs->block_quantity = ((int)$WarehouseblockQuantity->quantity - (int)$WarehouseblockQuantity->block_quantity);
                    } else {
                        $warehouseStockBlockLogs->block_quantity = $record['PO Quantity'];
                    }
                }
                $warehouseStockBlockLogs->reason = "Quantity Blocked For Sales Order Id - " . $saveOrder->id;
                $warehouseStockBlockLogs->save();

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
            }
            DB::commit();

            // Create notification for sales order
            // notifySalesOrder($saveOrder);

            return redirect()->route('order.index')->with('success', 'Order Completed Successful.');
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
            $rows = $reader->getRows();
            $products = [];
            $warehouseStockUpdate = [];
            $insertCount = 0;

            foreach ($rows as $record) {
                if (empty($record['SKU Code'])) continue;

                $customerInfo = Customer::where('client_name', $record['Facility Name'])
                    ->first();

                $salesOrderProductUpdate = SalesOrderProduct::where('sku', $record['SKU Code'])->where('sales_order_id', $request->sales_order_id)->where('customer_id', $customerInfo->id)->first();

                $products[] = [
                    'id' => $salesOrderProductUpdate->temp_order_id,
                    'customer_name' => Arr::get($record, 'Customer Name') ?? '',
                    'facility_name' => Arr::get($record, 'Facility Name') ?? '',
                    'hsn' => Arr::get($record, 'HSN') ?? '',
                    'gst' => Arr::get($record, 'GST') ?? '',
                    'item_code' =>  Arr::get($record, 'Item Code') ?? '',
                    'sku' => Arr::get($record, 'SKU Code') ?? '',
                    'description' => Arr::get($record, 'Title') ?? '',
                    'basic_rate' => Arr::get($record, 'Basic Rate') ?? '',
                    'net_landing_rate' => Arr::get($record, 'Net Landing Rate') ?? '',
                    'mrp' =>  Arr::get($record, 'PO MRP') ?? '',
                    'product_mrp' => Arr::get($record, 'Product MRP') ?? '',
                    'rate_confirmation' => Arr::get($record, 'Rate Confirmation') ?? '',
                    'po_qty' => Arr::get($record, 'Qty Requirement') ?? '',
                    'block' => Arr::get($record, 'Qty Requirement') ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $salesOrderProductUpdate->ordered_quantity = $record['Qty Requirement'];
                $salesOrderProductUpdate->save();

                $warehouseStockBlockLogsUpdate = WarehouseStockLog::where('sku', $record['SKU Code'])->where('sales_order_id', $request->sales_order_id)->where('customer_id', $customerInfo->id)->first();
                $warehouseStockUpdate = WarehouseStock::where('sku', $record['SKU Code'])->first();
                // $warehouseStockBlockLogsUpdate->block_quantity =  $record['Qty Requirement'];

                if ($warehouseStockBlockLogsUpdate->block_quantity > $record['Qty Requirement']) {
                    $blockQuantityUpdated = $warehouseStockBlockLogsUpdate->block_quantity - $record['Qty Requirement'];
                    $warehouseStockBlockLogsUpdate->block_quantity = $warehouseStockBlockLogsUpdate->block_quantity - $blockQuantityUpdated;

                    $blockStockQuantityUpdated = $warehouseStockUpdate->block_quantity - $record['Qty Requirement'];
                    $warehouseStockUpdate->block_quantity =  $warehouseStockUpdate->block_quantity - $blockStockQuantityUpdated;
                } else {
                    $blockStock = $record['Qty Requirement'] - (int)$warehouseStockBlockLogsUpdate->block_quantity;
                    $available = $warehouseStockUpdate->quantity - $warehouseStockUpdate->block_quantity;

                    if ($available >= $blockStock) {
                        $warehouseStockBlockLogsUpdate->block_quantity += $blockStock;
                        $warehouseStockUpdate->block_quantity += $blockStock;
                    } else {
                        $warehouseStockBlockLogsUpdate->block_quantity += $available;
                        $warehouseStockUpdate->block_quantity += $available;
                    }

                    // what about extra ?? 
                }

                $warehouseStockBlockLogsUpdate->save();
                $warehouseStockUpdate->save();

                $insertCount++;
            }

            TempOrder::upsert($products, ['id']);

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['products_excel' => 'No valid data found in the file.']);
            }

            DB::commit();
            return redirect()->route('order.index')->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function view($id)
    {
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder', 'orderedProducts.warehouseStock', 'orderedProducts.vendorPIProduct.order', 'vendorPIs.products')->findOrFail($id);
        foreach ($salesOrder->orderedProducts as $orderedProduct) {
            $orderedProduct->warehouseStockLog = WarehouseStockLog::where('sales_order_id', $orderedProduct->sales_order_id)
                ->where('customer_id', $orderedProduct->customer_id)
                ->where('sku', $orderedProduct->sku)
                ->first();
        }
        // dd($salesOrder->orderedProducts);
        return view('salesOrder.view', compact('salesOrder'));
    }

    public function destroy($id)
    {
        $order = SalesOrder::findOrFail($id);
        $orderedProducts = SalesOrderProduct::where('sales_order_id', $id)->get();
        foreach ($orderedProducts as $product) {
            $warehouseStockBlock = WarehouseStockLog::where('sales_order_id', $product->sales_order_id)->where('sku', $product->sku)->first();
            $warehouseStock = WarehouseStock::where('sku', $product->sku)->first();
            if (isset($warehouseStockBlock) && isset($warehouseStock)) {
                $warehouseStock->block_quantity = ($warehouseStock->block_quantity ?? 0) - ($warehouseStockBlock->block_quantity ?? 0);
                $warehouseStockBlock->block_quantity = 0;
                $warehouseStock->save();
            }
        }

        $order->delete();
        return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        foreach ($ids as $salesOrderIdKey => $salesOrderId) {
            $salesOrderProduct = SalesOrderProduct::where('id', $salesOrderId)->first();
            $blockQuantity = WarehouseStockLog::where('sales_order_id', $salesOrderProduct->sales_order_id)
                ->where('customer_id', $salesOrderProduct->customer_id)
                ->where('sku', $salesOrderProduct->sku)
                ->first();

            if ($blockQuantity->block_quantity > 0) {
                $WarehouseStockUpdate = WarehouseStock::where('sku', $salesOrderProduct->sku)->first();
                $WarehouseStockUpdate->block_quantity = $WarehouseStockUpdate->block_quantity - $blockQuantity->block_quantity;
                $WarehouseStockUpdate->save();

                // Released Products now ready to available for another products  
                // $allowToAnotherAvailableQuantityToBlock = SalesOrderProduct::whereNot('customer_id', $salesOrderProduct->customer_id)->where('sales_order_id', $salesOrderProduct->sales_order_id)->where('sku', $salesOrderProduct->sku)->first();
            }
        }

        SalesOrderProduct::destroy($ids);

        return redirect()->back()->with('success', 'Selected customers deleted successfully.');
    }

    public function  changeStatus(Request $request)
    {
        $salesOrder = SalesOrder::findOrFail($request->order_id);
        $salesOrderDetails = SalesOrderProduct::where('sales_order_id', $salesOrder->id)->get();

        $salesOrder->status = $request->status;

        // Create notifications based on status change
        if ($request->status == 'ready_to_package') {
            notifyPackagingList($salesOrder);
        } elseif ($request->status == 'ready_to_ship') {
            notifyReadyToShip($salesOrder);
        }

        if ($salesOrder->status == 'ready_to_ship') {
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
                    $product = Product::where('sku', $detail->sku)->first();

                    $invoiceDetails[] = [
                        'invoice_id' => $invoice->id,
                        'product_id' => $product->id,
                        'quantity' => $detail->ordered_quantity,
                        'unit_price' => $product->mrp,
                        'discount' => 0, // Assuming no discount for simplicity
                        'amount' => $detail->ordered_quantity * $product->mrp,
                        'tax' => $product->gst ?? 0, // Assuming tax is a field in Product model
                        'total_price' => ($detail->ordered_quantity * $product->price) - 0, // Total price after discount
                        'description' => isset($detail->tempOrder) ? $detail->tempOrder->description : null, // Assuming description is in TempOrder
                    ];
                }
                InvoiceDetails::insert($invoiceDetails);
            }
        }
        $salesOrder->save();

        if (!$salesOrder) {
            return redirect()->back('error', 'Status Not Changed. Please Try Again.');
        }

        return redirect()->route('packaging.list.index', $request->order_id)->with('success', 'Status has been changed.');
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
        $insertCount = 0;

        foreach ($reader->getRows() as $record) {
            $sku = trim($record['SKU Code']);
            // $productSku =
            $poQty = (int)$record['PO Quantity'];
            $warehouseId = $request->warehouse_id;

            // Default fallback
            $availableQty = 0;
            $unavailableStatus = "0";

            // Fetch stock if not already cached
            if (!isset($productStockCache[$sku])) {
                $stockEntry = WarehouseStock::with('product')
                    ->where('sku', $sku)
                    ->where('warehouse_id', $warehouseId)
                    ->first();


                if (!isset($stockEntry)) {
                    $productStockCache[$sku] = [
                        'remaining' => 0,
                        'ordered' => 0,
                    ];
                } else {
                    // if (empty($stockEntry->quantity)) {
                    //     $quantity = 0;
                    //     $block_quantity = 0;
                    // } else {
                    $quantity = $stockEntry->quantity;
                    $block_quantity = $stockEntry->block_quantity;
                    // }

                    if (isset($block_quantity) && $block_quantity >= $quantity) {
                        $productStockCache[$sku] = [
                            'remaining' => 0,
                            'ordered' => 0,
                        ];
                    } elseif (isset($block_quantity) && $block_quantity > 0 && $block_quantity < $quantity) {
                        $productStockCache[$sku] = [
                            'remaining' => $quantity - $block_quantity,
                            'ordered' => 0,
                        ];
                    } else {
                        if ($stockEntry) {
                            $productStockCache[$sku] = [
                                'remaining' => $quantity,
                                'ordered' => $stockEntry->product->sets_ctn ?? 0,
                            ];
                        } else {
                            $productStockCache[$sku] = [
                                'remaining' => 0,
                                'ordered' => 0,
                            ];
                        }
                    }
                }
            }

            // Use cached values
            $remaining = $productStockCache[$sku]['remaining'];
            $availableQty = $productStockCache[$sku]['ordered'];

            // Stock check
            if ($remaining >= $poQty) {
                // Sufficient stock
                $remaining = $poQty;
                $unavailableStatus = 0;
                $productStockCache[$sku]['remaining'] -= $poQty;
            } else {
                // Insufficient stock
                $shortage = $poQty - $remaining;
                $unavailableStatus = $shortage;
                $productStockCache[$sku]['remaining'] = 0;
            }

            // $casePackQty = (int)$stockEntry->product->pcs_set;
            $casePackQty = (int)$stockEntry->product->pcs_set * (int)$stockEntry->product->sets_ctn;

            // dd($casePackQty);
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
                'GST' => $record['GST'],
                'Basic Rate' => $record['Basic Rate'],
                'Net Landing Rate' => $record['Net Landing Rate'],
                'MRP' => $record['MRP'],
                'Product MRP' => $stockEntry->product->mrp,
                'Rate Confirmation' => ($record['MRP'] <= $stockEntry->product->mrp) ? 'Yes' : 'No',
                'Case Pack Quantity' => $casePackQty ?? 0,
                'PO Quantity' => $poQty,
                'Available Quantity' => $remaining,
                'Unavailable Quantity' => $unavailableStatus,
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
                'Basic Rate' => $row['Basic Rate'] ?? '',
                'Net Landing Rate' => $row['Net Landing Rate'] ?? '',
                'PO MRP' => $row['MRP'] ?? '',
                'Product MRP' => $row['MRP'] ?? '',
                'Rate Confirmation' => $row['Rate Confirmation'] ?? '',
                'PO Quantity' => $row['PO Quantity'] ?? '',
                'Available Quantity' => $row['Available Quantity'] ?? '',
                'Unavailable Quantity' => $row['Unavailable Quantity'] ?? '',
                'Case Pack Quantity' => $row['Case Pack Quantity'] ?? '',
                'Purchase Order Quantity' => $row['Unavailable Quantity'] ?? '',
                'Block' => '',
                'Vendor Code' => '',
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

        // Fetch data with relationships
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder', 'orderedProducts.vendorPIProduct.order', 'vendorPIs.products')->findOrFail($request->salesOrderId);
        foreach ($salesOrder->orderedProducts as $orderedProduct) {
            $orderedProduct->warehouseStockLog = WarehouseStockLog::where('sales_order_id', $orderedProduct->sales_order_id)
                ->where('customer_id', $orderedProduct->customer_id)
                ->where('sku', $orderedProduct->sku)
                ->first();
        }
        // Add rows
        foreach ($salesOrder->orderedProducts as $order) {
            if ($order->ordered_quantity > 0) {

                $fulfilledQuantity = 0;

                if ($order->product?->sets_ctn) {
                    if ($order->vendorPIProduct?->order?->status != 'completed') {
                        if (
                            $order->vendorPIProduct?->available_quantity + $order->warehouseStockLog?->block_quantity >=
                            $order->ordered_quantity
                        ) {
                            $fulfilledQuantity = $order->ordered_quantity;
                        } else {
                            $fulfilledQuantity = $order->vendorPIProduct?->available_quantity + $order->warehouseStockLog?->block_quantity;
                        }
                    } else {
                        if ($order->warehouseStockLog?->block_quantity >= $order->ordered_quantity) {
                            $fulfilledQuantity = $order->ordered_quantity;
                        } else {
                            $fulfilledQuantity = $order->warehouseStockLog?->block_quantity;
                        }
                    }
                } else {
                    $fulfilledQuantity = '0';
                }

                $writer->addRow([
                    'Order No' => $salesOrder->id,
                    'Customer Name' => $order->tempOrder->customer_name,
                    'Facility Name' => $order->tempOrder->facility_name,
                    'HSN' => $order->tempOrder->hsn,
                    'GST' => $order->tempOrder->gst,
                    'Item Code' =>  $order->tempOrder->item_code,
                    'SKU Code' => $order->tempOrder->sku,
                    'Title' => $order->tempOrder->description,
                    'Basic Rate' => $order->tempOrder->basic_rate,
                    'Net Landing Rate' => $order->tempOrder->net_landing_rate,
                    'PO MRP' =>  $order->tempOrder->mrp,
                    'Product MRP' => $order->tempOrder->product_mrp,
                    'Rate Confirmation' => ($order->tempOrder->mrp == $order->tempOrder->product_mrp) ? 'Confirmed' : 'Mismatched',
                    'Qty Requirement' => $order->ordered_quantity,
                    'Qty Fullfilled' => $fulfilledQuantity,
                ]);
            }
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'vendor_po.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
