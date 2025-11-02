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
                            <li class="breadcrumb-item active" aria-current="page">Customer Purchase Report</li>
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
                                    <p class="text-dark mb-1">Total Customer Orders</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $customers->count() }}</h4>
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
                                        <h4 class="text-dark">{{ number_format($invoicesAmountSum, 2) }}</h4>
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
                                        <h4 class="text-dark">{{ number_format($invoicesAmountPaidSum, 2) }}</h4>
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
                                        <h4 class="text-dark">
                                            {{ number_format($invoicesAmountSum - $invoicesAmountPaidSum, 2) }}</h4>
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
                                        <label class="form-label">Customer Name</label>
                                        <select id="customer-select" class="form-select">
                                            <option selected>-- Select --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer['id'] }}" data-name="{{ $customer['name'] }}">{{ $customer['name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
                            <table id="customer-sales-history-table" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Reference</th>
                                        {{-- <th>Customer Id</th> --}}
                                        <th>Customer&nbsp;Name</th>
                                        <th>Ordered&nbsp;Date</th>
                                        {{-- <th>Delivery Date</th> --}}
                                        <th>Total&nbsp;Amount</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Appointment&nbsp;Date</th>
                                        <th>POD</th>
                                        <th>LR</th>
                                        <th>DN</th>
                                        <th>GRN</th>
                                        <th>Invoice</th>
                                        <th>Payment&nbsp;Status</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            {{-- <td>#{{ $invoice->sales_order_id }}</td> --}}
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->client_name }}</td>
                                            <td>
                                                {{ $invoice->invoice_date }}
                                            </td>
                                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>{{ number_format($invoice->payments?->sum('amount'), 2) }}</td>
                                            <td>{{ number_format($invoice->total_amount - $invoice->payments?->sum('amount'), 2) }}
                                            </td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>Yes</td>
                                            <td>
                                                <a aria-label="anchor"
                                                    href="{{ route('invoice.downloadPdf', $invoice->id) }}"
                                                    class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                    data-bs-toggle="tooltip" data-bs-original-title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-eye text-primary">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                            </td>
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
            $(document).on('click', '#exportData', function() {
                var selectedDateFrom = $('#date-from').val();
                var selectedDateTo = $('#date-to').val();
                                var params = [];
                if (selectedDateFrom) {
                    params.push('from=' + encodeURIComponent(selectedDateFrom));
                }
                if (selectedDateTo) {
                    params.push('to=' + encodeURIComponent(selectedDateTo));
                }
                var customerId = $("#customer-select").val();
                if (customerId && customerId !== '-- Select --') {
                    params.push('customerId=' + encodeURIComponent(customerId));
                }
                var queryString = params.length ? '?' + params.join('&') : '';

                // Construct download URL with parameters
                var downloadUrl = '{{ route('customer.sales.history.excel') }}' + queryString;

                // Trigger browser download
                window.location.href = downloadUrl;
            });

            $(document).on('click', '#filterData', function() {
                var selectedDateFrom = $('#date-from').val();
                var selectedDateTo = $('#date-to').val();

                // Format date range for filtering
                var formattedDateRange = '';
                if (selectedDateFrom && selectedDateTo) {
                    formattedDateRange = selectedDateFrom + ' to ' + selectedDateTo;
                } else if (selectedDateFrom) {
                    formattedDateRange = selectedDateFrom;
                } else if (selectedDateTo) {
                    formattedDateRange = selectedDateTo;
                }

                // Apply date range filter to the DataTable
                customerSalesHistoryTable.column(3).search(formattedDateRange ? '^' + formattedDateRange + '$' : '', true,
                        false)
                    .draw();
            });

            var customerSalesHistoryTable = $('#customer-sales-history-table').DataTable({
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
                // date formatting
                // if (selected) {
                //     var parts = selected.split('-');
                //     var formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
                // }

                customerSalesHistoryTable.column(3).search(selected ? '^' + selected + '$' : '', true,
                        false)
                    .draw();
            });

            $('#customer-select').on('change', function() {
                // var selected = $(this).val().trim();
                var selected = $("#customer-select option:selected").data('name');
                console.log(selected)
                customerSalesHistoryTable.column(2).search(selected ? '^' + selected + '$' : '', true,
                        false)
                    .draw();
            });

        });
    </script>
@endsection
