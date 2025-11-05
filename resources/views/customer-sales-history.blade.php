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
                                    <i class="bx bx-receipt fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Invoices</p>
                                    <h4 class="mb-0 fw-bold">{{ $invoices->total() }}</h4>
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
                                    <p class="mb-0 text-secondary">Total Amount</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($invoicesAmountSum, 2) }}</h4>
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
                                    <i class="bx bx-check-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Paid</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($invoicesAmountPaidSum, 2) }}</h4>
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
                                    <p class="mb-0 text-secondary">Total Due</p>
                                    <h4 class="mb-0 fw-bold">
                                        ₹{{ number_format($invoicesAmountSum - $invoicesAmountPaidSum, 2) }}</h4>
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
                            <div class="col-lg-10">
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

                                    <!-- Customer Name Filter (Dropdown without DataTables) -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Name</label>
                                            <select name="customer_id" id="customer_id" class="form-select">
                                                <option value="">All Customers</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer['id'] }}"
                                                        {{ ($filters['customer_id'] ?? '') == $customer['id'] ? 'selected' : '' }}>
                                                        {{ $customer['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Apply Filter Button -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bx bx-filter-alt me-1"></i>Apply Filter
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <a href="{{ route('customer-sales-history') }}" class="btn btn-secondary w-100">
                                                <i class="bx bx-reset me-1"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <!-- Generate Report Button -->
                                    <button type="button" id="generateReport" class="btn btn-success w-100">
                                        <i class="bx bx-download me-1"></i>Generate Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer Sales Table -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customerSalesTable" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </th>
                                    <th>Invoice No</th>
                                    <th>Customer Name</th>
                                    <th>Invoice Date</th>
                                    <th>Total Amount</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Appointment Date</th>
                                    <th>POD</th>
                                    <th>LR</th>
                                    <th>DN</th>
                                    <th>GRN</th>
                                    <th>Payment Status</th>

                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td>
                                            <input class="form-check-input row-checkbox" type="checkbox"
                                                value="{{ $invoice->id }}">
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $invoice->invoice_number ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $invoice->customer?->client_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($invoice->invoice_date)
                                                {{ $invoice->invoice_date->format('d-m-Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="text-success fw-semibold">₹{{ number_format($invoice->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-info">₹{{ number_format($invoice->payments?->sum('amount') ?? 0, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $due =
                                                    $invoice->total_amount - ($invoice->payments?->sum('amount') ?? 0);
                                            @endphp
                                            <span class="text-{{ $due > 0 ? 'warning' : 'success' }} fw-semibold">
                                                ₹{{ number_format($due, 2) }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice->appointment?->appointment_date ?? 'N/A' }}</td>
                                        <td>{{ $invoice->appointment?->pod ? 'Yes' : 'No' }}</td>
                                        <td>{{ $invoice->appointment?->lr ? 'Yes' : 'No' }}</td>
                                        <td>{{ $invoice->dns?->dn_amount ? 'Yes' : 'No' }}</td>
                                        <td>{{ $invoice->appointment?->grn ? 'Yes' : 'No' }}</td>
                                        <td>{{ $invoice->payment_status ? ucfirst($invoice->payment_status) : 'Not Paid' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('invoice.downloadPdf', $invoice->id) }}" target="_blank"
                                                class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                data-bs-toggle="tooltip" title="Download Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            {{-- <a aria-label="anchor" href="" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                            No sales records found
                                            @if ($filters['from_date'] || $filters['to_date'] || $filters['customer_id'])
                                                for the selected filters.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($invoices->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of
                                {{ $invoices->total() }} entries
                            </div>
                            <div>
                                {{ $invoices->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize DataTable for customer sales table
            $('#customerSalesTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                "ordering": true,
                "searching": true,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });
            /**
             * Generate Report (CSV Export) Functionality
             *
             * Workflow:
             * 1. Collect current filter values from the form
             * 2. Build query string with filter parameters
             * 3. Redirect to CSV export route with filters
             * 4. Server generates CSV with filtered data
             * 5. Browser downloads the CSV file
             */
            $('#generateReport').on('click', function() {
                // Get current filter values
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('#customer_id').val();
                var appointmentDate = $('#appointment_date').val();
                var pod = $('#pod').val();
                var lr = $('#lr').val();
                var dn = $('#dn').val();
                var grn = $('#grn').val();
                var paymentStatus = $('#payment_status').val();

                // Build query parameters array
                var params = [];

                if (fromDate) {
                    params.push('from_date=' + encodeURIComponent(fromDate));
                }

                if (toDate) {
                    params.push('to_date=' + encodeURIComponent(toDate));
                }

                if (customerId) {
                    params.push('customer_id=' + encodeURIComponent(customerId));
                }

                // Construct download URL with filter parameters
                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('customer.sales.history.excel') }}' + queryString;

                // Trigger browser download
                window.location.href = downloadUrl;
            });

            /**
             * Select All Checkboxes Functionality
             *
             * Allows selecting/deselecting all invoice checkboxes at once
             */
            $('#select-all').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
            });

            /**
             * Update Select-All Checkbox State
             *
             * Automatically checks/unchecks the "select all" checkbox
             * based on individual checkbox states
             */
            $('.row-checkbox').on('change', function() {
                var totalCheckboxes = $('.row-checkbox').length;
                var checkedCheckboxes = $('.row-checkbox:checked').length;

                $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
            });

            /**
             * Initialize Bootstrap Tooltips
             *
             * Enables tooltips for action buttons
             */
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
    });
    </script>
@endsection
