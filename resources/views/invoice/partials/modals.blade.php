<!-- Appointment Modal -->
<div class="modal fade" id="appointmentView-{{ $invoice->id }}" data-bs-backdrop="static" data-bs-keyboard="false" 
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Invoice Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('invoices.appointment.update', $invoice->id) }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="col-12 mb-3">
                        <label for="appointment_date" class="form-label">Appointment Date</label>
                        <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                               value="{{ $invoice->appointment->appointment_date ?? '' }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="pod" class="form-label">Upload POD</label>
                        <input type="file" name="pod" id="pod" class="form-control">
                        @if ($invoice->appointment && $invoice->appointment->pod)
                            <a href="{{ asset('uploads/pod/' . $invoice->appointment->pod) }}" target="_blank">View POD</a>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <label for="grn" class="form-label">Upload GRN</label>
                        <input type="file" name="grn" id="grn" class="form-control">
                        @if ($invoice->appointment && $invoice->appointment->grn)
                            <a href="{{ asset('uploads/grn/' . $invoice->appointment->grn) }}" target="_blank">View GRN</a>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Submit" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DN Modal -->
<div class="modal fade" id="dnView-{{ $invoice->id }}" data-bs-backdrop="static" data-bs-keyboard="false" 
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update DN Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('invoice.dn.update', $invoice->id) }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="col-12 mb-3">
                        <label for="dn_amount" class="form-label">DN Amount<span class="text-danger">*</span></label>
                        <input type="text" name="dn_amount" id="dn_amount" class="form-control"
                               value="" required placeholder="Upload Amount">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="dn_reason" class="form-label">DN Reason<span class="text-danger">*</span></label>
                        <input type="text" name="dn_reason" id="dn_reason" class="form-control"
                               value="" required placeholder="DN Reason">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="dn_receipt" class="form-label">DN Receipt <span class="text-danger">*</span></label>
                        <input type="file" name="dn_receipt" id="dn_receipt" class="form-control"
                               value="" required placeholder="Upload ID Document">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentView-{{ $invoice->id }}" data-bs-backdrop="static" data-bs-keyboard="false" 
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Payment Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('invoice.payment.update', $invoice->id) }}" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body">
                    <div class="col-12 mb-3">
                        <label for="utr_no" class="form-label">UTR No<span class="text-danger">*</span></label>
                        <input type="text" name="utr_no" id="utr_no" class="form-control"
                               value="" required placeholder="UTR No">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="payment_status" class="form-label">Payment Amount<span class="text-danger">*</span></label>
                        <input type="text" name="pay_amount" class="form-control" placeholder="Enter Payment Amount">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="payment_method" class="form-label">Payment Method<span class="text-danger">*</span></label>
                        <select id="payment_method" name="payment_method" class="form-select">
                            <option selected disabled>Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfers">Bank Transfers</option>
                            <option value="checks">Checks</option>
                            <option value="mobile_payments">Mobile Payments</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="payment_status" class="form-label">Payment Status<span class="text-danger">*</span></label>
                        <select id="payment_status" name="payment_status" class="form-select">
                            <option selected disabled>Payment Status</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

