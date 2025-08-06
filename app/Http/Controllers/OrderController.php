<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TempOrder;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\ManageOrder;
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
                    'item_code' => $record['Item Code'],
                    'description' => $record['Description'],
                    'basic_rate' => $record['Basic Rate'],
                    'gst' => $record['GST'],
                    'net_landing_rate' => $record['Net Landing Rate'],
                    'mrp' => $record['MRP'],
                    'po_qty' => $record['PO Quantity'],
                    'available_quantity' => $remaining,
                    'unavailable_quantity' => $unavailableStatus,
                    'block' => $record['Block'],
                    'rate_confirmation' => $record['Rate Confirmation'],
                    'case_pack_quantity' => $record['Case Pack Quantity'],
                    'purchase_order_quantity' => $record['Purchase Order Quantity'],
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
                    $purchaseOrderProduct = new PurchaseOrderProduct();
                    $purchaseOrderProduct->sales_order_id = $saveOrder->id;
                    $purchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
                    $purchaseOrderProduct->ordered_quantity = $unavailableStatus;
                    $purchaseOrderProduct->sku = $record['SKU Code'];
                    $purchaseOrderProduct->vendor_code = $record['Vendor Code'];
                    $purchaseOrderProduct->save();
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

    public function update(Request $request, $id) {}

    public function view($id)
    {
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder', 'orderedProducts.vendorPIProduct.order', 'vendorPIs.products')->findOrFail($id);
        // dd($salesOrder->orderedProducts[0]);
        foreach ($salesOrder->orderedProducts as $orderedProduct) {
            $orderedProduct->warehouseStockLog = WarehouseStockLog::where('sales_order_id', $orderedProduct->sales_order_id)
                ->where('customer_id', $orderedProduct->customer_id)
                ->where('sku', $orderedProduct->sku)
                ->first();
        }
        // dd($salesOrder->orderedProducts[0]);
        return view('salesOrder.view', compact('salesOrder'));
    }

    public function destroy($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        // dd($ids);
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
                // dd($allowToAnotherAvailableQuantityToBlock);
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
                    return $product->ordered_quantity * $product->product->price; // Assuming 'price' is the field in Product model
                });
                // $invoiceDetails = new InvoiceDetails();
                $invoice->save();

                $invoiceDetails = [];
                foreach ($salesOrderDetails as $detail) {
                    $product = Product::where('sku', $detail->sku)->first();
                    // dd($product);

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
                // dd($stockEntry->product->sets_ctn);
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
                'Basic Rate' => $record['Basic Rate'],
                'GST' => $record['GST'],
                'Net Landing Rate' => $record['Net Landing Rate'],
                'MRP' => $record['MRP'],
                'PO Quantity' => $poQty,
                'Available Quantity' => $remaining,
                'Unavailable Quantity' => $unavailableStatus,
            ];
        }
        // dd($insertedRows);


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

        // dd($insertedRows);

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
                'Item Code' => $row['Item Code'] ?? '',
                'Description' => $row['Description'] ?? '',
                'Basic Rate' => $row['Basic Rate'] ?? '',
                'GST' => $row['GST'] ?? '',
                'Net Landing Rate' => $row['Net Landing Rate'] ?? '',
                'MRP' => $row['MRP'] ?? '',
                'PO Quantity' => $row['PO Quantity'] ?? '',
                'Available Quantity' => $row['Available Quantity'] ?? '',
                'Unavailable Quantity' => $row['Unavailable Quantity'] ?? '',
                'Block' => '',
                'Purchase Order Quantity' => '',
                'Vendor Code' => '',
                'Case Pack Quantity' => '',
                'Rate Confirmation' => '',
            ]);
        });

        $writer->close();

        // Return the XLSX as a download
        return response()->download($tempXlsxPath, 'blocked_orders.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // public function processBlockOrder2(Request $request)
    // {
    //     $file = $request->file('csv_file');
    //     if (!$file) {
    //         return redirect()->back()->withErrors(['csv_file' => 'Please upload a CSV file.']);
    //     }

    //     $file = $request->file('csv_file')->getPathname();
    //     $file_extension = $request->file('csv_file')->getClientOriginalExtension();

    //     $reader = SimpleExcelReader::create($file, $file_extension);

    //     DB::beginTransaction();

    //     $manageOrder = new ManageOrder();
    //     $manageOrder->order_id = '1000';
    //     $manageOrder->warehouse_id = $request->warehouse_id;
    //     $manageOrder->save();

    //     $manageCustomer = new ManageCustomer();
    //     $manageCustomer->order_id = $manageOrder->id;
    //     $manageCustomer->customer_id = $request->customer_group_id;
    //     $manageCustomer->save();

    //     $insertedRows = [];
    //     foreach ($reader->getRows() as $record) {

    //         $manageVendor = new ManageVendor();
    //         $manageVendor->order_id = $manageOrder->id;
    //         $manageVendor->vendor_id = $record['vendor_code'];
    //         $manageVendor->save();

    //         $insertedRows[] = [
    //             'order_id' => $manageOrder->id,
    //             'customer_name' => $record['customer_name'],
    //             'po_number' => $record['po_number'],
    //             'sku' => $record['sku'],
    //             'facility_name' => $record['facility_name'],
    //             'facility_location' => $record['facility_location'],
    //             'po_date' => $record['po_date']->format('d-m-Y'),
    //             'po_expiry_date' => $record['po_expiry_date']->format('d-m-Y'),
    //             'hsn' => $record['hsn'],
    //             'item_code' => $record['item_code'],
    //             'description' => $record['description'],
    //             'basic_rate' => $record['basic_rate'],
    //             'gst' => $record['gst'],
    //             'net_landing_rate' => $record['net_landing_rate'],
    //             'mrp' => $record['mrp'],
    //             'rate_confirmation' => $record['rate_confirmation'],
    //             'po_qty' => $record['po_qty'],
    //             'case_pack_quantity' => $record['case_pack_quantity'],
    //             'block' => $record['block'],
    //             'purchase_order_quantity' => $record['purchase_order_quantity'],
    //             'vendor_code' => $record['vendor_code'],
    //         ];
    //     }

    //     if (empty($insertedRows)) {
    //         return redirect()->back()->withErrors(['csv_file' => 'No valid data found in the CSV file.']);
    //     }

    //     $insert = TempOrder::insert($insertedRows);
    //     if (!$insert) {
    //         DB::rollBack();
    //         return redirect()->back()->withErrors(['csv_file' => 'Failed to insert data into the database.']);
    //     }
    //     DB::commit();

    //     return redirect()->route('order')->with('success', 'Order Completed Successful.');
    // }
}
