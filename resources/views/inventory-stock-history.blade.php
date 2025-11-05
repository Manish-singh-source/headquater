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
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Product </p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $productsSum }}</h4>
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
                                    <p class="text-dark mb-1">Total Available Products </p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $availableProductsSum }}</h4>
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
                                    <p class="text-dark mb-1">Total Blocked Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $blockProductsSum }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Shortage Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">0</h4>
                                        <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Exceed Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">0</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>

            </div>

            <div class="card mt-4">
                <div class="card-body pb-1">
                    <div class="row align-items-end">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">From Date <span
                                                class="text-muted">(Optional)</span></label>
                                        <div class="input-icon-start position-relative">
                                            <input type="date" class="form-control date-range bookingrange"
                                                id="date-from" placeholder="dd/mm/yyyy">
                                            <span class="input-icon-left">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">To Date <span class="text-muted">(Optional)</span></label>
                                        <div class="input-icon-start position-relative">
                                            <input type="date" class="form-control date-range bookingrange"
                                                id="date-to" placeholder="dd/mm/yyyy">
                                            <span class="input-icon-left">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <button id="filterData" class="btn btn-primary w-100">
                                            <i class="ti ti-filter me-1"></i>Apply Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <button id="resetFilter" class="btn btn-secondary w-100">
                                            <i class="ti ti-refresh me-1"></i>Reset Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <button id="exportData" class="btn btn-success w-100">
                                    <i class="ti ti-download me-1"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
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
                                        <th>Brand</th>
                                        <th>Brand&nbsp;Title</th>
                                        <th>Category</th>
                                        <th>SKU</th>
                                        <th>PCS/Set</th>
                                        <th>Sets/CTN</th>
                                        <th>MRP</th>
                                        {{-- <th>po&nbsp;status</th> --}}
                                        <th>Original&nbsp;Quantity</th>
                                        <th>Available&nbsp;Quantity</th>
                                        <th>Block&nbsp;Qty</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $product->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->product->brand }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->product->brand_title }}</td>
                                            <td>{{ $product->product->category }}</td>
                                            <td>{{ $product->product->sku }}</td>
                                            <td>{{ $product->product->pcs_set }}</td>
                                            <td>{{ $product->product->sets_ctn }}</td>
                                            <td>{{ $product->product->mrp }}</td>
                                            {{-- <td>{{ $product->product->status === '1' ? 'Active' : 'Inactive' }}</td> --}}
                                            <td>{{ $product->original_quantity ?? 0 }}</td>
                                            <td>{{ $product->available_quantity ?? 0 }}</td>
                                            <td>{{ $product->block_quantity ?? 0 }}</td>
                                            <td>{{ $product->product->created_at->format('d-m-Y') }}</td>
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

            // Initialize DataTable for inventory stock history
            var inventoryStockTable = $('#inventory-stock-history-table').DataTable({
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0] // Disable sorting for checkbox column
                }],
                lengthChange: true,
                pageLength: 10,
                order: [
                    [11, 'desc']
                ], // Sort by Date column (index 11) in descending order
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            /**
             * Apply Filter Button Click Handler
             *
             * Filter Logic:
             * 1. Get the selected from and to dates from input fields
             * 2. Convert dates from YYYY-MM-DD to DD-MM-YYYY format for matching table data
             * 3. Apply custom search function to filter table rows based on date range
             * 4. If only from date: show records on or after that date
             * 5. If only to date: show records on or before that date
             * 6. If both dates: show records within the range (inclusive)
             * 7. If no dates: show all records
             */
            $(document).on('click', '#filterData', function() {
                var selectedDateFrom = $('#date-from').val().trim();
                var selectedDateTo = $('#date-to').val().trim();

                // Clear any existing custom search functions to avoid stacking filters
                $.fn.dataTable.ext.search.length = 0;

                // Helper to parse table date (DD-MM-YYYY) and normalize to midnight
                function parseTableDate(dateStr) {
                    if (!dateStr) return null;
                    var parts = dateStr.trim().split('-');
                    if (parts.length !== 3) return null;
                    var d = new Date(+parts[2], +parts[1] - 1, +parts[0]);
                    d.setHours(0, 0, 0, 0);
                    return d;
                }

                // Helper to parse input[type=date] value (YYYY-MM-DD) and normalize to midnight
                function parseInputDate(input) {
                    if (!input) return null;
                    var parts = input.split('-');
                    if (parts.length !== 3) return null;
                    var d = new Date(+parts[0], +parts[1] - 1, +parts[2]);
                    d.setHours(0, 0, 0, 0);
                    return d;
                }

                // Custom search function for date range filtering
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    // Get the date from the last column (index 11)
                    var dateStr = data[11] || '';
                    var rowDate = parseTableDate(dateStr);
                    if (!rowDate) return true; // if no parsable date, don't filter it out

                    var fromDate = parseInputDate(selectedDateFrom);
                    var toDate = parseInputDate(selectedDateTo);

                    if (fromDate && toDate) {
                        return rowDate >= fromDate && rowDate <= toDate;
                    } else if (fromDate) {
                        return rowDate >= fromDate;
                    } else if (toDate) {
                        return rowDate <= toDate;
                    }

                    return true;
                });

                // Redraw the table with the filter applied
                inventoryStockTable.draw();
            });

            /**
             * Reset Filter Button Click Handler
             *
             * Reset Logic:
             * 1. Clear both date input fields
             * 2. Remove all custom search functions from DataTable
             * 3. Redraw the table to show all records
             * 4. Provide visual feedback to user
             */
            $(document).on('click', '#resetFilter', function() {
                // Clear date input fields
                $('#date-from').val('');
                $('#date-to').val('');

                // Remove all custom search functions (clear the array)
                $.fn.dataTable.ext.search.length = 0;

                // Redraw table to show all records
                inventoryStockTable.draw();

                // Optional: Show success message
                console.log('Filters reset successfully');
            });

            /**
             * Generate Report Button Click Handler
             *
             * CSV Export Logic:
             * 1. Get the selected from and to dates from input fields
             * 2. Build query parameters only for dates that are provided
             * 3. Construct download URL with parameters
             * 4. Trigger browser download of CSV file
             * 5. The backend will filter data based on these parameters
             * 6. If no dates provided, all records will be exported
             */
            $(document).on('click', '#exportData', function() {
                var selectedDateFrom = $("#date-from").val().trim();
                var selectedDateTo = $("#date-to").val().trim();

                // Build query parameters only for the values provided
                var params = [];
                if (selectedDateFrom) {
                    params.push('from=' + encodeURIComponent(selectedDateFrom));
                }
                if (selectedDateTo) {
                    params.push('to=' + encodeURIComponent(selectedDateTo));
                }
                var queryString = params.length ? '?' + params.join('&') : '';

                // Construct download URL with parameters
                var downloadUrl = '{{ route('inventory.stock.history.excel') }}' + queryString;

                // Trigger browser download
                window.location.href = downloadUrl;
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
