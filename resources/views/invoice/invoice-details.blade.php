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
                                        @if(!$invoiceDetails->irn)
                                            <form action="{{ route('invoice.generateEInvoice', $invoiceDetails->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-sm bg-success-subtle me-1">Generate E-Invoice</button>
                                            </form>
                                        @endif
                                    </span>
                                </li>
                                @if($invoiceDetails->irn)
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>IRN</b></span>
                                    <span>{{ $invoiceDetails->irn }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Ack No</b></span>
                                    <span>{{ $invoiceDetails->ack_no }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>E-Invoice Status</b></span>
                                    <span>
                                        @if($invoiceDetails->einvoice_status === 'ACT')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($invoiceDetails->einvoice_status === 'CAN')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            {{ $invoiceDetails->einvoice_status }}
                                        @endif
                                    </span>
                                </li>
                                @if($invoiceDetails->irn)
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>E-Invoice PDF</b></span>
                                    <span><a href="{{ route('invoice.downloadEInvoicePdf', $invoiceDetails->id) }}" target="_blank" class="btn btn-icon btn-sm bg-primary-subtle me-1">Download</a></span>
                                </li>
                                @if($invoiceDetails->einvoice_status === 'ACT' && (!$invoiceDetails->ewb_no || $invoiceDetails->ewb_valid_till < now()))
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Cancel E-Invoice</b></span>
                                    <span>
                                        <form action="{{ route('invoice.cancelEInvoice', $invoiceDetails->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this E-Invoice? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm bg-danger-subtle me-1">Cancel E-Invoice</button>
                                        </form>
                                    </span>
                                </li>
                                @endif
                                @endif

                                @if($invoiceDetails->irn)
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>E-Way Bill</b></span>
                                    <span>
                                        @if($invoiceDetails->ewb_no && $invoiceDetails->ewb_valid_till > now())
                                            <span class="badge bg-success me-2">Active</span>
                                            <a href="{{ $invoiceDetails->ewaybill_pdf }}" target="_blank" class="btn btn-icon btn-sm bg-primary-subtle me-1">Download PDF</a>
                                        @elseif($invoiceDetails->ewb_no && $invoiceDetails->ewb_valid_till <= now())
                                            <span class="badge bg-warning me-2">Expired</span>
                                            <form action="{{ route('invoice.generateEWayBill', $invoiceDetails->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-sm bg-info-subtle me-1">Generate New</button>
                                            </form>
                                        @else
                                            <form action="{{ route('invoice.generateEWayBill', $invoiceDetails->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-sm bg-success-subtle me-1">Generate E-Way Bill</button>
                                            </form>
                                        @endif
                                    </span>
                                </li>
                                @if($invoiceDetails->ewb_no)
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>E-Way Bill No</b></span>
                                    <span>{{ $invoiceDetails->ewb_no }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>E-Way Bill Date</b></span>
                                    <span>{{ $invoiceDetails->ewb_dt ? \Carbon\Carbon::parse($invoiceDetails->ewb_dt)->format('d/m/Y H:i') : 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Valid Till</b></span>
                                    <span>{{ $invoiceDetails->ewb_valid_till ? \Carbon\Carbon::parse($invoiceDetails->ewb_valid_till)->format('d/m/Y H:i') : 'N/A' }}</span>
                                </li>
                                @endif
                                @endif
                                @endif
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
