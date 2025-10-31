@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    @php
        $statuses = [
            'pending' => 'Pending',
            'received' => 'Products Received',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
        ];

        $payment_statuses = [
            'pending' => 'Pending',
            'partial_paid' => 'Partial Paid',
            'paid' => 'Paid',
        ];
    @endphp

    <main class="main-wrapper">
        <div class="main-content">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Purchase Order Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @if ($purchaseOrder->status == 'received')
                        <div>
                            <form id="statusForm" action="{{ route('change.purchase.order.status') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="order_id" value="{{ $purchaseOrder->id }}">
                                <select class="border-2 btn btn-sm border-primary text-start" id="changeStatus"
                                    aria-label="Default select example" name="status">
                                    <option value="" selected disabled>Change Status</option>
                                    {{-- 
                                    <option value="pending" @if ($purchaseOrder->status == 'pending') selected @endif
                                        @if (in_array($purchaseOrder->status, ['received', 'completed'])) disabled @endif>Pending</option>
                                    <option value="ready_to_package" @if ($purchaseOrder->status == 'received') selected @endif
                                        @if (in_array($purchaseOrder->status, ['completed'])) disabled @endif>Received</option> 
                                    --}}
                                    <option value="completed" @if ($purchaseOrder->status == 'completed') selected @endif>
                                        Completed</option>
                                </select>
                            </form>
                        </div>
                    @endif
                    <!-- Tabs Navigation -->
                    <div class="div d-flex justify-content-end my-3 gap-2">
                        {{-- Payment Details Module --}}
                        @if (isset($purchaseOrder->vendorPI[0]->total_due_amount) && $purchaseOrder->vendorPI[0]->total_due_amount > 0)
                            <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#paymentUploadSection" class="btn btn-sm border-2 border-primary">
                                Add Payment Details
                            </button>
                        @endif
                        {{-- Payment Details Module --}}

                        {{-- GRN uploading Module --}}
                        @if (!isset($purchaseGrn[0]->vendor_code) && $purchaseOrder->status != 'completed')
                            <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#grnUpload" class="btn btn-sm border-2 border-primary">
                                Add Vendor GRN
                            </button>
                        @endif

                        {{-- GRN uploading Module --}}

                        {{-- Invoice uploading Module --}}
                        @if ($purchaseOrder->status != 'completed' && !isset($purchaseInvoice[0]->vendor_code))
                            <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#invoiceUpload" class="btn btn-sm border-2 border-primary">
                                Add Vendor Invoice
                            </button>
                        @endif

                        {{-- Invoice uploading Module --}}
                    </div>
                </div>
            </div>

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>
                                    <span id="purchase-order-id">{{ $purchaseOrder->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Sales Order Id</b></span>
                                    <span id="sales-order-id">{{ $purchaseOrder->sales_order_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Code</b></span>
                                    <span>
                                        <a href="{{ route('vendor.view', $purchaseOrder->vendor->id) }}">
                                            <b class="d-inline-block text-truncate" style="max-width: 150px;"
                                                id="vendor-code">
                                                {{ $purchaseOrder->vendor_code ?? 'NA' }}
                                            </b>
                                        </a>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Client Name</b></span>
                                    <span>
                                        <b class="d-inline-block text-truncate" style="max-width: 150px;" id="vendor-code">
                                            {{ $purchaseOrder->vendor->client_name ?? 'NA' }}
                                        </b>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Contact Name</b></span>
                                    <span>
                                        <b class="d-inline-block text-truncate" style="max-width: 150px;" id="vendor-code">
                                            {{ $purchaseOrder->vendor->contact_name ?? 'NA' }}
                                        </b>
                                    </span>
                                </li>
                                @if (isset($purchaseOrder->vendorPI[0]->total_amount) && $purchaseOrder->vendorPI[0]->total_amount > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Total Amount</b></span>
                                        <span class="fw-bold"
                                            id="sales-order-id">{{ $purchaseOrder->vendorPI[0]->total_amount ?? 0 }}
                                            Rs.</span>
                                    </li>
                                @endif

                                @if (isset($purchaseOrder->vendorPI[0]->total_paid_amount) && $purchaseOrder->vendorPI[0]->total_paid_amount > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Total Paid Amount</b></span>
                                        <span class="fw-bold"
                                            id="sales-order-id">{{ $purchaseOrder->vendorPI[0]->total_paid_amount ?? 0 }}
                                            Rs.</span>
                                    </li>
                                @endif

                                @if (isset($purchaseOrder->vendorPI[0]->total_due_amount) && $purchaseOrder->vendorPI[0]->total_due_amount > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Total Due Amount</b></span>
                                        <span class="fw-bold"
                                            id="sales-order-id">{{ $purchaseOrder->vendorPI[0]->total_due_amount ?? 0 }}
                                            Rs.</span>
                                    </li>
                                @endif

                                @if (isset($purchaseOrder->vendorPI[0]->payment_status))
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Payment Status</b></span>
                                        <span class="fw-bold"
                                            id="sales-order-id">{{ $payment_statuses[$purchaseOrder->vendorPI[0]->payment_status] }}</span>
                                    </li>
                                @endif

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Status</b></span>
                                    <span>
                                        <b>
                                            {{ $statuses[$purchaseOrder->status] ?? 'Unknown Status' }}
                                        </b>
                                    </span>
                                </li>

                                @if (isset($purchaseOrder->vendorPI[0]->status) && $purchaseOrder->vendorPI[0]->status == 'reject') 

                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Vendor PI Status</b></span>
                                        <span>
                                            <b>{{ ucfirst($purchaseOrder->vendorPI[0]->status) }}</b>
                                        </span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Reject Reason</b></span>
                                        <span>
                                            <b>{{ $purchaseOrder->vendorPI[0]->approve_or_reject_reason ?? 'NA' }}</b>
                                        </span>
                                    </li>
                                @endif

                                {{-- Vendor Payments Details --}}
                                @isset($purchaseInvoice[0]->vendor_code) 
                                    @foreach ($purchaseInvoice as $invoiceDetail)
                                        <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Invoice Number</b></span>
                                            <span>{{ $invoiceDetail->invoice_no }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Invoice Amount</b></span>
                                            <span>{{ $invoiceDetail->invoice_amount }}</span>
                                        </li>
                                    @endforeach
                                @endisset


                                @foreach ($purchaseOrder->vendorPI as $vendorPI)
                                    @if ($vendorPI->status == 'approve')
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Packaged Received from {{ $vendorPI->vendor_code }}</b></span>
                                            <span>
                                                <div class="d-flex gap-2 justify-content-center align-items-center">
                                                    <button class="btn btn-sm border-2 border-success"
                                                        data-bs-toggle="modal" data-bs-target="#approvePopup"
                                                        class="btn btn-sm border-2 border-success">
                                                        Approve
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="approvePopup" data-bs-backdrop="approve"
                                                        data-bs-keyboard="false" tabindex="-1"
                                                        aria-labelledby="approvePopupLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('approve.vendor.pi.request') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="approvePopupLabel">
                                                                            Approve Reason</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="col-12 mb-3">
                                                                            <input type="hidden" name="purchase_order_id"
                                                                                value="{{ $purchaseOrder->id }}">
                                                                            <input type="hidden" name="vendor_code"
                                                                                value="{{ $purchaseOrder->vendor_code }}">
                                                                        </div>

                                                                        <div class="col-12 mb-3">
                                                                            <label for="approve_reason"
                                                                                class="form-label">Reason<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text"
                                                                                name="approve_or_reject_reason"
                                                                                id="approve_reason" class="form-control"
                                                                                value="" required="">
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

                                                    <button class="btn btn-sm border-2 border-danger"
                                                        data-bs-toggle="modal" data-bs-target="#rejectPopup"
                                                        class="btn btn-sm border-2 border-danger">
                                                        Reject
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="rejectPopup" data-bs-backdrop="reject"
                                                        data-bs-keyboard="false" tabindex="-1"
                                                        aria-labelledby="rejectPopupLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('reject.vendor.pi.request') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="rejectPopupLabel">
                                                                            Reject Reason</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="col-12 mb-3">
                                                                            <input type="hidden" name="purchase_order_id"
                                                                                value="{{ $purchaseOrder->id }}">
                                                                            <input type="hidden" name="vendor_code"
                                                                                value="{{ $purchaseOrder->vendor_code }}">
                                                                        </div>

                                                                        <div class="col-12 mb-3">
                                                                            <label for="approve_reason"
                                                                                class="form-label">Reason<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text"
                                                                                name="approve_or_reject_reason"
                                                                                id="approve_reason" class="form-control"
                                                                                value="" required="">
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
                                                </div>
                                            </span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            @isset($purchaseInvoice[0]->vendor_code)
                <div class="card">

                    <div class="card-header border-bottom-dashed">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <h5 class="card-title mb-0">
                                    Invoices
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Vendor Name</th>
                                    <th>Invoice File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseInvoice as $invoiceDetail)
                                    <tr>
                                        <th> {{ $invoiceDetail->vendor_code }}</th>
                                        <th>
                                            <a href="{{ asset('uploads/invoices/' . $invoiceDetail->invoice_file) }}"
                                                class="btn btn-sm border-2 border-success w-sm waves ripple-light">
                                                Preview
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endisset
            @isset($purchaseGrn[0]->vendor_code)
                <div class="card">
                    <div class="card-header border-bottom-dashed">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <h5 class="card-title mb-0">
                                    GRNs
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Vendor Name</th>
                                    <th>GRN File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseGrn as $grnDetail)
                                    <tr>
                                        <th> {{ $grnDetail->vendor_code }}</th>
                                        <th>
                                            <a href="{{ asset('uploads/invoices/' . $grnDetail->grn_file) }}"
                                                class="btn btn-sm border-2 border-success w-sm waves ripple-light">
                                                Preview
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endisset

            @if (in_array($purchaseOrder->status, ['pending', 'blocked']))
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center my-2">
                            <div class="div d-flex justify-content-end my-3 gap-2">
                                <h6 class="mb-3">Vendor PO Table</h6>
                            </div>
                            <!-- Tabs Navigation -->
                            <div class="div d-flex justify-content-end my-3 gap-2">
                                @if (
                                    ($purchaseOrder?->purchaseOrderProducts->count() ?? 0) !=
                                        (isset($purchaseOrder?->vendorPI[0]) ? $purchaseOrder?->vendorPI[0]?->products->count() : 0))
                                    <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                        data-bs-target="#approveBackdrop1" class="btn btn-sm border-2 border-primary">
                                        Add Vendor PI
                                    </button>
                                @endif

                                <!-- Modal -->
                                <div class="modal fade" id="approveBackdrop1" data-bs-backdrop="approve"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="approveBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('purchase.order.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="approveBackdropLabel">Add Vekndor PI
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" name="purchase_order_id"
                                                        value="{{ $purchaseOrder->id }}">
                                                    <input type="hidden" name="vendor_code"
                                                        value="{{ $purchaseOrder->vendor_code }}">
                                                    <input type="hidden" name="sales_order_id"
                                                        value="{{ $purchaseOrder->sales_order_id }}">
                                                    <div class="col-12 mb-3">
                                                        <label for="pi_excel" class="form-label">Vendor PI (CSV/ELSX)
                                                            <span class="text-danger">*</span></label>
                                                        <input type="file" name="pi_excel" id="pi_excel"
                                                            class="form-control" value="" required="">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-icon btn-sm border-2 border-primary me-1" id="exportData">
                                    <i class="fa fa-file-excel-o"></i> Export to Excel
                                </button>

                                <div class="ms-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn border-2 border-primary">Action</button>
                                        <button type="button"
                                            class="btn border-2 border-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle
                                                Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item cursor-pointer" id="delete-selected">Delete All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table id="vendorPOTable" class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <input class="form-check-input" type="checkbox" id="select-all">
                                            </th>
                                            <th>Sales&nbsp;Order&nbsp;No</th>
                                            <th>Purchase&nbsp;Order&nbsp;No</th>
                                            <th>Vendor&nbsp;Code</th>
                                            <th>Portal&nbsp;Code</th>
                                            <th>SKU&nbsp;Code</th>
                                            <th>Title</th>
                                            <th>MRP</th>
                                            <th>Qty&nbsp;Requirement</th>
                                            @if (
                                                ($purchaseOrder?->purchaseOrderProducts->count() ?? 0) !=
                                                    (isset($purchaseOrder?->vendorPI[0]) ? $purchaseOrder?->vendorPI[0]?->products->count() : 0))
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($purchaseOrder->purchaseOrderProducts as $order)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input row-checkbox" type="checkbox"
                                                        name="ids[]" value="{{ $order->id }}">
                                                </td>
                                                <td>{{ $order->sales_order_id }}</td>
                                                <td>{{ $order->purchase_order_id }}</td>
                                                <td>{{ $order->vendor_code }}</td>
                                                <td>{{ $order->tempOrder->item_code ?? 'NA' }}</td>
                                                <td>{{ $order->tempOrder->sku ?? 'NA' }}</td>
                                                <td>{{ $order->tempOrder->description ?? 'NA' }}</td>
                                                <td>{{ $order->tempOrder->mrp ?? 'NA' }}</td>
                                                <td>{{ $order->ordered_quantity ?? 'NA' }}</td>
                                                @if (
                                                    ($purchaseOrder?->purchaseOrderProducts->count() ?? 0) !=
                                                        (isset($purchaseOrder?->vendorPI[0]) ? $purchaseOrder?->vendorPI[0]?->products->count() : 0))
                                                    <td>
                                                        <div class="d-flex">
                                                            <form
                                                                action="{{ route('purchase.order.product.delete', $order->id) }}"
                                                                method="POST" onsubmit="return confirm('Are you sure?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-icon btn-sm bg-danger-subtle delete-row">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-trash-2 text-danger">
                                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                                        <path
                                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                        </path>
                                                                        <line x1="10" y1="11"
                                                                            x2="10" y2="17"></line>
                                                                        <line x1="14" y1="11"
                                                                            x2="14" y2="17"></line>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">No Records Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            @isset($purchaseOrder->vendorPI[0]->id)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center my-2">
                            <div class="div d-flex justify-content-end my-3 gap-2">
                                <h6 class="mb-3">Vendor PI Table</h6>
                            </div>
                        </div>

                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example2" class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order&nbsp;No</th>
                                            <th>Purchase&nbsp;Order&nbsp;No</th>
                                            <th>Vendor&nbsp;Code</th>
                                            <th>Vendor&nbsp;SKU&nbsp;Code</th>
                                            <th>Title</th>
                                            <th>GST</th>
                                            <th>HSN</th>
                                            <th>MRP</th>
                                            <th>Basic&nbsp;Rate</th>
                                            <th>Purchase&nbsp;Rate&nbsp;Basic</th>
                                            <th>Rate&nbsp;Confirmation</th>
                                            <th>PO&nbsp;Quantity</th>
                                            <th>PI&nbsp;Quantity</th>
                                            <th>Quantity&nbsp;Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($purchaseOrder->vendorPI as $vendorPI)
                                            @foreach ($vendorPI->products as $product)
                                                <tr>
                                                    <td>{{ $vendorPI->sales_order_id }}</td>
                                                    <td>{{ $vendorPI->purchase_order_id }}</td>
                                                    <td>{{ $vendorPI->vendor_code }}</td>
                                                    <td>{{ $product->vendor_sku_code }}</td>
                                                    <td>{{ $product->title ?? 'N/A' }}</td>
                                                    <td>{{ $product->gst }}</td>
                                                    <td>{{ $product->hsn }}</td>
                                                    <td>{{ $product->mrp }}</td>
                                                    <td>{{ $product->product->vendor_purchase_rate }}</td>
                                                    <td>{{ $product->purchase_rate }}</td>
                                                    <td>
                                                        @if ($product->purchase_rate <= $product->product->vendor_purchase_rate)
                                                            <span class="badge text-success bg-success-subtle">Correct</span>
                                                        @else
                                                            <span class="badge text-danger bg-danger-subtle">Incorrect</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $product->quantity_requirement }}</td>
                                                    <td>{{ $product->available_quantity }}</td>
                                                    <td>{{ $product->quantity_received ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="6">No Records Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset


            {{-- Models Section --}}

            <!-- Modal -->
            {{-- Payment Details Module --}}
            <div class="modal fade" id="paymentUploadSection" data-bs-backdrop="approve" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="paymentUploadSection" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                Add Payment Details</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ route('vendor.invoice.payment.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="col-12 mb-3">
                                    <input type="hidden" name="vendor_pi_id"
                                        value="{{ $purchaseOrder->vendorPI[0]->id ?? '' }}">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="utr_no" class="form-label">UTR
                                        No<span class="text-danger">*</span></label>
                                    <input type="text" name="utr_no" id="utr_no" class="form-control"
                                        value="" required="" placeholder="UTR No">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="payment_status" class="form-label">Payment Amount<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="pay_amount" class="form-control"
                                        placeholder="Enter Payment Amount">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="payment_method" class="form-label">Payment Method<span
                                            class="text-danger">*</span></label>
                                    <select id="payment_method" name="payment_method" class="form-select">
                                        <option selected="" disabled>Payment Method
                                        </option>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfers">Bank Transfers
                                        </option>
                                        <option value="checks">Checks</option>
                                        <option value="mobile_payments">Mobile Payments
                                        </option>
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
            {{-- Payment Details Module --}}


            {{-- GRN uploading Module --}}
            <!-- Modal -->
            <div class="modal fade" id="grnUpload" data-bs-backdrop="approve" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="grnUploadSection" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('purchase.order.grn.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="col-12 mb-3">
                                    <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                                    <input type="hidden" name="vendor_code" value="{{ $purchaseOrder->vendor_code }}">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="grn_file" class="form-label">Upload GRN <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="grn_file" id="grn_file" class="form-control"
                                        placeholder="Upload ID Document">
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
            {{-- GRN uploading Module --}}

            <!-- Modal -->
            {{-- Invoice uploading Module --}}
            <div class="modal fade" id="invoiceUpload" data-bs-backdrop="approve" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="invoiceUploadSection" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('purchase.order.invoice.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="col-12 mb-3">
                                    <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                                    <input type="hidden" name="vendor_code" value="{{ $purchaseOrder->vendor_code }}">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="invoice_no" class="form-label">Invoice Number<span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control"
                                        value="" required="" placeholder="Invoice Number">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="invoice_amount" class="form-label">Invoice Amount<span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_amount" id="invoice_amount" class="form-control"
                                        value="" required="" placeholder="Invoice Amount">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="invoice_file" class="form-label">Upload Invoice <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="invoice_file" id="invoice_file" class="form-control"
                                        placeholder="Upload ID Document">
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
            {{-- Invoice uploading Module --}}
            {{-- Models Section --}}
        </div>
    </main>
@endsection


@section('script')
    <script>
        document.getElementById('changeStatus').addEventListener('change', function() {
            if (confirm('Are you sure you want to change status for order?')) {
                document.getElementById('statusForm').submit();
            }
        });
    </script>
    <script>
        $(document).on('click', '#exportData', function() {
            var purchaseOrderId = $("#purchase-order-id").text().trim();
            var vendorCode = $("#vendor-code").text().trim();

            // Construct download URL with parameters
            var downloadUrl = '{{ route('download.vendor.po.excel') }}' +
                '?purchaseOrderId=' + encodeURIComponent(purchaseOrderId) +
                '&vendorCode=' + encodeURIComponent(vendorCode);

            // Trigger browser download
            window.location.href = downloadUrl;
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            // Delete Selected functionality
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected records?')) {
                    // Create a form and submit
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('purchase.order.products.delete') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table3 = $('#vendorPOTable').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        //   "targets": [0, -1],
                    } // Disable sorting for the 4th column (index starts at 0)
                ],
                lengthChange: true,
                // buttons: ['excel', 'pdf', 'print']
                // buttons: ['excel']
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            $('#vendorPOSelect').on('change', function() {
                var selected = $(this).val().trim();

                // Use regex for exact match
                table3.column(3).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

        });
    </script>
@endsection
