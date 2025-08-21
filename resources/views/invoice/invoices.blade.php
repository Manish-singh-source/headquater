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
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Invoices List</li>
                        </ol>
                    </nav>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-auto">
                        <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                            <a href="{{ route('create-invoice') }}"><button class="btn btn-primary px-4"><i
                                        class="bi bi-plus-lg me-2"></i>Create Invoice</button></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Order&nbsp;Id</th>
                                        <th>Invoice&nbsp;No</th>
                                        <th>Customer&nbsp;Name</th>
                                        <th>Due&nbsp;Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>#{{ $invoice->sales_order_id }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->client_name }}</td>
                                            <td>
                                                {{ $invoice->invoice_date }}
                                            </td>
                                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
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
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#appointmentView">
                                                    <img width="15" height="15"
                                                        src="https://img.icons8.com/ios/50/document--v1.png"
                                                        alt="bank-card-back-side--v1" />
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#dnView">
                                                    <img width="15" height="15"
                                                        src="https://img.icons8.com/ios/50/document--v1.png"
                                                        alt="bank-card-back-side--v1" />
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#paymentView">
                                                    <img width="15" height="15"
                                                        src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png"
                                                        alt="bank-card-back-side--v1" />
                                                </a>
                                                <div class="modal fade" id="appointmentView" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update
                                                                    Inovice Details</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST"
                                                                action="{{ route('invoices.appointment.update', $invoice->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-body">
                                                                    <div class="col-12 mb-3">
                                                                        <label for="appointment_date"
                                                                            class="form-label">Appointment Date <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="date" name="appointment_date"
                                                                            id="appointment_date" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="Upload ID Document">
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="pod" class="form-label">Upload
                                                                            POD <span class="text-danger">*</span></label>
                                                                        <input type="file" name="pod"
                                                                            id="pod" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="Upload ID Document">
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="grn" class="form-label">Upload
                                                                            GRN <span class="text-danger">*</span></label>
                                                                        <input type="file" name="grn"
                                                                            id="grn" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="Upload ID Document">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <input type="submit" class="btn btn-primary"
                                                                        value="Submit" />
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="dnView" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                    Update
                                                                    Inovice Details</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST"
                                                                action="{{ route('invoice.dn.update', $invoice->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-body">
                                                                    <div class="col-12 mb-3">
                                                                        <label for="dn_amount" class="form-label">DN
                                                                            Amount<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="dn_amount"
                                                                            id="dn_amount" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="Upload Amount">
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="dn_reason" class="form-label">DN
                                                                            Reason<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="dn_reason"
                                                                            id="dn_reason" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="DN Reason">
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="dn_receipt" class="form-label">DN
                                                                            Receipt <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="file" name="dn_receipt"
                                                                            id="dn_receipt" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="Upload ID Document">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" id="holdOrder"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="paymentView" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                    Update Payment Details</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('invoice.payment.update', $invoice->id) }}" enctype="multipart/form-data"> 
                                                                @csrf 
                                                                @method('post')
                                                                <div class="modal-body">
                                                                    <div class="col-12 mb-3">
                                                                        <label for="utr_no" class="form-label">UTR
                                                                            No<span class="text-danger">*</span></label>
                                                                        <input type="text" name="utr_no"
                                                                            id="utr_no" class="form-control"
                                                                            value="" required=""
                                                                            placeholder="UTR No">
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="payment_status"
                                                                            class="form-label">Payment Amount<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="pay_amount" class="form-control" placeholder="Enter Payment Amount">
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="payment_method"
                                                                            class="form-label">Payment Method<span
                                                                                class="text-danger">*</span></label>
                                                                        <select id="payment_method" name="payment_method" class="form-select">
                                                                            <option selected="" disabled>Payment Method
                                                                            </option>
                                                                            <option value="cash">Cash</option>
                                                                            <option value="bank_transfers">Bank Transfers</option>
                                                                            <option value="checks">Checks</option>
                                                                            <option value="mobile_payments">Mobile Payments</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label for="payment_status"
                                                                            class="form-label">Payment Status<span
                                                                                class="text-danger">*</span></label>
                                                                        <select id="payment_status" name="payment_status" class="form-select">
                                                                            <option selected="" disabled>Payment Status
                                                                            </option>
                                                                            <option value="pending">Pending</option>
                                                                            <option value="rejected">Rejected</option>
                                                                            <option value="completed">Completed</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" id="holdOrder"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
    <!--end main wrapper-->
@endsection
