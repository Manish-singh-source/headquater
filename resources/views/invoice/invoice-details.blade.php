@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Invoice Id</b></span>
                                    <span>
                                        <span id="orderId">{{ $invoiceDetails->invoice_number }}</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Invoice</b></span>
                                    <span>
                                        <a href="{{ route('invoice.downloadPdf', $invoiceDetails->id) }}"
                                            class="btn btn-icon btn-sm bg-primary-subtle me-1">Download</a>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Client Name</b></span>
                                    <span>{{ $invoiceDetails->customer->client_name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Address</b></span>
                                    <span>{{ $invoiceDetails->customer->billing_address }}</span>
                                </li>
                            </ul>
                        </div>

                        @if ($invoiceDetails->appointment)
                            <div class="card w-100 d-flex  flex-sm-row flex-col">
                                <ul class="col-12 list-group list-group-flush">
                                    @if ($invoiceDetails->appointment->appointment_date)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Appointment Date</b></span>
                                            <span>{{ $invoiceDetails->appointment->appointment_date }}</span>
                                        </li>
                                    @endif
                                    @if ($invoiceDetails->appointment->pod)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>POD</b></span>
                                            <a href="{{ asset('uploads/pod/' . $invoiceDetails->appointment->pod) }}"
                                                target="_blank" class="btn btn-icon btn-sm bg-primary-subtle me-1">View </a>
                                        </li>
                                    @endif
                                    @if ($invoiceDetails->appointment->grn)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>GRN</b></span>
                                            <a href="{{ asset('uploads/grn/' . $invoiceDetails->appointment->grn) }}"
                                                target="_blank" class="btn btn-icon btn-sm bg-primary-subtle me-1">View </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        @if (!empty($invoiceDetails->dns) && $invoiceDetails->dns->count() > 0)
                            <div class="card w-100 d-flex  flex-sm-row flex-col">
                                <ul class="col-12 list-group list-group-flush">
                                    {{-- <li>DN Details</li> --}}
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>DN Amount</b></span>
                                        <span>{{ $invoiceDetails->dns->dn_amount }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>DN Reason</b></span>
                                        <span>{{ $invoiceDetails->dns->dn_reason }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>DN Receipt</b></span>
                                        {{-- <span>{{ $invoiceDetails->dns->dn_reason }}</span> --}}
                                        <a href="{{ asset('uploads/dn_receipts/' . $invoiceDetails->dns->dn_receipt) }}"
                                            class="btn btn-icon btn-sm bg-primary-subtle me-1">View </a>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        @if (!empty($invoiceDetails->payments) && $invoiceDetails->payments->count() > 0)
                            <div class="card w-100 d-flex  flex-sm-row flex-col">
                                <ul class="col-12 list-group list-group-flush">
                                    <li class="list-group-item">Payment Details:</li>
                                    @foreach ($invoiceDetails->payments as $key => $payment)
                                        <li class="list-group-item">Payment Step {{ $key + 1 }}</li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Payment Status</b></span>
                                            <span>{{ ucfirst($payment->payment_status) }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Payment Method</b></span>
                                            <span>{{ ucfirst($payment->payment_method) }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Payment UTR No</b></span>
                                            <span>{{ ucfirst($payment->payment_utr_no) }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Payment Amount</b></span>
                                            <span>{{ ucfirst($payment->amount) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection
