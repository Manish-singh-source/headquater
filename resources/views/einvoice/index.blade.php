@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">E-Invoices List</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">E-Invoices List</h5>
                        <form id="bulkEInvoiceDownloadForm" action="{{ route('einvoices.bulkDownload') }}" method="POST">
                            @csrf
                            <div id="selectedEInvoiceIds"></div>
                            <button id="bulkEInvoiceDownload" type="submit" class="btn btn-primary btn-sm" disabled>
                                <i class="bx bx-download me-1"></i>Download Selected in ZIP
                            </button>
                        </form>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="sales-order" role="tabpanel" aria-labelledby="sales-order-tab">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example" class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;"><input class="form-check-input" type="checkbox" id="selectAllSales"></th>
                                            <th>Sales&nbsp;Order&nbsp;ID</th>
                                            <th>Client&nbsp;Name</th>
                                            <th>Invoice&nbsp;No</th>
                                            <th>IRN&nbsp;No</th>
                                            <th>Invoice&nbsp;PDF</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($eInvoice as $invoice)
                                            <tr>
                                                <td>
                                                    @if ($invoice->einvoice_status === 'ACT')
                                                        <input class="form-check-input einvoice-select" type="checkbox"
                                                            value="{{ $invoice->id }}" aria-label="Select e-invoice {{ $invoice->invoice->invoice_number ?? $invoice->id }}">
                                                    @endif
                                                </td>
                                                <td>{{ $invoice->invoice->salesOrder->order_number ?? 'N/A' }}</td>
                                                <td>{{ $invoice->invoice->customer->client_name ?? 'N/A' }}</td>
                                                <td>{{ $invoice->invoice->invoice_number ?? 'N/A' }}</td>
                                                <td>{{ $invoice->irn ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($invoice->einvoice_status === 'ACT')
                                                        <a href="{{ route('invoice.downloadEInvoicePdf', $invoice->id) }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-sm bg-primary-subtle me-1">Download</a>
                                                    @else
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($invoice->einvoice_status)
                                                        <span class="badge {{ $invoice->einvoice_status == 'ACT' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ ucfirst($invoice->einvoice_status) == 'ACT' ? 'Active' : 'Cancelled' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">No sales order invoices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
            var table = $('#example').DataTable();

            function updateBulkDownloadButton() {
                $('#bulkEInvoiceDownload').prop('disabled', $('#example tbody .einvoice-select:checked').length === 0);
            }

            $('#selectAllSales').on('change', function() {
                $('#example tbody .einvoice-select').prop('checked', $(this).prop('checked'));
                updateBulkDownloadButton();
            });

            $('#example tbody').on('change', '.einvoice-select', function() {
                var totalCheckboxes = $('#example tbody .einvoice-select').length;
                var checkedCheckboxes = $('#example tbody .einvoice-select:checked').length;
                $('#selectAllSales').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
                updateBulkDownloadButton();
            });

            table.on('draw', function() {
                $('#selectAllSales').prop('checked', false);
                updateBulkDownloadButton();
            });

            $('#bulkEInvoiceDownloadForm').on('submit', function() {
                var selectedIds = $('#example tbody .einvoice-select:checked').map(function() {
                    return this.value;
                }).get();
                var inputContainer = $('#selectedEInvoiceIds').empty();

                selectedIds.forEach(function(id) {
                    $('<input>', { type: 'hidden', name: 'einvoice_ids[]', value: id }).appendTo(inputContainer);
                });

                return selectedIds.length > 0;
            });
        });
    </script>
@endsection
