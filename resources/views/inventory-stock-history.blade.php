@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Inventory Stock Report</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col-xl-2 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-package fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Stock</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ number_format($productsSum/2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-success">
                                    <i class="ti ti-check-circle fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Available</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ number_format($availableProductsSum/2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-warning">
                                    <i class="ti ti-clock-pause fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Blocked</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ number_format($blockProductsSum/2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-info">
                                    <i class="ti ti-currency-rupee fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Stock Value</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">₹{{ number_format($totalStockValue/2, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-1 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-danger">
                                    <i class="ti ti-alert-triangle fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Low Stock</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $lowStockCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-secondary">
                                    <i class="ti ti-x fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Out of Stock</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $outOfStockCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold"><i class="bx bx-filter-alt me-2"></i>Filter Options</h6>
                    <form id="filterForm" method="GET" action="{{ route('inventory-stock-history') }}">
                        <div class="row align-items-start">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Warehouse</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="warehouseDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select Warehouse
                                                </button>
                                                <ul class="dropdown-menu w-100" id="warehouseCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($warehouses as $warehouse)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input warehouse-checkbox"
                                                                    type="checkbox" name="warehouse_id[]"
                                                                    value="{{ $warehouse->id }}"
                                                                    id="warehouse_{{ $loop->index }}"
                                                                    {{ in_array($warehouse->id, (array) request('warehouse_id')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="warehouse_{{ $loop->index }}">
                                                                    {{ $warehouse->name }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="categoryDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select Category
                                                </button>
                                                <ul class="dropdown-menu w-100" id="categoryCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($categories as $category)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input category-checkbox"
                                                                    type="checkbox" name="category[]"
                                                                    value="{{ $category }}"
                                                                    id="category_{{ $loop->index }}"
                                                                    {{ in_array($category, (array) request('category')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="category_{{ $loop->index }}">
                                                                    {{ $category }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Brand</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="brandDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select Brand
                                                </button>
                                                <ul class="dropdown-menu w-100" id="brandCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($brands as $brand)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input brand-checkbox"
                                                                    type="checkbox" name="brand[]"
                                                                    value="{{ $brand }}"
                                                                    id="brand_{{ $loop->index }}"
                                                                    {{ in_array($brand, (array) request('brand')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="brand_{{ $loop->index }}">
                                                                    {{ $brand }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">SKU</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="skuDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select SKU
                                                </button>
                                                <ul class="dropdown-menu w-100" id="skuCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($skus as $sku)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input sku-checkbox"
                                                                    type="checkbox" name="sku[]"
                                                                    value="{{ $sku }}"
                                                                    id="sku_{{ $loop->index }}"
                                                                    {{ in_array($sku, (array) request('sku')) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="sku_{{ $loop->index }}">
                                                                    {{ $sku }}
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="statusDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>Select Status
                                                </button>
                                                <ul class="dropdown-menu w-100" id="statusCheckboxList">
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input status-checkbox"
                                                                type="checkbox" name="status[]"
                                                                value="Normal"
                                                                id="status_normal"
                                                                {{ in_array('Normal', (array) request('status')) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="status_normal">
                                                                Normal
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input status-checkbox"
                                                                type="checkbox" name="status[]"
                                                                value="Low Stock"
                                                                id="status_low"
                                                                {{ in_array('Low Stock', (array) request('status')) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="status_low">
                                                                Low Stock
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input status-checkbox"
                                                                type="checkbox" name="status[]"
                                                                value="Out of Stock"
                                                                id="status_out"
                                                                {{ in_array('Out of Stock', (array) request('status')) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="status_out">
                                                                Out of Stock
                                                            </label>
                                                        </div>
                                                    </li>
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


            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="inventory-stock-history-table" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Warehouse</th>
                                        <th>Brand</th>
                                        <th>Brand Title</th>
                                        <th>Category</th>
                                        <th>SKU</th>
                                        <th>PCS/Set</th>
                                        <th>Sets/CTN</th>
                                        <th>MRP</th>
                                        <th>Original Qty</th>
                                        <th>Available Qty</th>
                                        <th>Block Qty</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @php
                                            $stockValue = ($product->available_quantity ?? 0) * ($product->product->mrp ?? 0);
                                            $status = 'Normal';
                                            if (($product->available_quantity ?? 0) <= 10 && ($product->available_quantity ?? 0) > 0) {
                                                $status = 'Low Stock';
                                            } elseif (($product->available_quantity ?? 0) == 0) {
                                                $status = 'Out of Stock';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $product->id }}">
                                            </td>
                                            <td>{{ $product->warehouse->name ?? 'N/A' }}</td>
                                            <td>{{ $product->product->brand ?? 'N/A' }}</td>
                                            <td>{{ $product->product->brand_title ?? 'N/A' }}</td>
                                            <td>{{ $product->product->category ?? 'N/A' }}</td>
                                            <td>{{ $product->product->sku ?? 'N/A' }}</td>
                                            <td>{{ $product->product->pcs_set ?? 0 }}</td>
                                            <td>{{ $product->product->sets_ctn ?? 0 }}</td>
                                            <td>₹{{ number_format($product->product->mrp ?? 0, 2) }}</td>
                                            <td>{{ number_format($product->original_quantity ?? 0) }}</td>
                                            <td>{{ number_format($product->available_quantity ?? 0) }}</td>
                                            <td>{{ number_format($product->block_quantity ?? 0) }}</td>
                                            <td>₹{{ number_format($stockValue, 2) }}</td>
                                            <td>
                                                @if($status == 'Out of Stock')
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($status == 'Low Stock')
                                                    <span class="badge bg-warning">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success">Normal</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->created_at->format('d-m-Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

            // Initialize DataTable for inventory stock history
            var inventoryStockTable = $('#inventory-stock-history-table').DataTable({
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0] // Disable sorting for checkbox column
                }],
                lengthChange: true,
                pageLength: 15,
                order: [
                    [14, 'desc']
                ], // Sort by Date column (index 14) in descending order
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });


            /**
             * Reset Filter Button Click Handler
             *
             * Reset Logic:
             * 1. Clear all filter input fields
             * 2. Reload page without any filters
             */
            $(document).on('click', '#resetFilters', function(e) {
                e.preventDefault();

                // Clear all filter inputs
                $('#date-from').val('');
                $('#date-to').val('');
                $('.warehouse-checkbox').prop('checked', false);
                $('.category-checkbox').prop('checked', false);
                $('.brand-checkbox').prop('checked', false);
                $('.sku-checkbox').prop('checked', false);
                $('.status-checkbox').prop('checked', false);

                // Redirect to base URL without filters
                window.location.href = '{{ route('inventory-stock-history') }}';
            });

            /**
             * Export CSV Button Click Handler
             *
             * CSV Export Logic:
             * 1. Collect all current filter values
             * 2. Build query parameters for all filters that have values
             * 3. Construct download URL with parameters
             * 4. Trigger browser download of CSV file
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

                // Add warehouse_id[] parameters from checked checkboxes
                $('.warehouse-checkbox:checked').each(function() {
                    params.push('warehouse_id[]=' + encodeURIComponent($(this).val()));
                });

                // Add category[] parameters from checked checkboxes
                $('.category-checkbox:checked').each(function() {
                    params.push('category[]=' + encodeURIComponent($(this).val()));
                });

                // Add brand[] parameters from checked checkboxes
                $('.brand-checkbox:checked').each(function() {
                    params.push('brand[]=' + encodeURIComponent($(this).val()));
                });

                // Add sku[] parameters from checked checkboxes
                $('.sku-checkbox:checked').each(function() {
                    params.push('sku[]=' + encodeURIComponent($(this).val()));
                });

                // Add status[] parameters from checked checkboxes
                $('.status-checkbox:checked').each(function() {
                    params.push('status[]=' + encodeURIComponent($(this).val()));
                });

                // Construct download URL with parameters
                var downloadUrl = '{{ route('inventory.stock.history.excel') }}';
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
             * Select All Checkbox Handler
             * Toggle all row checkboxes when header checkbox is clicked
             */
            $('#select-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.row-checkbox').prop('checked', isChecked);
            });

            /**
             * Individual Checkbox Handler
             * Update select-all checkbox state when individual checkboxes change
             */
            $(document).on('change', '.row-checkbox', function() {
                var totalCheckboxes = $('.row-checkbox').length;
                var checkedCheckboxes = $('.row-checkbox:checked').length;
                $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
            });

        });
    </script>
@endsection
