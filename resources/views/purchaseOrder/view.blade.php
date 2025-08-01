@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div my-2">

                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>
                                    <span>{{ 'ORDER-' . $purchaseOrderProducts[0]->purchase_order_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Name</b></span>
                                    <span>
                                        <b>
                                            @foreach ($vendors as $vendor)
                                                {{ $vendor . ',' }}
                                            @endforeach
                                        </b>
                                    </span>
                                </li>
                                @php
                                    $statuses = [
                                        'pending' => 'Pending',
                                        'blocked' => 'Blocked',
                                        'completed' => 'Completed',
                                        'ready_to_ship' => 'Ready To Ship',
                                        'ready_to_package' => 'Ready To Package',
                                    ];
                                @endphp

                                
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Status</b></span>
                                    <span>
                                        <b>
                                            {{ $statuses[$purchaseOrderProducts[0]->purchaseOrder->status] ?? 'On Hold' }}
                                        </b>
                                    </span>
                                </li>
                                @foreach ($vendorPIid as $vendorPI)
                                    @if ($vendorPI->status == 'approve')
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Packaged Received from {{ $vendorPI->vendor_code }}</b></span>
                                            <span>
                                                <div class="d-flex gap-2 justify-content-center align-items-center">
                                                    <form action="{{ route('approve.vendor.pi.request') }}" method="POST">
                                                        @csrf
                                                        @method('POST')
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
                                                                    <form action="{{ route('purchase.order.store') }}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('POST')
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="approvePopupLabel">
                                                                                Approve Reason</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="col-12 mb-3">
                                                                                <input type="hidden"
                                                                                    name="purchase_order_id"
                                                                                    value="{{ $vendorPI->purchase_order_id }}">
                                                                                <input type="hidden" name="vendor_code"
                                                                                    value="{{ $vendorPI->vendor_code }}">
                                                                            </div>

                                                                            <div class="col-12 mb-3">
                                                                                <label for="approve_reason"
                                                                                    class="form-label">Reason<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="approve_reason"
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
                                                    </form>

                                                    <form action="" method="POST">
                                                        @csrf
                                                        @method('POST')
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
                                                                    <form action="{{ route('purchase.order.store') }}"
                                                                        method="POST" enctype="multipart/form-data">
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
                                                                                <input type="hidden"
                                                                                    name="purchase_order_id"
                                                                                    value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                                                <input type="hidden" name="vendor_code"
                                                                                    value="">
                                                                            </div>

                                                                            <div class="col-12 mb-3">
                                                                                <label for="approve_reason"
                                                                                    class="form-label">Reason<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="approve_reason"
                                                                                    id="approve_reason"
                                                                                    class="form-control" value=""
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" id="holdOrder"
                                                                                class="btn btn-primary">Submit</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
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

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <div class="div d-flex justify-content-end my-3 gap-2">
                            <h6 class="mb-3">Vendor PO Table</h6>
                        </div>
                        <!-- Tabs Navigation -->
                        <div class="div d-flex justify-content-end my-3 gap-2">
                            <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#approveBackdrop1" class="btn btn-sm border-2 border-primary">
                                Add Vendor PI
                            </button>

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
                                                <h1 class="modal-title fs-5" id="approveBackdropLabel">Add Vendor PI</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="col-12 mb-3">
                                                    <input type="hidden" name="purchase_order_id"
                                                        value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                    <label for="vendor_code" class="form-label">Vendor Name
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="vendor_code" id="vendor_code">
                                                        <option selected disabled value="">-- Select --</option>
                                                        @foreach ($vendors as $vendor)
                                                            <option value="{{ $vendor }}">{{ $vendor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <label for="pi_excel" class="form-label">Vendor PI (CSV/ELSX) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="pi_excel" id="pi_excel"
                                                        class="form-control" value="" required="">
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

                            <button id="customExcelBtn" class="btn btn-sm border-2 border-primary">
                                <i class="fa fa-file-excel-o"></i> Export to Excel
                            </button>

                            <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                <select class="form-select border-2 border-primary" id="departmentFilter"
                                    aria-label="Default select example">
                                    <option value="" selected>All Vendors</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor }}">{{ $vendor }}</option>
                                    @endforeach
                                </select>
                            </ul>
                        </div>
                    </div>

                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Purchase Order No</th>
                                        <th>Vendor Name</th>
                                        <th>Portal Code</th>
                                        <th>SKU Code</th>
                                        <th>Title</th>
                                        <th>MRP</th>
                                        <th>Qty Requirement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchaseOrderProducts as $order)
                                        @if ($order->ordered_quantity > 0)
                                            <tr>
                                                <td>{{ 'PO-' . $order->id }}</td>
                                                <td>{{ $order->tempProduct->vendor_code }}</td>
                                                <td>{{ $order->tempProduct->po_number ?? 'NA' }}</td>
                                                <td>{{ $order->tempProduct->sku ?? 'NA' }}</td>
                                                <td>{{ $order->tempProduct->description ?? 'NA' }}</td>
                                                <td>{{ $order->tempProduct->mrp ?? 'NA' }}</td>
                                                <td>{{ $order->ordered_quantity ?? 'NA' }}</td>
                                            </tr>
                                        @endif
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

            {{-- 
            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add Vendor PI
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body pi-add">
                    <form action="{{ route('purchase.order.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row g-3">
                            <input type="hidden" name="purchase_order_id"
                                value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                            <div class="col-12 col-lg-3">
                                <label for="vendor_code" class="form-label">Vendor Name
                                    <span class="text-danger">*</span></label>
                                <select class="form-control" name="vendor_code" id="vendor_code">
                                    <option selected disabled value="">-- Select --</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor }}">
                                            {{ $vendor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="pi_excel" class="form-label">Upload PI Excel <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="pi_excel" id="pi_excel" class="form-control"
                                    value="" required="" placeholder="Upload ID Document">
                            </div>
                            <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success w-sm waves ripple-light upload">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
            --}}

            @isset($vendorPIs[0]->id)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center my-2">
                            <div class="div d-flex justify-content-end my-3 gap-2">
                                <h6 class="mb-3">Vendor PI Table</h6>
                            </div>
                            <!-- Tabs Navigation -->
                            <div class="div d-flex justify-content-end my-3 gap-2">
                                <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                    data-bs-target="#grnUpload" class="btn btn-sm border-2 border-primary">
                                    Add Vendor GRN
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="grnUpload" data-bs-backdrop="approve" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="grnUploadSection" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('purchase.order.grn.store') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-body">
                                                    <div class="col-12 mb-3">
                                                        <input type="hidden" name="purchase_order_id"
                                                            value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                        <label for="marital" class="form-label">Vendor Name
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="vendor_code" id="vendor_code">
                                                            <option selected disabled value="">-- Select --</option>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor }}">
                                                                    {{ $vendor }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label for="grn_file" class="form-label">Upload GRN <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" name="grn_file" id="grn_file"
                                                            class="form-control" placeholder="Upload ID Document">
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

                                <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                    data-bs-target="#invoiceUpload" class="btn btn-sm border-2 border-primary">
                                    Add Vendor Invoice
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="invoiceUpload" data-bs-backdrop="approve"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="invoiceUploadSection"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('purchase.order.invoice.store') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-body">
                                                    <div class="col-12 mb-3">
                                                        <input type="hidden" name="purchase_order_id"
                                                            value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                        <label for="marital" class="form-label">Vendor Name
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="vendor_code" id="vendor_code">
                                                            <option selected disabled value="">-- Select --</option>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor }}">
                                                                    {{ $vendor }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label for="invoice_file" class="form-label">Upload Invoice <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" name="invoice_file" id="invoice_file"
                                                            class="form-control" placeholder="Upload ID Document">
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
                                <ul class="nav nav-tabs" id="vendorTabs2" role="tablist">
                                    <select class="form-select border-2 border-primary" id="vendorSelect"
                                        aria-label="Default select example">
                                        <option value="" selected>All Vendors</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor }}">{{ $vendor }}</option>
                                        @endforeach
                                    </select>
                                </ul>
                            </div>
                        </div>

                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example2" class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order No</th>
                                            <th>Vendor Code</th>
                                            <th>Purchase Order No</th>
                                            <th>Vendor SKU Code</th>
                                            <th>Title</th>
                                            <th>MRP</th>
                                            <th>Quantity Requirement</th>
                                            <th>Available Quantity</th>
                                            <th>Purchase Rate Basic</th>
                                            <th>GST</th>
                                            <th>HSN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($vendorPIs as $vendorPI)
                                            @foreach ($vendorPI->products as $product)
                                                <tr>
                                                    <td>{{ $product->id }}</td>
                                                    <td>{{ $vendorPI->vendor_code }}</td>
                                                    <td>{{ $vendorPI->purchase_order_id }}</td>
                                                    <td>{{ $product->vendor_sku_code }}</td>
                                                    <td>{{ $product->vendor_sku_code }}</td>
                                                    <td>{{ $product->mrp }}</td>
                                                    <td>{{ $product->quantity_requirement }}</td>
                                                    <td>{{ $product->available_quantity }}</td>
                                                    <td>{{ $product->purchase_rate }}</td>
                                                    <td>{{ $product->gst }}</td>
                                                    <td>{{ $product->hsn }}</td>
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
                    {{-- 
                <div class="card-body">
                    <form action="{{ route('purchase.order.invoice.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row g-3">
                            <div class="col-12 col-lg-3">
                                <input type="hidden" name="purchase_order_id"
                                    value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                <label for="marital" class="form-label">Vendor Name
                                    <span class="text-danger">*</span></label>
                                <select class="form-control" name="vendor_code" id="vendor_code">
                                    <option selected disabled value="">-- Select --</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor }}">
                                            {{ $vendor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="invoice_file" class="form-label">Upload Invoice <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="invoice_file" id="invoice_file" class="form-control"
                                    placeholder="Upload ID Document">
                            </div>
                            <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success w-sm waves ripple-light">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div> 
                --}}

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

                    {{-- 
                <div class="card-body">
                    <form action="{{ route('purchase.order.grn.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row g-3">
                            <div class="col-12 col-lg-3">
                                <input type="hidden" name="purchase_order_id"
                                    value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                <label for="marital" class="form-label">Vendor Name
                                    <span class="text-danger">*</span></label>
                                <select class="form-control" name="vendor_code" id="vendor_code">
                                    <option selected disabled value="">-- Select --</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor }}">
                                            {{ $vendor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="grn_file" class="form-label">Upload GRN <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="grn_file" id="grn_file" class="form-control"
                                    placeholder="Upload ID Document">
                            </div>
                            <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success w-sm waves ripple-light">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div> 
                --}}


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

            {{-- <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Payment Status
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-2">
                            <label for="document_image" class="form-label">Update Payment Status<span
                                    class="text-danger">*</span></label>
                            <select id="input9" class="form-select">
                                <option selected="" disabled>Payment Status</option>
                                <option>Pending</option>
                                <option>Rejected</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-3 d-flex justify-content-end align-items-end gap-2">
                <a href="{{ route('purchase.order.index') }}" class="btn btn-success w-sm waves ripple-light">
                    Close Order
                </a>
            </div> --}}
        </div>
    </main>
@endsection
