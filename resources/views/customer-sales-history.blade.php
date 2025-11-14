@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <!-- Breadcrumb Navigation -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Sales History</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bx bx-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-user fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Customers</p>
                                    <h4 class="mb-0 fw-bold">{{ $customerAggregates->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-dollar-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Revenue</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalRevenue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-time-five fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Pending Payments</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalPendingPayments, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-trophy fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Top Customer</p>
                                    <h6 class="mb-0 fw-bold">{{ $topCustomer ? $topCustomer['customer']->client_name : 'N/A' }}</h6>
                                    <small class="text-muted">₹{{ $topCustomer ? number_format($topCustomer['total_sales_amount'], 2) : '0.00' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('customer-sales-history') }}" id="filterForm">
                        <div class="row align-items-end">
                            <div class="col-lg-12">
                                <div class="row">
                                    <!-- From Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date"
                                                value="{{ $filters['from_date'] ?? '' }}" placeholder="Select from date">
                                        </div>
                                    </div>

                                    <!-- To Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date"
                                                value="{{ $filters['to_date'] ?? '' }}" placeholder="Select to date">
                                        </div>
                                    </div>

                                    <!-- Customer Name Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Name</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="customerDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    @if($filters['customer_id'] ?? false)
                                                        {{ collect($customers)->where('id', $filters['customer_id'])->first()['name'] ?? 'Select Customer' }}
                                                    @else
                                                        Select Customer
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu w-100" id="customerCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input customer-checkbox"
                                                                type="radio" name="customer_id"
                                                                value=""
                                                                id="customer_all"
                                                                {{ !($filters['customer_id'] ?? false) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="customer_all">
                                                                All Customers
                                                            </label>
                                                        </div>
                                                    </li>
                                                    @foreach ($customers as $customer)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input customer-checkbox"
                                                                    type="radio" name="customer_id"
                                                                    value="{{ $customer['id'] }}"
                                                                    id="customer_{{ $loop->index }}"
                                                                    {{ ($filters['customer_id'] ?? '') == $customer['id'] ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="customer_{{ $loop->index }}">
                                                                    {{ $customer['name'] }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Region Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Region</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="regionDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    @if($filters['region'] ?? false)
                                                        {{ $filters['region'] }}
                                                    @else
                                                        Select Region
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu w-100" id="regionCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input region-checkbox"
                                                                type="radio" name="region"
                                                                value=""
                                                                id="region_all"
                                                                {{ !($filters['region'] ?? false) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="region_all">
                                                                All Regions
                                                            </label>
                                                        </div>
                                                    </li>
                                                    @foreach ($regions as $region)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input region-checkbox"
                                                                    type="radio" name="region"
                                                                    value="{{ $region }}"
                                                                    id="region_{{ $loop->index }}"
                                                                    {{ ($filters['region'] ?? '') == $region ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="region_{{ $loop->index }}">
                                                                    {{ $region }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Status Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Status</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="paymentStatusDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    @if($filters['payment_status'] ?? false)
                                                        {{ ucfirst($filters['payment_status']) }}
                                                    @else
                                                        Select Status
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu w-100" id="paymentStatusCheckboxList">
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="radio" name="payment_status"
                                                                value=""
                                                                id="payment_status_all"
                                                                {{ !($filters['payment_status'] ?? false) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_all">
                                                                All Status
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="radio" name="payment_status"
                                                                value="paid"
                                                                id="payment_status_paid"
                                                                {{ ($filters['payment_status'] ?? '') == 'paid' ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_paid">
                                                                Paid
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="radio" name="payment_status"
                                                                value="partial"
                                                                id="payment_status_partial"
                                                                {{ ($filters['payment_status'] ?? '') == 'partial' ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_partial">
                                                                Partial
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="radio" name="payment_status"
                                                                value="unpaid"
                                                                id="payment_status_unpaid"
                                                                {{ ($filters['payment_status'] ?? '') == 'unpaid' ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_unpaid">
                                                                Unpaid
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Type Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Type</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="customerTypeDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    @if($filters['customer_type'] ?? false)
                                                        {{ collect($customerGroups)->where('id', $filters['customer_type'])->first()->name ?? 'Select Type' }}
                                                    @else
                                                        Select Type
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu w-100" id="customerTypeCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input customer-type-checkbox"
                                                                type="radio" name="customer_type"
                                                                value=""
                                                                id="customer_type_all"
                                                                {{ !($filters['customer_type'] ?? false) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="customer_type_all">
                                                                All Types
                                                            </label>
                                                        </div>
                                                    </li>
                                                    @foreach ($customerGroups as $group)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input customer-type-checkbox"
                                                                    type="radio" name="customer_type"
                                                                    value="{{ $group->id }}"
                                                                    id="customer_type_{{ $loop->index }}"
                                                                    {{ ($filters['customer_type'] ?? '') == $group->id ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="customer_type_{{ $loop->index }}">
                                                                    {{ $group->name }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Apply Filter Button -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <button type="submit" id="filterData" class="btn btn-primary w-100">
                                                <i class="bx bx-filter-alt me-1"></i>Apply Filter
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Reset Filter Button -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <button type="button" id="resetFilters" class="btn btn-secondary w-100">
                                                <i class="bx bx-reset me-1"></i>Reset Filters
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-md-8">
                                        <div class="d-flex gap-2">
                                            <!-- Generate Excel Report Button -->
                                            <button type="button" id="generateExcelReport" class="btn btn-success">
                                                <i class="bx bx-download me-1"></i>Export Excel
                                            </button>
                                            <!-- Generate PDF Report Button -->
                                            <button type="button" id="generatePdfReport" class="btn btn-danger">
                                                <i class="bx bx-file-pdf me-1"></i>Export PDF
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer Sales Summary Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Sales Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customerSalesTable" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Customer Group</th>
                                    <th>Total Sales Amount</th>
                                    <th>Total Invoices</th>
                                    <th>Total Products Sold</th>
                                    <th>Payment Status (Paid/Unpaid/Partial)</th>
                                    <th>Outstanding Balance</th>
                                    <th>Date Range</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customerAggregates as $aggregate)
                                    <tr>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">{{ $aggregate['customer']->client_name ?? 'N/A' }}</span>
                                                <br><small class="text-muted">{{ $aggregate['customer']->email ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $aggregate['customer']->groupInfo->customerGroup->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="text-success fw-semibold">₹{{ number_format($aggregate['total_sales_amount'], 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $aggregate['total_invoices'] }}</span>
                                        </td>
                                        <td>{{ number_format($aggregate['total_products_sold']) }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if($aggregate['paid_invoices'] > 0)
                                                    <span class="badge bg-success">{{ $aggregate['paid_invoices'] }}P</span>
                                                @endif
                                                @if($aggregate['unpaid_invoices'] > 0)
                                                    <span class="badge bg-danger">{{ $aggregate['unpaid_invoices'] }}U</span>
                                                @endif
                                                @if($aggregate['partial_invoices'] > 0)
                                                    <span class="badge bg-warning">{{ $aggregate['partial_invoices'] }}T</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-{{ $aggregate['outstanding_balance'] > 0 ? 'warning' : 'success' }} fw-semibold">
                                                ₹{{ number_format($aggregate['outstanding_balance'], 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($aggregate['date_range_start'] && $aggregate['date_range_end'])
                                                {{ $aggregate['date_range_start']->format('d-m-Y') }} to {{ $aggregate['date_range_end']->format('d-m-Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                data-bs-toggle="tooltip" title="View Details"
                                                onclick="viewCustomerDetails({{ $aggregate['customer']->id }})">
                                                <i class="bx bx-show text-primary"></i>
                                            </button>
                                            <a href="{{ route('customer.detail', $aggregate['customer']->id) }}" target="_blank"
                                                class="btn btn-icon btn-sm bg-info-subtle"
                                                data-bs-toggle="tooltip" title="Customer Profile">
                                                <i class="bx bx-user text-info"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                            No customer sales records found
                                            @if ($filters['from_date'] || $filters['to_date'] || $filters['customer_id'] || $filters['region'] || $filters['payment_status'] || $filters['customer_type'])
                                                for the selected filters.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            /**
             * Prevent dropdown from closing when clicking on checkboxes
             */
            $(document).on('click', '.dropdown-menu', function(e) {
                e.stopPropagation();
            });

            /**
             * Add cursor pointer styling to checkbox labels
             */
            $('.form-check-label').css('cursor', 'pointer');

            // Initialize DataTable for customer sales table with custom sorting
            $('#customerSalesTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                "ordering": true,
                "searching": true,
                "order": [[2, "desc"]], // Default sort by Total Sales Amount descending
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries"
                },
                "columnDefs": [
                    {
                        "targets": 2, // Total Sales Amount column
                        "type": "num-fmt",
                        "render": function(data, type, row) {
                            if (type === 'sort') {
                                return parseFloat(data.replace(/[^\d.-]/g, ''));
                            }
                            return data;
                        }
                    },
                    {
                        "targets": 6, // Outstanding Balance column
                        "type": "num-fmt",
                        "render": function(data, type, row) {
                            if (type === 'sort') {
                                return parseFloat(data.replace(/[^\d.-]/g, ''));
                            }
                            return data;
                        }
                    }
                ]
            });

            /**
             * Update dropdown button text when radio buttons change
             */
            $(document).on('change', '.customer-checkbox', function() {
                var selectedText = $(this).closest('li').find('label').text().trim();
                if ($(this).val() === '') {
                    selectedText = 'Select Customer';
                }
                $('#customerDropdown').html('<i class="bx bx-filter-alt me-1"></i>' + selectedText);
            });

            $(document).on('change', '.region-checkbox', function() {
                var selectedText = $(this).val() === '' ? 'Select Region' : $(this).closest('li').find('label').text().trim();
                $('#regionDropdown').html('<i class="bx bx-filter-alt me-1"></i>' + selectedText);
            });

            $(document).on('change', '.payment-status-checkbox', function() {
                var selectedText = $(this).val() === '' ? 'Select Status' : $(this).closest('li').find('label').text().trim();
                $('#paymentStatusDropdown').html('<i class="bx bx-filter-alt me-1"></i>' + selectedText);
            });

            $(document).on('change', '.customer-type-checkbox', function() {
                var selectedText = $(this).val() === '' ? 'Select Type' : $(this).closest('li').find('label').text().trim();
                $('#customerTypeDropdown').html('<i class="bx bx-filter-alt me-1"></i>' + selectedText);
            });

            /**
             * Reset Filter Button Click Handler
             */
            $(document).on('click', '#resetFilters', function(e) {
                e.preventDefault();

                // Clear all filter inputs
                $('#from_date').val('');
                $('#to_date').val('');
                $('.customer-checkbox[value=""]').prop('checked', true).trigger('change');
                $('.region-checkbox[value=""]').prop('checked', true).trigger('change');
                $('.payment-status-checkbox[value=""]').prop('checked', true).trigger('change');
                $('.customer-type-checkbox[value=""]').prop('checked', true).trigger('change');

                // Redirect to base URL without filters
                window.location.href = '{{ route('customer-sales-history') }}';
            });

            /**
             * Generate Excel Report Functionality
             */
            $('#generateExcelReport').on('click', function() {
                // Get current filter values
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('input[name="customer_id"]:checked').val();
                var region = $('input[name="region"]:checked').val();
                var paymentStatus = $('input[name="payment_status"]:checked').val();
                var customerType = $('input[name="customer_type"]:checked').val();

                // Build query parameters array
                var params = [];

                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (customerId) params.push('customer_id=' + encodeURIComponent(customerId));
                if (region) params.push('region=' + encodeURIComponent(region));
                if (paymentStatus) params.push('payment_status=' + encodeURIComponent(paymentStatus));
                if (customerType) params.push('customer_type=' + encodeURIComponent(customerType));

                // Construct download URL with filter parameters
                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('customer.sales.history.excel') }}' + queryString;

                // Show loading indicator
                var originalText = $(this).html();
                $(this).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
                $(this).prop('disabled', true);

                // Trigger browser download
                window.location.href = downloadUrl;

                // Reset button after a short delay
                setTimeout(function() {
                    $('#generateExcelReport').html(originalText);
                    $('#generateExcelReport').prop('disabled', false);
                }, 2000);
            });

            /**
             * Generate PDF Report Functionality
             */
            $('#generatePdfReport').on('click', function() {
                // Get current filter values
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('input[name="customer_id"]:checked').val();
                var region = $('input[name="region"]:checked').val();
                var paymentStatus = $('input[name="payment_status"]:checked').val();
                var customerType = $('input[name="customer_type"]:checked').val();

                // Build query parameters array
                var params = [];

                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (customerId) params.push('customer_id=' + encodeURIComponent(customerId));
                if (region) params.push('region=' + encodeURIComponent(region));
                if (paymentStatus) params.push('payment_status=' + encodeURIComponent(paymentStatus));
                if (customerType) params.push('customer_type=' + encodeURIComponent(customerType));

                // Construct download URL with filter parameters
                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('customer.sales.history.pdf') }}' + queryString;

                // Show loading indicator
                var originalText = $(this).html();
                $(this).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
                $(this).prop('disabled', true);

                // Trigger browser download
                window.location.href = downloadUrl;

                // Reset button after a short delay
                setTimeout(function() {
                    $('#generatePdfReport').html(originalText);
                    $('#generatePdfReport').prop('disabled', false);
                }, 2000);
            });

            /**
             * Initialize Bootstrap Tooltips
             */
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        /**
         * View Customer Details Function
         */
        function viewCustomerDetails(customerId) {
            // Open customer detail page in new tab
            window.open('{{ route('customer.detail', ':id') }}'.replace(':id', customerId), '_blank');
        }
    </script>
@endsection
