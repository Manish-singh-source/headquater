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
                                        <h4 class="text-dark">{{ $totalOrders }}</h4>
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
                                            ₹{{ number_format($purchaseOrdersTotal, 2) }}</h4>
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
                                    <p class="text-dark mb-1">Total Quantity</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ number_format($purchaseOrdersTotalQuantity) }}</h4>
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
                                    <p class="text-dark mb-1">Total Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $vendorPIProducts->total() }}</h4>
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
                        <div class="row align-items-start">
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

                                    {{-- Purchase Order No --}}
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Purchase Order No</label>
                                            <select id="purchase-order-no" name="purchase_order_no" class="form-select">
                                                <option value="">-- All Purchase Orders --</option>
                                                @foreach ($purchaseOrderNumbers as $purchaseOrderNumber)
                                                    <option value="{{ $purchaseOrderNumber }}"
                                                        {{ request('purchase_order_no') == $purchaseOrderNumber ? 'selected' : '' }}>
                                                        {{ $purchaseOrderNumber }}
                                                    </option>
                                                @endforeach
                                            </select>
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

                                    {{-- Sku Filter --}}
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">SKU</label>
                                            <select id="sku" name="sku" class="form-select">
                                                <option value="">-- All SKUs --</option>
                                                @foreach ($purchaseOrdersSKUs as $product)
                                                    <option value="{{ $product }}"
                                                        {{ request('sku') == $product ? 'selected' : '' }}>
                                                        {{ $product }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>




                                </div>
                            </div>

                            <div class="col-lg-2">
                                <!-- Apply Filter Button -->
                                <div class="col">
                                    <div class="mb-3">
                                        <button type="submit" id="filterData" class="btn btn-primary w-100">
                                            <i class="bx bx-filter me-1"></i>Apply Filter
                                        </button>
                                    </div>
                                </div>

                                <!-- Reset Filter Button -->
                                <div class="col">
                                    <div class="mb-3">
                                        <button type="button" id="resetFilters" class="btn btn-secondary w-100">
                                            <i class="bx bx-reset me-1"></i>Reset Filters
                                        </button>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col">
                                    <div class="mb-3">
                                        <button type="button" id="exportData" class="btn btn-danger w-100">
                                            <i class="bx bx-download me-1"></i>Generate Report
                                        </button>
                                    </div>
                                </div>

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
                        <span class="badge bg-primary ms-2">{{ $vendorPIProducts->total() }} Total</span>
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
                                    <th>Purchse&nbsp;Order&nbsp;No</th>
                                    <th>Purchase&nbsp;Order&nbsp;Date</th>
                                    <th>Vendor&nbsp;Name</th>
                                    <th>GSTIN</th>
                                    <th>Item&nbsp;Name</th>
                                    <th>SKU</th>
                                    <th>HSN/SAC</th>
                                    <th>Quantity</th>
                                    <th>UoM</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Taxable&nbsp;Value</th>
                                    <th>GST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>IGST</th>
                                    <th>GST&nbsp;Amount</th>
                                    <th>Cess</th>
                                    <th>Cess&nbsp;Amount</th>
                                    <th>PAN</th>
                                    <th>Payment&nbsp;Status</th>
                                    <th>Payment&nbsp;Method</th>
                                    <th>Invoice&nbsp;Ref</th>
                                    <th>Invoice&nbsp;Date</th>
                                    <th>Due&nbsp;Date</th>
                                    <th>Shipping&nbsp;Charges</th>
                                    <th>Status</th>
                                    <th>Warehouse</th>
                                    {{-- <th>Remarks</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendorPIProducts as $product)
                                    @php
                                        $order = $product->order;
                                        $vendor = $order->vendor ?? null;
                                        $purchaseInvoice = $order->purchaseInvoice ?? null;
                                        $payment = $order->payments->first() ?? null;
                                        $gstRate = floatval($product->gst ?? 0);
                                        $taxableValue = floatval($product->mrp ?? 0);
                                        $gstAmount = ($taxableValue * $gstRate) / 100;

                                        // Calculate CGST/SGST/IGST based on state (simplified - you may need to add state comparison logic)
                                        $cgst = $gstRate / 2;
                                        $sgst = $gstRate / 2;
                                        $igst = 0; // Set to $gstRate if interstate
                                    @endphp
                                    <tr>
                                        <td>
                                            <input class="form-check-input item-checkbox" type="checkbox" name="ids[]"
                                                value="{{ $product->id }}">
                                        </td>
                                        <td>{{ $order->purchase_order_id ?? 'N/A' }}</td>
                                        <td>{{ $order->created_at ? $order->created_at->format('d-m-Y') : 'N/A' }}</td>
                                        <td>{{ $vendor->client_name ?? 'N/A' }}</td>
                                        <td>{{ $vendor->gst_number ?? 'N/A' }}</td>
                                        <td>{{ $product->title ?? 'N/A' }}</td>
                                        <td>{{ $product->vendor_sku_code ?? 'N/A' }}</td>
                                        <td>{{ $product->hsn ?? 'N/A' }}</td>
                                        <td>{{ $product->quantity_received ?? 0 }}</td>
                                        <td>{{ $product->product->unit_type ?? 'PCS' }}</td>
                                        <td>₹{{ number_format($product->purchase_rate ?? 0, 2) }}</td>
                                        <td>₹0.00</td>
                                        <td>₹{{ number_format($taxableValue, 2) }}</td>
                                        <td>{{ $gstRate }}%</td>
                                        <td>{{ $cgst }}%</td>
                                        <td>{{ $sgst }}%</td>
                                        <td>{{ $gstRate }}%</td>
                                        <td>₹{{ number_format($gstAmount, 2) }}</td>
                                        <td>0%</td>
                                        <td>₹0.00</td>
                                        <td>{{ $vendor->pan_number ?? 'N/A' }}</td>
                                        <td>
                                            @if ($payment && $payment->payment_status == 'completed')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                        <td>{{ $purchaseInvoice->invoice_no ?? 'N/A' }}</td>
                                        <td>{{ $purchaseInvoice ? ($purchaseInvoice->created_at ? $purchaseInvoice->created_at->format('d-m-Y') : 'N/A') : 'N/A' }}
                                        </td>
                                        <td>N/A</td>
                                        <td>₹0.00</td>
                                        <td>
                                            <span
                                                class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($order->status ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>{{ $order->warehouse->name ?? 'N/A' }}</td>
                                        {{-- <td>{{ $product->issue_description ?? '-' }}</td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="30" class="text-center text-muted py-4">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $vendorPIProducts->firstItem() ?? 0 }} to {{ $vendorPIProducts->lastItem() ?? 0 }}
                        of {{ $vendorPIProducts->total() }} entries
                    </div>
                    <div>
                        {{ $vendorPIProducts->links() }}
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
                var purchaseOrderNo = $("#purchase-order-no").val().trim();
                var vendorCode = $("#vendor-code").val().trim();
                var sku = $("#sku").val().trim();

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

                // Add purchase_order_no parameter if provided
                if (purchaseOrderNo) {
                    params.push('purchase_order_no=' + encodeURIComponent(purchaseOrderNo));
                }

                // Add vendor_code parameter if provided
                if (vendorCode) {
                    params.push('vendor_code=' + encodeURIComponent(vendorCode));
                }

                // Add sku parameter if provided
                if (sku) {
                    params.push('sku=' + encodeURIComponent(sku));
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
                $('#purchase-order-no').val('');
                $('#vendor-code').val('');
                $('#sku').val('');

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
