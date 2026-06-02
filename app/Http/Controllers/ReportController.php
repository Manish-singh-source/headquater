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
                'vendorPI' => function ($q) {
                    $q->where('status', 'completed')->with(['payments', 'products', 'warehouse']);
                },
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

            // Purchase order filter - filter by purchase order number (same as table display)
            if ($request->filled('purchase_order_no')) {
                $po = $request->purchase_order_no;
                $query->whereIn('order_number', (array) $po);
            }

            // Vendor filter - dropdown submits vendor_code
            if ($request->filled('vendor_code')) {
                $vc = $request->vendor_code;
                $query->whereHas('vendor', function ($v) use ($vc) {
                    $v->whereIn('vendor_code', (array) $vc);
                });
            }

            // SKU filter - filter by product SKU
            // if ($request->filled('sku')) {
            //     $sku = $request->sku;
            //     $query->with(['purchaseOrderProducts' => function ($p) use ($sku) {
            //         $p->whereIn('sku', (array) $sku);
            //     }]);
            //     $query->whereHas('purchaseOrderProducts', function ($p) use ($sku) {
            //         $p->whereIn('sku', (array) $sku);
            //     });
            // }

            // Clone for stats before pagination
            $statsQuery = clone $query;

            // Get paginated purchase orders (15 per page)
            $vendorPIProducts = $query->latest('id')->paginate(15)->appends($request->all());

            // dd($vendorPIProducts);
            // Calculate statistics based on filtered results
            $purchaseOrdersTotal = $statsQuery->sum('total_amount');
            $purchaseOrdersTotalQuantity = $statsQuery->withCount('purchaseOrderProducts')->get()->sum('purchase_order_products_count');

            // Count total orders
            $totalOrders = $statsQuery->count();

            // Calculate taxable amount, invoice amount, paid amount, and due amount
            $allPurchaseOrders = $statsQuery->get();
            $totalTaxableAmount = 0;
            $totalInvoiceAmount = 0;
            $totalPaidInvoiceAmount = 0;
            $totalDueInvoiceAmount = 0;

            foreach ($allPurchaseOrders as $po) {
                $vendorPI = $po->vendorPI->first();
                if ($vendorPI) {
                    // Calculate taxable amount from products
                    foreach ($vendorPI->products as $product) {
                        $totalTaxableAmount += $product->purchase_rate * $product->quantity_received;
                    }

                    // Invoice amount, paid amount, and due amount
                    $totalInvoiceAmount += $vendorPI->total_amount ?? 0;
                    $totalPaidInvoiceAmount += $vendorPI->total_paid_amount ?? 0;
                    $totalDueInvoiceAmount += $vendorPI->total_due_amount ?? 0;
                }
            }

            // Get unique purchase order IDs from all completed purchase orders for dropdown
            $purchaseOrderNumbers = PurchaseOrder::whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->whereNotNull('order_number')->distinct()->orderBy('id', 'desc')->pluck('order_number');

            // Get unique vendors (non-null) from completed purchase orders for dropdown
            $purchaseOrdersVendors = PurchaseOrder::whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            })
                ->with('vendor')
                ->get()
                ->filter(function ($po) {
                    return !empty($po->vendor?->vendor_code);
                })
                ->unique(function ($po) {
                    return $po->vendor->vendor_code;
                })
                ->values();

            // dd($purchaseOrdersVendors);
            // ->pluck('vendor.vendor_code')->unique()->filter()->sort()->values();

            // Get unique SKUs from all completed purchase orders for dropdown
            $purchaseOrdersSKUs = PurchaseOrderProduct::whereHas('purchaseOrder.vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->distinct('sku')->orderBy('sku')->pluck('sku');

            $filters = $request->only(['from_date', 'to_date', 'purchase_order_no', 'vendor_code', 'sku']);

            // dd($vendorPIProducts);
            return view('vendor-purchase-invoices', compact(
                'vendorPIProducts',
                'purchaseOrdersTotal',
                'purchaseOrdersTotalQuantity',
                'totalOrders',
                'purchaseOrderNumbers',
                'purchaseOrdersVendors',
                'purchaseOrdersSKUs',
                'filters',
                'totalTaxableAmount',
                'totalInvoiceAmount',
                'totalPaidInvoiceAmount',
                'totalDueInvoiceAmount'
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
                'vendorPI' => function ($q) {
                    $q->where('status', 'completed')->with(['payments', 'products', 'warehouse']);
                },
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

            // Purchase order filter - filter by purchase order number (same as table display)
            if ($request->filled('purchase_order_no')) {
                $po = $request->purchase_order_no;
                $query->whereIn('order_number', (array) $po);
            }

            // Vendor filter - dropdown submits vendor_code
            if ($request->filled('vendor_code')) {
                $vc = $request->vendor_code;
                $query->whereHas('vendor', function ($v) use ($vc) {
                    $v->whereIn('vendor_code', (array) $vc);
                });
            }


            // Apply sku filter if sku is provided (supports single or multiple)
            // if ($request->filled('sku')) {
            //     $sku = $request->input('sku');
            //     if (is_array($sku)) {
            //         $query->whereHas('purchaseOrderProducts', function ($q) use ($sku) {
            //             $q->whereIn('sku', $sku);
            //         });
            //     } else {
            //         $query->whereHas('purchaseOrderProducts', function ($q) use ($sku) {
            //             $q->where('sku', $sku);
            //         });
            //     }
            // }
            // Clone for stats before pagination
            $statsQuery = clone $query;

            // Get all matching purchase orders for export
            $vendorPIProducts = $query->latest('id')->get();

            // dd($vendorPIProducts);
            // Calculate statistics based on filtered results
            $purchaseOrdersTotal = $statsQuery->sum('total_amount');
            $purchaseOrdersTotalQuantity = $statsQuery->withCount('purchaseOrderProducts')->get()->sum('purchase_order_products_count');

            // Count total orders
            $totalOrders = $statsQuery->count();

            // Get unique purchase order IDs from all completed purchase orders for dropdown
            $purchaseOrderNumbers = PurchaseOrder::whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->whereNotNull('order_number')->distinct()->orderBy('id', 'desc')->pluck('order_number');

            // Get unique vendor codes from all completed purchase orders for dropdown
            $purchaseOrdersVendors = PurchaseOrder::whereHas('vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->with('vendor')->get();

            // Get unique SKUs from all completed purchase orders for dropdown
            $purchaseOrdersSKUs = PurchaseOrderProduct::whereHas('purchaseOrder.vendorPI', function ($q) {
                $q->where('status', 'completed');
            })->distinct('sku')->orderBy('sku')->pluck('sku');

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
                'Invoice Ref',
                'Invoice Date',
                'Due Date',
                'PO Quantity',
                'PI Quantity',
                'Taxable Value',
                'GST',
                'CGST',
                'SGST',
                'IGST',
                'GST Amount',
                'Total Amount',
                'Cess',
                'Cess Amount',
                'Invoice Amount',
                'Invoice Paid',
                'Invoice Due',
                'Payment Status',
                'Payment Method',
                'Invioice Uploaded',
                'GRN Uploaded',
                'Warehouse',
            ]);

            // Add data rows - loop through purchase orders and their products

            foreach ($vendorPIProducts as $purchaseOrder) {
                $totalTaxableValue = 0;
                $totalGstAmount = 0;
                foreach ($purchaseOrder->vendorPI[0]->products as $product) {
                    $totalTaxableValue += $product->purchase_rate * $product->quantity_received;
                    $totalGstAmount +=
                        $product->purchase_rate * $product->quantity_received * ($product->gst / 100);
                }

                if ($purchaseOrder->purchaseInvoices->count() > 0) {
                    $invoiceAmount = $purchaseOrder->purchaseInvoices[0]->invoice_amount;
                    $invoiceDate = $purchaseOrder->purchaseInvoices[0]->created_at;
                    $invoiceNo = $purchaseOrder->purchaseInvoices[0]->invoice_no;
                    $invoiceFile = $purchaseOrder->purchaseInvoices[0]->invoice_file;
                } else {
                    $invoiceAmount = 'N/A';
                    $invoiceDate = 'N/A';
                    $invoiceNo = 'N/A';
                    $invoiceFile = 'N/A';
                }

                $vendorPI = $purchaseOrder->vendorPI[0] ?? null;
                $payment = $vendorPI?->payments[0] ?? null;

                fputcsv($file, [
                    $purchaseOrder->order_number ?? 'N/A',
                    $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A',
                    $purchaseOrder->vendor->client_name ?? 'N/A',
                    $invoiceNo ?? 'N/A',
                    $invoiceDate instanceof \Carbon\Carbon ? $invoiceDate->format('d-m-Y') : 'N/A',
                    $vendorPI?->updated_at ? $vendorPI->updated_at->copy()->addMonth()->format('d-m-Y') : 'N/A',
                    $purchaseOrder->purchaseOrderProducts->sum('ordered_quantity') ?? 'N/A',
                    $vendorPI?->products->sum('quantity_received') ?? 'N/A',
                    $totalTaxableValue ?? 'N/A',
                    $vendorPI?->products->sum('gst') ?? 'N/A',
                    ($vendorPI?->products->sum('gst') ?? 0) / 2,
                    ($vendorPI?->products->sum('gst') ?? 0) / 2,
                    $vendorPI?->products->sum('gst') ?? 'N/A',
                    $totalGstAmount ?? 'N/A',
                    $totalTaxableValue + $totalGstAmount,
                    0,
                    0,
                    $vendorPI?->total_amount ?? 'N/A',
                    $vendorPI?->total_paid_amount ?? 'N/A',
                    $vendorPI?->total_due_amount ?? 'N/A',
                    $payment ? ucwords(str_replace('_', ' ', $payment->payment_status)) : 'N/A',
                    $payment ? ucwords(str_replace('_', ' ', $payment->payment_method)) : 'N/A',
                    $invoiceFile ? 'Yes' : 'No',
                    $purchaseOrder->purchaseGrn ? 'Yes' : 'No',
                    $vendorPI?->warehouse->name ?? 'N/A',
                ]);
            }

            fclose($file);

            // Log activity for audit trail
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'vendor_code' => $request->vendor_code,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
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
                ? 'Vendor-Purchase-Invoices-' . $vendorPart . '-' . date('d-m-Y') . '.csv'
                : 'Vendor-Purchase-Invoices-' . date('d-m-Y') . '.csv';

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
    public function vendorPurchaseHistory1(Request $request)
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
                'vendorPI',
            ]);

            // Filter only completed vendorPI for consistency with dropdowns
            // $query->whereHas('vendorPI', function ($q) {
            //     $q->where('status', 'completed');
            // });

            // Date range filter - filter by purchase order created date
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Purchase order filter - filter by purchase order number (same as table display)
            if ($request->filled('purchase_order_no')) {
                $po = $request->purchase_order_no;
                $query->whereIn('order_number', (array) $po);
            }

            // Vendor filter - filter by vendor name (same as table display)
            if ($request->filled('vendor_code')) {
                $vc = $request->vendor_code;
                $query->whereHas('vendor', function ($v) use ($vc) {
                    $v->whereIn('client_name', (array) $vc);
                });
            }

            // SKU filter - filter by product SKU
            if ($request->filled('sku')) {
                $sku = $request->sku;
                $query->with(['purchaseOrderProducts' => function ($p) use ($sku) {
                    $p->whereIn('sku', (array) $sku);
                }]);
                $query->whereHas('purchaseOrderProducts', function ($p) use ($sku) {
                    $p->whereIn('sku', (array) $sku);
                });
            }

            // Clone for stats before pagination
            $statsQuery = clone $query;

            // Get paginated purchase orders (15 per page)
            $vendorPIProducts = $query->latest('id')->paginate(15)->appends($request->all());
            // dd($vendorPIProducts);
            // Calculate statistics based on filtered results
            $purchaseOrdersTotal = $statsQuery->sum('total_amount');
            $purchaseOrdersTotalQuantity = $statsQuery->withCount('purchaseOrderProducts')->get()->sum('purchase_order_products_count');

            // Count total orders
            $totalOrders = $statsQuery->count();

            // Calculate SKU-level statistics
            $allPurchaseOrders = $statsQuery->get();
            $totalSKU = 0;
            $totalPOQuantity = 0;
            $totalPIQuantity = 0;
            $totalPIReceivedQuantity = 0;
            $totalTaxableAmount = 0;
            $skuSet = [];

            foreach ($allPurchaseOrders as $po) {
                // Count unique SKUs
                foreach ($po->purchaseOrderProducts as $product) {
                    if (!in_array($product->sku, $skuSet)) {
                        $skuSet[] = $product->sku;
                    }
                    $totalPOQuantity += $product->ordered_quantity ?? 0;
                }

                // Calculate PI quantity and taxable amount from vendorPI products
                $vendorPI = $po->vendorPI->first();
                if ($vendorPI) {
                    foreach ($vendorPI->products as $product) {
                        $totalPIQuantity += $product->available_quantity ?? 0;
                        $totalPIReceivedQuantity += $product->quantity_received ?? 0;
                        $totalTaxableAmount += ($product->mrp ?? 0) * ($product->quantity_received ?? 0);
                    }
                }
            }

            $totalSKU = count($skuSet);

            // Get dropdown values from all records (same scope expectation as table data)
            $purchaseOrderNumbers = PurchaseOrder::query()
                ->whereNotNull('order_number')
                ->distinct('order_number')
                ->orderBy('order_number', 'desc')
                ->pluck('order_number');

            $purchaseOrdersVendors = PurchaseOrder::with('vendor')
                ->get()
                ->pluck('vendor.client_name')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            $purchaseOrdersSKUs = PurchaseOrderProduct::query()
                ->whereNotNull('sku')
                ->distinct('sku')
                ->orderBy('sku')
                ->pluck('sku');

            $filters = $request->only(['from_date', 'to_date', 'purchase_order_no', 'vendor_code', 'sku']);

            return view('vendor-purchase-sku', compact(
                'vendorPIProducts',
                'purchaseOrdersTotal',
                'purchaseOrdersTotalQuantity',
                'totalOrders',
                'purchaseOrderNumbers',
                'purchaseOrdersVendors',
                'purchaseOrdersSKUs',
                'filters',
                'totalSKU',
                'totalPOQuantity',
                'totalPIQuantity',
                'totalPIReceivedQuantity',
                'totalTaxableAmount'
            ));
        } catch (\Exception $e) {
            Log::error('Error retrieving vendor purchase history: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error retrieving vendor purchase history: ' . $e->getMessage());
        }
    }

    public function vendorPurchaseHistoryExcel1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'purchase_order_no' => 'nullable|array',
            'purchase_order_no.*' => 'string|exists:purchase_orders,order_number',
            'vendor_code' => 'nullable|array',
            'vendor_code.*' => 'string',
            'sku' => 'nullable|array',
            'sku.*' => 'string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Build base query with all relations
            $query = PurchaseOrder::with([
                'vendor',
                'warehouse',
                'purchaseOrderProducts.product',
                'vendorPI.payments',
                'purchaseInvoices',
                'purchaseGrn',
                'vendorPI',
            ]);

            // Filter only completed vendorPI for consistency with dropdowns
            // $query->whereHas('vendorPI', function ($q) {
            //     $q->where('status', 'completed');
            // });

            // Date range filter - filter by purchase order created date
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Purchase order filter - filter by purchase order number (same as table display)
            if ($request->filled('purchase_order_no')) {
                $po = $request->purchase_order_no;
                $query->whereIn('order_number', (array) $po);
            }

            // Vendor filter - filter by vendor name (same as table display)
            if ($request->filled('vendor_code')) {
                $vc = $request->vendor_code;
                $query->whereHas('vendor', function ($v) use ($vc) {
                    $v->whereIn('client_name', (array) $vc);
                });
            }

            // SKU filter - filter by product SKU
            if ($request->filled('sku')) {
                $sku = $request->sku;
                $query->with(['purchaseOrderProducts' => function ($p) use ($sku) {
                    $p->whereIn('sku', (array) $sku);
                }]);
                $query->whereHas('purchaseOrderProducts', function ($p) use ($sku) {
                    $p->whereIn('sku', (array) $sku);
                });
            }

            // Clone for stats before pagination
            $statsQuery = clone $query;

            // Get all filtered purchase orders for export (no pagination)
            $vendorPIProducts = $query->latest('id')->get();
            // dd($vendorPIProducts);
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


            // Create temporary CSV file
            $tempCsvPath = storage_path('app/vendor_purchase_history_' . Str::random(8) . '.csv');
            $file = fopen($tempCsvPath, 'w');

            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add header row - matching all table columns
            fputcsv($file, [
                'Purchse Order No',
                'Purchase Order Date',
                'Vendor Name',
                'SKU',
                'Item Name',
                'HSN/SAC',
                'PO Created',
                'PI Received',
                'PO Quantity',
                'PI Quantity',
                'PI Received Quantity',
                'UoM',
                'Rate',
                'Discount',
                'Taxable Value',
                'GST',
                'CGST',
                'SGST',
                'IGST',
                'GST Amount',
                'Total Amount',
                'Cess',
                'Cess Amount',
                'Invioice Uploaded',
                'GRN Uploaded',
                'Shipping Charges',
                // 'Approved',
                'Warehouse',
            ]);

            // Add data rows - loop through purchase orders and their products

            foreach ($vendorPIProducts as $purchaseOrder) {
                $vendor = $purchaseOrder->vendor ?? null;
                $purchaseInvoice = $purchaseOrder->purchaseInvoices->first() ?? null;
                $vendorPI = $purchaseOrder->vendorPI->first() ?? null;
                $payment = $vendorPI ? $vendorPI->payments->first() : null;
                $purchaseGrn = $purchaseOrder->purchaseGrn ?? null;

                foreach ($purchaseOrder->purchaseOrderProducts as $product) {
                    $productDetails = $product->product ?? null;
                    $toNumber = static function ($value): float {
                        if (is_int($value) || is_float($value)) {
                            return (float) $value;
                        }

                        if (is_string($value)) {
                            $cleaned = str_replace(',', '', trim($value));
                            if (is_numeric($cleaned)) {
                                return (float) $cleaned;
                            }

                            $cleaned = preg_replace('/[^0-9.\-]/', '', $cleaned);
                            return is_numeric($cleaned) ? (float) $cleaned : 0.0;
                        }

                        return 0.0;
                    };

                    $gstRate = $toNumber($productDetails?->gst);
                    $quantity = $toNumber($product->ordered_quantity);
                    $mrp = $toNumber($productDetails?->mrp);
                    $discountPerUnit = $toNumber($product->discount_per_unit);
                    $taxableValue = $mrp * $quantity;
                    $gstAmount = ($taxableValue * $gstRate) / 100;
                    $totalAmount = $taxableValue + $gstAmount;

                    // Calculate CGST/SGST/IGST based on state (simplified)
                    $cgst = $gstRate / 2;
                    $sgst = $gstRate / 2;
                    $igst = 0;


                    fputcsv($file, [
                        $purchaseOrder->order_number ?? 'N/A',
                        $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A',
                        $vendor->client_name ?? 'N/A',
                        $product->sku ?? 'N/A',
                        $productDetails->brand_title ?? 'N/A',
                        $productDetails->hsn ?? 'N/A',
                        $purchaseOrder->created_at ? $purchaseOrder->created_at : 'N/A',
                        $vendorPI?->updated_at ? $vendorPI->updated_at->copy()->addMonth()->format('d-m-Y') : 'N/A',
                        $product->ordered_quantity ?? 'N/A',
                        $product->ordered_quantity ?? 'N/A',
                        $product->ordered_quantity ?? 'N/A',
                        'PCS',
                        number_format($mrp, 2, '.', ''),
                        number_format($discountPerUnit, 2, '.', ''),
                        number_format($taxableValue, 2, '.', ''),
                        number_format($gstRate, 2, '.', ''),
                        number_format($cgst, 2, '.', ''),
                        number_format($sgst, 2, '.', ''),
                        number_format($igst, 2, '.', ''),
                        number_format($gstAmount, 2, '.', ''),
                        number_format($totalAmount, 2, '.', ''),
                        0 ?? 'N/A',
                        0 ?? 'N/A',
                        $purchaseInvoice ? 'Yes' : 'No',
                        $purchaseGrn ? 'Yes' : 'No',
                        'N/A',
                        $vendorPI?->warehouse?->name ?? 'N/A',
                    ]);
                }
            }

            fclose($file);

            // Log activity for audit trail
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'vendor_code' => $request->vendor_code,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
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
                ? 'Vendor-Purchase-SKU-' . $vendorPart . '-' . date('d-m-Y') . '.csv'
                : 'Vendor-Purchase-SKU-' . date('d-m-Y') . '.csv';

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
            $query = WarehouseStock::with('product', 'warehouse', 'productMapping');

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

            // Get paginated results
            $products = $query->get();

            // Calculate statistics
            $toNumber = static function ($value): float {
                if (is_int($value) || is_float($value)) {
                    return (float) $value;
                }
                if (is_string($value)) {
                    $cleaned = str_replace(',', '', trim($value));
                    if (is_numeric($cleaned)) {
                        return (float) $cleaned;
                    }
                    $cleaned = preg_replace('/[^0-9.\-]/', '', $cleaned);
                    return is_numeric($cleaned) ? (float) $cleaned : 0.0;
                }
                return 0.0;
            };

            $productsSum = $products->sum('original_quantity');
            $availableProductsSum = $products->sum('available_quantity');
            $blockProductsSum = $products->sum('block_quantity');
            $totalStockValue = $products->sum(function ($record) use ($toNumber) {
                $baseRate = $toNumber($record->productMapping?->basic_rate) ?: $toNumber($record->product?->basic_rate);
                return $toNumber($record->available_quantity) * $baseRate;
            });
            
            $totalAmountValue = $products->sum(function ($record) use ($toNumber) {
                $baseRate = $toNumber($record->productMapping?->basic_rate) ?: $toNumber($record->product?->basic_rate);
                $taxableValue = $toNumber($record->available_quantity) * $baseRate;
                $gstValue = ($taxableValue * $toNumber($record->product?->gst)) / 100;

                return $taxableValue + $gstValue;
            });

            // Get filter dropdown data
            $warehouses = Warehouse::active()->select('id', 'name')->get();
            $categories = Product::distinct('category')->whereNotNull('category')->pluck('category');
            $brands = Product::distinct('brand')->whereNotNull('brand')->pluck('brand');
            $skus = Product::distinct('sku')->whereNotNull('sku')->pluck('sku');

            // Low stock alerts (available_quantity <= 10) - based on filtered results
            $lowStockCount = $products
                ->where('available_quantity', '<=', 10)
                ->where('available_quantity', '>', 0)
                ->count();

            // Out of stock count - based on filtered results
            $outOfStockCount = $products->where('available_quantity', 0)->count();

            return view('inventory-stock-history', compact(
                'products',
                'productsSum',
                'availableProductsSum',
                'blockProductsSum',
                'totalStockValue',
                'totalAmountValue',
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
            $query = WarehouseStock::with('product', 'warehouse', 'productMapping');

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
                'Basic Rate',
                'Net Landing Rate',
                'Original Quantity',
                'Available Quantity',
                'Hold Qty',
                'Taxable Value',
                'GST',
                'GST Value',
                'Total Value',
                'Date',
            ]);

            // Add data rows
            foreach ($products as $record) {
                $product = $record->product;
                $toNumber = static function ($value): float {
                    if (is_int($value) || is_float($value)) {
                        return (float) $value;
                    }

                    if (is_string($value)) {
                        $cleaned = str_replace(',', '', trim($value));
                        if (is_numeric($cleaned)) {
                            return (float) $cleaned;
                        }

                        // Handles values like "18%" or "Rs 1,250.50"
                        $cleaned = preg_replace('/[^0-9.\-]/', '', $cleaned);
                        return is_numeric($cleaned) ? (float) $cleaned : 0.0;
                    }

                    return 0.0;
                };

                $mappedMrp = $toNumber($record->productMapping?->mrp);
                $mappedBasicRate = $toNumber($record->productMapping?->basic_rate);
                $mappedNetLandingRate = $toNumber($record->productMapping?->net_landing_rate);

                $mrp = $mappedMrp ?: $toNumber($product?->mrp);
                $basicRate = $mappedBasicRate ?: $toNumber($product?->basic_rate);
                $netLandingRate = $mappedNetLandingRate ?: $toNumber($product?->net_landing_rate);
                $gst = $toNumber($product?->gst);
                $availableQty = $toNumber($record->available_quantity);
                $originalQty = $toNumber($record->original_quantity);
                $blockQty = $toNumber($record->block_quantity);

                $stockValue = $availableQty * $basicRate;
                $gstValue = ($stockValue * $gst) / 100;
                $totalValue = $stockValue + $gstValue;

                fputcsv($file, [
                    $record->warehouse?->name ?? 'N/A',
                    $product?->brand ?? 'N/A',
                    $product?->brand_title ?? 'N/A',
                    $product?->category ?? 'N/A',
                    $product?->sku ?? 'N/A',
                    $product?->pcs_set ?? 0,
                    $product?->sets_ctn ?? 0,
                    number_format($mrp, 2, '.', ''),
                    number_format($basicRate, 2, '.', ''),
                    number_format($netLandingRate, 2, '.', ''),
                    $originalQty,
                    $availableQty,
                    $blockQty,
                    number_format($stockValue, 2, '.', ''),
                    $gst,
                    number_format($gstValue, 2, '.', ''),
                    number_format($totalValue, 2, '.', ''),
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

    private function applyCustomerSalesSkuProductFilters($query, Request $request): void
    {
        if (! $request->filled('customer_id') && ! $request->filled('po_no')) {
            return;
        }

        $productFilter = function ($productQuery) use ($request) {
            if ($request->filled('customer_id')) {
                $productQuery->whereIn('customer_id', (array) $request->customer_id);
            }

            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;

                $productQuery->whereHas('tempOrder', function ($tempOrderQuery) use ($poNos) {
                    $tempOrderQuery->whereIn('po_number', $poNos);
                });
            }
        };

        $query->whereHas('orderedProducts', $productFilter)
            ->with(['orderedProducts' => $productFilter]);
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
    public function customerSalesHistory1(Request $request)
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
                'orderedProducts.invoiceDetails.invoice',
                'orderedProducts.invoiceDetails.invoice.appointment',
                'orderedProducts.warehouseAllocations.warehouse',
                'orderedProducts.vendorPIProduct'
            ]);



            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Sales Order Number Filter
            if ($request->filled('sales_order_no')) {
                $query->whereIn('order_number', (array) $request->sales_order_no);
            }

            // Warehouse Filter - filter by warehouse allocations, not sales_order warehouse_id
            if ($request->filled('warehouse_id')) {
                $warehouseIds = (array) $request->warehouse_id;
                $query->with('orderedProducts.warehouseAllocations', function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                })
                    ->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($warehouseIds) {
                        $q->whereIn('warehouse_id', $warehouseIds);
                    });
            }

            // Customer Filter - filter by product-level customer (orderedProducts.customer)
            if ($request->filled('customer_id')) {
                $customerIds = (array) $request->customer_id;

                $query->where(function ($q) use ($customerIds) {
                    $q->with('orderedProducts.customer', function ($c) use ($customerIds) {
                        $c->whereIn('id', $customerIds);
                    })->whereHas('orderedProducts.customer', function ($c) use ($customerIds) {
                        $c->whereIn('id', $customerIds);
                    });
                });
            }

            // Region Filter - filter by product-level customer's shipping/billing state
            if ($request->filled('region')) {
                $regions = (array) $request->region;

                $query->where(function ($q) use ($regions) {
                    $q->with('orderedProducts.customer', function ($c) use ($regions) {
                        $c->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    })->whereHas('orderedProducts.customer', function ($c) use ($regions) {
                        $c->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    });
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
            // if ($request->filled('invoice_no')) {
            //     $invoiceNos = (array) $request->invoice_no;
            //     $query->where(function ($q) use ($invoiceNos) {
            //         $q->with('invoices', function ($inv) use ($invoiceNos) {
            //             $inv->whereIn('invoice_number', $invoiceNos);
            //         })->whereHas('invoices', function ($inv) use ($invoiceNos) {
            //             $inv->whereIn('invoice_number', $invoiceNos);
            //         });
            //     });
            // }

            // Invoice Number Filter
            if ($request->filled('invoice_no')) {
                $invoiceNos = (array) $request->invoice_no;

                $query->whereHas('invoices', function ($q) use ($invoiceNos) {
                    $q->whereIn('invoice_number', $invoiceNos);
                })
                    ->whereHas('orderedProducts.warehouseAllocations', function ($q) {
                        $q->whereNotNull('id'); // ensures allocation exists
                    });
            }

            // PO Number Filter
            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;
                $query->with('orderedProducts.tempOrder', function ($tmp) use ($poNos) {
                    $tmp->whereIn('po_number', $poNos);
                })
                    ->where(function ($q) use ($poNos) {
                        $q->whereHas('orderedProducts.tempOrder', function ($tmp) use ($poNos) {
                            $tmp->whereIn('po_number', $poNos);
                        }); // keep when no orderedProducts
                    });
            }

            $this->applyCustomerSalesSkuProductFilters($query, $request);

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
                    $q->with('invoices.appointment', function ($app) use ($convertedDates) {
                        $app->whereIn('appointment_date', $convertedDates);
                    });
                    $q->whereHas('invoices.appointment', function ($app) use ($convertedDates) {
                        $app->whereIn('appointment_date', $convertedDates);
                    });
                });
            }

            // Final result
            $salesOrders = $query->latest('created_at')->get();

            // dd($salesOrders);

            // Stats
            $statsQuery = clone $query;
            $allSalesOrders = $statsQuery->get();

            // Calculate all required statistics for SKU level
            $totalInvoices = 0;
            $totalTaxableAmount = 0;
            $totalInvoiceAmount = 0;
            $totalPurchaseOrder = 0;
            $totalPurchaseOrderAmount = 0;
            $uniqueCustomers = [];

            foreach ($allSalesOrders as $salesOrder) {
                // Count purchase orders
                $totalPurchaseOrder++;
                $totalPurchaseOrderAmount += $salesOrder->total_amount ?? 0;

                foreach ($salesOrder->invoices as $invoice) {
                    $totalInvoices++;
                    $totalTaxableAmount += $invoice->taxable_amount ?? 0;
                    $totalInvoiceAmount += $invoice->total_amount ?? 0;

                    // Track unique customers
                    if ($invoice->customer_id && !in_array($invoice->customer_id, $uniqueCustomers)) {
                        $uniqueCustomers[] = $invoice->customer_id;
                    }
                }
            }

            $totalCustomers = count($uniqueCustomers);

            // Legacy variables for backward compatibility
            $totalRevenue = $totalInvoiceAmount;
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

            // Get unique Sales Order numbers
            $salesOrderNumbers = SalesOrder::whereNotNull('order_number')
                ->distinct()
                ->pluck('order_number')
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
            
            $invoicesData = Invoice::with('details.product')
                ->where('invoice_type', 'sales_order')
                ->get();

            $total_sales_overall = $invoicesData->sum(function ($invoice) {
                return $invoice->details->sum('total_price');
            });


            $data = [
                'total_sales_overall' => $total_sales_overall,
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
                'salesOrderNumbers' => $salesOrderNumbers,
                'appointmentDates' => $appointmentDates,
                'totalInvoices' => $totalInvoices,
                'totalCustomers' => $totalCustomers,
                'totalTaxableAmount' => $totalTaxableAmount,
                'totalInvoiceAmount' => $totalInvoiceAmount,
                'totalPurchaseOrder' => $totalPurchaseOrder,
                'totalPurchaseOrderAmount' => $totalPurchaseOrderAmount,
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
                    'sales_order_no' => $request->input('sales_order_no', []),
                    'appointment_date' => $request->input('appointment_date', []),
                ],
            ];

            return view('customer-sales-sku', $data);
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
    public function customerSalesHistoryExcel1(Request $request)
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
            'sales_order_no' => 'nullable|array',
            'sales_order_no.*' => 'string|exists:sales_orders,order_number',
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
                'orderedProducts.vendorPIProduct'
            ]);

            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Apply sales order number filter
            if ($request->filled('sales_order_no')) {
                $query->whereIn('order_number', (array) $request->sales_order_no);
            }

            // Apply warehouse filter - filter by warehouse allocations, not sales_order warehouse_id
            if ($request->filled('warehouse_id')) {
                $warehouseIds = (array) $request->warehouse_id;

                $query->with('orderedProducts.warehouseAllocations', function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                })
                    ->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($warehouseIds) {
                        $q->whereIn('warehouse_id', $warehouseIds);
                    });
            }

            // Customer Filter - filter by product-level customer (orderedProducts.customer)
            if ($request->filled('customer_id')) {
                $customerIds = (array) $request->customer_id;

                $query->where(function ($q) use ($customerIds) {
                    $q->with('orderedProducts.customer', function ($c) use ($customerIds) {
                        $c->whereIn('id', $customerIds);
                    })->whereHas('orderedProducts.customer', function ($c) use ($customerIds) {
                        $c->whereIn('id', $customerIds);
                    });
                });
            }

            // Region Filter - filter by product-level customer's shipping/billing state
            if ($request->filled('region')) {
                $regions = (array) $request->region;

                $query->where(function ($q) use ($regions) {
                    $q->with('orderedProducts.customer', function ($c) use ($regions) {
                        $c->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    })->whereHas('orderedProducts.customer', function ($c) use ($regions) {
                        $c->where(function ($subQ) use ($regions) {
                            $subQ->whereIn('billing_state', $regions)
                                ->orWhereIn('shipping_state', $regions);
                        });
                    });
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

            // Invoice Number Filter
            if ($request->filled('invoice_no')) {
                $invoiceNos = (array) $request->invoice_no;
                $query->where(function ($q) use ($invoiceNos) {
                    $q->with('invoices', function ($inv) use ($invoiceNos) {
                        $inv->whereIn('invoice_number', $invoiceNos);
                    })->whereHas('invoices', function ($inv) use ($invoiceNos) {
                        $inv->whereIn('invoice_number', $invoiceNos);
                    });
                });
            }

            // PO Number Filter
            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;
                $query->with('orderedProducts.tempOrder', function ($tmp) use ($poNos) {
                    $tmp->whereIn('po_number', $poNos);
                })
                    ->where(function ($q) use ($poNos) {
                        $q->whereHas('orderedProducts.tempOrder', function ($tmp) use ($poNos) {
                            $tmp->whereIn('po_number', $poNos);
                        }); // keep when no orderedProducts
                    });
            }

            $this->applyCustomerSalesSkuProductFilters($query, $request);

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
            $salesOrders = $query->latest('created_at')->get();

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
                            // if ($salesOrder->invoices->count() > 0) {
                            //     foreach ($salesOrder->invoices as $invoice) {
                            //         if ($invoice->warehouse_id == $allocation->warehouse_id) {
                            //             $invoiceNumber = $invoice->invoice_number ?? 'N/A';
                            //             break;
                            //         }
                            //     }
                            // }
                            
                            // Get invoice details
                            $invoiceDetail = $product->invoiceDetails->first();
                            $invoice = $invoiceDetail?->invoice;
                            $invoiceNumber = $invoice->invoice_number ?? 'N/A';
                            $appointment = $invoice?->appointment;
                            $dns = $invoice?->dns;
                            $payment = $invoice?->payments?->first();
    
                            // Calculate subtotal and total for this allocation
                            $subtotal = $allocation->final_final_dispatched_quantity * $product->tempOrder?->basic_rate;
                            $gstRate = $product->tempOrder->gst ?? 0;
                            $total = $subtotal * (1 + $gstRate / 100);
                            $gstAmount = 0;

                            // dd($product->tempOrder->po_qty);
                            $exportData->push([
                                'Sales Order No' => $salesOrder->order_number ?? 'N/A',
                                'Sales Order Date' => $salesOrder->created_at?->format('d-m-Y') ?? 'N/A',
                                'Customer Group Name' => $customerGroup->name ?? 'N/A',
                                'Warehouse Name' => $allocation->warehouse->name ?? 'N/A',
                                'Customer Name' => $customer->client_name ?? 'N/A',
                                'Invoice No' => $invoiceNumber,
                                'Invoice Date' => $invoice?->created_at?->format('d-m-Y') ?? 'N/A',
                                'Customer Phone No' => $customer->contact_no ?? 'N/A',
                                'Customer Email' => $customer->email ?? 'N/A',
                                'Customer City' => $customer->shipping_city ?? 'N/A',
                                'Customer State' => $customer->shipping_state ?? 'N/A',
                                'PO No' => $product->tempOrder->po_number ?? 'N/A',
                                // Product details
                                'SKU Code' => $product->tempOrder->sku ?? 'N/A',
                                'Item Code' => $product->tempOrder->item_code ?? 'N/A',
                                'Title' => $product->tempOrder->description ?? $product->product->brand_title,
                                'Brand' => $product->product->brand ?? 'N/A',
                                'HSN' => $product->tempOrder->hsn ?? $product->product->hsn,

                                // Order quantities
                                'Orderd Quantity' => intval($product->tempOrder?->po_qty) ?? intval($product->ordered_quantity),
                                'Dispatched Quantity' => $allocation->final_final_dispatched_quantity ?? 0,
                                'Box Count' => $allocation->box_count ?? 0,
                                'Weight' => $allocation->weight ?? 0,

                                // Sale price fields
                                'Unit Price' => $product->tempOrder?->basic_rate ?? 0,
                                'Taxable Amount' => $invoiceNumber === 'N/A' ? 0 : $allocation->final_final_dispatched_quantity * ($product->tempOrder?->basic_rate ?? 0),
                                'GST' => $product->tempOrder->gst ?? 0,
                                'GST Amount' => $invoiceNumber === 'N/A' ? 0 : $allocation->final_final_dispatched_quantity * ($product->tempOrder?->basic_rate ?? 0) * (($product->tempOrder->gst ?? 0) / 100),
                                'Invoice Amount' => $invoiceNumber === 'N/A' ? 0 : $allocation->final_final_dispatched_quantity * ($product->tempOrder?->basic_rate ?? 0) * (1 + (($product->tempOrder->gst ?? 0) / 100)),

                                // Purchase details
                                'Purchase Order No' => $product->vendorPIProduct && $product->purchase_ordered_quantity > 0 ? $product->purchase_ordered_quantity : 0,
                                'Purchase Rate' => $product->vendorPIProduct && $product->purchase_ordered_quantity > 0 ? $product->vendorPIProduct?->purchase_rate : 0,

                                // Subtotal (purchase)
                                'Subtotal' => $product->vendorPIProduct && $product->purchase_ordered_quantity > 0 ? ($subtotal = ($product->purchase_ordered_quantity * ($product->vendorPIProduct?->purchase_rate ?? 0))) : 0,

                                // Purchase GST %
                                'Purchase GST' => $product->vendorPIProduct && $product->purchase_ordered_quantity > 0 ? $product->vendorPIProduct?->gst : 0,

                                // Purchase GST Amount
                                'GST Amount (Purchase)' => $product->vendorPIProduct && $product->purchase_ordered_quantity > 0 ? ($gstAmount = $subtotal * (($product->vendorPIProduct?->gst ?? 0) / 100)) : 0,

                                // Total Purchase Amount
                                'Total Amount' => $product->vendorPIProduct && $product->purchase_ordered_quantity > 0 ? ($subtotal + $gstAmount) : 0,
                                'Product Status' => ucwords(str_replace('_', ' ', (($allocation->product_status == 'completed') ? 'Shipped' : $allocation->product_status) ?? 'Pending')) ?? 'N/A',
                                'Invoice Status' => ucwords(str_replace('_', ' ', $allocation?->invoice_status ?? 'N/A'))
                            ]);
                        }
                    }
                }
            }

            // Create temporary Excel file
            $tempXlsxPath = storage_path('app/customer_sales_history_' . Str::random(8) . '.xlsx');

            // Create writer
            $writer = \Spatie\SimpleExcel\SimpleExcelWriter::create($tempXlsxPath);

            $writer->noHeaderRow();

            // Add header row (matching view table columns exactly)
            $writer->addRow([
                'Sales Order No',
                'Sales Order Date',
                'Customer Group Name',
                'Warehouse Name',
                'Customer Name',
                'Invoice No',
                'Invoice Date',
                'Customer Phone No',
                'Customer Email',
                'Customer City',
                'Customer State',
                'PO No',
                'SKU Code',
                'Item Code',
                'Title',
                'Brand',
                'HSN',
                'Orderd Quantity',
                'Dispatched Quantity',
                'Box Count',
                'Weight',
                'Unit Price',
                'Taxable Amount',
                'GST',
                'GST Amount',
                'Invoice Amount',
                'Purchase Order No',
                'Purchase Rate',
                'Subtotal',
                'GST',
                'GST Amount',
                'Total Amount',
                'Product Status',
                'Invoice Status',
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
            $fileName = 'Customer-Sales-SKU-' . date('d-m-Y') . '.xlsx';

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

    private function applyCustomerSalesInvoiceFilters($query, Request $request)
    {
        if (
            ! $request->filled('from_date') &&
            ! $request->filled('to_date') &&
            ! $request->filled('invoice_no') &&
            ! $request->filled('customer_id') &&
            ! $request->filled('appointment_date') &&
            ! $request->filled('po_no')
        ) {
            return;
        }

        $invoiceFilter = function ($invoiceQuery) use ($request) {
            if ($request->filled('from_date')) {
                $invoiceQuery->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $invoiceQuery->whereDate('created_at', '<=', $request->to_date);
            }

            if ($request->filled('invoice_no')) {
                $invoiceQuery->whereIn('invoice_number', (array) $request->invoice_no);
            }

            if ($request->filled('customer_id')) {
                $invoiceQuery->whereIn('customer_id', (array) $request->customer_id);
            }

            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;

                $invoiceQuery->where(function ($poQuery) use ($poNos) {
                    $poQuery->whereIn('po_number', $poNos)
                        ->orWhereHas('details', function ($detailQuery) use ($poNos) {
                            $detailQuery->whereIn('po_number', $poNos)
                                ->orWhereHas('tempOrder', function ($tempOrderQuery) use ($poNos) {
                                    $tempOrderQuery->whereIn('po_number', $poNos);
                                })
                                ->orWhereHas('salesOrderProduct.tempOrder', function ($tempOrderQuery) use ($poNos) {
                                    $tempOrderQuery->whereIn('po_number', $poNos);
                                });
                        });
                });
            }

            if ($request->filled('appointment_date')) {
                $appointmentDates = collect((array) $request->appointment_date)
                    ->map(function ($date) {
                        try {
                            return \Carbon\Carbon::parse($date)->format('Y-m-d');
                        } catch (\Exception $e) {
                            return $date;
                        }
                    })
                    ->all();

                $invoiceQuery->whereHas('appointment', function ($appointmentQuery) use ($appointmentDates) {
                    $appointmentQuery->whereIn('appointment_date', $appointmentDates);
                });
            }
        };

        $query->whereHas('invoices', $invoiceFilter)
            ->with(['invoices' => $invoiceFilter]);
    }

    private function resolveInvoicePoNumber(Invoice $invoice): string
    {
        if (! empty($invoice->po_number)) {
            return $invoice->po_number;
        }

        foreach ($invoice->details as $detail) {
            $poNumber = $detail->po_number
                ?? $detail->tempOrder?->po_number
                ?? $detail->salesOrderProduct?->tempOrder?->po_number;

            if (! empty($poNumber)) {
                return $poNumber;
            }
        }

        return 'N/A';
    }

    public function customerSalesHistory(Request $request)
    {
        $salesOrderInvoiceFilter = function ($q) {
            $q->where('invoice_type', 'sales_order');
        };

        $query = SalesOrder::with([
            'customerGroup',
            'invoices' => function ($q) use ($salesOrderInvoiceFilter) {
                $salesOrderInvoiceFilter($q);
                $q->with([
                    'payments',
                    'details.tempOrder',
                    'details.salesOrderProduct.tempOrder',
                    'appointment',
                    'customer',
                    'dns',
                    'warehouse',
                ]);
            },
            'orderedProducts',
            'orderedProducts.tempOrder',
        ]);

        if ($request->filled('sales_order_no')) {
            $query->whereIn('order_number', (array) $request->sales_order_no);
        }
        if ($request->filled('invoice_no')) {
            $invoiceNos = (array) $request->invoice_no;

            $query->with([
                'invoices' => function ($q) use ($invoiceNos, $salesOrderInvoiceFilter) {
                    $salesOrderInvoiceFilter($q);
                    $q->whereIn('invoice_number', $invoiceNos);
                }
            ]);

            $query->whereHas('invoices', function ($q) use ($invoiceNos, $salesOrderInvoiceFilter) {
                $salesOrderInvoiceFilter($q);
                $q->whereIn('invoice_number', $invoiceNos);
            });
        }

        // 
        if ($request->filled('appointment_date')) {
            $appointmentDates = (array) $request->appointment_date;

            $query->with([
                'invoices' => function ($q) use ($appointmentDates, $salesOrderInvoiceFilter) {
                    $salesOrderInvoiceFilter($q);
                    $q->whereHas('appointment', function ($appointmentQuery) use ($appointmentDates) {
                        $appointmentQuery->whereIn('appointment_date', $appointmentDates);
                    })->with([
                        'appointment' => function ($appointmentQuery) use ($appointmentDates) {
                            $appointmentQuery->whereIn('appointment_date', $appointmentDates);
                        },
                        'payments',
                        'details.tempOrder',
                        'details.salesOrderProduct.tempOrder',
                        'customer',
                        'dns',
                        'warehouse',
                    ]);
                },
            ]);

            $query->whereHas('invoices', function ($q) use ($appointmentDates, $salesOrderInvoiceFilter) {
                $salesOrderInvoiceFilter($q);
                $q->whereHas('appointment', function ($appointmentQuery) use ($appointmentDates) {
                    $appointmentQuery->whereIn('appointment_date', $appointmentDates);
                });
            });
        }

        if ($request->filled('po_no')) {
            $poNos = (array) $request->po_no;

            $query->with([
                'orderedProducts.tempOrder' => function ($q) use ($poNos) {
                    $q->whereIn('po_number', $poNos);
                }
            ]);

            $query->whereHas('orderedProducts.tempOrder', function ($q) use ($poNos) {
                $q->whereIn('po_number', $poNos);
            });
        }

        // Customer Filter
        if ($request->filled('customer_id')) {
            $customerIds = (array) $request->customer_id;

            $query->with([
                'orderedProducts.customer' => function ($q) use ($customerIds) {
                    $q->whereIn('id', $customerIds);
                }
            ]);

            $query->whereHas('orderedProducts.customer', function ($q) use ($customerIds) {
                $q->whereIn('id', $customerIds);
            });
        }

        // Warehouse Filter
        if ($request->filled('warehouse_id')) {
            $warehouseIds = (array) $request->warehouse_id;

            $query->with([
                'orderedProducts.warehouseAllocations' => function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                }
            ]);

            $query->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($warehouseIds) {
                $q->whereIn('warehouse_id', $warehouseIds);
            });
        }


        // Customer Group Filter
        if ($request->filled('customer_type')) {
            $customerTypes = (array) $request->customer_type;

            $query->with([
                'customerGroup' => function ($q) use ($customerTypes) {
                    $q->whereIn('id', $customerTypes);
                }
            ]);

            $query->whereHas('customerGroup', function ($q) use ($customerTypes) {
                $q->whereIn('id', $customerTypes);
            });
        }

        $this->applyCustomerSalesInvoiceFilters($query, $request);

        $query->whereHas('invoices', $salesOrderInvoiceFilter);

        // Final result
        $salesOrders = $query->latest('created_at')->get();

        // dd($salesOrders[0]->invoices[0]);

        // Stats
        $statsQuery = clone $query;
        $allSalesOrders = $statsQuery->get();

        // Calculate all required statistics
        $totalInvoices = 0;
        $totalTaxableAmount = 0;
        $totalAmount = 0;
        $totalAmountPaid = 0;
        $totalBalanceAmount = 0;
        $uniqueCustomers = [];

        foreach ($allSalesOrders as $salesOrder) {
            foreach ($salesOrder->invoices as $invoice) {
                $totalInvoices++;
                $totalTaxableAmount += $invoice->taxable_amount ?? 0;
                $totalAmount += $invoice->total_amount ?? 0;
                $totalAmountPaid += $invoice->paid_amount ?? 0;
                $totalBalanceAmount += $invoice->balance_due ?? 0;

                // Track unique customers
                if ($invoice->customer_id && !in_array($invoice->customer_id, $uniqueCustomers)) {
                    $uniqueCustomers[] = $invoice->customer_id;
                }
            }
        }

        $totalCustomers = count($uniqueCustomers);

        // Legacy variables for backward compatibility
        $totalRevenue = $totalAmount;
        $totalPaid = $totalAmountPaid;
        $totalPendingPayments = $totalBalanceAmount;

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
            ->where('invoice_type', 'sales_order')
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

        $salesOrderNumbers = SalesOrder::whereNotNull('order_number')
            ->distinct()
            ->pluck('order_number')
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

        // Prepare filters array for view
        $filters = [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'customer_id' => $request->customer_id,
            'warehouse_id' => $request->warehouse_id,
            'region' => $request->region,
            'payment_status' => $request->payment_status,
            'customer_type' => $request->customer_type,
            'invoice_no' => $request->input('invoice_no', []),
            'po_no' => $request->input('po_no', []),
            'sales_order_no' => $request->input('sales_order_no', []),
            'appointment_date' => $request->input('appointment_date', []),
        ];

        $invoicesData = Invoice::with('details.product')
            ->where('invoice_type', 'sales_order')
            ->get();

        $total_sales_overall = $invoicesData->sum(function ($invoice) {
            return $invoice->details->sum('total_price');
        });

        $paid_amount_overall = $invoicesData->sum('paid_amount');
        $balance_due_overall = $total_sales_overall - $paid_amount_overall;

        return view('customer-sales-invoices', compact(
            'total_sales_overall',
            'paid_amount_overall',
            'balance_due_overall',
            'salesOrders',
            'customerGroups',
            'customers',
            'warehouses',
            'regions',
            'invoiceNumbers',
            'poNumbers',
            'salesOrderNumbers',
            'appointmentDates',
            'totalRevenue',
            'totalPaid',
            'totalPendingPayments',
            'filters',
            'totalInvoices',
            'totalTaxableAmount',
            'totalAmount',
            'totalAmountPaid',
            'totalBalanceAmount',
            'totalCustomers'
        ));
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

        try {
            $query = SalesOrder::with([
                'customerGroup',
                'invoices.payments',
                'invoices.details.tempOrder',
                'invoices.details.salesOrderProduct.tempOrder',
                'invoices.appointment',
                'invoices.customer',
                'invoices.dns',
                'invoices.warehouse',
                'orderedProducts',
                'orderedProducts.tempOrder',
            ]);

            if ($request->filled('sales_order_no')) {
                $query->whereIn('order_number', (array) $request->sales_order_no);
            }
            if ($request->filled('invoice_no')) {
                $invoiceNos = (array) $request->invoice_no;

                $query->with([
                    'invoices' => function ($q) use ($invoiceNos) {
                        $q->whereIn('invoice_number', $invoiceNos);
                    }
                ]);

                $query->whereHas('invoices', function ($q) use ($invoiceNos) {
                    $q->whereIn('invoice_number', $invoiceNos);
                });
            }

            // 
            if ($request->filled('appointment_date')) {
                $appointmentDates = (array) $request->appointment_date;

                $query->with([
                    'invoices.appointment' => function ($q) use ($appointmentDates) {
                        $q->whereIn('appointment_date', $appointmentDates);
                    }
                ]);

                $query->whereHas('invoices.appointment', function ($q) use ($appointmentDates) {
                    $q->whereIn('appointment_date', $appointmentDates);
                });
            }

            if ($request->filled('po_no')) {
                $poNos = (array) $request->po_no;

                $query->with([
                    'orderedProducts.tempOrder' => function ($q) use ($poNos) {
                        $q->whereIn('po_number', $poNos);
                    }
                ]);

                $query->whereHas('orderedProducts.tempOrder', function ($q) use ($poNos) {
                    $q->whereIn('po_number', $poNos);
                });
            }

            // Customer Filter
            if ($request->filled('customer_id')) {
                $customerIds = (array) $request->customer_id;

                $query->with([
                    'orderedProducts.customer' => function ($q) use ($customerIds) {
                        $q->whereIn('id', $customerIds);
                    }
                ]);

                $query->whereHas('orderedProducts.customer', function ($q) use ($customerIds) {
                    $q->whereIn('id', $customerIds);
                });
            }

            // Warehouse Filter
            if ($request->filled('warehouse_id')) {
                $warehouseIds = (array) $request->warehouse_id;

                $query->with([
                    'orderedProducts.warehouseAllocations' => function ($q) use ($warehouseIds) {
                        $q->whereIn('warehouse_id', $warehouseIds);
                    }
                ]);

                $query->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                });
            }

            // Customer Group Filter
            if ($request->filled('customer_type')) {
                $customerTypes = (array) $request->customer_type;

                $query->with([
                    'customerGroup' => function ($q) use ($customerTypes) {
                        $q->whereIn('id', $customerTypes);
                    }
                ]);

                $query->whereHas('customerGroup', function ($q) use ($customerTypes) {
                    $q->whereIn('id', $customerTypes);
                });
            }
            $this->applyCustomerSalesInvoiceFilters($query, $request);

            // Final result
            $salesOrders = $query->latest('created_at')->get();

            // dd($salesOrders[0]->invoices[0]);

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
                foreach ($salesOrder->invoices as $invoice) {
                    // Calculate totals from invoice details and salesOrderProduct
                    $totalBoxCount = 0;
                    $totalWeight = 0;
                    $taxableValue = 0;
                    $gstAmount = 0;
                    $cgstAmount = 0;
                    $sgstAmount = 0;
                    $igstAmount = 0;
                    $cessAmount = 0;
                    $firstGstRate = 0;

                    foreach ($invoice->details as $detail) {
                        // Box count - use invoice_details or fallback to salesOrderProduct
                        $totalBoxCount +=
                            $detail->box_count ?? ($detail->salesOrderProduct?->box_count ?? 0);

                        // Weight - use invoice_details or fallback to salesOrderProduct
                        $totalWeight +=
                            $detail->weight ?? ($detail->salesOrderProduct?->weight ?? 0);

                        // Taxable value is the 'amount' field
                        $taxableValue += $detail->amount ?? 0;

                        // GST amount calculation: (amount * tax) / 100
                        $detailGstAmount = ($detail->amount * $detail->tax) / 100;
                        $gstAmount += $detailGstAmount;
                        $cessAmount += (float) ($detail->cess ?? 0);

                        // Store first GST rate for display
                        if ($firstGstRate == 0 && $detail->tax > 0) {
                            $firstGstRate = $detail->tax;
                        }

                        // Calculate CGST/SGST/IGST based on customer state
                        // If same state: CGST + SGST, else: IGST
                        $customerState =
                            $invoice->customer?->shipping_state ??
                            $invoice->customer?->billing_state;
                        $warehouseState = $invoice->warehouse?->state ?? 'Maharashtra'; // Default warehouse state

                        if (
                            $customerState &&
                            $warehouseState &&
                            strtolower($customerState) === strtolower($warehouseState)
                        ) {
                            // Intra-state: CGST + SGST (split GST equally)
                            $cgstAmount += $detailGstAmount / 2;
                            $sgstAmount += $detailGstAmount / 2;
                        } else {
                            // Inter-state: IGST
                            // these two are optional
                            $cgstAmount += $detailGstAmount / 2;
                            $sgstAmount += $detailGstAmount / 2;
                            $igstAmount += $detailGstAmount;
                        }
                    }

                    // If line-level cess is unavailable, derive from invoice-level tax
                    if ($cessAmount <= 0) {
                        $invoiceTaxAmount = (float) ($invoice->tax_amount ?? 0);
                        if ($invoiceTaxAmount > $gstAmount) {
                            $cessAmount = $invoiceTaxAmount - $gstAmount;
                        }
                    }

                    $exportData->push([
                        'Sales Order No' => $salesOrder->order_number ?? 'N/A',
                        'Customer Group Name' => $salesOrder->customerGroup->name ?? 'N/A',
                        'Customer Name' => $invoice->customer->client_name ?? 'N/A',
                        'Invoice No' => $invoice->invoice_number,
                        'Invoice Date' => $invoice->created_at?->format('d-m-Y') ?? 'N/A',
                        'Customer Phone No' => $invoice->customer->contact_no ?? 'N/A',
                        'Customer Email' => $invoice->customer->email ?? 'N/A',
                        'Customer City' => $invoice->customer->shipping_city ?? 'N/A',
                        'Customer State' => $invoice->customer->shipping_state ?? 'N/A',
                        'PO No' => $this->resolveInvoicePoNumber($invoice),
                        'PO Date' => $invoice->created_at->format('d-m-Y') ?? 'N/A',
                        'Appointment Date' => $invoice->appointment?->appointment_date?->format('d-m-Y') ?? 'N/A',
                        'Due Date' => $invoice->appointment?->appointment_date?->addMonth()->format('d-m-Y') ?? 'N/A',
                        'GRN Date' => $invoice->appointment?->grn_date?->format('d-m-Y') ?? 'N/A',
                        'GRN' => $invoice->appointment?->grn ? 'Yes' : 'No',
                        'POD' => $invoice->appointment?->pod ? 'Yes' : 'No',
                        'DN Number' => $invoice->dns?->first()?->dn_number ?? 'N/A',
                        'DN Amount' => $invoice->dns?->first()?->dn_amount ? $invoice->dns?->first()?->dn_amount : 0,
                        'DN Receipt' => $invoice->dns?->first()?->dn_receipt ? 'Yes' : 'No',
                        // 'LR' => $invoice->lr ? 'Yes' : 'No',
                        'Currency' => $invoice->currency ?? 'INR',
                        // 'HSN' => $invoice->details->first()->hsn ?? 'N/A',
                        'Ordered Quantity' => $invoice->details->sum('quantity') ?? 'N/A',
                        'Dispatched Quantity' => $invoice->details->sum('quantity') ?? 'N/A',
                        'Box Count' => number_format($totalBoxCount, 0) ?? 'N/A',
                        'Weight' => number_format($totalWeight, 2) ?? 'N/A',
                        'Taxable Value' => number_format($taxableValue, 2) ?? 'N/A',
                        'GST' => $firstGstRate,
                        'GST Value' => number_format($gstAmount, 2),
                        'Total' => number_format($gstAmount + $taxableValue ?? 0, 2),
                        'Invoice Status' => ucfirst($invoice->payment_status ?? 'N/A') ?? 'N/A',
                        'Amount Paid' => number_format($invoice->paid_amount ?? 0, 2) ?? 'N/A',
                        'Balance' => number_format($invoice->balance_due ?? 0, 2) ?? 'N/A',
                        'Date Of Payment' => $invoice->payments?->first()?->created_at?->format('d-m-Y') ?? 'N/A',
                        'Payment Mode' => ucwords(str_replace('_', ' ', $invoice->payments->first()->payment_method ?? 'N/A')),
                        'CGST' => number_format($cgstAmount, 2),
                        'SGST' => number_format($sgstAmount, 2),
                        'IGST' => number_format($igstAmount, 2),
                        'Cess' => number_format($cessAmount, 2),
                    ]);
                }
            }

            // Create temporary Excel file
            $tempXlsxPath = storage_path('app/customer_sales_history_' . Str::random(8) . '.xlsx');

            // Create writer
            $writer = \Spatie\SimpleExcel\SimpleExcelWriter::create($tempXlsxPath);

            $writer->noHeaderRow();

            // Add header row (matching view table columns exactly)
            $writer->addRow([
                'Sales Order No',
                'Customer Group Name',
                'Customer Name',
                'Invoice No',
                'Invoice Date',
                'Customer Phone No',
                'Customer Email',
                'Customer City',
                'Customer State',
                'PO No',
                'PO Date',
                'Appointment Date',
                'Due Date',
                'GRN Date',
                'GRN',
                'POD',
                'DN Number',
                'DN Amount',
                'DN Receipt',
                // 'LR',
                'Currency',
                // 'HSN',
                'Ordered Quantity',
                'Dispatched Quantity',
                'Box Count',
                'Weight',
                'Taxable Value',
                'GST',
                'GST Value',
                'Total',
                'Invoice Status',
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
            $fileName = 'Customer-Sales-Invoices-' . date('d-m-Y') . '.xlsx';

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
            $query = Invoice::with([
                'warehouse',
                'customer.groupInfo.customerGroup',
                'salesOrder.customerGroup',
                'payments',
                'details.tempOrder',
                'details.salesOrderProduct.tempOrder',
                'appointment',
                'dns',
            ]);

            // Apply filters (same as index method)
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
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
                $poNos = (array) $request->input('po_no');

                $query->where(function ($poQuery) use ($poNos) {
                    $poQuery->whereIn('po_number', $poNos)
                        ->orWhereHas('details', function ($detailQuery) use ($poNos) {
                            $detailQuery->whereIn('po_number', $poNos)
                                ->orWhereHas('tempOrder', function ($tempOrderQuery) use ($poNos) {
                                    $tempOrderQuery->whereIn('po_number', $poNos);
                                })
                                ->orWhereHas('salesOrderProduct.tempOrder', function ($tempOrderQuery) use ($poNos) {
                                    $tempOrderQuery->whereIn('po_number', $poNos);
                                });
                        });
                });
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
                    'invoice_date' => $invoice->created_at?->format('d-m-Y') ?? 'N/A',
                    'creator_name' => 'System',
                    'customer_phone_no' => $customer->contact_no ?? 'N/A',
                    'customer_email' => $customer->email ?? 'N/A',
                    'customer_city' => $customer->billing_city ?? $customer->shipping_city ?? 'N/A',
                    'customer_state' => $customer->billing_state ?? $customer->shipping_state ?? 'N/A',
                    'po_no' => $this->resolveInvoicePoNumber($invoice),
                    'po_date' => $invoice->created_at?->format('d-m-Y') ?? 'N/A',
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
                    'top_customer' => $invoiceData->groupBy('customer_name')
                        ->map(fn ($rows) => $rows->sum('total'))
                        ->sortDesc()
                        ->keys()
                        ->first() ?? 'N/A',
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
