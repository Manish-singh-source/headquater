@extends('layouts.master')
@section('main-content')


    @php
        $statusBadges = [
            'paid' => 'bg-success',
            'pending' => 'bg-danger',
            'partial_paid' => 'bg-warning',
        ];
        $statusLabels = [
            'paid' => 'Paid',
            'partial_paid' => 'Partial Paid',
            'pending' => 'Pending',
        ];

        $allocationStatusBadges = [
            'pending' => 'bg-secondary',
            'approve' => 'bg-warning',
            'reject' => 'bg-danger',
            'completed' => 'bg-success',
        ];

        $allocationStatusLabels = [
            'pending' => 'Pending',
            'approve' => 'Approval Pending',
            'reject' => 'Rejected',
            'completed' => 'Completed',
        ];
    @endphp

    <main class="main-wrapper">
        <div class="main-content">
            <!-- Breadcrumb Navigation -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Purchase SKU Level</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('layouts.errors')

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
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold"><i class="bx bx-filter-alt me-2"></i>Filter Options</h6>
                    <form id="filterForm" method="GET" action="{{ route('vendor-purchase-sku') }}">
                        <div class="row align-items-start">
                            <div class="col-lg-10">
                                <div class="row">
                                    <!-- From Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">From Date</label>
                                            <div class="input-icon-start position-relative">
                                                <input type="date" class="form-control" id="date-from" name="from_date"
                                                    value="{{ request('from_date') }}" placeholder="dd/mm/yyyy">
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
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="poDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select PO
                                                </button>
                                                <ul class="dropdown-menu w-100" id="poCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($purchaseOrderNumbers as $purchaseOrderNumber)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input po-checkbox"
                                                                    type="checkbox" name="purchase_order_no[]"
                                                                    value="{{ $purchaseOrderNumber }}"
                                                                    id="po_{{ $loop->index }}"
                                                                    {{ in_array($purchaseOrderNumber, (array) request('purchase_order_no')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="po_{{ $loop->index }}">
                                                                    {{ $purchaseOrderNumber }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vendor Name Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Vendor Name</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="vendorDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select Vendor
                                                </button>
                                                <ul class="dropdown-menu w-100" id="vendorCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($purchaseOrdersVendors as $purchaseOrdersVendor)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input vendor-checkbox"
                                                                    type="checkbox" name="vendor_code[]"
                                                                    value="{{ $purchaseOrdersVendor }}"
                                                                    id="vendor_{{ $loop->index }}"
                                                                    {{ in_array($purchaseOrdersVendor, (array) request('vendor_code')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="vendor_{{ $loop->index }}">
                                                                    {{ $purchaseOrdersVendor }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Sku Filter --}}
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">SKU</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="skuDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select SKU
                                                </button>
                                                <ul class="dropdown-menu w-100" id="skuCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($purchaseOrdersSKUs as $product)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input sku-checkbox"
                                                                    type="checkbox" name="sku[]"
                                                                    value="{{ $product }}"
                                                                    id="sku_{{ $loop->index }}"
                                                                    {{ in_array($product, (array) request('sku')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="sku_{{ $loop->index }}">
                                                                    {{ $product }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
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
                    </form>
                </div>
            </div>

            <!-- Active Filters Summary -->
            @php
                $activeFilters = [];
                if (!empty($filters['from_date'])) {
                    $activeFilters['From'] = $filters['from_date'];
                }
                if (!empty($filters['to_date'])) {
                    $activeFilters['To'] = $filters['to_date'];
                }
                if (!empty($filters['purchase_order_no'])) {
                    $activeFilters['Purchase Orders'] = (array) $filters['purchase_order_no'];
                }
                if (!empty($filters['vendor_code'])) {
                    $activeFilters['Vendors'] = (array) $filters['vendor_code'];
                }
                if (!empty($filters['sku'])) {
                    $activeFilters['SKUs'] = (array) $filters['sku'];
                }
            @endphp

            @if (!empty($activeFilters))
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-2 fw-bold">Active Filters</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($activeFilters as $label => $value)
                                @if (is_array($value))
                                    <div class="me-2">
                                        <strong>{{ $label }}:</strong>
                                        @foreach ($value as $v)
                                            <span class="badge bg-secondary ms-1">{{ $v }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="me-2"><strong>{{ $label }}:</strong> <span
                                            class="text-muted">{{ $value }}</span></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-2 text-muted">Total matching records:
                            <strong>{{ $vendorPIProducts->total() }}</strong>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Data Table Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bx bx-list-ul me-2"></i>Vendor Purchase SKU Records
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
                                        <th>Invoice&nbsp;Ref</th>
                                        <th>Invoice&nbsp;Date</th>
                                        <th>SKU</th>
                                        <th>Item&nbsp;Name</th>
                                        <th>HSN/SAC</th>
                                        <th>PO&nbsp;Created</th>
                                        <th>PI&nbsp;Received</th>
                                        <th>PO&nbsp;Quantity</th>
                                        <th>PI&nbsp;Quantity</th>
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
                                        <th>Invoice&nbsp;Amount</th>
                                        <th>Invioice&nbsp;Uploaded</th>
                                        <th>GRN&nbsp;Uploaded</th>
                                        <th>Shipping&nbsp;Charges</th>
                                        <th>Approved</th>
                                        <th>Warehouse</th>

                                        {{-- <th>GSTIN</th>
                                        <th>PAN</th>
                                        <th>Payment&nbsp;Status</th>
                                        <th>Payment&nbsp;Method</th>
                                        <th>Invoice&nbsp;Due</th>
                                        <th>Invoice&nbsp;Paid</th>
                                        <th>Due&nbsp;Date</th> --}}
                                        {{-- <th>Remarks</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendorPIProducts as $purchaseOrder)
                                        @php
                                            $vendor = $purchaseOrder->vendor ?? null;
                                            $purchaseInvoice = $purchaseOrder->purchaseInvoices->first() ?? null;
                                            $vendorPI = $purchaseOrder->vendorPI->first() ?? null;
                                            $payment = $vendorPI ? $vendorPI->payments->first() : null;
                                            $purchaseGrn = $purchaseOrder->purchaseGrn ?? null;
                                        @endphp

                                        @forelse($purchaseOrder->purchaseOrderProducts as $product)
                                            @php
                                                $productDetails = $product->product ?? null;
                                                $gstRate = floatval($productDetails->gst ?? 0);
                                                $unitCost = floatval($product->unit_cost ?? 0);
                                                $quantity = floatval($product->ordered_quantity ?? 0);
                                                $taxableValue = $product->product->mrp * $quantity;
                                                $gstAmount = ($taxableValue * $gstRate) / 100;

                                                // Calculate CGST/SGST/IGST based on state (simplified)
                                                $cgst = $gstRate / 2;
                                                $sgst = $gstRate / 2;
                                                $igst = 0; // Set to $gstRate if interstate
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input class="form-check-input item-checkbox" type="checkbox"
                                                        name="ids[]" value="{{ $purchaseOrder->id }}">
                                                </td>
                                                <td>{{ $purchaseOrder->id ?? 'N/A' }}</td>
                                                <td>{{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $vendor->client_name ?? 'N/A' }}</td>
                                                <td>{{ $purchaseInvoice->invoice_no ?? 'N/A' }}</td>
                                                <td>{{ $purchaseInvoice ? ($purchaseInvoice->created_at ? $purchaseInvoice->created_at->format('d-m-Y') : 'N/A') : 'N/A' }}
                                                </td>
                                                <td>{{ $product->sku ?? 'N/A' }}</td>
                                                <td>{{ $productDetails->brand_title ?? 'N/A' }}</td>
                                                <td>{{ $productDetails->hsn ?? 'N/A' }}</td>
                                                <td>{{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $vendorPI && $vendorPI->updated_at ? $vendorPI->updated_at->format('d-m-Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $product->ordered_quantity ?? 0 }}</td>
                                                <td>{{ $product->ordered_quantity ?? 0 }}</td>
                                                <td>PCS</td>
                                                <td>₹{{ number_format($product->product->mrp ?? 0, 2) }}</td>
                                                <td>₹{{ number_format($product->discount_per_unit ?? 0, 2) }}</td>
                                                <td>₹{{ number_format($taxableValue, 2) }}</td>
                                                <td>{{ $gstRate }}%</td>
                                                <td>{{ $cgst }}%</td>
                                                <td>{{ $sgst }}%</td>
                                                <td>{{ $gstRate }}%</td>
                                                <td>₹{{ number_format($gstAmount, 2) }}</td>
                                                <td>0%</td>
                                                <td>₹0.00</td>
                                                <td>{{ $vendorPI->total_paid_amount ?? 'N/A' }}</td>
                                                <td>{{ $purchaseInvoice ? 'Yes' : 'No' }}</td>
                                                <td>{{ $purchaseGrn ? 'Yes' : 'No' }}</td>
                                                <td>N/A</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $vendorPI && $allocationStatusBadges[$vendorPI->status] ? $allocationStatusBadges[$vendorPI->status] : 'bg-secondary' }}">
                                                        {{ $vendorPI ? $allocationStatusLabels[$vendorPI->status] : 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>{{ $vendorPI->warehouse->name ?? 'N/A' }}</td>


                                                {{-- <td>{{ $vendor->gst_number ?? 'N/A' }}</td>
                                                <td>{{ $vendor->pan_number ?? 'N/A' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $vendorPI && $statusBadges[$vendorPI->payment_status] ? $statusBadges[$vendorPI->payment_status] : 'bg-danger' }}">
                                                        {{ $vendorPI && $statusLabels[$vendorPI->payment_status] ? $statusLabels[$vendorPI->payment_status] : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                                <td>{{ $vendorPI->total_amount ?? 'N/A' }}</td>
                                                <td>{{ $vendorPI->total_due_amount ?? 'N/A' }}</td>
                                                <td>{{ $vendorPI && $vendorPI->updated_at ? $vendorPI->updated_at->addMonth()->format('d-m-Y') : 'N/A' }}
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="34" class="text-center text-muted py-4">No products in this
                                                    order</td>
                                            </tr>
                                        @endforelse
                                    @empty
                                        <tr>
                                            <td colspan="34" class="text-center text-muted py-4">No records found</td>
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
             * Prevent dropdown from closing when clicking on checkboxes
             */
            $(document).on('click', '.dropdown-menu', function(e) {
                e.stopPropagation();
            });

            /**
             * Add cursor pointer styling to checkbox labels
             */
            $('.form-check-label').css('cursor', 'pointer');

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

                // Build query parameters for CSV export
                var params = [];

                // Add from_date parameter if provided
                if (dateFrom) {
                    params.push('from_date=' + encodeURIComponent(dateFrom));
                }

                // Add to_date parameter if provided
                if (dateTo) {
                    params.push('to_date=' + encodeURIComponent(dateTo));
                }

                // Add purchase_order_no[] parameters from checked checkboxes
                $('.po-checkbox:checked').each(function() {
                    params.push('purchase_order_no[]=' + encodeURIComponent($(this).val()));
                });

                // Add vendor_code[] parameters from checked checkboxes
                $('.vendor-checkbox:checked').each(function() {
                    params.push('vendor_code[]=' + encodeURIComponent($(this).val()));
                });

                // Add sku[] parameters from checked checkboxes
                $('.sku-checkbox:checked').each(function() {
                    params.push('sku[]=' + encodeURIComponent($(this).val()));
                });

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
                $('.po-checkbox').prop('checked', false);
                $('.vendor-checkbox').prop('checked', false);
                $('.sku-checkbox').prop('checked', false);

                // Redirect to base URL without filters
                window.location.href = '{{ route('vendor-purchase-sku') }}';
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
