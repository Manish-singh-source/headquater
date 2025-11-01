<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\VendorPI;
use App\Models\VendorPIProduct;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    /**
     * Display vendor purchase history
     *
     * @return \Illuminate\View\View
     */
    public function vendorPurchaseHistory()
    {
        try {
            $purchaseOrders = VendorPI::with('products')
                ->where('status', 'completed')
                ->latest()
                ->paginate(15);

            $purchaseOrdersTotal = VendorPIProduct::sum('mrp');
            $purchaseOrdersTotalQuantity = VendorPIProduct::sum('quantity_received');

            $purchaseOrdersVendors = VendorPI::where('status', 'completed')
                ->distinct('vendor_code')
                ->pluck('vendor_code');

            return view('vendor-purchase-history', compact(
                'purchaseOrders',
                'purchaseOrdersTotal',
                'purchaseOrdersTotalQuantity',
                'purchaseOrdersVendors'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving vendor purchase history: ' . $e->getMessage());
        }
    }

    /**
     * Download vendor purchase history as Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function vendorPurchaseHistoryExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedDate' => 'required|date_format:Y-m-d',
            'vendorCode' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $tempXlsxPath = storage_path('app/vendor_purchase_history_' . Str::random(8) . '.xlsx');
            $writer = SimpleExcelWriter::create($tempXlsxPath);

            // Fetch data with relationships
            $vendorReports = VendorPI::with('products')
                ->where('status', 'completed')
                ->when($request->vendorCode, function ($query) use ($request) {
                    $query->where('vendor_code', trim($request->vendorCode));
                })
                ->when($request->selectedDate, function ($query) use ($request) {
                    $query->whereDate('created_at', $request->selectedDate);
                })
                ->latest()
                ->get();

            if ($vendorReports->isEmpty()) {
                return redirect()->back()->with('info', 'No records found for the selected criteria.');
            }

            // Add header row
            $writer->addRow([
                'Order Id' => 'Order Id',
                'Vendor Name' => 'Vendor Name',
                'Ordered Status' => 'Ordered Status',
                'Ordered Quantity' => 'Ordered Quantity',
                'Received Quantity' => 'Received Quantity',
                'Total Amount' => 'Total Amount',
                'Paid' => 'Paid',
                'Due' => 'Due',
                'Ordered Date' => 'Ordered Date',
            ]);

            // Add data rows
            foreach ($vendorReports as $record) {
                $orderedQty = $record->products->sum('quantity_requirement');
                $receivedQty = $record->products->sum('quantity_received');
                $totalAmount = $record->products->sum('mrp');
                $paidAmount = $record->products->sum('paid_amount') ?? 0;
                $dueAmount = $totalAmount - $paidAmount;

                $writer->addRow([
                    'Order Id' => $record->purchase_order_id ?? 'NA',
                    'Vendor Name' => $record->vendor_code ?? 'NA',
                    'Ordered Status' => ucfirst($record->status ?? 'N/A'),
                    'Ordered Quantity' => $orderedQty,
                    'Received Quantity' => $receivedQty,
                    'Total Amount' => number_format($totalAmount, 2),
                    'Paid' => number_format($paidAmount, 2),
                    'Due' => number_format($dueAmount, 2),
                    'Ordered Date' => $record->created_at?->format('d-m-Y') ?? 'NA',
                ]);
            }

            $writer->close();

            $fileName = $request->vendorCode
                ? 'Vendor-Purchase-History-' . str_replace(' ', '-', $request->vendorCode) . '.xlsx'
                : 'Vendor-Purchase-History.xlsx';

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'vendor_code' => $request->vendorCode,
                    'date' => $request->selectedDate,
                    'records' => $vendorReports->count(),
                ])
                ->event('report_generated')
                ->log('Vendor purchase history report generated');

            return response()->download($tempXlsxPath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * Display inventory stock history
     *
     * @return \Illuminate\View\View
     */
    public function inventoryStockHistory()
    {
        try {
            $products = WarehouseStock::with('product', 'warehouse')
                ->latest()
                ->paginate(15);

            $productsSum = WarehouseStock::sum('original_quantity');
            $blockProductsSum = WarehouseStock::sum('block_quantity');

            return view('inventory-stock-history', compact(
                'products',
                'productsSum',
                'blockProductsSum'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving inventory: ' . $e->getMessage());
        }
    }

    /**
     * Download inventory stock history as Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function inventoryStockHistoryExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedDate' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $tempXlsxPath = storage_path('app/inventory_stock_history_' . Str::random(8) . '.xlsx');
            $writer = SimpleExcelWriter::create($tempXlsxPath);

            $products = WarehouseStock::with('product', 'warehouse')
                ->when($request->selectedDate, function ($query) use ($request) {
                    $query->whereDate('created_at', $request->selectedDate);
                })
                ->latest()
                ->get();

            if ($products->isEmpty()) {
                return redirect()->back()->with('info', 'No inventory records found for the selected date.');
            }

            // Add header row
            $writer->addRow([
                'Brand' => 'Brand',
                'Brand Title' => 'Brand Title',
                'Category' => 'Category',
                'SKU' => 'SKU',
                'PCS/Set' => 'PCS/Set',
                'Sets/CTN' => 'Sets/CTN',
                'MRP' => 'MRP',
                'Status' => 'Status',
                'Original Quantity' => 'Original Quantity',
                'Available Quantity' => 'Available Quantity',
                'Hold Qty' => 'Hold Qty',
                'Date' => 'Date',
            ]);

            // Add data rows
            foreach ($products as $record) {
                $product = $record->product;

                $writer->addRow([
                    'Brand' => $product?->brand ?? 'N/A',
                    'Brand Title' => $product?->brand_title ?? 'N/A',
                    'Category' => $product?->category ?? 'N/A',
                    'SKU' => $product?->sku ?? 'N/A',
                    'PCS/Set' => $product?->pcs_set ?? 0,
                    'Sets/CTN' => $product?->sets_ctn ?? 0,
                    'MRP' => number_format($product?->mrp ?? 0, 2),
                    'Status' => ($product?->status == '1') ? 'Active' : 'Inactive',
                    'Original Quantity' => $record->original_quantity ?? 0,
                    'Available Quantity' => $record->available_quantity ?? 0,
                    'Hold Qty' => $record->block_quantity ?? 0,
                    'Date' => $product?->created_at?->format('d-m-Y') ?? 'N/A',
                ]);
            }

            $writer->close();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'date' => $request->selectedDate,
                    'records' => $products->count(),
                ])
                ->event('report_generated')
                ->log('Inventory stock history report generated');

            $fileName = 'Inventory-Stock-History-' . date('d-m-Y', strtotime($request->selectedDate)) . '.xlsx';

            return response()->download($tempXlsxPath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating inventory report: ' . $e->getMessage());
        }
    }

    /**
     * Display customer sales history
     *
     * @return \Illuminate\View\View
     */
    public function customerSalesHistory()
    {
        try {
            $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments'])
                ->latest()
                ->paginate(15);

            $invoicesAmountSum = Invoice::sum('total_amount');

            $invoicesAmountPaidSum = DB::table('invoices')
                ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
                ->sum('payments.amount');

            $customers = Invoice::with('customer')
                ->get()
                ->pluck('customer')
                ->filter()
                ->unique('id')
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->client_name ?? 'N/A',
                    ];
                })
                ->values();

            $data = [
                'title' => 'Customer Sales History',
                'invoices' => $invoices,
                'invoicesAmountSum' => $invoicesAmountSum,
                'invoicesAmountPaidSum' => $invoicesAmountPaidSum,
                'customers' => $customers,
            ];

            return view('customer-sales-history', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving sales history: ' . $e->getMessage());
        }
    }

    /**
     * Download customer sales history as Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function customerSalesHistoryExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedDate' => 'required|date_format:Y-m-d',
            'customerId' => 'required|integer|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $tempXlsxPath = storage_path('app/customer_sales_history_' . Str::random(8) . '.xlsx');
            $writer = SimpleExcelWriter::create($tempXlsxPath);

            $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments'])
                ->when($request->selectedDate, function ($query) use ($request) {
                    $query->whereDate('invoice_date', $request->selectedDate);
                })
                ->when($request->customerId, function ($query) use ($request) {
                    $query->where('customer_id', (int)$request->customerId);
                })
                ->latest()
                ->get();

            if ($invoices->isEmpty()) {
                return redirect()->back()->with('info', 'No sales records found for the selected criteria.');
            }

            // Add header row
            $writer->addRow([
                'Reference' => 'Reference',
                'Customer Name' => 'Customer Name',
                'Ordered Date' => 'Ordered Date',
                'Total Amount' => 'Total Amount',
                'Paid' => 'Paid',
                'Due' => 'Due',
            ]);

            // Add data rows
            foreach ($invoices as $invoice) {
                $totalAmount = (float)($invoice->total_amount ?? 0);
                $paidAmount = (float)($invoice->payments?->sum('amount') ?? 0);
                $dueAmount = max(0, $totalAmount - $paidAmount);

                $writer->addRow([
                    'Reference' => $invoice->invoice_number ?? 'NA',
                    'Customer Name' => $invoice->customer?->client_name ?? 'NA',
                    'Ordered Date' => $invoice->invoice_date ? date('d-m-Y', strtotime($invoice->invoice_date)) : 'NA',
                    'Total Amount' => number_format($totalAmount, 2),
                    'Paid' => number_format($paidAmount, 2),
                    'Due' => number_format($dueAmount, 2),
                ]);
            }

            $writer->close();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'customer_id' => $request->customerId,
                    'date' => $request->selectedDate,
                    'records' => $invoices->count(),
                ])
                ->event('report_generated')
                ->log('Customer sales history report generated');

            $fileName = 'Customer-Sales-History-' . date('d-m-Y', strtotime($request->selectedDate)) . '.xlsx';

            return response()->download($tempXlsxPath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating sales report: ' . $e->getMessage());
        }
    }
}
