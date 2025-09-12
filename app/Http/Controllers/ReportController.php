<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\VendorPI;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\VendorPIProduct;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    //
    public function vendorPurchaseHistory()
    {
        $purchaseOrdersTotal = VendorPIProduct::sum('mrp');
        $purchaseOrdersTotalQuantity = VendorPIProduct::sum('quantity_received');

        $purchaseOrders = VendorPI::with('products')->where('status', 'completed')->get();
        $purchaseOrdersVendors = VendorPI::where('status', 'completed')->pluck('vendor_code', 'vendor_code');
        // dd($purchaseOrders);
        return view('vendor-purchase-history', compact('purchaseOrders', 'purchaseOrdersTotal', 'purchaseOrdersTotalQuantity', 'purchaseOrdersVendors'));
    }

    public function vendorPurchaseHistoryExcel(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'selectedDate' => 'required|date',
            'vendorCode' => 'required',
        ]);


        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/vendor_purchase_history_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $VendorReport = VendorPI::with('products')
            ->where('status', 'completed')
            ->when($request->vendorCode, function ($query) use ($request) {
                $query->where('vendor_code', $request->vendorCode);
            })
            ->when($request->selectedDate, function ($query) use ($request) {
                $query->whereDate('created_at', $request->selectedDate);
            })
            ->get();


        // Add rows
        foreach ($VendorReport as $record) {
            $writer->addRow([
                'Order Id' => $record->purchase_order_id,
                'Vendor Name' => $record->vendor_code ?? 'NA',
                'Ordered Status' => ucfirst($record->status),
                'Ordered Quantity' => $record->products->sum('quantity_requirement'),
                'Received Quantity' => $record->products->sum('quantity_received'),
                'Total Amount' => $record->products->sum('mrp'),
                'Paid' => $record->products->sum('paid_amount') ?? '0',
                'Due' => $record->products->sum('due_amount') ?? '0',
                'Ordered Date' => $record->created_at?->format('d-m-Y') ?? 'NA',
            ]);
        }

        // Close the writer
        $writer->close();
        if ($request->vendorCode) {
            $fileName = 'Vendor-Purchase-History-' . $request->vendorCode . '.xlsx';
        } else {
            $fileName = 'Vendor-Purchase-History.xlsx';
        }

        return response()->download($tempXlsxPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function inventoryStockHistory()
    {
        $products = WarehouseStock::with('product', 'warehouse')->get();
        $productsSum = WarehouseStock::sum('original_quantity');
        $blockProductsSum = WarehouseStock::sum('block_quantity');
        return view('inventory-stock-history', compact('products', 'productsSum', 'blockProductsSum'));
    }

    public function inventoryStockHistoryExcel(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'selectedDate' => 'required|date',
        ]);

        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/inventory_stock_history_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $products = WarehouseStock::with('product', 'warehouse')
            ->when($request->selectedDate, function ($query) use ($request) {
                $query->whereDate('created_at', $request->selectedDate);
            })
            ->get();

        // Add rows
        foreach ($products as $record) {
            $writer->addRow([
                'Brand' => $record->product->brand ?? '',
                'Brand Title' => $record->product->brand_title ?? '',
                'Category' => $record->product->category ?? '',
                'SKU' => $record->product->sku ?? '',
                'PCS/Set' => $record->product->pcs_set ?? '',
                'Sets/CTN' => $record->product->sets_ctn ?? '',
                'MRP' => $record->product->mrp ?? '',
                'po status' => $record->product->status == '1' ? 'Active' : 'Inactive',
                'Original Quantity' => $record->original_quantity ?? '',
                'Available Quantity' => $record->available_quantity ?? '',
                'Hold Qty' => $record->block_quantity ?? '',
                'Date' => $record->product->created_at->format('d-m-Y') ?? '',
            ]);
        }

        // Close the writer
        $writer->close();

        $fileName = 'Inventory-Stock-History.xlsx';

        return response()->download($tempXlsxPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function customerSalesHistory()
    {
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments'])->get(),
            'invoicesAmountSum' => Invoice::sum('total_amount'),
            'invoicesAmountPaidSum' => Invoice::with('payments')->get()->sum(function ($invoice) {
                return $invoice->payments->sum('amount');
            }),
            'customers' => Invoice::with('customer')
                ->get()
                ->pluck('customer') // get the customer relation
                ->filter()           // remove nulls
                ->unique('id')       // get distinct by id
                ->values()           // reset keys
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->client_name,
                    ];
                })
        ];
        return view('customer-sales-history', $data);
    }


    public function customerSalesHistoryExcel(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'selectedDate' => 'required|date',
            'customerId' => 'required',
        ]);

        if ($validated->failed()) {
            return back()->with('error', 'Please Try Again.');
        }

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/customer_sales_history_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Fetch data with relationships
        $data = [
            'title' => 'Invoices',
            'invoices' => Invoice::with(['warehouse', 'customer', 'salesOrder', 'payments'])->get(),
            'invoicesAmountSum' => Invoice::sum('total_amount'),
            'invoicesAmountPaidSum' => Invoice::with('payments')->get()->sum(function ($invoice) {
                return $invoice->payments->sum('amount');
            }),
            'customers' => Invoice::with('customer')->get()->map(function ($invoice) {
                return $invoice->customer->client_name ?? null;
            })
        ];


        // Add rows
        foreach ($data['invoices'] as $invoice) {
            $writer->addRow([
                'Reference' => $invoice->invoice_number ?? 'NA',
                'Customer Name' => $invoice->customer->client_name ?? 'NA',
                'Ordered Date' => $invoice->invoice_date ?? 'NA',
                'Total Amount' => number_format($invoice->total_amount, 2) ?? '0',
                'Paid' => number_format($invoice->payments?->sum('amount'), 2) ?? '0',
                'Due' => number_format($invoice->total_amount - $invoice->payments?->sum('amount'), 2) ?? '0',
            ]);
        }

        // Close the writer
        $writer->close();

        $fileName = 'Customer-Sales-History.xlsx';

        return response()->download($tempXlsxPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
