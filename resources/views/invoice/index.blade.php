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
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Invoices List</li>
                        </ol>
                    </nav>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-auto">
                        <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                            <a href="{{ route('invoices.manual.create') }}" class="btn btn-success px-4">
                                <i class="bi bi-plus-lg me-2"></i>Create Manual Invoice
                            </a>
                            {{-- <a href="{{ route('create-invoice') }}" class="btn btn-primary px-4">
                                <i class="bi bi-plus-lg me-2"></i>Create Invoice
                            </a> --}}
                        </div>
                    </div>
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
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-3" id="invoiceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual"
                                    type="button" role="tab" aria-controls="manual" aria-selected="true">
                                <i class="bx bx-receipt me-1"></i>Manual Invoices ({{ $manualInvoices->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sales-order-tab" data-bs-toggle="tab" data-bs-target="#sales-order"
                                    type="button" role="tab" aria-controls="sales-order" aria-selected="false">
                                <i class="bx bx-cart me-1"></i>Sales Order Invoices ({{ $salesOrderInvoices->count() }})
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="invoiceTabsContent">
                        <!-- Manual Invoices Tab -->
                        <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                            <div class="table-responsive white-space-nowrap">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;"><input class="form-check-input" type="checkbox" id="selectAllManual"></th>
                                            <th>Invoice&nbsp;No</th>
                                            <th>PO&nbsp;No</th>
                                            <th>Customer&nbsp;Name</th>
                                            <th>Due&nbsp;Date</th>
                                            <th>Amount</th>
                                            <th>Paid&nbsp;Amount</th>
                                            <th>Due&nbsp;Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($manualInvoices as $invoice)
                                            <tr>
                                                <td><input class="form-check-input" type="checkbox"></td>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>{{ $invoice->po_number ?? 'N/A' }}</td>
                                                <td>{{ $invoice->customer->client_name ?? 'N/A' }}</td>
                                                <td>{{ $invoice->appointment?->appointment_date ?? 'N/A' }}</td>
                                                <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
                                                <td>₹{{ number_format($invoice->paid_amount ?? $invoice->payments->sum('amount'), 2) }}</td>
                                                <td>₹{{ number_format($invoice->balance_due ?? ($invoice->total_amount - $invoice->payments->sum('amount')), 2) }}</td>
                                                <td>
                                                    <a aria-label="anchor" href="{{ route('invoices-details', $invoice->id) }}"
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
                                                    @if (!$invoice->appointment || !$invoice->appointment->appointment_date || !$invoice->appointment->pod || !$invoice->appointment->grn)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal" data-bs-target="#appointmentView-{{ $invoice->id }}">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/calendar--v1.png"
                                                                alt="calendar" />
                                                        </a>
                                                    @endif
                                                    @if (!$invoice->dns)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal" data-bs-target="#dnView-{{ $invoice->id }}">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/document--v1.png"
                                                                alt="document" />
                                                        </a>
                                                    @endif
                                                    <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                        data-bs-toggle="modal" data-bs-target="#paymentView-{{ $invoice->id }}">
                                                        <img width="15" height="15"
                                                            src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png"
                                                            alt="payment" />
                                                    </a>
                                                    @include('invoice.partials.modals', ['invoice' => $invoice])
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">No manual invoices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sales Order Invoices Tab -->
                        <div class="tab-pane fade" id="sales-order" role="tabpanel" aria-labelledby="sales-order-tab">
                            <div class="table-responsive white-space-nowrap">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;"><input class="form-check-input" type="checkbox" id="selectAllSales"></th>
                                            <th>Invoice&nbsp;No</th>
                                            <th>PO&nbsp;No</th>
                                            <th>Customer&nbsp;Name</th>
                                            <th>Due&nbsp;Date</th>
                                            <th>Amount</th>
                                            <th>Paid&nbsp;Amount</th>
                                            <th>Due&nbsp;Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($salesOrderInvoices as $invoice)
                                            <tr>
                                                <td><input class="form-check-input" type="checkbox"></td>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>{{ $invoice->po_number ?? 'N/A' }}</td>
                                                <td>{{ $invoice->customer->client_name ?? 'N/A' }}</td>
                                                <td>{{ $invoice->appointment?->appointment_date ?? 'N/A' }}</td>
                                                <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
                                                <td>₹{{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                                <td>₹{{ number_format($invoice->total_amount - $invoice->payments->sum('amount'), 2) }}</td>
                                                <td>
                                                    <a aria-label="anchor" href="{{ route('invoices-details', $invoice->id) }}"
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
                                                    @if (!$invoice->appointment || !$invoice->appointment->appointment_date || !$invoice->appointment->pod || !$invoice->appointment->grn)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal" data-bs-target="#appointmentView-{{ $invoice->id }}">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/calendar--v1.png"
                                                                alt="calendar" />
                                                        </a>
                                                    @endif
                                                    @if (!$invoice->dns)
                                                        <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                            data-bs-toggle="modal" data-bs-target="#dnView-{{ $invoice->id }}">
                                                            <img width="15" height="15"
                                                                src="https://img.icons8.com/ios/50/document--v1.png"
                                                                alt="document" />
                                                        </a>
                                                    @endif
                                                    <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                        data-bs-toggle="modal" data-bs-target="#paymentView-{{ $invoice->id }}">
                                                        <img width="15" height="15"
                                                            src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png"
                                                            alt="payment" />
                                                    </a>
                                                    @include('invoice.partials.modals', ['invoice' => $invoice])
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">No sales order invoices found</td>
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
    <!--end main wrapper-->
@endsection
