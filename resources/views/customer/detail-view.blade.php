@extends('layouts.master')

@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('customer-sales-history') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer-sales-history') }}">Customer Sales History</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('customer-sales-history') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Sales History
                    </a>
                </div>
            </div>

            <!-- Customer Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-primary text-white flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">
                                <i class="ti ti-currency-rupee fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-white-50 mb-1">Total Sales</p>
                                <h4 class="text-white">₹{{ number_format($customerMetrics['total_sales'] ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-success text-white flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-success">
                                <i class="ti ti-file-invoice fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-white-50 mb-1">Total Invoices</p>
                                <h4 class="text-white">{{ $customerMetrics['total_invoices'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-warning text-white flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-warning">
                                <i class="ti ti-cash fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-white-50 mb-1">Pending Payments</p>
                                <h4 class="text-white">₹{{ number_format($customerMetrics['pending_payments'] ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-info text-white flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-info">
                                <i class="ti ti-package fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-white-50 mb-1">Total Products</p>
                                <h4 class="text-white">{{ number_format($customerMetrics['total_products'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Customer Information</h5>
                            <span class="badge {{ $customerDetails->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $customerDetails->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Customer Group:</strong> {{ $customerDetails->groupInfo->customerGroup->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Client Name:</strong> {{ $customerDetails->client_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Contact Name:</strong> {{ $customerDetails->contact_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Contact Number:</strong> {{ $customerDetails->contact_no ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $customerDetails->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>GSTIN:</strong> {{ $customerDetails->gstin ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>PAN:</strong> {{ $customerDetails->pan ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>GST Treatment:</strong> {{ $customerDetails->gst_treatment ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Company:</strong> {{ $customerDetails->company_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Created:</strong> {{ $customerDetails->created_at->format('d-m-Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Address Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-primary">Billing Address</h6>
                                    <p class="mb-1">{{ $customerDetails->billing_address ?? 'N/A' }}</p>
                                    <p class="mb-1">{{ $customerDetails->billing_city ?? '' }}, {{ $customerDetails->billing_state ?? '' }}</p>
                                    <p class="mb-1">{{ $customerDetails->billing_country ?? '' }} - {{ $customerDetails->billing_zip ?? '' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-success">Shipping Address</h6>
                                    <p class="mb-1">{{ $customerDetails->shipping_address ?? 'N/A' }}</p>
                                    <p class="mb-1">{{ $customerDetails->shipping_city ?? '' }}, {{ $customerDetails->shipping_state ?? '' }}</p>
                                    <p class="mb-1">{{ $customerDetails->shipping_country ?? '' }} - {{ $customerDetails->shipping_zip ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">
                                <i class="bx bx-file me-1"></i>Invoices ({{ $customerInvoices->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                                <i class="bx bx-shopping-bag me-1"></i>Sales Orders ({{ $customerOrders->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                                <i class="bx bx-money me-1"></i>Payments ({{ $customerPayments->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button" role="tab">
                                <i class="bx bx-refresh me-1"></i>Returns ({{ $customerReturns->count() }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="customerTabsContent">
                        <!-- Invoices Tab -->
                        <div class="tab-pane fade show active" id="invoices" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Invoice History</h5>
                                <div class="d-flex gap-2">
                                    <input type="text" id="invoiceSearch" class="form-control form-control-sm" placeholder="Search invoices..." style="width: 200px;">
                                    <select id="invoiceStatusFilter" class="form-select form-select-sm" style="width: 150px;">
                                        <option value="">All Status</option>
                                        <option value="paid">Paid</option>
                                        <option value="partial">Partial</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="invoicesTable" class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">{{ $invoice->invoice_number ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $invoice->sales_order_id ?? 'N/A' }}</td>
                                                <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('d-m-Y') : 'N/A' }}</td>
                                                <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
                                                <td>₹{{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                                <td>
                                                    @php
                                                        $balance = $invoice->total_amount - $invoice->payments->sum('amount');
                                                    @endphp
                                                    <span class="text-{{ $balance > 0 ? 'warning' : 'success' }} fw-semibold">
                                                        ₹{{ number_format($balance, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($balance <= 0)
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($invoice->payments->sum('amount') > 0)
                                                        <span class="badge bg-warning">Partial</span>
                                                    @else
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('invoices.view', $invoice->id) }}" target="_blank"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                        data-bs-toggle="tooltip" title="View Invoice">
                                                        <i class="bx bx-show text-primary"></i>
                                                    </a>
                                                    <a href="{{ route('invoice.downloadPdf', $invoice->id) }}" target="_blank"
                                                        class="btn btn-icon btn-sm bg-success-subtle"
                                                        data-bs-toggle="tooltip" title="Download PDF">
                                                        <i class="bx bx-download text-success"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    <i class="bx bx-file-blank fs-4 d-block mb-2"></i>
                                                    No invoices found for this customer
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sales Orders Tab -->
                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Sales Order History</h5>
                                <div class="d-flex gap-2">
                                    <input type="text" id="orderSearch" class="form-control form-control-sm" placeholder="Search orders..." style="width: 200px;">
                                    <select id="orderStatusFilter" class="form-select form-select-sm" style="width: 150px;">
                                        <option value="">All Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="ordersTable" class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Warehouse</th>
                                            <th>Status</th>
                                            <th>Total Products</th>
                                            <th>Total Value</th>
                                            <th>Order Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerOrders as $order)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">{{ $order->id }}</span>
                                                </td>
                                                <td>{{ $order->warehouse->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'shipped' ? 'info' : 'warning') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $order->orderedProducts->count() }}</td>
                                                <td>₹{{ number_format($order->orderedProducts->sum('subtotal'), 2) }}</td>
                                                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <a href="{{ route('sales.order.view', $order->id) }}" target="_blank"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                        data-bs-toggle="tooltip" title="View Order">
                                                        <i class="bx bx-show text-primary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="bx bx-shopping-bag fs-4 d-block mb-2"></i>
                                                    No sales orders found for this customer
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payments Tab -->
                        <div class="tab-pane fade" id="payments" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Payment History</h5>
                                <div class="d-flex gap-2">
                                    <input type="text" id="paymentSearch" class="form-control form-control-sm" placeholder="Search payments..." style="width: 200px;">
                                    <select id="paymentMethodFilter" class="form-select form-select-sm" style="width: 150px;">
                                        <option value="">All Methods</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="online">Online</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="paymentsTable" class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>UTR/Reference</th>
                                            <th>Status</th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerPayments as $payment)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">{{ $payment->invoice->invoice_number ?? 'N/A' }}</span>
                                                </td>
                                                <td>₹{{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
                                                <td>{{ $payment->payment_utr_no ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($payment->payment_status ?? 'pending') }}
                                                    </span>
                                                </td>
                                                <td>{{ $payment->created_at->format('d-m-Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="bx bx-money fs-4 d-block mb-2"></i>
                                                    No payment records found for this customer
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Returns Tab -->
                        <div class="tab-pane fade" id="returns" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Return History</h5>
                                <div class="d-flex gap-2">
                                    <input type="text" id="returnSearch" class="form-control form-control-sm" placeholder="Search returns..." style="width: 200px;">
                                    <select id="returnStatusFilter" class="form-select form-select-sm" style="width: 150px;">
                                        <option value="">All Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="accept">Accepted</option>
                                        <option value="on_the_way">On The Way</option>
                                        <option value="returned">Returned</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="returnsTable" class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>SKU</th>
                                            <th>Return Quantity</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerReturns as $return)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">{{ $return->sales_order_id }}</span>
                                                </td>
                                                <td>{{ $return->sku ?? 'N/A' }}</td>
                                                <td>{{ $return->return_quantity ?? 0 }}</td>
                                                <td>{{ $return->return_reason ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $return->return_status === 'completed' ? 'success' : ($return->return_status === 'pending' ? 'warning' : 'info') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $return->return_status ?? 'pending')) }}
                                                    </span>
                                                </td>
                                                <td>{{ $return->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <a href="{{ route('customer.returns.view', $return->id) }}" target="_blank"
                                                        class="btn btn-icon btn-sm bg-primary-subtle"
                                                        data-bs-toggle="tooltip" title="View Return">
                                                        <i class="bx bx-show text-primary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="bx bx-refresh fs-4 d-block mb-2"></i>
                                                    No return records found for this customer
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
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize DataTables for each tab
            const invoicesTable = $('#invoicesTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: false, // We'll use custom search
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
                order: [[2, 'desc']] // Sort by date descending
            });

            const ordersTable = $('#ordersTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: false,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
                order: [[5, 'desc']]
            });

            const paymentsTable = $('#paymentsTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: false,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
                order: [[5, 'desc']]
            });

            const returnsTable = $('#returnsTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: false,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
                order: [[5, 'desc']]
            });

            // Custom search functionality
            $('#invoiceSearch').on('keyup', function() {
                invoicesTable.search(this.value).draw();
            });

            $('#orderSearch').on('keyup', function() {
                ordersTable.search(this.value).draw();
            });

            $('#paymentSearch').on('keyup', function() {
                paymentsTable.search(this.value).draw();
            });

            $('#returnSearch').on('keyup', function() {
                returnsTable.search(this.value).draw();
            });

            // Status filtering
            $('#invoiceStatusFilter').on('change', function() {
                const status = this.value;
                if (status) {
                    invoicesTable.column(6).search(status === 'paid' ? 'Paid' : (status === 'partial' ? 'Partial' : 'Unpaid'), true, false).draw();
                } else {
                    invoicesTable.column(6).search('').draw();
                }
            });

            $('#orderStatusFilter').on('change', function() {
                const status = this.value;
                if (status) {
                    ordersTable.column(2).search(status.charAt(0).toUpperCase() + status.slice(1), true, false).draw();
                } else {
                    ordersTable.column(2).search('').draw();
                }
            });

            $('#paymentMethodFilter').on('change', function() {
                const method = this.value;
                if (method) {
                    paymentsTable.column(2).search(method.charAt(0).toUpperCase() + method.slice(1), true, false).draw();
                } else {
                    paymentsTable.column(2).search('').draw();
                }
            });

            $('#returnStatusFilter').on('change', function() {
                const status = this.value;
                if (status) {
                    returnsTable.column(4).search(status.charAt(0).toUpperCase() + status.slice(1), true, false).draw();
                } else {
                    returnsTable.column(4).search('').draw();
                }
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
