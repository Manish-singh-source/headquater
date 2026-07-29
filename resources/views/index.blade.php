@extends('layouts.master')

@section('main-content')
    <style>
        .bg-success {
            background-color: rgb(139 255 212) !important;
        }

        .bg-danger {
            background-color: rgb(255 132 168) !important;
        }

        .bg-primary {
            background-color: rgb(187 214 255) !important;
        }

        .sales-order-pagination nav {
            margin-bottom: 0;
        }

        .sales-order-pagination .pagination {
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 4px;
            margin-bottom: 0;
        }

        .sales-order-pagination .page-link {
            padding: 0.375rem 0.65rem;
            line-height: 1.25;
        }

        .sales-order-data-table th,
        .sales-order-data-table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .sales-order-data-table th {
            font-size: 0.82rem;
        }

        .sales-order-kpi-table th,
        .sales-order-kpi-table td {
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
        }

        .sales-order-kpi-table .kpi-value {
            font-size: 1.15rem;
            font-weight: 700;
        }

        .invoice-workflow-table th,
        .invoice-workflow-table td {
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
        }

        .invoice-workflow-table .summary-value {
            font-size: 1.15rem;
            font-weight: 700;
        }
    </style>

    <!--start main wrapper-->
    @if ($user && $user->hasRole(['Super Admin', 'Super Admin 2']))
        <main class="main-wrapper">
            <div class="main-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Analytics Dashboard</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Multi-Brand Analytics</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->

                <!-- Filters Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('index') }}" class="row g-3">
                                    <div class="col-md-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            value="{{ $startDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            value="{{ $endDate }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brands" class="form-label">Select Brands</label>
                                        <select class="form-select" id="brands" name="brands[]" multiple size="5">
                                            <option value="">All Brands</option>
                                            @foreach ($allBrands as $brand)
                                                <option value="{{ $brand }}"
                                                    {{ in_array($brand, $selectedBrands, true) ? 'selected' : '' }}>
                                                    {{ $brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hold Ctrl to select multiple brands.</small>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end gap-2">
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                        <a href="{{ route('index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Sales Order Count Summary Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white"
                                style="background-color: rgb(187 214 255) !important;">
                                <h5 class="mb-0"><i class="material-icons-outlined">query_stats</i> Sales Order Counts
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-bordered align-middle mb-0 sales-order-kpi-table">
                                        <thead>
                                            <tr>
                                                <th>Total Sales Orders</th>
                                                <th>Pending</th>
                                                <th>Allocation Updated</th>
                                                <th>Send To Packaging</th>
                                                <th>Packaged</th>
                                                <th>Admin Approval Pending</th>
                                                <th>Admin Approved</th>
                                                <th>Shipped</th>
                                                <th>Invoiced</th>
                                                <th>Completed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['total_sales_orders']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['pending']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['allocation_updated']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['send_to_packaging']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['packaged']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['admin_approval_pending']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['admin_approved']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['shipped']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['invoiced']) }}</span>
                                                </td>
                                                <td><span
                                                        class="kpi-value">{{ number_format($salesOrderStatusCounts['completed']) }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sales Order Data Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white"
                                style="background-color: rgb(187 214 255) !important;">
                                <h5 class="mb-0"><i class="material-icons-outlined">receipt_long</i> Sales Order Data</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-bordered align-middle mb-0 sales-order-data-table">
                                        <thead>
                                            <tr>
                                                <th>Sales Order</th>
                                                <th>PO Qty</th>
                                                <th>Allocation Qty</th>
                                                <th>Packaging Qty</th>
                                                <th>Packaged Qty</th>
                                                <th>Admin Approval Pending Qty</th>
                                                <th>Admin Approved Qty</th>
                                                <th>Shipped Qty</th>
                                                <th>Invoice Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($salesOrderData as $salesOrder)
                                                <tr>
                                                    <td>{{ $salesOrder->order_number ?? '-' }}</td>
                                                    <td>{{ number_format((float) $salesOrder->po_qty) }}</td>
                                                    <td>{{ number_format((float) $salesOrder->update_po_qty) }}</td>
                                                    <td>{{ number_format((float) $salesOrder->send_to_packaging_qty) }}
                                                    </td>
                                                    <td>{{ number_format((float) $salesOrder->packaged_qty) }}</td>
                                                    <td>{{ number_format((float) $salesOrder->admin_approval_pending_qty) }}
                                                    </td>
                                                    <td>{{ number_format((float) $salesOrder->admin_approved_qty) }}</td>
                                                    <td>{{ number_format((float) $salesOrder->shipped_qty) }}</td>
                                                    <td>{{ number_format((float) $salesOrder->invoice_qty) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sales-order-pagination mt-3">
                                    {{ $salesOrderData->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Sales Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white"
                                style="background-color: rgb(187 214 255) !important;">
                                <h5 class="mb-0"><i class="material-icons-outlined">trending_up</i> Sales Analytics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="text-muted">Total Sales</h6>
                                                <h3 class="text-success">
                                                    ₹{{ number_format($salesData['total_sales_overall'], 2) }}</h3>
                                                <small class="text-muted">Selected Period</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="mb-3">Sales Trend (Selected Period)</h6>
                                        <canvas id="salesTrendChart" height="100"></canvas>
                                    </div>
                                </div>

                                <!-- Brand-wise breakdown -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="mb-3">Sales by Brand</h6>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Brand</th>
                                                        <th>Total Sales</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($salesData['total_sales_by_brand'] as $brandSale)
                                                        <tr>
                                                            <td>{{ $brandSale->brand }}</td>
                                                            <td>₹{{ number_format($brandSale->total_sales, 2) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="text-center">No data available</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white"
                                    style="background-color: rgb(187 214 255) !important;">
                                    <h5 class="mb-0"><i class="material-icons-outlined">shopping_cart</i> Purchase
                                        Analytics
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h6 class="text-muted">Total Purchases</h6>
                                                    <h3 class="text-info">
                                                        ₹{{ number_format($purchaseData['total_amount_overall'] / 2, 2) }}
                                                    </h3>
                                                    <small class="text-muted">Selected Period</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="mb-3">Purchase Trend (Selected Period)</h6>
                                            <canvas id="purchaseTrendChart" height="100"></canvas>
                                        </div>
                                    </div>

                                    <!-- Brand-wise breakdown -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="mb-3">Purchases by Brand</h6>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Brand</th>
                                                            <th>Total Purchases</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($purchaseData['total_purchases_by_brand'] as $brandPurchase)
                                                            <tr>
                                                                <td>{{ $brandPurchase->brand }}</td>
                                                                <td>₹{{ number_format($brandPurchase->total_cost / 2, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="text-center">No data available
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Workflow Summary Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-primary text-white"
                                        style="background-color: rgb(187 214 255) !important;">
                                        <h5 class="mb-0"><i class="material-icons-outlined">receipt</i> Invoice Workflow
                                            Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-striped table-bordered align-middle mb-0 invoice-workflow-table">
                                                <thead>
                                                    <tr>
                                                        <th>Total Invoices</th>
                                                        <th>Appointment Date Added</th>
                                                        <th>POD File</th>
                                                        <th>GRN File</th>
                                                        <th>GRN Number</th>
                                                        <th>DN Details</th>
                                                        <th>Payment Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['total_invoices']) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['appointment_date_added']) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['pod']) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['grn']) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['grn_number']) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['dn_details']) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="summary-value">{{ number_format($invoiceWorkflowData['payment_details']) }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dispatch, Delivery, GRN, Payment Row -->
                        <div class="row mb-4">
                            <!-- Dispatch Section -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-secondary text-white"
                                        style="background-color: rgb(187 214 255) !important;">
                                        <h5 class="mb-0"><i class="material-icons-outlined">local_shipping</i> Appointments Date
                                            Status
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-12">
                                                <div class="card bg-primary text-white mb-0"
                                                    style="background-color: rgb(187 214 255) !important;">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Total Appointments</h6>
                                                        <h4 class="mb-0">
                                                            {{ $dispatchData['total_appointments'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-success text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Appointment Date Added</h6>
                                                        <h4 class="mb-0">
                                                            {{ $dispatchData['appointment_date_added'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-danger text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Appointment Date Pending</h6>
                                                        <h4 class="mb-0">
                                                            {{ $dispatchData['appointment_date_pending'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Confirmation Section cleanup: use only $deliveryData not dispatchData -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white"
                                        style="background-color: rgb(187 214 255) !important;">
                                        <h5 class="mb-0"><i class="material-icons-outlined">check_circle</i>
                                            POD 
                                            Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <!-- Total Delivered section removed as per requirement -->
                                            <div class="col-6">
                                                <div class="card bg-primary text-white">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Total POD</h6>
                                                        <h4 class="mb-0">{{ $deliveryData['total_pod_received'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card bg-success text-white">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">POD Received</h6>
                                                        <h4 class="mb-0">{{ $deliveryData['pod_received'] }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card bg-danger text-white">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">POD Not Received</h6>
                                                        <h4 class="mb-0">{{ $deliveryData['pod_not_received'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- 
                                                <div class="mt-3">
                                                    <canvas id="deliveryChart" height="200"></canvas>
                                                </div> 
                                                --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- GRN and Payment Row -->
                        <div class="row mb-4">
                            <!-- GRN Section -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-dark text-white"
                                        style="background-color: rgb(187 214 255) !important;">
                                        <h5 class="mb-0"><i class="material-icons-outlined">receipt</i> GRN
                                            Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-12">
                                                <div class="card bg-primary text-white mb-0">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Total GRN</h6>
                                                        <h4 class="mb-0">{{ $grnData['total'] }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-success text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">GRN File Done</h6>
                                                        <h4 class="mb-0">{{ $grnData['grn_done'] }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-danger text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">GRN File Not Done</h6>
                                                        <h4 class="mb-0">{{ $grnData['grn_not_done'] }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-success text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">GRN Number Done</h6>
                                                        <h4 class="mb-0">{{ $grnData['grn_number_done'] }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-danger text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">GRN Number Not Done</h6>
                                                        <h4 class="mb-0">{{ $grnData['grn_number_not_done'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 
                                            <div class="mt-3">
                                                <canvas id="grnChart" height="200"></canvas>
                                            </div> 
                                            --}}
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Section -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white"
                                        style="background-color: rgb(187 214 255) !important;">
                                        <h5 class="mb-0"><i class="material-icons-outlined">payments</i> Payment
                                            Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-12">
                                                <div class="card bg-primary text-white mb-0"
                                                    style="background-color: rgb(187 214 255) !important;">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Total Payment Invoices</h6>
                                                        <h4 class="mb-0">
                                                            {{ $paymentData['total_payment_invoices'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-success text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Payment Details Added</h6>
                                                        <h4 class="mb-0">
                                                            {{ $paymentData['payment_details_added'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-danger text-white mb-0 h-100">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Payment Details Pending</h6>
                                                        <h4 class="mb-0">
                                                            {{ $paymentData['payment_details_pending'] }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12 mb-2">
                                                <div class="card bg-danger text-white"
                                                    style="background-color: rgb(187 214 255) !important;">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Total Invoice Value</h6>
                                                        <h4 class="mb-0">
                                                            ₹{{ number_format($paymentData['total_invoice_value'], 2) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card bg-success text-white">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Paid Value</h6>
                                                        <h5 class="mb-0">
                                                            ₹{{ number_format($paymentData['total_paid_value'], 2) }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card bg-danger text-dark">
                                                    <div class="card-body text-center p-3">
                                                        <h6 class="mb-1">Unpaid Value</h6>
                                                        <h5 class="mb-0" id="paymentDueOutstanding">
                                                            ₹{{ number_format($paymentData['total_unpaid_value'], 2) }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- 
                                                <div class="mt-3">
                                                    <h6 class="mb-2">Payment Trend (Selected Period)</h6>
                                                    <canvas id="paymentTrendChart" height="150"></canvas>
                                                </div> 
                                                --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warehouse Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white"
                                        style="background-color: rgb(187 214 255) !important;">
                                        <h5 class="mb-0"><i class="material-icons-outlined">warehouse</i>
                                            Warehouse Inventory
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <h6 class="text-muted">Total Inventory Units</h6>
                                                        <h3 class="text-primary">
                                                            <a href="{{ route('products.index') }}"
                                                                style="color: inherit; text-decoration: none">
                                                                {{ number_format($warehouseData['total_units']) }}
                                                            </a>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- 
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <h6 class="text-muted">Total Inventory Value</h6>
                                                        <h3 class="text-success">
                                                            <a href="{{ route('products.index') }}"
                                                                style="color: inherit; text-decoration: none">
                                                                ₹{{ number_format($warehouseData['total_cost'], 2) }}
                                                            </a>
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div> 
                                            --}}
                                        </div>

                                        <!-- Brand-wise breakdown -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="mb-3">Inventory by Brand</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-striped" id="example">
                                                        <thead>
                                                            <tr>
                                                                <th>Brand</th>
                                                                <th>Inventory Units</th>
                                                                {{-- <th>Inventory Value</th>
                                                                <th>Inventory Cost</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($warehouseData['inventory_by_brand'] as $brandInventory)
                                                                <tr>
                                                                    <td>{{ $brandInventory->brand }}</td>
                                                                    <td>
                                                                        <a href="{{ route('products.index') }}?brand={{ urlencode($brandInventory->brand) }}"
                                                                            class="filter-brand-link">
                                                                            {{ number_format($brandInventory->total_units) }}
                                                                        </a>
                                                                    </td>
                                                                    {{-- <td>₹{{ number_format((float) $brandInventory->total_value, 2) }}</td>
                                                                    <td>₹{{ number_format((float) $brandInventory->total_cost, 2) }}</td> --}}
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No
                                                                        data available</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--end main wrapper-->
    @else
        <main class="main-wrapper">
            <div class="main-content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="mb-2">Welcome {{ ucfirst($user->user_name) ?? ucfirst($user->fname) }}</h3>
                                <p class="text-muted">You do not have permission to view the analytics dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--end main wrapper-->
    @endif

@endsection

@section('script')
    @if ($user && $user->hasRole(['Super Admin', 'Super Admin 2']))
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Sales Trend Chart
            const salesTrendCtx = document.getElementById('salesTrendChart')?.getContext('2d');
            const salesTrendData = @json($salesData['monthly_trend']);

            // Prepare data for sales trend chart
            const salesMonths = salesTrendData.map(item => item.month);
            const salesBrands = [...new Set(salesTrendData.flatMap(item => item.data.map(d => d.brand)))];

            const salesDatasets = salesBrands.map((brand, index) => {
                const colors = ['#91f0ff', '#6bff8d', '#ffc107', '#ffdf7f', '#7dbcff', '#ae82ff'];
                return {
                    label: brand,
                    data: salesTrendData.map(month => {
                        const brandData = month.data.find(d => d.brand === brand);
                        return brandData ? brandData.total_sales : 0;
                    }),
                    borderColor: colors[index % colors.length],
                    backgroundColor: colors[index % colors.length] + '20',
                    tension: 0.4,
                    fill: true
                };
            });

            if (salesTrendCtx) {
                new Chart(salesTrendCtx, {
                    type: 'line',
                    data: {
                        labels: salesMonths,
                        datasets: salesDatasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ₹' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Purchase Trend Chart
            const purchaseTrendCtx = document.getElementById('purchaseTrendChart')?.getContext('2d');
            const purchaseTrendData = @json($purchaseData['monthly_trend']);

            const purchaseMonths = purchaseTrendData.map(item => item.month);
            const purchaseBrands = [...new Set(purchaseTrendData.flatMap(item => item.data.map(d => d.brand)))];

            const purchaseDatasets = purchaseBrands.map((brand, index) => {
                const colors = ['#91f0ff', '#6bff8d', '#ffc107', '#ffdf7f', '#7dbcff', '#ae82ff'];
                return {
                    label: brand,
                    data: purchaseTrendData.map(month => {
                        const brandData = month.data.find(d => d.brand === brand);
                        return brandData ? (brandData.total_cost / 2) : 0;
                    }),
                    borderColor: colors[index % colors.length],
                    backgroundColor: colors[index % colors.length] + '20',
                    tension: 0.4,
                    fill: true
                };
            });

            if (purchaseTrendCtx) {
                new Chart(purchaseTrendCtx, {
                    type: 'line',
                    data: {
                        labels: purchaseMonths,
                        datasets: purchaseDatasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ₹' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Dispatch Chart (Pie)
            const dispatchCtx = document.getElementById('dispatchChart')?.getContext('2d');
            const dispatchData = @json($dispatchData);

            if (dispatchCtx) {
                new Chart(dispatchCtx, {
                    type: 'pie',
                    data: {
                        labels: ['LR Pending', 'Appointment Rec. & GRN Pending', 'Appointment Pending'],
                        datasets: [{
                            data: [dispatchData.lr_pending, dispatchData.appt_received_grn_pending, dispatchData
                                .appt_pending
                            ],
                            backgroundColor: ['#17a2b8', '#ffc107', '#dc3545'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(2) :
                                            0;
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Delivery Chart (Donut)
            const deliveryCtx = document.getElementById('deliveryChart')?.getContext('2d');
            const deliveryData = @json($deliveryData);

            if (deliveryCtx) {
                new Chart(deliveryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['POD Received', 'POD Not Received'],
                        datasets: [{
                            data: [deliveryData.pod_received, deliveryData.pod_not_received],
                            backgroundColor: ['#8bffd4', '#ff84a8'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(2) :
                                            0;
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // GRN Chart (Bar)
            const grnCtx = document.getElementById('grnChart')?.getContext('2d');
            const grnData = @json($grnData);

            if (grnCtx) {
                new Chart(grnCtx, {
                    type: 'bar',
                    data: {
                        labels: ['GRN Done', 'GRN Not Done'],
                        datasets: [{
                            label: 'Count',
                            data: [grnData.grn_done, grnData.grn_not_done],
                            backgroundColor: ['#8bffd4', '#ff84a8'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed.x;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Payment Trend Chart
            const paymentTrendCtx = document.getElementById('paymentTrendChart')?.getContext('2d');
            const paymentTrendData = @json($paymentData['monthly_trend']);

            if (paymentTrendCtx) {
                new Chart(paymentTrendCtx, {
                    type: 'line',
                    data: {
                        labels: paymentTrendData.map(item => item.month),
                        datasets: [{
                            label: 'Payment Received',
                            data: paymentTrendData.map(item => item.amount),
                            borderColor: '#8bffd4',
                            backgroundColor: '#8bffd4',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Payment: ₹' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endif
@endsection

