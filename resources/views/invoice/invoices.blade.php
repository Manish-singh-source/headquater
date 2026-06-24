@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('invoices') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Order Invoices</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0">Invoice List</h5>
                            @if (request()->filled('invoice_no'))
                                <span class="badge bg-primary">
                                    Filter: {{ request('invoice_no') }}
                                </span>
                            @endif
                        </div>

                        @if (request()->filled('invoice_no'))
                            <a href="{{ route('invoices.view', ['id' => request()->route('id')]) }}"
                                class="btn btn-outline-secondary btn-sm">
                                Clear
                            </a>
                        @endif

                    </div>
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;"><input class="form-check-input" type="checkbox"
                                                id="selectAll"></th>
                                        <th>Sales&nbsp;Order&nbsp;ID</th>
                                        <th>Invoice&nbsp;No</th>
                                        <th>PO&nbsp;No</th>
                                        <th>Customer&nbsp;Name</th>
                                        <th>Warehouse</th>
                                        <th>Due&nbsp;Date</th>
                                        <th>Taxable&nbsp;Amount</th>
                                        <th>Tax&nbsp;Amount</th>
                                        <th>Total&nbsp;Amount</th>
                                        <th>Paid&nbsp;Amount</th>
                                        <th>Due&nbsp;Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                        {{-- @php
                                        $taxAmount = 0;
                                        $totalAmount = 0;
                                        foreach ($invoice->details as $detail) {
                                            $taxAmount += (($detail->unit_price * $detail->quantity) * ($detail->tax / 100));
                                        }

                                        $totalAmount += ($invoice->total_amount + $taxAmount);
                                        
                                    @endphp --}}
                                        <tr>
                                            <td><input class="form-check-input" type="checkbox"></td>
                                            <td>{{ $invoice->salesOrder->order_number }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->po_number ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->client_name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->warehouse->name ?? 'All Warehouses' }}</td>
                                            <td>{{ $invoice->appointment?->appointment_date ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($invoice->taxable_amount, 2) }}</td>
                                            <td>₹{{ number_format($invoice->tax_amount, 2) }}</td>
                                            <td>₹{{ number_format($invoice->total_amount, 2) }}</td>

                                            <td>₹{{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                            <td>₹{{ number_format($invoice->total_amount - $invoice->payments->sum('amount'), 2) }}
                                            </td>
                                            <td style="min-width: 180px;">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <a aria-label="anchor"
                                                        href="{{ route('invoices-details', $invoice->id) }}"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="View"
                                                        title="View Invoice Details">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-eye text-primary">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                    {{-- 
                                                    <a aria-label="anchor"
                                                        href="{{ route('invoice.edit', $invoice->id) }}"
                                                        class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-edit text-warning">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                            </path>
                                                            <path
                                                                d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('invoice.delete', $invoice->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this invoice? This will remove linked appointment, DN, payments and invoice lines.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm bg-danger-subtle me-1"
                                                            data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-trash-2 text-danger">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                                <line x1="10" y1="11" x2="10"
                                                                    y2="17"></line>
                                                                <line x1="14" y1="11" x2="14"
                                                                    y2="17"></line>
                                                            </svg>
                                                        </button>
                                                    </form> 
                                                    --}}
                                                    @if (
                                                        !$invoice->appointment ||
                                                            !$invoice->appointment->appointment_date ||
                                                            !$invoice->appointment->pod ||
                                                            !$invoice->appointment->grn)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#appointmentView-{{ $invoice->id }}"
                                                            title="Update Appointment / POD / GRN">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/calendar--v1.png"
                                                                alt="calendar" />
                                                        </a>
                                                    @endif
                                                    @if (!$invoice->dns)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#dnView-{{ $invoice->id }}"
                                                            title="Update Delivery Note">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/document--v1.png"
                                                                alt="document" />
                                                        </a>
                                                    @endif
                                                    @if ($invoice->payments->sum('amount') < $invoice->total_amount)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#paymentView-{{ $invoice->id }}"
                                                            title="Update Payment Details">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png"
                                                                alt="payment" />
                                                        </a>
                                                    @endif
                                                    @include('invoice.partials.modals', [
                                                        'invoice' => $invoice,
                                                    ])
                                                </div>    
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted py-4">No invoices found for
                                                this sales order</td>
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
    <!--end main wrapper-->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // DataTable is already initialized in master.blade.php for #example table
            // This script adds additional functionality
            var invoiceNo = @json(trim((string) request('invoice_no', '')));
            var table = $('#example').DataTable().order([]);

            if (invoiceNo) {
                table.column(2).search(invoiceNo).draw();
            } else {
                table.draw();
            }

            // Export to Excel functionality
            $('#exportInvoices').on('click', function() {
                // Trigger the hidden Excel export button from DataTable
                table.button('.buttons-excel').trigger();
            });

            // Select All checkbox functionality
            $('#selectAll').on('click', function() {
                var isChecked = $(this).prop('checked');
                $('.form-check-input[type="checkbox"]').not('#selectAll').prop('checked', isChecked);
            });

            // Individual checkbox click
            $('.form-check-input[type="checkbox"]').not('#selectAll').on('click', function() {
                var totalCheckboxes = $('.form-check-input[type="checkbox"]').not('#selectAll').length;
                var checkedCheckboxes = $('.form-check-input[type="checkbox"]:checked').not('#selectAll')
                    .length;
                $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
            });
        });
    </script>
@endsection
