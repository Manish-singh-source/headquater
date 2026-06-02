<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\VendorPIProduct;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodStart = $this->parseDate($request->input('start_date'), Carbon::now()->startOfMonth())->startOfDay();
        $periodEnd = $this->parseDate($request->input('end_date'), Carbon::now()->endOfMonth())->endOfDay();

        if ($periodEnd->lt($periodStart)) {
            [$periodStart, $periodEnd] = [$periodEnd->copy()->startOfDay(), $periodStart->copy()->endOfDay()];
        }

        $startDate = $periodStart->format('Y-m-d');
        $endDate = $periodEnd->format('Y-m-d');
        $selectedBrands = $this->normalizeBrands($request->input('brands', []));

        // Get all unique brands for filter dropdown
        $allBrands = Product::whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        // 1. SALES SECTION
        $salesData = $this->getSalesData($periodStart, $periodEnd, $selectedBrands);

        // 2. PURCHASE SECTION
        $purchaseData = $this->getPurchaseData($periodStart, $periodEnd, $selectedBrands);

        // dd($purchaseData);
        // 3. ORDER STATUS SECTION
        $orderStatusData = $this->getOrderStatusData($periodStart, $periodEnd, $selectedBrands);

        // 4. DISPATCH SECTION
        $dispatchData = $this->getDispatchData($periodStart, $periodEnd, $selectedBrands);

        // dd($dispatchData);
        // 5. DELIVERY CONFIRMATION SECTION
        $deliveryData = $this->getDeliveryData($periodStart, $periodEnd, $selectedBrands);

        // 6. GRN SECTION
        $grnData = $this->getGRNData($periodStart, $periodEnd, $selectedBrands);

        // 7. PAYMENT SECTION
        $paymentData = $this->getPaymentData($periodStart, $periodEnd, $selectedBrands);

        // 8. WAREHOUSE SECTION
        $warehouseData = $this->getWarehouseData($selectedBrands);

        $user = auth()->user();
        
        // dd($warehouseData);
        return view('index', compact(
            'user',
            'allBrands',
            'selectedBrands',
            'startDate',
            'endDate',
            'salesData',
            'purchaseData',
            'orderStatusData',
            'dispatchData',
            'deliveryData',
            'grnData',
            'paymentData',
            'warehouseData'
        ));
    }

    private function parseDate($date, Carbon $fallback)
    {
        try {
            return $date ? Carbon::parse($date) : $fallback->copy();
        } catch (\Throwable $e) {
            return $fallback->copy();
        }
    }

    private function normalizeBrands($brands)
    {
        if (is_string($brands)) {
            $brands = [$brands];
        }

        if (! is_array($brands)) {
            return [];
        }

        return collect($brands)
            ->map(fn ($brand) => trim((string) $brand))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function applySalesOrderBrandFilter($query, array $selectedBrands)
    {
        if (! empty($selectedBrands)) {
            $query->whereHas('orderedProducts.product', function ($productQuery) use ($selectedBrands) {
                $productQuery->whereIn('brand', $selectedBrands);
            });
        }

        return $query;
    }

    private function applyInvoiceBrandFilter($query, array $selectedBrands)
    {
        if (! empty($selectedBrands)) {
            $query->whereHas('details.product', function ($productQuery) use ($selectedBrands) {
                $productQuery->whereIn('brand', $selectedBrands);
            });
        }

        return $query;
    }

    private function applyPaymentBrandFilter($query, array $selectedBrands)
    {
        if (! empty($selectedBrands)) {
            $query->whereHas('invoice.details.product', function ($productQuery) use ($selectedBrands) {
                $productQuery->whereIn('brand', $selectedBrands);
            });
        }

        return $query;
    }

    private function monthlyPeriods(Carbon $startDate, Carbon $endDate)
    {
        $periods = [];
        $monthStart = $startDate->copy()->startOfMonth();

        while ($monthStart->lte($endDate)) {
            $periodStart = $monthStart->lt($startDate) ? $startDate->copy() : $monthStart->copy();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $periodEnd = $monthEnd->gt($endDate) ? $endDate->copy() : $monthEnd;

            $periods[] = [
                'label' => $monthStart->format('M Y'),
                'start' => $periodStart,
                'end' => $periodEnd,
            ];

            $monthStart->addMonthNoOverflow()->startOfMonth();
        }

        return $periods;
    }

    private function getSalesData($startDate, $endDate, $selectedBrands)
    {
        $totalSalesQuery = Invoice::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('products', 'invoice_details.product_id', '=', 'products.id')
            ->whereBetween('invoices.invoice_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        $overallSalesQuery = Invoice::whereBetween('invoice_date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ]);

        if (! empty($selectedBrands)) {
            $totalSalesQuery->whereIn('products.brand', $selectedBrands);
        }

        $totalSalesByBrand = $totalSalesQuery
            ->select('products.brand', DB::raw('SUM(invoice_details.total_price) as total_sales'))
            ->groupBy('products.brand')
            ->orderByDesc(DB::raw('SUM(invoice_details.total_price)'))
            ->get();

        $monthlyTrend = [];
        foreach ($this->monthlyPeriods($startDate, $endDate) as $period) {
            $monthlySalesQuery = Invoice::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->join('products', 'invoice_details.product_id', '=', 'products.id')
                ->whereBetween('invoices.invoice_date', [$period['start']->toDateString(), $period['end']->toDateString()])
                ->whereNotNull('products.brand')
                ->where('products.brand', '!=', '');

            if (! empty($selectedBrands)) {
                $monthlySalesQuery->whereIn('products.brand', $selectedBrands);
            }

            $monthlySales = $monthlySalesQuery
                ->select('products.brand', DB::raw('SUM(invoice_details.total_price) as total_sales'))
                ->groupBy('products.brand')
                ->get();

            $monthlyTrend[] = [
                'month' => $period['label'],
                'data' => $monthlySales,
            ];
        }

        $invoicesData = Invoice::with('details.product')
            ->where('invoice_type', 'sales_order')
            ->whereBetween('invoice_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $total_sales_overall = $invoicesData->sum(function ($invoice) {
            return $invoice->details->sum('total_price');
        });

        return [
            'total_sales_by_brand' => $totalSalesByBrand,
            'monthly_trend' => $monthlyTrend,
            'total_sales_overall' => (float) $total_sales_overall,
        ];
    }

    // done
    private function getPurchaseData($startDate, $endDate, $selectedBrands)
    {
        $totalPurchasesQuery = VendorPIProduct::join('products', 'vendor_p_i_products.vendor_sku_code', '=', 'products.sku')
            ->where('vendor_p_i_products.quantity_received', '>', 0)
            ->whereBetween('vendor_p_i_products.created_at', [$startDate, $endDate])
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        if (! empty($selectedBrands)) {
            $totalPurchasesQuery->whereIn('products.brand', $selectedBrands);
        }

        $totalPurchasesQuery->select(
            'products.brand',
            \DB::raw('SUM(vendor_p_i_products.quantity_received) as total_quantity'),
            \DB::raw('SUM(vendor_p_i_products.mrp) as total_mrp'),
            \DB::raw('SUM(vendor_p_i_products.quantity_received * vendor_p_i_products.mrp) as total_cost')
        )
            ->groupBy('products.brand');

        $totalPurchasesByBrand = $totalPurchasesQuery->get();

        $monthlyTrend = [];
        foreach ($this->monthlyPeriods($startDate, $endDate) as $period) {
            $monthlyPurchasesQuery = VendorPIProduct::join('products', 'vendor_p_i_products.vendor_sku_code', '=', 'products.sku')
                ->where('vendor_p_i_products.quantity_received', '>', 0)
                ->whereBetween('vendor_p_i_products.created_at', [$period['start'], $period['end']])
                ->whereNotNull('products.brand')
                ->where('products.brand', '!=', '');

            if (! empty($selectedBrands)) {
                $monthlyPurchasesQuery->whereIn('products.brand', $selectedBrands);
            }

            $monthlyPurchasesQuery->select(
                'products.brand',
                \DB::raw('SUM(vendor_p_i_products.quantity_received) as total_quantity'),
                \DB::raw('SUM(vendor_p_i_products.mrp) as total_mrp'),
                \DB::raw('SUM(vendor_p_i_products.quantity_received * vendor_p_i_products.mrp) as total_cost')
            )
                ->groupBy('products.brand');

            $monthlyPurchases = $monthlyPurchasesQuery->get();

            $monthlyTrend[] = [
                'month' => $period['label'],
                'data' => $monthlyPurchases,
            ];
        }

        return [
            'total_purchases_by_brand' => $totalPurchasesByBrand,
            'monthly_trend' => $monthlyTrend,
            'total_amount_overall' => $totalPurchasesByBrand->sum('total_cost'),
        ];

    }

    // done
    private function getOrderStatusData($startDate, $endDate, $selectedBrands)
    {
        $ordersQuery = SalesOrder::join('sales_order_products', 'sales_orders.id', '=', 'sales_order_products.sales_order_id')
            ->join('products', 'sales_order_products.product_id', '=', 'products.id')
            ->whereBetween('sales_orders.created_at', [$startDate, $endDate])
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        if (! empty($selectedBrands)) {
            $ordersQuery->whereIn('products.brand', $selectedBrands);
        }

        $ordersByBrand = $ordersQuery
            ->select(
                'products.brand',
                DB::raw('COUNT(sales_order_products.id) as total_orders'),
                DB::raw('COUNT(CASE WHEN sales_orders.status IN ("pending", "blocked", "ready_to_package", "ready_to_ship", "shipped", "delivered") THEN sales_order_products.id END) as open_orders'),
                DB::raw('COUNT(CASE WHEN sales_orders.status IN ("completed") THEN sales_order_products.id END) as processed_orders')
            )
            ->groupBy('products.brand')
            ->get();

        return [
            'orders_by_brand' => $ordersByBrand,
            'total_orders' => $ordersByBrand->sum('total_orders'),
            'total_open' => $ordersByBrand->sum('open_orders'),
            'total_processed' => $ordersByBrand->sum('processed_orders'),
        ];
    }

    // done
    private function getDispatchData($startDate, $endDate, $selectedBrands)
    {
        // Only consider sales orders with status 'ready_to_ship'
        $orders = SalesOrder::with(['invoices.appointment'])
            ->whereBetween('created_at', [$startDate, $endDate]);
            // ->where('status', 'ready_to_ship');

        $this->applySalesOrderBrandFilter($orders, $selectedBrands);

        $orders = $orders->get();

        $lrPending = 0;
        $apptReceivedGrnPending = 0;
        $apptPending = 0;

        foreach ($orders as $order) {
            // Get all related invoices with appointments
            $invoices = $order->invoices;
            $hasAppointment = false;
            $hasGrn = false;
            $hasLR = false;
            foreach ($invoices as $invoice) {
                $appt = $invoice->appointment;
                if ($appt) {
                    if (! empty($appt->appointment_date)) {
                        $hasAppointment = true;
                        if (! empty($appt->grn)) {
                            $hasGrn = true;
                        }
                    }
                }
                // For LR Pending logic,
                // If you have an LR doc/number column (update this logic as needed):
                if (! empty($invoice->lr_number) || ! empty($invoice->lr_doc) || ! empty($invoice->lr_file)) {
                    $hasLR = true;
                }
                // If LR is stored in appointment or invoice with file, update here as well
            }
            if (! $hasAppointment) {
                $apptPending++;
            } elseif ($hasAppointment && ! $hasGrn) {
                $apptReceivedGrnPending++;
            }
            // LR Pending: if none of the invoices for this order have LR
            if (! $hasLR) {
                $lrPending++;
            }
        }

        return [
            'lr_pending' => $lrPending,
            'appt_received_grn_pending' => $apptReceivedGrnPending,
            'appt_pending' => $apptPending,
        ];
    }

    // done
    private function getDeliveryData($startDate, $endDate, $selectedBrands)
    {
        // Count POD received from appointments
        $podReceivedQuery = Invoice::whereHas('appointment', function ($query) {
            $query->whereNotNull('pod');
        })->whereBetween('created_at', [$startDate, $endDate]);

        $this->applyInvoiceBrandFilter($podReceivedQuery, $selectedBrands);

        $podReceived = $podReceivedQuery->count();

        // Count POD not received for ready to ship orders
        $totalPodReceivedQuery = SalesOrder::
            // where('status', 'ready_to_ship')
            whereBetween('created_at', [$startDate, $endDate]);

        $this->applySalesOrderBrandFilter($totalPodReceivedQuery, $selectedBrands);

        $totalPodReceived = $totalPodReceivedQuery->count();

        $podNotReceived = max(0, $totalPodReceived - $podReceived);

        return [
            'total_pod_received' => $totalPodReceived,
            'pod_received' => $podReceived,
            'pod_not_received' => $podNotReceived,
        ];
    }

    // done
    private function getGRNData($startDate, $endDate, $selectedBrands)
    {
        // Total = Sales Orders with status complete
        $totalQuery = SalesOrder::
            // where('status', 'completed')
            whereBetween('created_at', [$startDate, $endDate]);

        $this->applySalesOrderBrandFilter($totalQuery, $selectedBrands);

        $total = $totalQuery->count();

        // GRN Complete = GRN uploaded in Appointment via Invoice
        $grnDoneQuery = SalesOrder::
            // where('status', 'completed')
            whereHas('invoices.appointment', function ($query) {
                $query->whereNotNull('grn');
            })
            ->whereBetween('created_at', [$startDate, $endDate]);

        $this->applySalesOrderBrandFilter($grnDoneQuery, $selectedBrands);

        $grnDone = $grnDoneQuery->count();

        // GRN Pending = Total - GRN Complete
        $grnPending = $total - $grnDone;

        return [
            'total' => $total,
            'grn_done' => $grnDone,
            'grn_not_done' => $grnPending,
        ];
    }

    // done
    private function getPaymentData($startDate, $endDate, $selectedBrands)
    {
        $overdueCutoff = Carbon::now()->subDays(30);

        $invoiceQuery = Invoice::with(['details.product', 'payments'])
            ->where('invoice_type', 'sales_order')
            ->whereBetween('invoice_date', [$startDate->toDateString(), $endDate->toDateString()]);
        $this->applyInvoiceBrandFilter($invoiceQuery, $selectedBrands);

        $invoiceTotals = $invoiceQuery->get();

        $totalInvoiceValue = $invoiceTotals->sum(function ($invoice) {
            return (float) $invoice->details->sum('total_price');
        });

        $totalPaidValue = $invoiceTotals->sum(function ($invoice) {
            $invoiceTotal = (float) $invoice->details->sum('total_price');

            return min($invoiceTotal, (float) $invoice->payments->sum('amount'));
        });

        $totalUnpaidValue = $invoiceTotals->sum(function ($invoice) {
            $invoiceTotal = (float) $invoice->details->sum('total_price');

            return max(0, $invoiceTotal - (float) $invoice->payments->sum('amount'));
        });

        $monthlyReceivedQuery = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('invoice', function ($query) {
                $query->where('invoice_type', 'sales_order');
            });
        $this->applyPaymentBrandFilter($monthlyReceivedQuery, $selectedBrands);
        $monthlyReceived = $monthlyReceivedQuery->sum('amount');
        // $monthlyReceived = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
        //     ->where('payment_status', 'paid')
        //     ->sum('amount');

        // Payment Due Outstanding (overdue unpaid balance, including partial payments)
        $paymentDueOutstandingQuery = Invoice::with(['details.product', 'payments'])
            ->where('invoice_type', 'sales_order')
            ->where('invoice_date', '<', $overdueCutoff);
        $this->applyInvoiceBrandFilter($paymentDueOutstandingQuery, $selectedBrands);
        $paymentDueOutstanding = $paymentDueOutstandingQuery->get()->sum(function ($invoice) {
            $invoiceTotal = (float) $invoice->details->sum('total_price');

            return max(0, $invoiceTotal - $invoice->payments->sum('amount'));
        });

        $monthlyTrend = [];
        foreach ($this->monthlyPeriods($startDate, $endDate) as $period) {
            $monthlyPaymentQuery = Payment::whereBetween('created_at', [$period['start'], $period['end']])
                ->whereHas('invoice', function ($query) {
                    $query->where('invoice_type', 'sales_order');
                });
            $this->applyPaymentBrandFilter($monthlyPaymentQuery, $selectedBrands);
            $monthlyPayment = $monthlyPaymentQuery->sum('amount');
            // $monthlyPayment = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
            //     ->where('payment_status', 'paid')
            //     ->sum('amount');

            $monthlyTrend[] = [
                'month' => $period['label'],
                'amount' => $monthlyPayment,
            ];
        }

        return [
            'total_invoice_value' => $totalInvoiceValue,
            'total_paid_value' => $totalPaidValue,
            'total_unpaid_value' => $totalUnpaidValue,
            'total_outstanding' => $totalUnpaidValue,
            'monthly_received' => $monthlyReceived,
            'payment_due_outstanding' => $paymentDueOutstanding,
            'monthly_trend' => $monthlyTrend,
        ];
    }

    // done
    private function getWarehouseData($selectedBrands)
    {
        $warehouseQuery = WarehouseStock::join('products', 'warehouse_stocks.sku', '=', 'products.sku')
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        if (! empty($selectedBrands)) {
            $warehouseQuery->whereIn('products.brand', $selectedBrands);
        }

        $inventoryByBrand = $warehouseQuery
            ->select(
                'products.brand',
                DB::raw('SUM(warehouse_stocks.available_quantity) as total_units'),
                DB::raw('SUM(warehouse_stocks.available_quantity * products.mrp) as total_value'),
                DB::raw('SUM(warehouse_stocks.available_quantity * products.mrp) as total_cost')
            )
            ->groupBy('products.brand')
            ->get();

        return [
            'inventory_by_brand' => $inventoryByBrand,
            'total_units' => $inventoryByBrand->sum('total_units'),
            'total_value' => $inventoryByBrand->sum('total_value'),
            'total_cost' => $inventoryByBrand->sum('total_cost'),
        ];
    }
}
