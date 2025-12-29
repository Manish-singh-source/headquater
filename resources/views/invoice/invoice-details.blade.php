@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100">
                            <div class="card-header">
                                <h4 class="card-title">Invoice Details</h4>
                            </div>
                            <div class="card-body">
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
                                            @if (count($invoiceDetails->einvoices) == 0)
                                                <form action="{{ route('invoice.generateEInvoice', $invoiceDetails->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-icon btn-sm bg-success-subtle me-1">Generate
                                                        E-Invoice</button>
                                                </form>
                                            @endif
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
                        </div>

                        @if (count($invoiceDetails->einvoices) > 0)
                            <div class="card w-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">E-Invoice Details</h4>

                                    @if (count($invoiceDetails->einvoices) > 0)
                                        <div
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span>
                                                @if ($total_einvoices == 0)
                                                    <form
                                                        action="{{ route('invoice.generateEInvoice', $invoiceDetails->id) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm bg-info-subtle me-1">Re Generate
                                                            E-Invoice</button>
                                                    </form>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>E-Invoice IRN</th>
                                            <th>Ack No</th>
                                            <th>E-Invoice Status</th>
                                            <th>E-Invoice PDF</th>
                                            <th>Cancel Before</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($invoiceDetails->einvoices as $einvoice)
                                            <tr>
                                                <td>{{ $einvoice->irn }}</td>
                                                <td>{{ $einvoice->ack_no }}</td>
                                                <td>
                                                    @if ($einvoice->einvoice_status === 'ACT')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($einvoice->einvoice_status === 'CAN')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        {{ $einvoice->einvoice_status }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($einvoice->einvoice_status === 'ACT')
                                                        <a href="{{ route('invoice.downloadEInvoicePdf', $einvoice->id) }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-sm bg-primary-subtle me-1">Download</a>
                                                    @elseif ($einvoice->einvoice_status === 'CAN')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($einvoice->einvoice_status === 'ACT')
                                                        @if ($einvoice->created_at->addDays(1) > now())
                                                            @if ($einvoice->ewaybills->count() == 0)
                                                                <span class="badge bg-warning me-2">
                                                                    {{ $einvoice->created_at->addDays(1)->format('d/m/Y H:i') }}</span>
                                                            @else
                                                                <span class="badge bg-success">E-Way Bill Generated</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-success me-2">Cannot Cancel</span>
                                                        @endif
                                                    @elseif ($einvoice->einvoice_status === 'CAN')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($einvoice->einvoice_status === 'ACT')
                                                        @if ($einvoice->ewaybills->count() == 0)
                                                            <button type="button"
                                                                class="btn btn-icon btn-sm bg-danger-subtle me-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#cancelEInvoiceModal">Cancel
                                                                E-Invoice</button>
                                                            <button type="button"
                                                                class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                                data-bs-toggle="modal" data-bs-target="#ewayBillModal"
                                                                data-invoiceid="{{ $invoiceDetails->id }}"
                                                                data-einvoiceid="{{ $einvoice->id }}"
                                                                data-irn="{{ $einvoice->irn }}">Generate
                                                                E-Way Bill</button>
                                                        @else
                                                            <span class="badge bg-success">E-Way Bill Generated</span>
                                                        @endif
                                                    @elseif($einvoice->einvoice_status === 'CAN')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>

                                </div>
                            </div>
                        @endif

                        @if ($invoiceDetails->ewaybills->count() > 0)
                            <div class="card w-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">E-Way Bill Details</h4>
                                </div>
                                <div class="card-body">

                                    <table class="table table-bordered">
                                        <tr>
                                            <th>E-Invoice IRN</th>
                                            <th>E-Way Bill No</th>
                                            <th>E-Way Bill Date</th>
                                            <th>Valid Till</th>
                                            <th>E-Way Bill PDF</th>
                                            <th>Cancel Before</th>
                                            <th>Action</th>
                                        </tr>

                                        @foreach ($invoiceDetails->ewaybills as $ewaybill)
                                            <tr>
                                                <td>{{ $ewaybill->einvoice->irn }}</td>
                                                <td>{{ $ewaybill->ewb_no }}</td>
                                                <td>{{ $ewaybill->ewb_dt }}</td>
                                                <td>{{ $ewaybill->ewb_valid_till }}</td>
                                                <td>
                                                    <a href="{{ $ewaybill->ewaybill_pdf }}" target="_blank"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1">Download</a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning me-2">
                                                        {{ $ewaybill->created_at->addDays(1)->format('d/m/Y H:i') }}</span>
                                                </td>
                                                <td>
                                                    @if ($ewaybill->created_at->addDays(1) > now())
                                                        <form action="{{ route('invoice.cancelEWayBill', $ewaybill->id) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Are you sure you want to cancel this E-Way Bill? This action cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-icon btn-sm bg-danger-subtle me-1">Cancel
                                                                E-Way
                                                                Bill</button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-success me-2">Cannot Cancel</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif


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
                                                target="_blank" class="btn btn-icon btn-sm bg-primary-subtle me-1">View
                                            </a>
                                        </li>
                                    @endif
                                    @if ($invoiceDetails->appointment->grn)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>GRN</b></span>
                                            <a href="{{ asset('uploads/grn/' . $invoiceDetails->appointment->grn) }}"
                                                target="_blank" class="btn btn-icon btn-sm bg-primary-subtle me-1">View
                                            </a>
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
    </main>


    @if (count($invoiceDetails->einvoices) > 0)
        {{-- Cancel E-Invoice Modal --}}
        <div class="modal fade" id="cancelEInvoiceModal" tabindex="-1" aria-labelledby="cancelEInvoiceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('invoice.cancelEInvoice', $einvoice->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelEInvoiceModalLabel">Cancel
                                E-Invoice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger">Are you sure you want
                                to cancel this E-Invoice? This action
                                cannot be undone.</p>
                            <div class="mb-3">
                                <label for="cancel_reason">Cancel Reason</label>
                                <select name="cancel_reason" id="cancel_reason" class="form-control" required>
                                    <option value="" selected disabled>Select Cancel Reason</option>
                                    <option value="1">Duplicate IRN</option>
                                    <option value="2">Data entry mistake</option>
                                    <option value="3">Order cancelled</option>
                                    <option value="4">Others</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cancel_reason">Cancel Remark</label>
                                <input type="text" name="cancel_remark" class="form-control"
                                    placeholder="Cancel Remark" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-danger">Cancel
                                E-Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- E-Way Bill Modal -->
    <div class="modal fade" id="ewayBillModal" tabindex="-1" aria-labelledby="ewayBillModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ewayBillModalLabel">Generate E-Way Bill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('invoice.generateEWayBill', $invoiceDetails->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="update_mode" name="update_mode"
                                        value="API" readonly>
                                    <input type="hidden" name="einvoice_irn" value="" id="einvoice_irn">
                                    <input type="hidden" name="invoice_id" value="" id="invoice_id">
                                    <input type="hidden" name="einvoice_id" value="" id="einvoice_id">
                                    <label for="vehicle_number" class="form-label">Vehicle Number</label>
                                    <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                                        placeholder="KA01AB1234" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="place_of_consignor" class="form-label">Place of Consignor</label>
                                    <input type="text" class="form-control" id="place_of_consignor"
                                        name="place_of_consignor" placeholder="Haldwani" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_of_consignor" class="form-label">State of Consignor</label>
                                    <input type="text" class="form-control" id="state_of_consignor"
                                        name="state_of_consignor" placeholder="UTTARAKHAND" required>
                                </div>
                            </div>
                            
                            {{-- 
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="distance" class="form-label">Distance (km)</label>
                                    <input type="number" class="form-control" id="distance" name="distance"
                                        placeholder="280" required>
                                </div>
                            </div> 
                            --}}
                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userGstin" class="form-label">User GSTIN</label>
                                    <input type="text" class="form-control" id="userGstin" name="userGstin"
                                        placeholder="05AAAPG7885R002" required>
                                </div>
                            </div> --}}
                            
                        {{-- 
                        
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tripshtNo" class="form-label">Trip Sheet No</label>
                                    <input type="number" class="form-control" id="tripshtNo" name="tripshtNo"
                                        value="0">
                                </div>
                            </div>
                            --}}
                        
                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_number_update_date" class="form-label">Vehicle Number Update
                                        Date</label>
                                    <input type="text" class="form-control" id="vehicle_number_update_date"
                                        name="vehicle_number_update_date" placeholder="12/12/2025 11:38:00 AM" required>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transporter_name" class="form-label">Transporter Name</label>
                                    <input type="text" class="form-control" id="transporter_name"
                                        name="transporter_name" placeholder="Test Transporter" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transporter_id" class="form-label">Transporter ID(GSTIN)</label>
                                    <input type="text" class="form-control" id="transporter_id"
                                        name="transporter_id" placeholder="05AAABB0639G1Z8" value="05AAABB0639G1Z8" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transportation_mode" class="form-label">Transportation Mode</label>
                                    <select class="form-control" id="transportation_mode" name="transportation_mode"
                                        required>
                                        <option value="Road" selected>Road</option>
                                        <option value="Rail">Rail</option>
                                        <option value="Air">Air</option>
                                        <option value="Ship">Ship</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transporter_document_number" class="form-label">Transporter Document
                                        Number</label>
                                    <input type="text" class="form-control" id="transporter_document_number"
                                        name="transporter_document_number" placeholder="DOC1765519669" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transporter_document_date" class="form-label">Transporter Document
                                        Date</label>
                                    <input type="date" class="form-control" id="transporter_document_date"
                                        name="transporter_document_date" placeholder="12/12/2025" required>
                                </div>
                            </div>
                            
                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group_number" class="form-label">Group Number</label>
                                    <input type="text" class="form-control" id="group_number" name="group_number"
                                        value="0">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Generate E-Way Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        // 
        $(document).ready(function() {
            $('#ewayBillModal').on('show.bs.modal', function(event) {
                console.log("Hello")
                var button = $(event.relatedTarget) // Button that triggered the modal
                var invoiceId = button.data('invoiceid') // Extract info from data* attributes
                var einvoiceId = button.data('einvoiceid') // Extract info from data-* attributes
                var irn = button.data('irn') // Extract info from data-* attributes
                console.log(irn);
                console.log(invoiceId);
                console.log(einvoiceId);

                var modal = $(this)
                modal.find('.modal-body #invoice_id').val(invoiceId)
                modal.find('.modal-body #einvoice_id').val(einvoiceId)
                modal.find('.modal-body #einvoice_irn').val(irn)
            })

            $('#cancelInvoiceModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var invoiceId = button.data('invoice-id') // Extract info from data-* attributes
                var modal = $(this)
                modal.find('.modal-body #invoice_id').val(invoiceId)
            })

        });
    </script>
@endsection
