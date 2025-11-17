<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SalesOrder;
use App\Models\TempOrder;
use App\Models\VendorPI;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

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
     * @return \Illuminate\View\View
     */
    public function vendorPurchaseHistory(Request $request)
    {
        try {
            // Build base query with all relations
            $query = PurchaseOrder::with([
                'vendor',
                'warehouse',
                'purchaseOrderProducts.product',
                'vendorPI.payments',
                'purchaseInvoices',
                'purchaseGrn',
            ]);

            // Filter only completed vendorPI for consistency with dropdowns
            $query->whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            });

            // Date range filter - filter by purchase order created date
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Purchase order filter - filter by purchase order ID
            if ($request->filled('purchase_order_no')) {
                $po = $request->purchase_order_no;
                $query->whereIn('id', (array) $po);
            }

            // Vendor filter - filter by vendor code
            if ($request->filled('vendor_code')) {
                $vc = $request->vendor_code;
                $query->whereHas('vendor', function ($v) use ($vc) {
                    $v->whereIn('vendor_code', (array) $vc);
                });
            }

            // SKU filter - filter by product SKU
            if ($request->filled('sku')) {
                $sku = $request->sku;
                $query->whereHas('purchaseOrderProducts', function ($p) use ($sku) {
                    $p->whereIn('sku', (array) $sku);
                });
            }

            // Clone for stats before pagination
            $statsQuery = clone $query;

            // Get paginated purchase orders (15 per page)
            $vendorPIProducts = $query->latest('id')->paginate(15)->appends($request->all());

            // Calculate statistics based on filtered results
            $purchaseOrdersTotal = $statsQuery->sum('total_amount');
            $purchaseOrdersTotalQuantity = $statsQuery->withCount('purchaseOrderProducts')->get()->sum('purchase_order_products_count');

            // Count total orders
            $totalOrders = $statsQuery->count();

            // Get unique purchase order IDs from all completed purchase orders for dropdown
            $purchaseOrderNumbers = PurchaseOrder::whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->distinct('id')->orderBy('id', 'desc')->pluck('id');

            // Get unique vendor codes from all completed purchase orders for dropdown
            $purchaseOrdersVendors = PurchaseOrder::whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->with('vendor')->get()->pluck('vendor.vendor_code')->unique()->filter()->sort()->values();

            // Get unique SKUs from all completed purchase orders for dropdown
            $purchaseOrdersSKUs = PurchaseOrderProduct::whereHas('purchaseOrder.vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->distinct('sku')->orderBy('sku')->pluck('sku');

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
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function vendorPurchaseHistoryExcel(Request $request)
    {
        DB::beginTransaction();
        try {
            // Build the base query from PurchaseOrder with all necessary relationships
            $query = PurchaseOrder::with([
                'vendor',
                'warehouse',
                'purchaseOrderProducts.product',
                'vendorPI.payments',
                'purchaseInvoices',
                'purchaseGrn',
            ]);

            // Filter only completed orders
            $query->whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            });

            // Apply date range filter if from_date is provided
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            // Apply date range filter if to_date is provided
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Apply purchase order filter if purchase_order_no is provided (supports single or multiple)
            if ($request->filled('purchase_order_no')) {
                $po = $request->input('purchase_order_no');
                if (is_array($po)) {
                    $query->whereIn('id', $po);
                } else {
                    $query->where('id', $po);
                }
            }

            // Apply vendor filter if vendor_code is provided (supports single or multiple)
            if ($request->filled('vendor_code')) {
                $vc = $request->input('vendor_code');
                if (is_array($vc)) {
                    $query->whereHas('vendor', function ($q) use ($vc) {
                        $q->whereIn('vendor_code', $vc);
                    });
                } else {
                    $query->whereHas('vendor', function ($q) use ($vc) {
                        $q->where('vendor_code', $vc);
                    });
                }
            }

            // Apply sku filter if sku is provided (supports single or multiple)
            if ($request->filled('sku')) {
                $sku = $request->input('sku');
                if (is_array($sku)) {
                    $query->whereHas('purchaseOrderProducts', function ($q) use ($sku) {
                        $q->whereIn('sku', $sku);
                    });
                } else {
                    $query->whereHas('purchaseOrderProducts', function ($q) use ($sku) {
                        $q->where('sku', $sku);
                    });
                }
            }

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

            // Add header row - matching all table columns
            fputcsv($file, [
                'Purchase Order No',
                'Purchase Order Date',
                'Vendor Name',
                'GSTIN',
                'Item Name',
                'SKU',
                'HSN/SAC',
                'Quantity',
                'UoM',
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
                'Invoice Amount',
                'Invoice Due',
                'Invoice Paid',
                'Invoice Uploaded',
                'GRN Uploaded',
                'Due Date',
                'Shipping Charges',
                'PO Created',
                'PI Received',
                'Approved',
                'Warehouse',
            ]);

            // Add data rows - loop through purchase orders and their products
            foreach ($vendorPIProducts as $purchaseOrder) {
                $vendor = $purchaseOrder->vendor ?? null;
                $purchaseInvoice = $purchaseOrder->purchaseInvoices->first() ?? null;
                $vendorPI = $purchaseOrder->vendorPI->first() ?? null;
                $payment = $vendorPI ? $vendorPI->payments->first() : null;
                $purchaseGrn = $purchaseOrder->purchaseGrn ?? null;

                // Loop through each product in the purchase order
                foreach ($purchaseOrder->purchaseOrderProducts as $product) {
                    $productDetails = $product->product ?? null;
                    $gstRate = floatval($productDetails->gst ?? 0);
                    $unitCost = floatval($product->product->mrp ?? 0);
                    $quantity = floatval($product->ordered_quantity ?? 0);
                    $taxableValue = $unitCost * $quantity;
                    $gstAmount = ($taxableValue * $gstRate) / 100;
                    $cessRate = 0; // Cess not in purchase_order_products table
                    $cessAmount = 0;

                    // Calculate CGST/SGST/IGST
                    $cgst = $gstRate / 2;
                    $sgst = $gstRate / 2;
                    $igst = $gstRate; // Simplified - should check if interstate

                    // Determine payment status
                    $paymentStatus = 'Pending';
                    if ($vendorPI) {
                        $statusLabels = [
                            'paid' => 'Paid',
                            'partial_paid' => 'Partial Paid',
                            'pending' => 'Pending',
                        ];
                        $paymentStatus = $statusLabels[$vendorPI->payment_status] ?? 'Pending';
                    }

                    // Determine approved status
                    $approvedStatus = 'N/A';
                    if ($vendorPI) {
                        $allocationStatusLabels = [
                            'pending' => 'Pending',
                            'approve' => 'Approval Pending',
                            'reject' => 'Rejected',
                            'completed' => 'Completed',
                        ];
                        $approvedStatus = $allocationStatusLabels[$vendorPI->status] ?? 'N/A';
                    }

                    fputcsv($file, [
                        $purchaseOrder->id ?? 'N/A',
                        $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A',
                        $vendor->client_name ?? 'N/A',
                        $vendor->gst_number ?? 'N/A',
                        $productDetails->brand_title ?? 'N/A',
                        $product->sku ?? 'N/A',
                        $productDetails->hsn ?? 'N/A',
                        $product->ordered_quantity ?? 0,
                        'PCS',
                        number_format($product->product->mrp ?? 0, 2),
                        number_format($product->discount_per_unit ?? 0, 2),
                        number_format($taxableValue, 2),
                        $gstRate . '%',
                        $cgst . '%',
                        $sgst . '%',
                        $igst . '%',
                        number_format($gstAmount, 2),
                        $cessRate . '%',
                        number_format($cessAmount, 2),
                        $vendor->pan_number ?? 'N/A',
                        $paymentStatus,
                        $payment->payment_method ?? 'N/A',
                        $purchaseInvoice->invoice_no ?? 'N/A',
                        $purchaseInvoice?->created_at ? $purchaseInvoice->created_at->format('d-m-Y') : 'N/A',
                        $vendorPI->total_amount ?? 'N/A',
                        $vendorPI->total_due_amount ?? 'N/A',
                        $vendorPI->total_paid_amount ?? 'N/A',
                        $purchaseInvoice ? 'Yes' : 'No',
                        $purchaseGrn ? 'Yes' : 'No',
                        $vendorPI && $vendorPI->updated_at ? $vendorPI->updated_at->addMonth()->format('d-m-Y') : 'N/A',
                        'N/A', // Shipping Charges
                        $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A',
                        $vendorPI && $vendorPI->updated_at ? $vendorPI->updated_at->format('d-m-Y') : 'N/A',
                        $approvedStatus,
                        $vendorPI->warehouse->name ?? 'N/A',
                    ]);
                }
            }

            fclose($file);

            // Log activity for audit trail
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'vendor_code' => $request->vendor_code,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'sku' => $request->sku,
                    'records' => $vendorPIProducts->count(),
                ])
                ->event('csv_report_generated')
                ->log('Vendor purchase history CSV report generated');

            DB::commit();

            // Generate filename with vendor code(s) or date
            $vendorPart = '';
            if ($request->filled('vendor_code')) {
                $vc = $request->input('vendor_code');
                if (is_array($vc)) {
                    $vendorPart = implode('-', array_map(function ($v) {
                        return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $v));
                    }, $vc));
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
     * Display customer sales history with detailed invoice information
     *
     * Shows individual invoices with all required columns:
     * Customer Group Name, Customer Name, Customer GSTIN, Invoice No, Creator Name,
     * Customer Phone No, Customer Email, Customer City, Customer State, PO No, PO Date,
     * Appointment Date, Due Date, Currency, Amount, Tax, Total, Status, Amount Paid,
     * Balance, Date Of Payment, Payment Mode, CGST, SGST, IGST, Cess
     *
     * @return \Illuminate\View\View
     */
    public function customerSalesHistory(Request $request)
    {
        try { // Base Query with all relationships
            $query = SalesOrder::with([
                'warehouse',
                'customer.groupInfo.customerGroup',
                'customerGroup',
                'invoices.payments',
                'invoices.details',
                'invoices.appointment',
                'invoices.dns',
                'orderedProducts',
                'orderedProducts.tempOrder',
                'orderedProducts.customer',
                'orderedProducts.invoiceDetails.invoice.appointment',
                'orderedProducts.warehouseAllocations.warehouse',
            ]);

            // Date Filters
            if ($request->filled('from_date')) {
                $query->where('order_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->where('order_date', '<=', $request->to_date);
            }

            // Warehouse Filter - filter by warehouse allocations, not sales_order warehouse_id
            if ($request->filled('warehouse_id')) {
                $warehouseIds = (array) $request->warehouse_id;

                $query->where(function ($q) use ($warehouseIds) {
                    $q->whereHas('orderedProducts.warehouseAllocations', function ($wa) use ($warehouseIds) {
                        $wa->whereIn('warehouse_id', $warehouseIds);
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Customer Filter - filter by product-level customer (orderedProducts.customer)
            if ($request->filled('customer_id')) {
                $customerIds = (array) $request->customer_id;

                $query->where(function ($q) use ($customerIds) {
                    $q->whereHas('orderedProducts.customer', function ($c) use ($customerIds) {
                        $c->whereIn('id', $customerIds);
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Region Filter - filter by product-level customer's shipping/billing state
            if ($request->filled('region')) {
                $regions = (array) $request->region;

                $query->where(function ($q) use ($regions) {
                    $q->whereHas('orderedProducts.customer', function ($c) use ($regions) {
                        $c->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Payment Status Filter
            if ($request->filled('payment_status')) {
                $statuses = (array) $request->payment_status;

                $query->where(function ($q) use ($statuses) {
                    $q->whereHas('invoices', function ($inv) use ($statuses) {
                        foreach ($statuses as $status) {
                            if ($status === 'paid') {
                                $inv->orWhereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) <= 0');
                            }
                            if ($status === 'unpaid') {
                                $inv->orWhereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) = total_amount');
                            }
                            if ($status === 'partial') {
                                $inv->orWhere(function ($p) {
                                    $p->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) > 0')
                                        ->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) < total_amount');
                                });
                            }
                        }
                    })
                        ->orDoesntHave('invoices'); // keep when no invoice
                });
            }

            // Customer Group Filter
            if ($request->filled('customer_type')) {
                $query->whereIn('customer_group_id', (array) $request->customer_type);
            }

            // Invoice Number Filter
            if ($request->filled('invoice_no')) {
                $invoiceNos = (array) $request->invoice_no;

                $query->where(function ($q) use ($invoiceNos) {
                    $q->whereHas('invoices', function ($inv) use ($invoiceNos) {
                        $inv->whereIn('invoice_number', $invoiceNos);
                    })
                        ->orDoesntHave('invoices');
                });
            }

            // PO Number Filter
            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;

                $query->where(function ($q) use ($poNos) {
                    $q->whereHas('orderedProducts.tempOrder', function ($tmp) use ($poNos) {
                        $tmp->whereIn('po_number', $poNos);
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Appointment Date Filter
            if ($request->filled('appointment_date')) {
                $apptDates = (array) $request->appointment_date;

                // Convert dates from d-m-Y to Y-m-d format for database comparison
                $convertedDates = array_map(function ($date) {
                    try {
                        return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $date; // Return as-is if conversion fails
                    }
                }, $apptDates);

                $query->where(function ($q) use ($convertedDates) {
                    $q->whereHas('invoices.appointment', function ($app) use ($convertedDates) {
                        $app->whereIn('appointment_date', $convertedDates);
                    })
                        ->orDoesntHave('invoices'); // keep even if no invoice/appointment
                });
            }

            // Final result
            $salesOrders = $query->latest('order_date')->get();
            // dd($salesOrders[0]->orderedProducts->first()->invoiceDetails);

            // Stats
            $statsQuery = clone $query;
            $allSalesOrders = $statsQuery->get();

            $totalRevenue = $allSalesOrders->sum('total_amount');
            $totalPaid = $allSalesOrders->sum(function ($salesOrder) {
                return $salesOrder->invoices->sum(function ($invoice) {
                    return $invoice->payments->sum('amount');
                });
            });
            $totalPendingPayments = $totalRevenue - $totalPaid;

            // Get filter dropdown data
            $customers = Customer::select('id', 'client_name')
                ->whereHas('orders')
                ->orderBy('client_name')
                ->get()
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->client_name ?? 'N/A',
                    ];
                });

            $warehouses = Warehouse::active()->select('id', 'name')->get();

            $regions = Customer::distinct()
                ->whereNotNull('billing_state')
                ->pluck('billing_state')
                ->merge(Customer::distinct()->whereNotNull('shipping_state')->pluck('shipping_state'))
                ->unique()
                ->sort()
                ->values();

            $customerGroups = \App\Models\CustomerGroup::active()->select('id', 'name')->get();

            // Get unique invoice numbers for filter dropdown (from sales orders' invoices)
            $invoiceNumbers = Invoice::distinct('invoice_number')
                ->whereNotNull('invoice_number')
                ->whereNotNull('sales_order_id')
                ->pluck('invoice_number')
                ->sort()
                ->values();

            // Get unique PO numbers from sales orders
            $poNumbers = TempOrder::whereNotNull('po_number')
                ->whereHas('orderedProduct.salesOrder')
                ->distinct()
                ->pluck('po_number')
                ->sort()
                ->values();

            // Get unique appointment dates (from invoices related to sales orders)
            $appointmentDates = \App\Models\Appointment::distinct('appointment_date')
                ->whereNotNull('appointment_date')
                ->whereHas('invoice', function ($q) {
                    $q->whereNotNull('sales_order_id');
                })
                ->pluck('appointment_date')
                ->map(function ($date) {
                    return $date->format('d-m-Y');
                })
                ->sort()
                ->values();

            // dd($salesOrders);

            $data = [
                'title' => 'Customer Sales Summary',
                'warehouses' => $warehouses,
                'invoices' => $salesOrders, // Keep variable name for view compatibility
                'totalRevenue' => $totalRevenue,
                'totalPendingPayments' => $totalPendingPayments,
                'customers' => $customers,
                'regions' => $regions,
                'customerGroups' => $customerGroups,
                'invoiceNumbers' => $invoiceNumbers,
                'poNumbers' => $poNumbers,
                'appointmentDates' => $appointmentDates,
                'filters' => [
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'customer_id' => $request->customer_id,
                    'warehouse_id' => $request->warehouse_id,
                    'region' => $request->region,
                    'payment_status' => $request->payment_status,
                    'customer_type' => $request->customer_type,
                    'invoice_no' => $request->input('invoice_no', []),
                    'po_no' => $request->input('po_no', []),
                    'appointment_date' => $request->input('appointment_date', []),
                ],
            ];

            return view('customer-sales-history', $data);
        } catch (\Exception $e) {
            Log::error('Error retrieving customer sales history: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error retrieving sales history: ' . $e->getMessage());
        }
    }

    /**
     * Download customer sales history as Excel
     *
     * Excel Generation Workflow:
     * 1. Validate optional filter parameters
     * 2. Build query with same filtering logic as index method
     * 3. Aggregate data by customer
     * 4. Generate Excel file with customer summary data
     * 5. Log activity for audit trail
     * 6. Return Excel file as download and delete temp file after sending
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function customerSalesHistoryExcel(Request $request)
    {
        // Validate optional filters
        $validator = Validator::make($request->all(), [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'customer_id' => 'nullable|array',
            'customer_id.*' => 'integer|exists:customers,id',
            'warehouse_id' => 'nullable|array',
            'warehouse_id.*' => 'integer|exists:warehouses,id',
            'region' => 'nullable|array',
            'region.*' => 'string',
            'payment_status' => 'nullable|array',
            'payment_status.*' => 'in:paid,unpaid,partial',
            'customer_type' => 'nullable|array',
            'customer_type.*' => 'integer|exists:customer_groups,id',
            'invoice_no' => 'nullable|array',
            'invoice_no.*' => 'string',
            'po_no' => 'nullable|array',
            'po_no.*' => 'string',
            'appointment_date' => 'nullable|array',
            'appointment_date.*' => 'date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Use the same query structure as customerSalesHistory method
            $query = SalesOrder::with([
                'warehouse',
                'customer.groupInfo.customerGroup',
                'customerGroup',
                'invoices.payments',
                'invoices.details',
                'invoices.appointment',
                'invoices.dns',
                'orderedProducts',
                'orderedProducts.tempOrder',
                'orderedProducts.customer',
                'orderedProducts.invoiceDetails.invoice.appointment',
                'orderedProducts.warehouseAllocations.warehouse',
            ]);

            // Apply filters (same as customerSalesHistory method)
            if ($request->filled('from_date')) {
                $query->where('order_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->where('order_date', '<=', $request->to_date);
            }

            // Apply warehouse filter - filter by warehouse allocations, not sales_order warehouse_id
            if ($request->filled('warehouse_id')) {
                $warehouseIds = (array) $request->warehouse_id;

                $query->where(function ($q) use ($warehouseIds) {
                    $q->whereHas('orderedProducts.warehouseAllocations', function ($wa) use ($warehouseIds) {
                        $wa->whereIn('warehouse_id', $warehouseIds);
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Apply customer filter - filter by product-level customer (orderedProducts.customer)
            if ($request->filled('customer_id')) {
                $customerIds = (array) $request->customer_id;

                $query->where(function ($q) use ($customerIds) {
                    $q->whereHas('orderedProducts.customer', function ($c) use ($customerIds) {
                        $c->whereIn('id', $customerIds);
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Apply region filter - filter by product-level customer's shipping/billing state
            if ($request->filled('region')) {
                $regions = (array) $request->region;

                $query->where(function ($q) use ($regions) {
                    $q->whereHas('orderedProducts.customer', function ($c) use ($regions) {
                        $c->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Apply payment status filter
            if ($request->filled('payment_status')) {
                $statuses = (array) $request->payment_status;

                $query->where(function ($q) use ($statuses) {
                    $q->whereHas('invoices', function ($inv) use ($statuses) {
                        foreach ($statuses as $status) {
                            if ($status === 'paid') {
                                $inv->orWhereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) <= 0');
                            }
                            if ($status === 'unpaid') {
                                $inv->orWhereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) = total_amount');
                            }
                            if ($status === 'partial') {
                                $inv->orWhere(function ($p) {
                                    $p->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) > 0')
                                        ->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id),0)) < total_amount');
                                });
                            }
                        }
                    })
                        ->orDoesntHave('invoices'); // keep when no invoice
                });
            }

            // Apply customer type/group filter
            if ($request->filled('customer_type')) {
                $query->whereIn('customer_group_id', (array) $request->customer_type);
            }

            // Apply invoice no filter
            if ($request->filled('invoice_no')) {
                $invoiceNos = (array) $request->invoice_no;

                $query->where(function ($q) use ($invoiceNos) {
                    $q->whereHas('invoices', function ($inv) use ($invoiceNos) {
                        $inv->whereIn('invoice_number', $invoiceNos);
                    })
                        ->orDoesntHave('invoices');
                });
            }

            // Apply PO no filter
            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;

                $query->where(function ($q) use ($poNos) {
                    $q->whereHas('orderedProducts.tempOrder', function ($tmp) use ($poNos) {
                        $tmp->whereIn('po_number', $poNos);
                    })
                        ->orDoesntHave('orderedProducts'); // keep when no orderedProducts
                });
            }

            // Apply appointment date filter
            if ($request->filled('appointment_date')) {
                $apptDates = (array) $request->appointment_date;

                // Convert dates from d-m-Y to Y-m-d format for database comparison
                $convertedDates = array_map(function ($date) {
                    try {
                        return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $date; // Return as-is if conversion fails
                    }
                }, $apptDates);

                $query->where(function ($q) use ($convertedDates) {
                    $q->whereHas('invoices.appointment', function ($app) use ($convertedDates) {
                        $app->whereIn('appointment_date', $convertedDates);
                    })
                        ->orDoesntHave('invoices'); // keep even if no invoice/appointment
                });
            }

            // Get all sales orders
            $salesOrders = $query->latest('order_date')->get();

            if ($salesOrders->isEmpty()) {
                return redirect()->back()->with('error', 'No customer sales records found for the selected criteria.');
            }

            // Define status mapping (same as view)
            $statuses = [
                'pending' => 'Pending',
                'blocked' => 'Blocked',
                'shipped' => 'Shipped',
                'completed' => 'Complete',
                'ready_to_ship' => 'Ready To Ship',
                'ready_to_package' => 'Ready To Package',
            ];

            // Process data exactly like the view: salesOrders -> orderedProducts -> warehouseAllocations
            $exportData = collect();

            foreach ($salesOrders as $salesOrder) {
                $customerGroup = $salesOrder->customerGroup;

                foreach ($salesOrder->orderedProducts as $product) {
                    $customer = $product->customer;

                    if ($product->warehouseAllocations->count() > 0) {
                        // Loop through warehouse allocations (same as view)
                        foreach ($product->warehouseAllocations as $allocation) {
                            // Get invoice number for this warehouse
                            $invoiceNumber = 'N/A';
                            if ($salesOrder->invoices->count() > 0) {
                                foreach ($salesOrder->invoices as $invoice) {
                                    if ($invoice->warehouse_id == $allocation->warehouse_id) {
                                        $invoiceNumber = $invoice->invoice_number ?? 'N/A';
                                        break;
                                    }
                                }
                            }

                            // Get invoice details
                            $invoiceDetail = $product->invoiceDetails->first();
                            $invoice = $invoiceDetail?->invoice;
                            $appointment = $invoice?->appointment;
                            $dns = $invoice?->dns;
                            $payment = $invoice?->payments?->first();

                            // Calculate subtotal and total for this allocation
                            $subtotal = $allocation->final_dispatched_quantity * $product->price;
                            $gstRate = $product->tempOrder->gst ?? 0;
                            $total = $subtotal * (1 + $gstRate / 100);

                            $exportData->push([
                                'Customer Group Name' => $customerGroup->name ?? 'N/A',
                                'Warehouse Name' => $allocation->warehouse->name ?? 'N/A',
                                'Customer Name' => $customer->client_name ?? 'N/A',
                                'Invoice No' => $invoiceNumber,
                                'Customer Phone No' => $customer->contact_no ?? 'N/A',
                                'Customer Email' => $customer->email ?? 'N/A',
                                'Customer City' => $customer->shipping_city ?? 'N/A',
                                'Customer State' => $customer->shipping_state ?? 'N/A',
                                'PO No' => $product->tempOrder->po_number ?? 'N/A',
                                'PO Date' => $product->tempOrder->po_date ?? 'N/A',
                                'Appointment Date' => $appointment?->appointment_date?->format('d-m-Y') ?? 'N/A',
                                'Due Date' => $appointment?->appointment_date?->addMonth()->format('d-m-Y') ?? 'N/A',
                                'POD' => $appointment?->pod ? 'Yes' : 'No',
                                'GRN' => $appointment?->grn ? 'Yes' : 'No',
                                'DN' => $dns?->dn_amount ?? 0,
                                'DN Receipt' => $dns?->dn_receipt ? 'Yes' : 'No',
                                'LR' => $salesOrder->appointment?->lr ? 'Yes' : 'No',
                                'Currency' => $salesOrder->invoices->first()->currency ?? 'INR',
                                'SKU' => $product->tempOrder->sku ?? 'N/A',
                                'HSN' => $product->tempOrder->hsn ?? 'N/A',
                                'Ordered Quantity' => $product->ordered_quantity ?? 'N/A',
                                'Dispatched Quantity' => $allocation->final_dispatched_quantity ?? 'N/A',
                                'Box Count' => $allocation->box_count ?? 'N/A',
                                'Weight' => $allocation->weight ?? 'N/A',
                                'Unit Price' => $product->price ?? 'N/A',
                                'Subtotal' => number_format($subtotal, 2, '.', ''),
                                'GST' => $gstRate,
                                'Total' => number_format($total, 2, '.', ''),
                                'Status' => $statuses[$salesOrder->status] ?? 'N/A',
                                'Total Amount' => $invoice?->total_amount ?? 'N/A',
                                'Amount Paid' => $invoice?->paid_amount ?? 'N/A',
                                'Balance' => $invoice?->balance_due ?? 'N/A',
                                'Date Of Payment' => $payment?->created_at?->format('d-m-Y') ?? 'N/A',
                                'Payment Mode' => $payment?->payment_method ?? 'N/A',
                                'CGST' => $gstRate / 2,
                                'SGST' => $gstRate / 2,
                                'IGST' => $gstRate,
                                'Cess' => $invoice?->cess ?? 'N/A',
                            ]);
                        }
                    }
                }
            }

            // Create temporary Excel file
            $tempXlsxPath = storage_path('app/customer_sales_history_' . Str::random(8) . '.xlsx');

            // Create writer
            $writer = \Spatie\SimpleExcel\SimpleExcelWriter::create($tempXlsxPath);

            // Add header row (matching view table columns exactly)
            $writer->addRow([
                'Customer Group Name',
                'Warehouse Name',
                'Customer Name',
                'Invoice No',
                'Customer Phone No',
                'Customer Email',
                'Customer City',
                'Customer State',
                'PO No',
                'PO Date',
                'Appointment Date',
                'Due Date',
                'POD',
                'GRN',
                'DN',
                'DN Receipt',
                'LR',
                'Currency',
                'SKU',
                'HSN',
                'Ordered Quantity',
                'Dispatched Quantity',
                'Box Count',
                'Weight',
                'Unit Price',
                'Subtotal',
                'GST',
                'Total',
                'Status',
                'Total Amount',
                'Amount Paid',
                'Balance',
                'Date Of Payment',
                'Payment Mode',
                'CGST',
                'SGST',
                'IGST',
                'Cess',
            ]);

            // Add data rows
            foreach ($exportData as $row) {
                $writer->addRow($row);
            }

            $writer->close();

            // Log activity for audit trail
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'customer_id' => $request->customer_id,
                    'warehouse_id' => $request->warehouse_id,
                    'region' => $request->region,
                    'payment_status' => $request->payment_status,
                    'customer_type' => $request->customer_type,
                    'invoice_no' => $request->invoice_no,
                    'po_no' => $request->po_no,
                    'appointment_date' => $request->appointment_date,
                    'records' => $exportData->count(),
                ])
                ->event('excel_report_generated')
                ->log('Customer sales history Excel report generated');

            DB::commit();

            // Generate filename
            $fileName = 'Customer-Sales-Summary-' . date('d-m-Y') . '.xlsx';

            // Return Excel file as download and delete after sending
            return response()->download($tempXlsxPath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating customer sales Excel report: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error generating sales report: ' . $e->getMessage());
        }
    }

    /**
     * Download customer sales history as PDF
     *
     * PDF Generation Workflow:
     * 1. Validate optional filter parameters
     * 2. Build query with same filtering logic as index method
     * 3. Aggregate data by customer
     * 4. Generate PDF with formatted customer summary report
     * 5. Log activity for audit trail
     * 6. Return PDF file as download and delete temp file after sending
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function customerSalesHistoryPdf(Request $request)
    {
        // Validate optional filters (same as Excel)
        $validator = Validator::make($request->all(), [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'customer_id' => 'nullable|array',
            'customer_id.*' => 'integer|exists:customers,id',
            'region' => 'nullable|array',
            'region.*' => 'string',
            'payment_status' => 'nullable|array',
            'payment_status.*' => 'in:paid,unpaid,partial',
            'customer_type' => 'nullable|array',
            'customer_type.*' => 'integer|exists:customer_groups,id',
            'invoice_no' => 'nullable|array',
            'invoice_no.*' => 'string',
            'po_no' => 'nullable|array',
            'po_no.*' => 'string',
            'appointment_date' => 'nullable|array',
            'appointment_date.*' => 'date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Use the same aggregation logic as the index method
            $query = Invoice::with(['warehouse', 'customer.groupInfo.customerGroup', 'salesOrder', 'payments']);

            // Apply filters (same as index method)
            if ($request->filled('from_date')) {
                $query->where('invoice_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->where('invoice_date', '<=', $request->to_date);
            }

            // Apply customer filter (supports single or multiple)
            if ($request->filled('customer_id')) {
                $customerIds = $request->input('customer_id');
                if (is_array($customerIds)) {
                    $query->whereIn('customer_id', $customerIds);
                } else {
                    $query->where('customer_id', $customerIds);
                }
            }

            // Apply region filter (supports single or multiple)
            if ($request->filled('region')) {
                $regions = $request->input('region');
                if (is_array($regions)) {
                    $query->whereHas('customer', function ($q) use ($regions) {
                        $q->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    });
                } else {
                    $query->whereHas('customer', function ($q) use ($regions) {
                        $q->where('billing_state', $regions)
                            ->orWhere('shipping_state', $regions);
                    });
                }
            }

            // Apply payment status filter (supports single or multiple)
            if ($request->filled('payment_status')) {
                $paymentStatuses = $request->input('payment_status');
                if (is_array($paymentStatuses)) {
                    $query->where(function ($q) use ($paymentStatuses) {
                        foreach ($paymentStatuses as $status) {
                            if ($status === 'paid') {
                                $q->orWhereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) <= 0');
                            } elseif ($status === 'unpaid') {
                                $q->orWhereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) = total_amount');
                            } elseif ($status === 'partial') {
                                $q->orWhere(function ($subQ) {
                                    $subQ->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) > 0')
                                        ->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) < total_amount');
                                });
                            }
                        }
                    });
                } else {
                    if ($paymentStatuses === 'paid') {
                        $query->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) <= 0');
                    } elseif ($paymentStatuses === 'unpaid') {
                        $query->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) = total_amount');
                    } elseif ($paymentStatuses === 'partial') {
                        $query->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) > 0')
                            ->whereRaw('(total_amount - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)) < total_amount');
                    }
                }
            }

            // Apply customer type/group filter (supports single or multiple)
            if ($request->filled('customer_type')) {
                $customerTypes = $request->input('customer_type');
                if (is_array($customerTypes)) {
                    $query->whereHas('customer.groupInfo.customerGroup', function ($q) use ($customerTypes) {
                        $q->whereIn('id', $customerTypes);
                    });
                } else {
                    $query->whereHas('customer.groupInfo.customerGroup', function ($q) use ($customerTypes) {
                        $q->where('id', $customerTypes);
                    });
                }
            }

            // Apply invoice no filter (supports single or multiple)
            if ($request->filled('invoice_no')) {
                $invoiceNos = $request->input('invoice_no');
                if (is_array($invoiceNos)) {
                    $query->whereIn('invoice_number', $invoiceNos);
                } else {
                    $query->where('invoice_number', $invoiceNos);
                }
            }

            // Apply PO no filter (supports single or multiple)
            if ($request->filled('po_no')) {
                $poNos = $request->input('po_no');
                if (is_array($poNos)) {
                    $query->whereIn('po_number', $poNos);
                } else {
                    $query->where('po_number', $poNos);
                }
            }

            // Apply appointment date filter (supports single or multiple)
            if ($request->filled('appointment_date')) {
                $appointmentDates = $request->input('appointment_date');
                if (is_array($appointmentDates)) {
                    $query->whereHas('appointment', function ($q) use ($appointmentDates) {
                        $q->whereIn('appointment_date', $appointmentDates);
                    });
                } else {
                    $query->whereHas('appointment', function ($q) use ($appointmentDates) {
                        $q->where('appointment_date', $appointmentDates);
                    });
                }
            }

            // Get all invoices for aggregation
            $invoices = $query->get();

            if ($invoices->isEmpty()) {
                return redirect()->back()->with('error', 'No customer sales records found for the selected criteria.');
            }

            // Calculate overall statistics
            $totalRevenue = $invoices->sum('total_amount');
            $totalPendingPayments = $invoices->sum(function ($invoice) {
                $totalPaid = $invoice->payments->sum('amount');

                return $invoice->total_amount - $totalPaid;
            });

            // Get filter dropdown data
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

            // Process individual invoices for detailed PDF
            $invoiceData = $invoices->map(function ($invoice) {
                $customer = $invoice->customer;
                $customerGroup = $customer->groupInfo->customerGroup ?? null;
                $salesOrder = $invoice->salesOrder;
                $payments = $invoice->payments;
                $totalPaid = $payments->sum('amount');
                $balance = $invoice->total_amount - $totalPaid;
                $appointment = $invoice->appointment;
                $dns = $invoice->dns;

                // Calculate tax breakdown
                $cgst = 0;
                $sgst = 0;
                $igst = 0;
                $cess = 0;
                $taxAmount = 0;

                foreach ($invoice->details as $detail) {
                    $gstRate = $detail->tax ?? 0;
                    $taxAmount += $detail->tax ?? 0;
                    $cess += $detail->cess ?? 0;

                    // CGST/SGST split for intra-state, IGST for inter-state
                    if ($customer->billing_state === $customer->shipping_state) {
                        $cgst += $gstRate / 2;
                        $sgst += $gstRate / 2;
                    } else {
                        $igst += $gstRate;
                    }
                }

                // Get latest payment details
                $latestPayment = $payments->sortByDesc('created_at')->first();

                return [
                    'customer_group_name' => $customerGroup->name ?? 'N/A',
                    'customer_name' => $customer->client_name ?? 'N/A',
                    'customer_gstin' => $customer->gstin ?? 'N/A',
                    'invoice_no' => $invoice->invoice_number,
                    'creator_name' => 'System',
                    'customer_phone_no' => $customer->contact_no ?? 'N/A',
                    'customer_email' => $customer->email ?? 'N/A',
                    'customer_city' => $customer->billing_city ?? $customer->shipping_city ?? 'N/A',
                    'customer_state' => $customer->billing_state ?? $customer->shipping_state ?? 'N/A',
                    'po_no' => $invoice->po_number ?? $salesOrder->po_number ?? 'N/A',
                    'po_date' => $salesOrder ? $salesOrder->created_at->format('d-m-Y') : 'N/A',
                    'appointment_date' => $appointment ? $appointment->appointment_date->format('d-m-Y') : 'N/A',
                    'due_date' => 'N/A',
                    'currency' => 'INR',
                    'amount' => $invoice->subtotal ?? ($invoice->total_amount - $taxAmount),
                    'tax' => $taxAmount,
                    'total' => $invoice->total_amount,
                    'status' => $balance <= 0 ? 'Paid' : ($totalPaid > 0 ? 'Partial' : 'Unpaid'),
                    'amount_paid' => $totalPaid,
                    'balance' => $balance,
                    'date_of_payment' => $latestPayment ? $latestPayment->created_at->format('d-m-Y') : 'N/A',
                    'payment_mode' => $latestPayment ? $latestPayment->payment_method : 'N/A',
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'igst' => $igst,
                    'cess' => $cess,
                ];
            });

            // Prepare data for PDF
            $pdfData = [
                'title' => 'Customer Sales Summary Report',
                'generated_at' => now()->format('d-m-Y H:i:s'),
                'filters' => [
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'customer_id' => $request->customer_id,
                    'region' => $request->region,
                    'payment_status' => $request->payment_status,
                    'customer_type' => $request->customer_type,
                ],
                'summary' => [
                    'total_invoices' => $invoices->count(),
                    'total_revenue' => $totalRevenue,
                    'total_pending_payments' => $totalPendingPayments,
                    'total_customers' => $customers->count(),
                ],
                'invoices' => $invoiceData,
            ];

            // Generate PDF
            $pdf = \PDF::loadView('reports.customer-sales-summary-pdf', $pdfData);
            $pdf->setPaper('a4', 'landscape');

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'customer_id' => $request->customer_id,
                    'region' => $request->region,
                    'payment_status' => $request->payment_status,
                    'customer_type' => $request->customer_type,
                    'records' => $invoiceData->count(),
                ])
                ->event('pdf_report_generated')
                ->log('Customer sales history PDF report generated');

            // Generate filename
            $fileName = 'Customer-Sales-Summary-' . date('d-m-Y') . '.pdf';

            // Return PDF as download
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Error generating customer sales PDF report: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error generating PDF report: ' . $e->getMessage());
        }
    }
}
