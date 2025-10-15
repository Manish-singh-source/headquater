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

        // 5. DELIVERY CONFIRMATION SECTION
        $deliveryData = $this->getDeliveryData($startDate, $endDate, $selectedBrands);

        // 6. GRN SECTION
        $grnData = $this->getGRNData($startDate, $endDate, $selectedBrands);

        // 7. PAYMENT SECTION
        $paymentData = $this->getPaymentData($startDate, $endDate, $selectedBrands);

        // 8. WAREHOUSE SECTION
        $warehouseData = $this->getWarehouseData($selectedBrands);

        // dd($warehouseData);
        return view('analytics-dashboard', compact(
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

    // 
    private function getPurchaseData($startDate, $endDate, $selectedBrands)
    {
        // Total Purchases Till Date (current year)
        $yearStart = Carbon::now()->startOfYear();

        $totalPurchasesQuery = PurchaseOrder::join('purchase_order_products', 'purchase_orders.id', '=', 'purchase_order_products.purchase_order_id')
            ->join('vendor_p_i_s', 'purchase_orders.id', '=', 'vendor_p_i_s.purchase_order_id')
            ->join('vendor_p_i_products', 'purchase_orders.id', '=', 'vendor_p_i_products.purchase_order_id')
            ->join('products', 'purchase_order_products.product_id', '=', 'products.id')
            ->where('purchase_orders.created_at', '>=', $yearStart)
            ->whereNotNull('products.brand')
            ->where('products.brand', '!=', '');

        if (!empty($selectedBrands)) {
            $totalPurchasesQuery->where('products.brand', $selectedBrands);
        }

        $totalPurchasesByBrand = $totalPurchasesQuery
            ->select(
                'products.brand',
                DB::raw('SUM(vendor_p_i_products.quantity_received * vendor_p_i_products.mrp) as total_purchases'),
                DB::raw('SUM(vendor_p_i_s.total_paid_amount) as total_paid'),
                DB::raw('SUM(vendor_p_i_s.total_due_amount) as total_due')
            )
            ->groupBy('products.brand')
            ->get();

        // Monthly Purchase Trend (last 3 months + current month)
        $monthlyTrend = [];
        for ($i = 3; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlyPurchasesQuery = PurchaseOrder::join('purchase_order_products', 'purchase_orders.id', '=', 'purchase_order_products.purchase_order_id')
                ->join('vendor_p_i_s', 'purchase_orders.id', '=', 'vendor_p_i_s.purchase_order_id')
                ->join('vendor_p_i_products', 'purchase_orders.id', '=', 'vendor_p_i_products.purchase_order_id')
                ->join('products', 'purchase_order_products.product_id', '=', 'products.id')
                ->whereBetween('purchase_orders.created_at', [$monthStart, $monthEnd])
                ->whereNotNull('products.brand')
                ->where('products.brand', '!=', '');

            if (!empty($selectedBrands)) {
                $monthlyPurchasesQuery->where('products.brand', $selectedBrands);
            }

            $monthlyPurchases = $monthlyPurchasesQuery
                ->select(
                    'products.brand',
                    DB::raw('SUM(vendor_p_i_products.quantity_received * vendor_p_i_products.mrp) as total_purchases'),
                    DB::raw('SUM(vendor_p_i_s.total_paid_amount) as total_paid'),
                    DB::raw('SUM(vendor_p_i_s.total_due_amount) as total_due')
                )
                ->groupBy('products.brand')
                ->get();

            $monthlyTrend[] = [
                'month' => $monthStart->format('M Y'),
                'data' => $monthlyPurchases
            ];
        }

        return [
            'total_purchases_by_brand' => $totalPurchasesByBrand,
            'monthly_trend' => $monthlyTrend,
            'total_purchases_overall' => $totalPurchasesByBrand->sum('total_purchases'),
            'total_paid_overall' => $totalPurchasesByBrand->sum('total_paid'),
            'total_due_overall' => $totalPurchasesByBrand->sum('total_due')
        ];
    }

    // 
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
                DB::raw('COUNT(DISTINCT sales_orders.id) as total_orders'),
                DB::raw('COUNT(DISTINCT CASE WHEN sales_orders.status IN ("pending", "blocked", "ready_to_package", "ready_to_ship", "shipped", "delivered") THEN sales_orders.id END) as open_orders'),
                DB::raw('COUNT(DISTINCT CASE WHEN sales_orders.status IN ("completed") THEN sales_orders.id END) as processed_orders')
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

    private function getDispatchData($startDate, $endDate, $selectedBrands)
    {
        // LR Pending, Appointments Received but GRN Pending, Appointments Pending
        $invoicesQuery = Invoice::whereBetween('invoice_date', [$startDate, $endDate]);

        $totalInvoices = $invoicesQuery->count();

        // Appointments Received but GRN Pending
        $appointmentsReceivedGrnPending = Appointment::whereNotNull('appointment_date')
            ->whereNull('grn')
            ->count();

        // Appointments Pending (no appointment date set)
        $appointmentsPending = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->whereDoesntHave('appointment')
            ->count();

        // LR Pending - assuming invoices without appointments are LR pending
        $lrPending = $appointmentsPending;

        // Completed Dispatches
        $completedDispatches = Appointment::whereNotNull('appointment_date')
            ->whereNotNull('grn')
            ->count();

        return [
            'lr_pending' => $lrPending,
            'appointments_received_grn_pending' => $appointmentsReceivedGrnPending,
            'appointments_pending' => $appointmentsPending,
            'completed_dispatches' => $completedDispatches,
            'total_dispatches' => $totalInvoices
        ];
    }

    private function getDeliveryData($startDate, $endDate, $selectedBrands)
    {
        $appointmentsQuery = Appointment::join('invoices', 'appointments.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.invoice_date', [$startDate, $endDate]);

        $podReceived = (clone $appointmentsQuery)->whereNotNull('appointments.pod')->count();
        $podNotReceived = (clone $appointmentsQuery)->whereNull('appointments.pod')->count();

        return [
            'pod_received' => $podReceived,
            'pod_not_received' => $podNotReceived,
            'total' => $podReceived + $podNotReceived
        ];
    }

    private function getGRNData($startDate, $endDate, $selectedBrands)
    {
        $appointmentsQuery = Appointment::join('invoices', 'appointments.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.invoice_date', [$startDate, $endDate]);

        $grnDone = (clone $appointmentsQuery)->whereNotNull('appointments.grn')->count();
        $grnNotDone = (clone $appointmentsQuery)->whereNull('appointments.grn')->count();

        return [
            'grn_done' => $grnDone,
            'grn_not_done' => $grnNotDone,
            'total' => $grnDone + $grnNotDone
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
