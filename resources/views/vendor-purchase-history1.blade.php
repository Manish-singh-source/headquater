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
                            <li class="breadcrumb-item active" aria-current="page">Vendor Purchase Report</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Display Success/Error Messages -->
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

            <div class="col">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Vendor Orders</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $purchaseOrders->count() }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Amount</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">
                                            {{ $purchaseOrders->sum('total_amount') . '₹' }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Paid Amount</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $purchaseOrders->sum('total_paid_amount') }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Due Amount</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $purchaseOrders->sum('total_due_amount') }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Filter Section -->
            <div class="card mt-4">
                <div class="card-body pb-1">
                    <h6 class="mb-3 fw-bold"><i class="bx bx-filter-alt me-2"></i>Filter Options</h6>
                    <form id="filterForm" method="GET" action="{{ route('vendor-purchase-history') }}">
                        <div class="row align-items-end">
                            <div class="col-lg-10">
                                <div class="row">
                                    <!-- From Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">From Date</label>
                                            <div class="input-icon-start position-relative">
                                                <input type="date" class="form-control" id="date-from"
                                                    name="from_date" value="{{ request('from_date') }}"
                                                    placeholder="dd/mm/yyyy">
                                                <span class="input-icon-left">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- To Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">To Date</label>
                                            <div class="input-icon-start position-relative">
                                                <input type="date" class="form-control" id="date-to" name="to_date"
                                                    value="{{ request('to_date') }}" placeholder="dd/mm/yyyy">
                                                <span class="input-icon-left">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vendor Name Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Vendor Name</label>
                                            <select id="vendor-code" name="vendor_code" class="form-select">
                                                <option value="">-- All Vendors --</option>
                                                @foreach ($purchaseOrdersVendors as $purchaseOrdersVendor)
                                                    <option value="{{ $purchaseOrdersVendor }}"
                                                        {{ request('vendor_code') == $purchaseOrdersVendor ? 'selected' : '' }}>
                                                        {{ $purchaseOrdersVendor }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Apply Filter Button -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="submit" id="filterData" class="btn btn-primary w-100">
                                                <i class="bx bx-filter me-1"></i>Apply Filter
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Reset Filter Button -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="button" id="resetFilters" class="btn btn-secondary w-100">
                                                <i class="bx bx-reset me-1"></i>Reset Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <button type="button" id="exportData" class="btn btn-danger w-100">
                                        <i class="bx bx-download me-1"></i>Generate Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Data Table Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bx bx-list-ul me-2"></i>Vendor Purchase Records
                            <span class="badge bg-primary ms-2">{{ $purchaseOrders->total() }} Total</span>
                        </h6>
                    </div>

                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="vendor-purchase-history-table" class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Order&nbsp;Id</th>
                                        <th>Vendor&nbsp;Name</th>
                                        <th>Ordered&nbsp;Status</th>
                                        <th>Ordered&nbsp;Quantity</th>
                                        <th>Received&nbsp;Quantity</th>
                                        <th>Total&nbsp;Amount</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Ordered&nbsp;Date</th>
                                        {{-- <th>Appointment&nbsp;Date</th> --}}
                                        <th>POD</th>
                                        <th>LR</th>
                                        <th>DN</th>
                                        <th>GRN</th>
                                        <th>Invoice</th>
                                        <th>Payment&nbsp;Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchaseOrders as $purchaseOrder)
                                        <tr>
                                            <td>
                                                <input class="form-check-input item-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $purchaseOrder->id }}">
                                            </td>
                                            <td><strong>{{ $purchaseOrder->purchase_order_id }}</strong></td>
                                            <td>{{ $purchaseOrder->vendor_code ?? 'NA' }}</td>
                                            <td>
                                                @if ($purchaseOrder->purchaseOrder->status == 'pending')
                                                    <span
                                                        class="badge bg-danger">{{ ucfirst($purchaseOrder->purchaseOrder->status) }}</span>
                                                @elseif($purchaseOrder->purchaseOrder->status == 'received')
                                                    <span
                                                        class="badge bg-warning">{{ ucfirst($purchaseOrder->purchaseOrder->status) }}</span>
                                                @elseif($purchaseOrder->purchaseOrder->status == 'completed')
                                                    <span
                                                        class="badge bg-success">{{ ucfirst($purchaseOrder->purchaseOrder->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $purchaseOrder->products->sum('quantity_requirement') }}</td>
                                            <td>{{ $purchaseOrder->products->sum('quantity_received') }}</td>
                                            <td>₹{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                                            <td>₹{{ number_format($purchaseOrder->total_paid_amount ?? 0, 2) }}
                                            <td>₹{{ number_format($purchaseOrder->total_due_amount ?? 0, 2) }}
                                            </td>
                                            <td>{{ $purchaseOrder->purchaseOrder?->order_date ?? 'NA' }}</td>
                                            {{-- <td>{{ $purchaseOrder->appointment?->appointment_date ?? 'NA' }}</td> --}}
                                            <td>{{ $purchaseOrder->appointment?->pod ? 'Yes' : 'No' }}</td>
                                            <td>{{ $purchaseOrder->appointment?->lr ? 'Yes' : 'No' }}</td>
                                            <td>{{ $purchaseOrder->dns?->dn_amount ? 'Yes' : 'No' }}</td>
                                            <td>{{ $purchaseOrder->purchaseGrn?->id ? 'Yes' : 'No' }}</td>
                                            <td>{{ $purchaseOrder->purchaseInvoice?->id ? 'Yes' : 'No' }}</td>
                                            <td>{{ $purchaseOrder->payment_status ? ucfirst($purchaseOrder->payment_status) : 'Not Paid' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="17" class="text-center text-muted py-4">
                                                <i class="bx bx-info-circle fs-3 d-block mb-2"></i>
                                                <p class="mb-0">No vendor purchase records found.</p>
                                                @if (request()->hasAny(['from_date', 'to_date', 'vendor_code']))
                                                    <small>Try adjusting your filters or <a
                                                            href="{{ route('vendor-purchase-history') }}"
                                                            class="text-primary">reset filters</a>.</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($purchaseOrders->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Showing {{ $purchaseOrders->firstItem() }} to {{ $purchaseOrders->lastItem() }} of
                                    {{ $purchaseOrders->total() }} entries
                                </div>
                                <div>
                                    {{ $purchaseOrders->links() }}
                                </div>
                            </div>
                        @endif
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
             * CSV Export Functionality
             *
             * When "Generate Report" button is clicked:
             * 1. Collect current filter values (from_date, to_date, vendor_code)
             * 2. Build download URL with filter parameters
             * 3. Trigger browser download of CSV file
             *
             * The CSV export will match the currently applied filters,
             * ensuring consistency between displayed data and exported data.
             */
            $(document).on('click', '#exportData', function(e) {
                e.preventDefault();

                // Get current filter values from the form
                var dateFrom = $("#date-from").val().trim();
                var dateTo = $("#date-to").val().trim();
                var vendorCode = $("#vendor-code").val().trim();

                // Build query parameters for CSV export
                var params = [];

                // Add date_from parameter if provided
                if (dateFrom) {
                    params.push('date_from=' + encodeURIComponent(dateFrom));
                }

                // Add date_to parameter if provided
                if (dateTo) {
                    params.push('date_to=' + encodeURIComponent(dateTo));
                }

                // Add vendor_code parameter if provided
                if (vendorCode) {
                    params.push('vendor_code=' + encodeURIComponent(vendorCode));
                }

                // Construct download URL with parameters
                var downloadUrl = '{{ route('vendor.purchase.history.excel') }}';
                if (params.length > 0) {
                    downloadUrl += '?' + params.join('&');
                }

                // Show loading indicator
                var originalText = $(this).html();
                $(this).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
                $(this).prop('disabled', true);

                // Trigger browser download
                window.location.href = downloadUrl;

                // Reset button after a short delay
                setTimeout(function() {
                    $('#exportData').html(originalText);
                    $('#exportData').prop('disabled', false);
                }, 2000);
            });

            /**
             * Reset Filters Functionality
             *
             * When "Reset Filters" button is clicked:
             * 1. Clear all filter input values
             * 2. Redirect to the base URL without any query parameters
             *
             * This will show all vendor purchase records without any filtering.
             */
            $(document).on('click', '#resetFilters', function(e) {
                e.preventDefault();

                // Clear all filter inputs
                $('#date-from').val('');
                $('#date-to').val('');
                $('#vendor-code').val('');

                // Redirect to base URL without filters
                window.location.href = '{{ route('vendor-purchase-history') }}';
            });

            /**
             * Select All Checkbox Functionality
             *
             * Toggle all item checkboxes when the header checkbox is clicked.
             */
            $('#select-all').on('change', function() {
                $('.item-checkbox').prop('checked', $(this).prop('checked'));
            });

            /**
             * Individual Checkbox Functionality
             *
             * Uncheck the "select all" checkbox if any individual checkbox is unchecked.
             */
            $('.item-checkbox').on('change', function() {
                if (!$(this).prop('checked')) {
                    $('#select-all').prop('checked', false);
                } else {
                    // Check if all checkboxes are checked
                    var allChecked = $('.item-checkbox:checked').length === $('.item-checkbox').length;
                    $('#select-all').prop('checked', allChecked);
                }
            });

            /**
             * Auto-dismiss alerts after 5 seconds
             */
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

        });
    </script>
@endsection
