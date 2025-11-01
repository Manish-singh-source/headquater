<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PurchaseGrn;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Models\VendorPIProduct;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $selectedBrands = $request->input('brands', []);

        // dd($selectedBrands);

        // Get all unique brands for filter dropdown
        $allBrands = Product::whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        // 1. SALES SECTION
        $salesData = $this->getSalesData($startDate, $endDate, $selectedBrands);

        // 2. PURCHASE SECTION
        $purchaseData = $this->getPurchaseData($startDate, $endDate, $selectedBrands);

        // dd($purchaseData);
        // 3. ORDER STATUS SECTION
        $orderStatusData = $this->getOrderStatusData($startDate, $endDate, $selectedBrands);

        // 4. DISPATCH SECTION
        $dispatchData = $this->getDispatchData($startDate, $endDate, $selectedBrands);

        // dd($dispatchData);
        // 5. DELIVERY CONFIRMATION SECTION
        $deliveryData = $this->getDeliveryData($startDate, $endDate, $selectedBrands);

        // 6. GRN SECTION
        $grnData = $this->getGRNData($startDate, $endDate, $selectedBrands);

        // 7. PAYMENT SECTION
        $paymentData = $this->getPaymentData($startDate, $endDate, $selectedBrands);

        // 8. WAREHOUSE SECTION
        $warehouseData = $this->getWarehouseData($selectedBrands);

        // dd($warehouseData);
        return view('index', compact(
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

    // done
    private function getSalesData($startDate, $endDate, $selectedBrands)
    {
        // Total Sales Till Date (current year)
        $yearStart = Carbon::now()->startOfYear();

        $totalSalesQuery = Invoice::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('products', 'invoice_details.product_id', '=', 'products.id')
            ->where('invoices.invoice_date', '>=', $yearStart)
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        if (!empty($selectedBrands)) {
            $totalSalesQuery->where('products.brand', $selectedBrands);
        }

        $totalSalesByBrand = $totalSalesQuery
            ->select('products.brand', DB::raw('SUM(invoice_details.total_price) as total_sales'))
            ->groupBy('products.brand')
            ->get();

        // Monthly Sales Trend (last 3 months + current month)
        $monthlyTrend = [];
        for ($i = 3; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlySalesQuery = Invoice::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->join('products', 'invoice_details.product_id', '=', 'products.id')
                ->whereBetween('invoices.invoice_date', [$monthStart, $monthEnd])
                ->whereNotNull('products.brand')
                ->where('products.brand', '!=', '');

            if (!empty($selectedBrands)) {
                $monthlySalesQuery->where('products.brand', $selectedBrands);
            }

            $monthlySales = $monthlySalesQuery
                ->select('products.brand', DB::raw('SUM(invoice_details.total_price) as total_sales'))
                ->groupBy('products.brand')
                ->get();

            $monthlyTrend[] = [
                'month' => $monthStart->format('M Y'),
                'data' => $monthlySales
            ];
        }

        return [
            'total_sales_by_brand' => $totalSalesByBrand,
            'monthly_trend' => $monthlyTrend,
            'total_sales_overall' => $totalSalesByBrand->sum('total_sales')
        ];
    }

    // done
    private function getPurchaseData($startDate, $endDate, $selectedBrands)
    {
        // Total Purchases Till Date (current year)
        $yearStart = Carbon::now()->startOfYear();

        $totalPurchasesQuery = VendorPIProduct::join('products', 'vendor_p_i_products.vendor_sku_code', '=', 'products.sku')
            ->where('vendor_p_i_products.quantity_received', '>', 0)
            ->where('vendor_p_i_products.created_at', '>=', $yearStart);

        if (!empty($selectedBrands)) {
            $totalPurchasesQuery->whereIn('products.brand', $selectedBrands); // use whereIn for multiple brands
        }

        $totalPurchasesQuery->select(
            'products.brand',
            \DB::raw('SUM(vendor_p_i_products.quantity_received) as total_quantity'),
            \DB::raw('SUM(vendor_p_i_products.mrp) as total_mrp'),
            \DB::raw('SUM(vendor_p_i_products.quantity_received * vendor_p_i_products.mrp) as total_cost')
        )
            ->groupBy('products.brand');

        $totalPurchasesByBrand = $totalPurchasesQuery->get();


        // Monthly Purchase Trend (last 3 months + current month)
        $monthlyTrend = [];
        for ($i = 3; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlyPurchasesQuery = VendorPIProduct::join('products', 'vendor_p_i_products.vendor_sku_code', '=', 'products.sku')
                ->where('vendor_p_i_products.quantity_received', '>', 0)
                ->whereBetween('vendor_p_i_products.created_at', [$monthStart, $monthEnd]);

            if (!empty($selectedBrands)) {
                $monthlyPurchasesQuery->whereIn('products.brand', $selectedBrands); // use whereIn for multiple brands
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
                'month' => $monthStart->format('M Y'),
                'data' => $monthlyPurchases
            ];
        }

        return [
            'total_purchases_by_brand' => $totalPurchasesByBrand,
            'monthly_trend' => $monthlyTrend,
            'total_amount_overall' => $totalPurchasesByBrand->sum('total_cost')
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

        if (!empty($selectedBrands)) {
            $ordersQuery->where('products.brand', $selectedBrands);
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
            'total_processed' => $ordersByBrand->sum('processed_orders')
        ];
    }

    // done
    private function getDispatchData($startDate, $endDate, $selectedBrands)
    {
        // Only consider sales orders with status 'ready_to_ship'
        $orders = SalesOrder::with(['invoices.appointment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'ready_to_ship')
            ->get();

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
                    if (!empty($appt->appointment_date)) {
                        $hasAppointment = true;
                        if (!empty($appt->grn)) {
                            $hasGrn = true;
                        }
                    }
                }
                // For LR Pending logic,
                // If you have an LR doc/number column (update this logic as needed):
                if (property_exists($invoice, 'lr_number')) {
                    if (!empty($invoice->lr_number)) {
                        $hasLR = true;
                    }
                }
                // If LR is stored in appointment or invoice with file, update here as well
            }
            if (!$hasAppointment) {
                $apptPending++;
            } else if ($hasAppointment && !$hasGrn) {
                $apptReceivedGrnPending++;
            }
            // LR Pending: if none of the invoices for this order have LR
            if (!$hasLR) {  
                $lrPending++;
            }
        }
        return [
            'lr_pending' => $lrPending,
            'appt_received_grn_pending' => $apptReceivedGrnPending,
            'appt_pending' => $apptPending
        ];
    }

    // done
    private function getDeliveryData($startDate, $endDate, $selectedBrands)
    {
        // Count POD received from appointments
        $podReceived = Invoice::whereHas('appointment', function($query) {
            $query->whereNotNull('pod');
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        // Count POD not received for ready to ship orders
        $podNotReceived = SalesOrder::where('status', 'ready_to_ship')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'pod_received' => $podReceived,
            'pod_not_received' => $podNotReceived
        ];
    }

    // done
    private function getGRNData($startDate, $endDate, $selectedBrands)
    {
        // Total = Sales Orders with status complete
        $total = SalesOrder::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // GRN Complete = GRN uploaded in Appointment via Invoice
        $grnDone = SalesOrder::where('status', 'completed')
            ->whereHas('invoices.appointment', function ($query) {
                $query->whereNotNull('grn');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // GRN Pending = Total - GRN Complete
        $grnPending = $total - $grnDone;

        return [
            'total' => $total,
            'grn_done' => $grnDone,
            'grn_not_done' => $grnPending
        ];
    }

    // done
    private function getPaymentData($startDate, $endDate, $selectedBrands)
    {
        // Total Payment Outstanding (all unpaid invoices)
        $totalOutstanding = Invoice::whereDoesntHave('payments')->sum('total_amount');
        // $totalOutstanding = Invoice::whereDoesntHave('payments', function ($query) {
        //     $query->where('payment_status', 'paid');
        // })->sum('total_amount');

        // Monthly Payment Received (current month)
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthlyReceived = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');
        // $monthlyReceived = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
        //     ->where('payment_status', 'paid')
        //     ->sum('amount');

        // Payment Due Outstanding (overdue payments)
        $paymentDueOutstanding = Invoice::where('invoice_date', '<', Carbon::now()->subDays(30))
            ->whereDoesntHave('payments')->sum('total_amount');
        // $paymentDueOutstanding = Invoice::where('invoice_date', '<', Carbon::now()->subDays(30))
        //     ->whereDoesntHave('payments', function ($query) {
        //         $query->where('payment_status', 'paid');
        //     })->sum('total_amount');

        // Monthly Payment Trend (last 3 months + current month)
        $monthlyTrend = [];
        for ($i = 3; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlyPayment = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');
            // $monthlyPayment = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
            //     ->where('payment_status', 'paid')
            //     ->sum('amount');

            $monthlyTrend[] = [
                'month' => $monthStart->format('M Y'),
                'amount' => $monthlyPayment
            ];
        }

        return [
            'total_outstanding' => $totalOutstanding,
            'monthly_received' => $monthlyReceived,
            'payment_due_outstanding' => $paymentDueOutstanding,
            'monthly_trend' => $monthlyTrend
        ];
    }

    // done
    private function getWarehouseData($selectedBrands)
    {
        $warehouseQuery = WarehouseStock::join('products', 'warehouse_stocks.sku', '=', 'products.sku')
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        if (!empty($selectedBrands)) {
            $warehouseQuery->where('products.brand', $selectedBrands);
        }

        $inventoryByBrand = $warehouseQuery
            ->select(
                'products.brand',
                DB::raw('SUM(warehouse_stocks.available_quantity) as total_units'),
                DB::raw('SUM(warehouse_stocks.available_quantity * products.mrp) as total_value'),
                DB::raw('SUM(warehouse_stocks.available_quantity * products.mrp * warehouse_stocks.available_quantity) as total_cost')
            )
            ->groupBy('products.brand')
            ->get();

        return [
            'inventory_by_brand' => $inventoryByBrand,
            'total_units' => $inventoryByBrand->sum('total_units'),
            'total_value' => $inventoryByBrand->sum('total_value'),
            'total_cost' => $inventoryByBrand->sum('total_cost')
        ];
    }
}
