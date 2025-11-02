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
                            <li class="breadcrumb-item active" aria-current="page">Vendor Purchase Report</li>
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
                                            {{ $purchaseOrdersTotal * $purchaseOrdersTotalQuantity . '₹' }}</h4>
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
                                        <h4 class="text-dark">{{ $purchaseOrders->sum('paid_amount') }}</h4>
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
                                        <h4 class="text-dark">{{ $purchaseOrders->sum('due_amount') }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4">
                <div class="card-body pb-1">
                    <div class="row align-items-end">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Choose From Date</label>
                                        <div class="input-icon-start position-relative">
                                            <input type="date" class="form-control date-range bookingrange"
                                                id="date-from" placeholder="dd/mm/yyyy">
                                            <span class="input-icon-left">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Choose To Date</label>
                                        <div class="input-icon-start position-relative">
                                            <input type="date" class="form-control date-range bookingrange"
                                                id="date-to" placeholder="dd/mm/yyyy">
                                            <span class="input-icon-left">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Vendor Name</label>
                                        <select id="vendor-code" class="form-select">
                                            <option value="" selected>-- Select --</option>
                                            @foreach ($purchaseOrdersVendors as $purchaseOrdersVendor)
                                                <option value="{{ $purchaseOrdersVendor }}">{{ $purchaseOrdersVendor }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Cash</option>
                                                <option>Paypal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Status</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>All</option>
                                                <option>Paid</option>
                                                <option>Unpaid </option>
                                                <option>Paid</option>
                                            </select>
                                        </div>
                                    </div> --}}
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <button id="filterData" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="mb-3">
                                <button id="exportData" class="btn btn-danger w-100">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            @php
                                $statuses = [
                                    'approve' => 'Pending',
                                    'blocked' => 'Blocked',
                                    'completed' => 'Completed',
                                    'ready_to_ship' => 'Ready To Ship',
                                    'ready_to_package' => 'Ready To Package',
                                ];
                            @endphp
                            <table id="vendor-purchase-history-table" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Order&nbsp;Id</th>
                                        <th>Vendor&nbsp;Name</th>
                                        <th>Ordered&nbsp;Status</th>
                                        <th>Ordered&nbsp;Quantity</th>
                                        <th>Received&nbsp;Quantity</th>
                                        <th>Total&nbsp;Amount</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Ordered&nbsp;Date</th>
                                        <th>Appointment&nbsp;Date</th>
                                        <th>POD</th>
                                        <th>LR</th>
                                        <th>DN</th>
                                        <th>GRN</th>
                                        <th>Invoice</th>
                                        <th>Payment&nbsp;Status</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchaseOrders as $purchaseOrder)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>{{ $purchaseOrder->purchase_order_id }}</td>
                                            <td>{{ $purchaseOrder->vendor_code ?? 'NA' }}</td>
                                            <td>{{ ucfirst($purchaseOrder->status) }}</td>
                                            <td>{{ $purchaseOrder->products->sum('quantity_requirement') }}</td>
                                            <td>{{ $purchaseOrder->products->sum('quantity_received') }}</td>
                                            <td>{{ $purchaseOrder->products->sum('mrp') }}</td>
                                            <td>{{ $purchaseOrder->products->sum('paid_amount') ?? '0' }}</td>
                                            <td>{{ $purchaseOrder->products->sum('due_amount') ?? '0' }}</td>
                                            <td>{{ $purchaseOrder->created_at?->format('d-m-Y') ?? 'NA' }}</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No Records Found</td>
                                        </tr>
                                    @endforelse

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

            $(document).on('click', '#exportData', function() {
                var selectedDateFrom = $("#date-from").val().trim();
                var selectedDateTo = $("#date-to").val().trim();

                // Format dates from yyyy-mm-dd to dd-mm-yyyy if backend expects that format
                var formattedFrom = '';
                var formattedTo = '';
                if (selectedDateFrom) {
                    var partsFrom = selectedDateFrom.split('-');
                    if (partsFrom.length === 3) {
                        formattedFrom = partsFrom[2] + '-' + partsFrom[1] + '-' + partsFrom[0];
                    } else {
                        formattedFrom = selectedDateFrom;
                    }
                }
                if (selectedDateTo) {
                    var partsTo = selectedDateTo.split('-');
                    if (partsTo.length === 3) {
                        formattedTo = partsTo[2] + '-' + partsTo[1] + '-' + partsTo[0];
                    } else {
                        formattedTo = selectedDateTo;
                    }
                }

                var vendorCode = $("#vendor-code").val();

                // Construct download URL with parameters
                var downloadUrl = '{{ route('vendor.purchase.history.excel') }}' +
                    '?date_from=' + encodeURIComponent(formattedFrom) +
                    '&date_to=' + encodeURIComponent(formattedTo) +
                    '&vendorCode=' + encodeURIComponent(vendorCode);

                // Trigger browser download
                window.location.href = downloadUrl;
            });

            $(document).on('click', '#filterData', function() {
                var selectedDateFrom = $('#date-from').val();
                var selectedDateTo = $('#date-to').val();

                // Format date range for filtering (convert yyyy-mm-dd to dd-mm-yyyy)
                var formattedDateRange = '';
                var formattedFrom = '';
                var formattedTo = '';
                if (selectedDateFrom) {
                    var partsFrom = selectedDateFrom.split('-');
                    if (partsFrom.length === 3) {
                        formattedFrom = partsFrom[2] + '-' + partsFrom[1] + '-' + partsFrom[0];
                    } else {
                        formattedFrom = selectedDateFrom;
                    }
                }
                if (selectedDateTo) {
                    var partsTo = selectedDateTo.split('-');
                    if (partsTo.length === 3) {
                        formattedTo = partsTo[2] + '-' + partsTo[1] + '-' + partsTo[0];
                    } else {
                        formattedTo = selectedDateTo;
                    }
                }

                if (formattedFrom && formattedTo) {
                    formattedDateRange = formattedFrom + ' to ' + formattedTo;
                } else if (formattedFrom) {
                    formattedDateRange = formattedFrom;
                } else if (formattedTo) {
                    formattedDateRange = formattedTo;
                }

                // Apply date range filter to the DataTable (use the initialized vendorHistoryTable and the "Ordered Date" column index)
                vendorHistoryTable.column(9).search(formattedDateRange ? '^' + formattedDateRange + '$' : '', true, false)
                    .draw();
            });


            var vendorHistoryTable = $('#vendor-purchase-history-table').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        //   "targets": [0, -1],
                    } // Disable sorting for the 4th column (index starts at 0)
                ],
                lengthChange: true,
                // buttons: ['excel', 'pdf', 'print']
                // buttons: ['excel']
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            $('#date-select').on('change', function() {
                var selected = $(this).val().trim();
                if (selected) {
                    var parts = selected.split('-');
                    var formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
                }
                vendorHistoryTable.column(-1).search(formatted ? '^' + formatted + '$' : '', true, false)
                    .draw();
            });

            $('#vendor-code').on('change', function() {
                var selected = $(this).val().trim();
                vendorHistoryTable.column(2).search(selected ? '^' + selected + '$' : '', true, false)
                    .draw();
            });

        });
    </script>
@endsection
