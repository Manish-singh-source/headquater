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
                    <!-- Invoice Summary -->
                    <div class="alert alert-info mb-3">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Total Amount:</small>
                                <div class="fw-bold">₹{{ number_format($invoice->total_amount, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Paid Amount:</small>
                                <div class="fw-bold text-success">₹{{ number_format($invoice->payments->sum('amount'), 2) }}</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <small class="text-muted">Due Amount:</small>
                                <div class="fw-bold text-warning">₹{{ number_format($invoice->total_amount - $invoice->payments->sum('amount'), 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="utr_no_{{ $invoice->id }}" class="form-label">UTR No<span class="text-danger">*</span></label>
                        <input type="text" name="utr_no" id="utr_no_{{ $invoice->id }}" class="form-control @error('utr_no') is-invalid @enderror"
                               value="{{ old('utr_no') }}" required placeholder="Enter UTR No">
                        @error('utr_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="pay_amount_{{ $invoice->id }}" class="form-label">Payment Amount<span class="text-danger">*</span></label>
                        <input type="number" name="pay_amount" id="pay_amount_{{ $invoice->id }}" class="form-control @error('pay_amount') is-invalid @enderror"
                               value="{{ old('pay_amount') }}" required placeholder="Enter Payment Amount" step="0.01" min="0.01"
                               max="{{ $invoice->total_amount - $invoice->payments->sum('amount') }}">
                        @error('pay_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="payment_method_{{ $invoice->id }}" class="form-label">Payment Method<span class="text-danger">*</span></label>
                        <select id="payment_method_{{ $invoice->id }}" name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="" selected disabled>Select Payment Method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfers" {{ old('payment_method') == 'bank_transfers' ? 'selected' : '' }}>Bank Transfers</option>
                            <option value="checks" {{ old('payment_method') == 'checks' ? 'selected' : '' }}>Checks</option>
                            <option value="mobile_payments" {{ old('payment_method') == 'mobile_payments' ? 'selected' : '' }}>Mobile Payments</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="payment_status_{{ $invoice->id }}" class="form-label">Payment Status<span class="text-danger">*</span></label>
                        <select id="payment_status_{{ $invoice->id }}" name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                            <option value="" selected disabled>Select Payment Status</option>
                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ old('payment_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

