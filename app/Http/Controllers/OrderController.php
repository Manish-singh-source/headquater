<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TempOrder;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\ManageOrder;
use App\Models\ManageVendor;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\PurchaseOrder;
use App\Models\InvoiceDetails;
use App\Models\ManageCustomer;
use App\Models\WarehouseStock;
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
                $sku = trim($record['SKU']);
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

                    if (isset($stockEntry->block_quantity) && $stockEntry->block_quantity >= $stockEntry->quantity) {
                        $productStockCache[$sku] = [
                            'remaining' => 0,
                            'ordered' => 0,
                        ];
                    } else {
                        if ($stockEntry) {
                            $productStockCache[$sku] = [
                                'remaining' => $stockEntry->quantity,
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
                    'sku' => $record['SKU'],
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

                $customerInfo = Customer::where('contact_name', $record['Customer Name'])
                    ->first();

                $saveOrderProduct = new SalesOrderProduct();
                $saveOrderProduct->sales_order_id = $saveOrder->id;
                $saveOrderProduct->temp_order_id = $tempOrder->id;
                $saveOrderProduct->ordered_quantity = $record['PO Quantity'];
                $saveOrderProduct->sku = $record['SKU'];
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
                    $purchaseOrderProduct->sku = $record['SKU'];
                    $purchaseOrderProduct->vendor_code = $record['Vendor Code'];
                    $purchaseOrderProduct->save();
                }

                $blockQuantity = WarehouseStock::where('sku', $sku)->first();
                $WarehouseblockQuantity = WarehouseStock::where('sku', $sku)->first(); // For Updating WarehouseStockLog Table

                // Update WarehouseStock Table
                if ($record['PO Quantity'] > ($blockQuantity->quantity - $blockQuantity->block_quantity)) {
                    $blockQuantity->block_quantity = $blockQuantity->block_quantity + ($blockQuantity->quantity - $blockQuantity->block_quantity);
                } else {
                    $blockQuantity->block_quantity += $record['PO Quantity'];
                }
                $blockQuantity->save();

                // Update WarehouseStockLog Table
                $warehouseStockBlockLogs = new WarehouseStockLog();
                $warehouseStockBlockLogs->warehouse_id = $warehouse_id;
                $warehouseStockBlockLogs->sales_order_id = $saveOrder->id;
                $warehouseStockBlockLogs->sku = $record['SKU'];
                if ($record['PO Quantity'] > ($WarehouseblockQuantity->quantity - $WarehouseblockQuantity->block_quantity)) {
                    $warehouseStockBlockLogs->block_quantity = ($WarehouseblockQuantity->quantity - $WarehouseblockQuantity->block_quantity);
                } else {
                    $warehouseStockBlockLogs->block_quantity = $record['PO Quantity'];
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


    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
    }

    public function view($id)
    {
        $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder', 'orderedProducts.vendorPIProduct.order', 'vendorPIs.products')->findOrFail($id);
        // $warehouseStockBlockLogs = WarehouseStockLog::where('sales_order_id', $id)->get();
        // get vendor pi quantity 
        // vendor_code, product_sku, purchase_order_id, sales_order_id 
        // $vendorQty = PurchaseOrder::where('sales_order_id', $salesOrder->id)->with('vendorPI')->get();
        // dd($warehouseStockBlockLogs); 

        foreach ($salesOrder->orderedProducts as $orderedProduct) {
            $orderedProduct->warehouseStockLog = WarehouseStockLog::where('sales_order_id', $orderedProduct->sales_order_id)
                ->where('sku', $orderedProduct->sku)
                ->first();
        }
        // dd($salesOrder->orderedProducts);
        return view('salesOrder.view', compact('salesOrder'));
    }

    public function  changeStatus(Request $request)
    {
        $salesOrder = SalesOrder::findOrFail($request->order_id);
        $salesOrderDetails = SalesOrderProduct::where('sales_order_id', $salesOrder->id)->get();

        $salesOrder->status = $request->status;
        
        if($salesOrder->status == 'ready_to_ship')
        {
            $invoice = new Invoice();
            $invoice->warehouse_id = $salesOrder->warehouse_id;
            $invoice->invoice_number = 'INV-' . time();
            $invoice->customer_id = $salesOrder->customer_group_id;
            $invoice->sales_order_id = $salesOrder->id;
            $invoice->invoice_date = now();
            $invoice->round_off = 0;
            $invoice->total_amount = $salesOrder->orderedProducts->sum(function ($product)
            {
                return $product->ordered_quantity * $product->product->price; // Assuming 'price' is the field in Product model
            });
            $invoiceDetails = new InvoiceDetails();
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

        foreach ($reader->getRows() as $record) {
            $sku = trim($record['SKU']);
            $productSku =
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

                if (isset($stockEntry->block_quantity) && $stockEntry->block_quantity >= $stockEntry->quantity) {
                    $productStockCache[$sku] = [
                        'remaining' => 0,
                        'ordered' => 0,
                    ];
                } else {
                    if (isset($stockEntry->block_quantity)) {
                        $stockEntry->quantity -= $stockEntry->block_quantity;
                    }
                    if ($stockEntry) {
                        $productStockCache[$sku] = [
                            'remaining' => $stockEntry->quantity,
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
                $unavailableStatus = $availableQty;
                $productStockCache[$sku]['remaining'] -= $poQty;
            } else {
                // Insufficient stock
                $shortage = $poQty - $remaining;
                $unavailableStatus = $shortage;
                $productStockCache[$sku]['remaining'] = 0;
            }

            $insertedRows[] = [
                'customer_name' => $record['Customer Name'],
                'po_number' => $record['PO Number'],
                'sku' => $sku,
                'facility_name' => $record['Facility Name'],
                'facility_location' => $record['Facility Location'],
                'po_date' => $record['PO Date']->format('d-m-Y'),
                'po_expiry_date' => $record['PO Expiry Date']->format('d-m-Y'),
                'hsn' => $record['HSN'],
                'item_code' => $record['Item Code'],
                'description' => $record['Description'],
                'basic_rate' => $record['Basic Rate'],
                'gst' => $record['GST'],
                'net_landing_rate' => $record['Net Landing Rate'],
                'mrp' => $record['MRP'],
                'po_qty' => $poQty,
                'available_quantity' => $remaining,
                'unavailable_quantity' => $unavailableStatus,
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

        // dd($insertedRows);

        $customerGroup = CustomerGroup::all();
        $warehouses = Warehouse::all();
        return view('process-order', ['customerGroup' => $customerGroup, 'warehouses' => $warehouses, 'fileData' => $insertedRows]);
    }


    public function downloadBlockedCSV()
    {
        $filePath = public_path(session('processed_csv_path'));
        // print all data
        // dd(file_get_contents($filePath));
        // add 3 columns to the csv file
        // We'll add: 'Blocked Quantity', 'Processed At', 'Processed By'
        // Read the CSV, add columns, and write back

        $rows = [];

        // Use SimpleExcelReader to read the file
        $reader = SimpleExcelReader::create($filePath);
        foreach ($reader->getRows() as $row) {
            $rows[] = [
                'Customer Name' => $row['customer_name'] ?? '',
                'PO Number' => $row['po_number'] ?? '',
                'SKU' => $row['sku'] ?? '',
                'Facility Name' => $row['facility_name'] ?? '',
                'Facility Location' => $row['facility_location'] ?? '',
                'PO Date' => $row['po_date'] ?? '',
                'PO Expiry Date' => $row['po_expiry_date'] ?? '',
                'HSN' => $row['hsn'] ?? '',
                'Item Code' => $row['item_code'] ?? '',
                'Description' => $row['description'] ?? '',
                'Basic Rate' => $row['basic_rate'] ?? '',
                'GST' => $row['gst'] ?? '',
                'Net Landing Rate' => $row['net_landing_rate'] ?? '',
                'MRP' => $row['mrp'] ?? '',
                'Rate Confirmation' => '',
                'PO Quantity' => $row['po_qty'] ?? '',
                'Case Pack Quantity' => '',
                'Available Quantity' => $row['available_quantity'] ?? '',
                'Unavailable Quantity' => $row['unavailable_quantity'] ?? '',
                'Block' => '',
                'Purchase Order Quantity' => '',
                'Vendor Code' => '',
                'Blocked Quantity' => '',
                'Processed At' => '',
                'Processed By' => '',
            ];
        }

        // Write the new CSV with extra columns
        SimpleExcelWriter::create($filePath)->addRows($rows);

        // Now download the file
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, basename($filePath), [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        // if (!file_exists($filePath)) {
        //     abort(404);
        // }

        // return response()->download($filePath, basename($filePath), [
        //     'Content-Type' => 'text/csv',
        //     // 'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        // ]);
        // return redirect()->route->('orders')->response()->download(public_path(session('processed_csv_path')));
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
