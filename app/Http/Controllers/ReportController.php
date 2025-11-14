<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\VendorPI;
use App\Models\VendorPIProduct;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display vendor purchase history with optional filtering
     *
     * Filtering Logic:
     * - All filters are optional and can be applied independently or in combination
     * - from_date: Filter purchase orders from this date onwards (inclusive)
     * - to_date: Filter purchase orders up to this date (inclusive)
     * - vendor_code: Filter purchase orders for a specific vendor
     * - If no filters applied, shows all completed purchase orders
     * - Statistics (total amount, quantity) are calculated based on filtered results
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function vendorPurchaseHistory(Request $request)
    {
        try {
            // Build the base query from VendorPIProduct with all necessary relationships
            $query = VendorPIProduct::with([
                'order' => function ($q) {
                    $q->with(['vendor', 'payments', 'purchaseInvoice', 'purchaseGrn', 'purchaseOrder', 'warehouse']);
                },
                'product'
            ]);

            // Apply filters on the parent VendorPI relationship
            $query->whereHas('order', function ($q) use ($request) {
                // Filter only completed orders
                $q->where('status', 'completed');

                // Apply date range filter if from_date is provided
                if ($request->filled('from_date')) {
                    $q->whereDate('created_at', '>=', $request->from_date);
                }

                // Apply date range filter if to_date is provided
                if ($request->filled('to_date')) {
                    $q->whereDate('created_at', '<=', $request->to_date);
                }

                // Apply purchase order filter if purchase_order_no is provided (supports single or multiple)
                if ($request->filled('purchase_order_no')) {
                    $po = $request->input('purchase_order_no');
                    if (is_array($po)) {
                        $q->whereIn('purchase_order_id', $po);
                    } else {
                        $q->where('purchase_order_id', $po);
                    }
                }

                // Apply vendor filter if vendor_code is provided (supports single or multiple)
                if ($request->filled('vendor_code')) {
                    $vc = $request->input('vendor_code');
                    if (is_array($vc)) {
                        $q->whereIn('vendor_code', $vc);
                    } else {
                        $q->where('vendor_code', $vc);
                    }
                }

                // Apply sku filter if sku is provided (supports single or multiple)
                if ($request->filled('sku')) {
                    $sku = $request->input('sku');
                    if (is_array($sku)) {
                        $q->whereIn('vendor_sku_code', $sku);
                    } else {
                        $q->where('vendor_sku_code', $sku);
                    }
                }
            });

            // Clone query for statistics calculation before pagination
            $statsQuery = clone $query;

            // Get paginated vendor PI products (15 per page)
            $vendorPIProducts = $query->latest('id')->paginate(15)->appends($request->all());

            // Calculate statistics based on filtered results
            $purchaseOrdersTotal = $statsQuery->sum('mrp');
            $purchaseOrdersTotalQuantity = $statsQuery->sum('quantity_received');

            // Count unique vendor PIs for total orders
            $totalOrders = $statsQuery->distinct('vendor_pi_id')->count('vendor_pi_id');

            // Get unique purchase order numbers from all completed purchase orders for dropdown
            $purchaseOrderNumbers = VendorPI::distinct('purchase_order_id')
                ->pluck('purchase_order_id');

            // Get unique vendors from all completed purchase orders for dropdown
            $purchaseOrdersVendors = VendorPI::where('status', 'completed')
                ->distinct('vendor_code')
                ->pluck('vendor_code');

            // Get unique vendors from all completed purchase orders for dropdown
            $purchaseOrdersSKUs = VendorPIProduct::distinct('vendor_sku_code')
                ->pluck('vendor_sku_code');    

            // dd($purchaseOrdersSKUs);

            $filters = $request->only(['from_date', 'to_date', 'purchase_order_no', 'vendor_code', 'sku']);

            return view('vendor-purchase-history', compact(
                'vendorPIProducts',
                'purchaseOrdersTotal',
                'purchaseOrdersTotalQuantity',
                'totalOrders',
                'purchaseOrderNumbers',
                'purchaseOrdersVendors',
                'purchaseOrdersSKUs',
                'filters'
            ));
        } catch (\Exception $e) {
            Log::error('Error retrieving vendor purchase history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error retrieving vendor purchase history: ' . $e->getMessage());
        }
    }

    /**
     * Download vendor purchase history as CSV
     *
     * CSV Generation Workflow:
     * 1. Validate optional filter parameters (from_date, to_date, vendor_code)
     * 2. Build query with same filtering logic as index method
     * 3. Retrieve all matching vendor purchase orders (no pagination for export)
     * 4. Generate CSV file with headers and data rows
     * 5. Calculate amounts (total, paid, due) for each order
     * 6. Log activity for audit trail
     * 7. Return CSV file as download and delete temp file after sending
     *
     * All filters are optional - if none provided, exports all completed records
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function vendorPurchaseHistoryExcel(Request $request)
    {
        DB::beginTransaction();
        try {
           // Build the base query from VendorPIProduct with all necessary relationships
            $query = VendorPIProduct::with([
                'order' => function ($q) {
                    $q->with(['vendor', 'payments', 'purchaseInvoice', 'purchaseGrn', 'purchaseOrder', 'warehouse']);
                },
                'product'
            ]);

            // Apply filters on the parent VendorPI relationship
            $query->whereHas('order', function ($q) use ($request) {
                // Filter only completed orders
                $q->where('status', 'completed');

                // Apply date range filter if from_date is provided
                if ($request->filled('from_date')) {
                    $q->whereDate('created_at', '>=', $request->from_date);
                }

                // Apply date range filter if to_date is provided
                if ($request->filled('to_date')) {
                    $q->whereDate('created_at', '<=', $request->to_date);
                }

                // Apply purchase order filter if purchase_order_no is provided (supports single or multiple)
                if ($request->filled('purchase_order_no')) {
                    $po = $request->input('purchase_order_no');
                    if (is_array($po)) {
                        $q->whereIn('purchase_order_id', $po);
                    } else {
                        $q->where('purchase_order_id', $po);
                    }
                }

                // Apply vendor filter if vendor_code is provided (supports single or multiple)
                if ($request->filled('vendor_code')) {
                    $vc = $request->input('vendor_code');
                    if (is_array($vc)) {
                        $q->whereIn('vendor_code', $vc);
                    } else {
                        $q->where('vendor_code', $vc);
                    }
                }

                // Apply sku filter if sku is provided (supports single or multiple)
                if ($request->filled('sku')) {
                    $sku = $request->input('sku');
                    if (is_array($sku)) {
                        $q->whereIn('vendor_sku_code', $sku);
                    } else {
                        $q->where('vendor_sku_code', $sku);
                    }
                }
            });

            // Clone query for statistics calculation before export
            $statsQuery = clone $query;

            // For export we want all matching records (no pagination)
            $vendorPIProducts = $query->latest('id')->get();

            if ($vendorPIProducts->isEmpty()) {
                return redirect()->back()->with('error', 'No vendor purchase records found for the selected criteria.');
            }

            // Create temporary CSV file
            $tempCsvPath = storage_path('app/vendor_purchase_history_' . Str::random(8) . '.csv');
            $file = fopen($tempCsvPath, 'w');

            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add header row
            fputcsv($file, [
                'Purchse Order No',
                'Purchase Order Date',
                'Vendor Name',
                'GSTIN',
                'Item Name',
                'SKU',
                'HSN/SAC',
                'Quantity',
                'UOM',
                'Rate',
                'Discount',
                'Taxable Value',
                'GST',
                'CGST',
                'SGST',
                'IGST',
                'GST Amount',
                'Cess',
                'Cess Amount',
                'PAN',
                'Payment Status',
                'Payment Method',
                'Invoice Ref',
                'Invoice Date',
                'Due Date',
                'Shipping Charges',
                'Status',
                'Warehouse',
            ]);

            // Add data rows
            foreach ($vendorPIProducts as $product) {
                $order = $product['order'];
                $vendor = $order->vendor ?? null;
                $purchaseInvoice = $order->purchaseInvoice ?? null;
                $payment = $order->payments->first() ?? null;
                $gstRate = floatval($product->gst ?? 0);
                $taxableValue = floatval($product->mrp ?? 0);
                $gstAmount = ($taxableValue * $gstRate) / 100;
                $cessRate = floatval($product->cess ?? 0);
                $cessAmount = ($taxableValue * $cessRate) / 100;

                fputcsv($file, [
                    $order->purchase_order_id ?? 'N/A',
                    // $order->created_at ? $order->created_at->format('d-m-Y') : 'N/A',
                    $order->created_at->format('d-m-Y') ?? 'N/A',
                    $vendor->client_name ?? 'N/A',
                    $vendor->gst_number ?? 'N/A',
                    $product->title ?? 'N/A',
                    $product->vendor_sku_code ?? 'N/A',
                    $product->hsn ?? 'N/A',
                    $product->quantity_received ?? 0,
                    $product->product->unit_type ?? 'PCS',
                    $product->purchase_rate ?? 0,
                    0, // Discount
                    $taxableValue,
                    $gstRate,
                    $gstRate/2 ?? 0,    // Adjust based on your logic
                    $gstRate/2 ?? 0,    // Adjust based on your logic
                    $gstRate ?? 0,    // Adjust based on your logic
                    $gstAmount,
                    $cessRate,
                    $cessAmount,
                    $vendor->pan_number ?? 'N/A',
                    $payment ? ($payment->payment_status == 'completed' ? 'Paid' : 'Pending') : 'Pending',
                    $payment ? $payment->payment_method : 'N/A',
                    $purchaseInvoice->invoice_no ?? 'N/A',
                    $purchaseInvoice?->created_at ? $purchaseInvoice->created_at->format('d-m-Y') : 'N/A',
                    'N/A', // Due Date
                    0, // Shipping Charges
                    ucfirst($order->status ?? 'N/A'),
                    $order->warehouse->name ?? 'N/A',
                ]);
            }

            fclose($file);

            // Log activity for audit trail
            // activity()
            //     ->causedBy(Auth::user())
            //     ->withProperties([
            //         'vendor_code' => $request->vendor_code,
            //         'date_from' => $request->date_from,
            //         'date_to' => $request->date_to,
            //         'sku' => $request->sku,
            //         'records' => $vendorReports->count(),
            //     ])
            //     ->event('csv_report_generated')
            //     ->log('Vendor purchase history CSV report generated');

            DB::commit();

            // Generate filename with vendor code(s) or date
            $vendorPart = '';
            if ($request->filled('vendor_code')) {
                $vc = $request->input('vendor_code');
                if (is_array($vc)) {
                    $vendorPart = implode('-', array_map(function ($v) { return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $v)); }, $vc));
                } else {
                    $vendorPart = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $vc));
                }
            }

            $fileName = $vendorPart
                ? 'Vendor-Purchase-History-' . $vendorPart . '-' . date('d-m-Y') . '.csv'
                : 'Vendor-Purchase-History-' . date('d-m-Y') . '.csv';

            // Return CSV file as download and delete after sending
            return response()->download($tempCsvPath, $fileName, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating vendor purchase CSV report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * Display inventory stock history with enhanced filtering
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function inventoryStockHistory(Request $request)
    {
        try {
            // Build base query
            $query = WarehouseStock::with('product', 'warehouse');

            // Apply filters
            if ($request->filled('warehouse_id')) {
                $wid = $request->input('warehouse_id');
                if (is_array($wid)) {
                    $query->whereIn('warehouse_stocks.warehouse_id', $wid);
                } else {
                    $query->where('warehouse_stocks.warehouse_id', $wid);
                }
            }

            if ($request->filled('category')) {
                $cat = $request->input('category');
                if (is_array($cat)) {
                    $query->whereHas('product', function ($q) use ($cat) {
                        $q->whereIn('category', $cat);
                    });
                } else {
                    $query->whereHas('product', function ($q) use ($cat) {
                        $q->where('category', $cat);
                    });
                }
            }

            if ($request->filled('brand')) {
                $brd = $request->input('brand');
                if (is_array($brd)) {
                    $query->whereHas('product', function ($q) use ($brd) {
                        $q->whereIn('brand', $brd);
                    });
                } else {
                    $query->whereHas('product', function ($q) use ($brd) {
                        $q->where('brand', $brd);
                    });
                }
            }

            if ($request->filled('sku')) {
                $sku = $request->input('sku');
                if (is_array($sku)) {
                    $query->whereIn('warehouse_stocks.sku', $sku);
                } else {
                    $query->where('warehouse_stocks.sku', $sku);
                }
            }

            if ($request->filled('from_date')) {
                $query->whereDate('warehouse_stocks.created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('warehouse_stocks.created_at', '<=', $request->to_date);
            }

            if ($request->filled('status')) {
                $statuses = $request->input('status');
                if (is_array($statuses)) {
                    $query->where(function ($q) use ($statuses) {
                        foreach ($statuses as $status) {
                            if ($status == 'Normal') {
                                $q->orWhere('available_quantity', '>', 10);
                            } elseif ($status == 'Low Stock') {
                                $q->orWhere(function ($sub) {
                                    $sub->where('available_quantity', '>=', 1)->where('available_quantity', '<=', 10);
                                });
                            } elseif ($status == 'Out of Stock') {
                                $q->orWhere('available_quantity', '=', 0);
                            }
                        }
                    });
                } else {
                    if ($statuses == 'Normal') {
                        $query->where('available_quantity', '>', 10);
                    } elseif ($statuses == 'Low Stock') {
                        $query->whereBetween('available_quantity', [1, 10]);
                    } elseif ($statuses == 'Out of Stock') {
                        $query->where('available_quantity', '=', 0);
                    }
                }
            }

            // Clone for stats
            $statsQuery = clone $query;

            // Get paginated results
            $products = $query->get();

            // Calculate statistics
            $stats = $statsQuery->selectRaw('
                SUM(original_quantity) as total_original,
                SUM(available_quantity) as total_available,
                SUM(block_quantity) as total_blocked,
                SUM(available_quantity * COALESCE(products.mrp, 0)) as total_value
            ')
            ->leftJoin('products', 'warehouse_stocks.sku', '=', 'products.sku')
            ->first();

            $productsSum = $stats->total_original ?? 0;
            $availableProductsSum = $stats->total_available ?? 0;
            $blockProductsSum = $stats->total_blocked ?? 0;
            $totalStockValue = $stats->total_value ?? 0;

            // Get filter dropdown data
            $warehouses = Warehouse::active()->select('id', 'name')->get();
            $categories = Product::distinct('category')->whereNotNull('category')->pluck('category');
            $brands = Product::distinct('brand')->whereNotNull('brand')->pluck('brand');
            $skus = Product::distinct('sku')->whereNotNull('sku')->pluck('sku');

            // Low stock alerts (available_quantity <= 10)
            $lowStockCount = WarehouseStock::where('available_quantity', '<=', 10)
                ->where('available_quantity', '>', 0)
                ->count();

            // Out of stock count
            $outOfStockCount = WarehouseStock::where('available_quantity', 0)->count();

            return view('inventory-stock-history', compact(
                'products',
                'productsSum',
                'availableProductsSum',
                'blockProductsSum',
                'totalStockValue',
                'warehouses',
                'categories',
                'brands',
                'skus',
                'lowStockCount',
                'outOfStockCount'
            ));
        } catch (\Exception $e) {
            Log::error('Error retrieving inventory: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error retrieving inventory: ' . $e->getMessage());
        }
    }

    /**
     * Download inventory stock history as Excel/CSV
     *
     * CSV Generation Workflow:
     * 1. Validate optional filter parameters (from, to dates)
     * 2. Build query with date range filtering if provided
     * 3. Retrieve all matching warehouse stock records (no pagination for export)
     * 4. Generate CSV file with headers and data rows
     * 5. Log activity for audit trail
     * 6. Return CSV file as download and delete temp file after sending
     *
     * Date Filtering Logic:
     * - Both 'from' and 'to' parameters are optional
     * - If 'from' is provided: filter records created on or after this date
     * - If 'to' is provided: filter records created on or before this date
     * - If both provided: filter records within the date range (inclusive)
     * - If neither provided: export all records
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function inventoryStockHistoryExcel(Request $request)
    {
        // Validate optional filters
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'nullable|array',
            'warehouse_id.*' => 'integer|exists:warehouses,id',
            'category' => 'nullable|array',
            'category.*' => 'string',
            'brand' => 'nullable|array',
            'brand.*' => 'string',
            'sku' => 'nullable|array',
            'sku.*' => 'string',
            'status' => 'nullable|array',
            'status.*' => 'in:Normal,Low Stock,Out of Stock',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Build query with all filters
            $query = WarehouseStock::with('product', 'warehouse');

            if ($request->filled('warehouse_id')) {
                $wid = $request->input('warehouse_id');
                if (is_array($wid)) {
                    $query->whereIn('warehouse_stocks.warehouse_id', $wid);
                } else {
                    $query->where('warehouse_stocks.warehouse_id', $wid);
                }
            }

            if ($request->filled('category')) {
                $cat = $request->input('category');
                if (is_array($cat)) {
                    $query->whereHas('product', function ($q) use ($cat) {
                        $q->whereIn('category', $cat);
                    });
                } else {
                    $query->whereHas('product', function ($q) use ($cat) {
                        $q->where('category', $cat);
                    });
                }
            }

            if ($request->filled('brand')) {
                $brd = $request->input('brand');
                if (is_array($brd)) {
                    $query->whereHas('product', function ($q) use ($brd) {
                        $q->whereIn('brand', $brd);
                    });
                } else {
                    $query->whereHas('product', function ($q) use ($brd) {
                        $q->where('brand', $brd);
                    });
                }
            }

            if ($request->filled('sku')) {
                $sku = $request->input('sku');
                if (is_array($sku)) {
                    $query->whereIn('warehouse_stocks.sku', $sku);
                } else {
                    $query->where('warehouse_stocks.sku', $sku);
                }
            }

            if ($request->filled('from_date')) {
                $query->whereDate('warehouse_stocks.created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('warehouse_stocks.created_at', '<=', $request->to_date);
            }

            if ($request->filled('status')) {
                $statuses = $request->input('status');
                if (is_array($statuses)) {
                    $query->where(function ($q) use ($statuses) {
                        foreach ($statuses as $status) {
                            if ($status == 'Normal') {
                                $q->orWhere('available_quantity', '>', 10);
                            } elseif ($status == 'Low Stock') {
                                $q->orWhere(function ($sub) {
                                    $sub->where('available_quantity', '>=', 1)->where('available_quantity', '<=', 10);
                                });
                            } elseif ($status == 'Out of Stock') {
                                $q->orWhere('available_quantity', '=', 0);
                            }
                        }
                    });
                } else {
                    if ($statuses == 'Normal') {
                        $query->where('available_quantity', '>', 10);
                    } elseif ($statuses == 'Low Stock') {
                        $query->whereBetween('available_quantity', [1, 10]);
                    } elseif ($statuses == 'Out of Stock') {
                        $query->where('available_quantity', '=', 0);
                    }
                }
            }

            // Get all matching records (no pagination for export)
            $products = $query->latest()->get();

            if ($products->isEmpty()) {
                return redirect()->back()->with('error', 'No inventory records found for the selected criteria.');
            }

            // Create temporary CSV file
            $tempCsvPath = storage_path('app/inventory_stock_history_' . Str::random(8) . '.csv');
            $file = fopen($tempCsvPath, 'w');

            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add header row
            fputcsv($file, [
                'Warehouse',
                'Brand',
                'Brand Title',
                'Category',
                'SKU',
                'PCS/Set',
                'Sets/CTN',
                'MRP',
                'Original Quantity',
                'Available Quantity',
                'Hold Qty',
                'Stock Value',
                'Date',
            ]);

            // Add data rows
            foreach ($products as $record) {
                $product = $record->product;
                $stockValue = ($record->available_quantity ?? 0) * ($product->mrp ?? 0);

                fputcsv($file, [
                    $record->warehouse?->name ?? 'N/A',
                    $product?->brand ?? 'N/A',
                    $product?->brand_title ?? 'N/A',
                    $product?->category ?? 'N/A',
                    $product?->sku ?? 'N/A',
                    $product?->pcs_set ?? 0,
                    $product?->sets_ctn ?? 0,
                    number_format($product?->mrp ?? 0, 2, '.', ''),
                    $record->original_quantity ?? 0,
                    $record->available_quantity ?? 0,
                    $record->block_quantity ?? 0,
                    number_format($stockValue, 2, '.', ''),
                    $record->created_at?->format('d-m-Y') ?? 'N/A',
                ]);
            }

            fclose($file);

            // Log activity for audit trail
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'warehouse_id' => $request->warehouse_id,
                    'category' => $request->category,
                    'brand' => $request->brand,
                    'sku' => $request->sku,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'records' => $products->count(),
                ])
                ->event('csv_report_generated')
                ->log('Enhanced inventory stock history CSV report generated');

            DB::commit();

            // Generate filename
            $fileName = 'Inventory-Stock-History-' . date('d-m-Y') . '.csv';

            // Return CSV file as download and delete after sending
            return response()->download($tempCsvPath, $fileName, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating inventory CSV report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating inventory report: ' . $e->getMessage());
        }
    }

    /**
     * Display customer sales history with optional filtering
     *
     * Filtering Logic:
     * - All filters are optional and can be applied independently or in combination
     * - from_date: Filter invoices from this date onwards (inclusive)
     * - to_date: Filter invoices up to this date (inclusive)
     * - customer_id: Filter invoices for a specific customer
     * - If no filters applied, shows all invoices
     * - Statistics (total amount, paid, due) are calculated based on filtered results
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function customerSalesHistory(Request $request)
    {
        try {
            // Build the base query with relationships
            $query = Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments']);

            // Apply date range filter if from_date is provided
            if ($request->filled('from_date')) {
                $query->where('invoice_date', '>=', $request->from_date);
            }

            // Apply date range filter if to_date is provided
            if ($request->filled('to_date')) {
                $query->where('invoice_date', '<=', $request->to_date);
            }

            // Apply customer filter if customer_id is provided
            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            // Clone query for statistics calculation before pagination
            $statsQuery = clone $query;

            // Get paginated invoices (15 per page)
            $invoices = $query->latest('invoice_date')->get();

            // Calculate statistics based on filtered results
            $invoicesAmountSum = $statsQuery->sum('total_amount');

            // Calculate total paid amount from filtered invoices
            $filteredInvoiceIds = $statsQuery->pluck('id');
            $invoicesAmountPaidSum = DB::table('payments')
                ->whereIn('invoice_id', $filteredInvoiceIds)
                ->sum('amount');

            // Get unique customers from all invoices for dropdown
            $customers = Customer::select('id', 'client_name')
                ->whereHas('invoices')
                ->orderBy('client_name')
                ->get()
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->client_name ?? 'N/A',
                    ];
                });

            $data = [
                'title' => 'Customer Sales History',
                'invoices' => $invoices,
                'invoicesAmountSum' => $invoicesAmountSum,
                'invoicesAmountPaidSum' => $invoicesAmountPaidSum,
                'customers' => $customers,
                'filters' => [
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'customer_id' => $request->customer_id,
                ],
            ];

            // dd($data);
            return view('customer-sales-history', $data);
        } catch (\Exception $e) {
            Log::error('Error retrieving customer sales history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error retrieving sales history: ' . $e->getMessage());
        }
    }

    /**
     * Download customer sales history as CSV
     *
     * CSV Generation Workflow:
     * 1. Validate optional filter parameters (from_date, to_date, customer_id)
     * 2. Build query with same filtering logic as index method
     * 3. Retrieve all matching invoices (no pagination for export)
     * 4. Generate CSV file with headers and data rows
     * 5. Calculate amounts (total, paid, due) for each invoice
     * 6. Log activity for audit trail
     * 7. Return CSV file as download and delete temp file after sending
     *
     * All filters are optional - if none provided, exports all records
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function customerSalesHistoryExcel(Request $request)
    {
        // Validate optional filters
        $validator = Validator::make($request->all(), [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'customer_id' => 'nullable|integer|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Build query with same filtering logic as index method
            $query = Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments']);

            // Apply date range filter if from_date is provided
            if ($request->filled('from_date')) {
                $query->where('invoice_date', '>=', $request->from_date);
            }

            // Apply date range filter if to_date is provided
            if ($request->filled('to_date')) {
                $query->where('invoice_date', '<=', $request->to_date);
            }

            // Apply customer filter if customer_id is provided
            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            // Get all matching invoices (no pagination for export)
            $invoices = $query->latest('invoice_date')->get();

            if ($invoices->isEmpty()) {
                return redirect()->back()->with('error', 'No sales records found for the selected criteria.');
            }

            // Create temporary CSV file
            $tempCsvPath = storage_path('app/customer_sales_history_' . Str::random(8) . '.csv');
            $file = fopen($tempCsvPath, 'w');

            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add header row
            fputcsv($file, [
                'Invoice No',
                'Customer Name',
                'Ordered Date',
                'Total Amount',
                'Paid',
                'Due',
                'Appointment Date',
                'POD',
                'LR',
                'DN',
                'GRN',
                'Payment Status',
            ]);

            // Add data rows
            foreach ($invoices as $invoice) {
                $totalAmount = (float)($invoice->total_amount ?? 0);
                $paidAmount = (float)($invoice->payments?->sum('amount') ?? 0);
                $dueAmount = max(0, $totalAmount - $paidAmount);

                fputcsv($file, [
                    $invoice->invoice_number ?? 'N/A',
                    $invoice->customer?->client_name ?? 'N/A',
                    $invoice->invoice_date ? $invoice->invoice_date->format('d-m-Y') : 'N/A',
                    number_format($totalAmount, 2, '.', ''),
                    number_format($paidAmount, 2, '.', ''),
                    number_format($dueAmount, 2, '.', ''),
                    $invoice->appointment?->appointment_date ?? 'N/A',
                    $invoice->appointment?->pod ? 'Yes' : 'No',
                    $invoice->appointment?->lr ? 'Yes' : 'No',
                    $invoice->dns?->dn_amount ? 'Yes' : 'No',
                    $invoice->appointment?->grn ? 'Yes' : 'No',
                    isset($invoice->payments?->first()->payment_status) != null && $invoice->payments?->first()->payment_status == 'completed'  ? 'paid' : 'not paid',
                ]);
            }

            fclose($file);

            // Log activity for audit trail
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'customer_id' => $request->customer_id,
                    'records' => $invoices->count(),
                ])
                ->event('csv_report_generated')
                ->log('Customer sales history CSV report generated');

            DB::commit();

            // Generate filename with current date
            $fileName = 'Customer-Sales-History-' . date('d-m-Y') . '.csv';

            // Return CSV file as download and delete after sending
            return response()->download($tempCsvPath, $fileName, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating customer sales CSV report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating sales report: ' . $e->getMessage());
        }
    }
}
