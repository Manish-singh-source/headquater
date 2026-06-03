@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">GST Report</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('layouts.errors')

            <div class="row">
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">
                                <i class="ti ti-file-text fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Rows</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">{{ $totalRows }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">
                                <i class="ti ti-currency-rupee fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Invoice Value</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">&#8377;{{ number_format($totalInvoiceValue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">
                                <i class="ti ti-file-text fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Taxable Value</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">&#8377;{{ number_format($totalTaxableValue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold"><i class="bx bx-filter-alt me-2"></i>Filter Options</h6>
                    <form method="GET" action="{{ route('gst-report') }}" id="filterForm">
                        <div class="row align-items-start">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">From Invoice Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date"
                                                value="{{ $filters['from_date'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">To Invoice Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date"
                                                value="{{ $filters['to_date'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Invoice Number</label>
                                            <input type="text" class="form-control" name="invoice_no" id="invoice_no"
                                                value="{{ $filters['invoice_no'] ?? '' }}"
                                                placeholder="Enter invoice number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bx bx-filter-alt me-1"></i>Apply Filter
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <button type="button" id="resetFilters" class="btn btn-secondary w-100">
                                                <i class="bx bx-reset me-1"></i>Reset Filters
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <button type="button" id="generateExcelReport" class="btn btn-danger w-100">
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

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">GST Report</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>GSTIN/UIN of Recipient</th>
                                    <th>Name of Recipient</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice date</th>
                                    <th>Invoice Value</th>
                                    <th>Place Of Supply</th>
                                    <th>Reverse Charge</th>
                                    <th>Applicable % of Tax Rate</th>
                                    <th>Invoice Type</th>
                                    <th>E-Commerce GSTIN</th>
                                    <th>Rate</th>
                                    <th>Taxable Value</th>
                                    <th>Cess Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gstRows as $row)
                                    <tr>
                                        <td>{{ $row['GSTIN/UIN of Recipient'] }}</td>
                                        <td>{{ $row['Name of Recipient'] }}</td>
                                        <td>{{ $row['Invoice Number'] }}</td>
                                        <td>{{ $row['Invoice date'] }}</td>
                                        <td>{{ number_format($row['Invoice Value'], 2) }}</td>
                                        <td>{{ $row['Place Of Supply'] }}</td>
                                        <td>{{ $row['Reverse Charge'] }}</td>
                                        <td>{{ number_format($row['Applicable % of Tax Rate'], 2) }}</td>
                                        <td>{{ $row['Invoice Type'] }}</td>
                                        <td>{{ $row['E-Commerce GSTIN'] }}</td>
                                        <td>{{ number_format($row['Rate'], 2) }}</td>
                                        <td>{{ number_format($row['Taxable Value'], 2) }}</td>
                                        <td>{{ number_format($row['Cess Amount'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted py-4">No GST rows found.</td>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#resetFilters').on('click', function() {
                window.location.href = '{{ route('gst-report') }}';
            });

            $('#generateExcelReport').on('click', function() {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var invoiceNo = $('#invoice_no').val().trim();

                var params = [];
                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (invoiceNo) params.push('invoice_no=' + encodeURIComponent(invoiceNo));

                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('gst-report.excel') }}' + queryString;

                var originalText = $(this).html();
                $(this).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
                $(this).prop('disabled', true);

                window.location.href = downloadUrl;

                setTimeout(function() {
                    $('#generateExcelReport').html(originalText);
                    $('#generateExcelReport').prop('disabled', false);
                }, 2000);
            });
        });
    </script>
@endpush
