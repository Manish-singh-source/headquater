@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
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
                <div class="justify-end">
                    @empty($purchaseOrderProducts)
                        <a href="{{ route('purches.create') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Create Order</a>
                    @endempty
                    @isset($purchaseOrderProducts[0])
                        <a href="{{ route('purches.create', $purchaseOrderProducts[0]?->purchase_order_id) }}"
                            class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Create Order</a>
                    @endisset
                    {{-- @if ($purchaseOrderProducts[0]?->purchase_order_id)
                        <a href="{{ route('purches.create') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Create Order</a>
                    @endif --}}
                </div>
            </div>

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>
                                    @isset($purchaseOrderProducts[0])
                                        <span id="purchase-order-id">{{ $purchaseOrderProducts[0]->purchase_order_id }}</span>
                                    @endisset
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Name</b></span>
                                    <span>
                                        <b class="d-inline-block text-truncate" style="max-width: 150px;">
                                            {{-- @foreach ($vendors as $vendor)
                                                {{ $vendor . ',' }}
                                            @endforeach --}}
                                            @php
                                                $vendors = $purchaseOrderProducts
                                                    ->pluck('vendor_code')
                                                    ->filter()
                                                    ->unique();
                                            @endphp
                                            @forelse($vendors as $vendor)
                                                {{ $vendor }},
                                            @empty
                                                NA
                                            @endforelse
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
                                            @isset($purchaseOrderProducts[0])
                                                {{ $statuses[$purchaseOrderProducts[0]->purchaseOrder->status] ?? 'On Hold' }}
                                            @endisset
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
                                                                                @isset($purchaseOrderProducts[0])
                                                                                    <input type="hidden"
                                                                                        name="purchase_order_id"
                                                                                        value="{{ $vendorPI->purchase_order_id }}">
                                                                                @endisset
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
                                                                                @isset($purchaseOrderProducts[0])
                                                                                    <input type="hidden"
                                                                                        name="purchase_order_id"
                                                                                        value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                                                @endisset
                                                                                <input type="hidden" name="vendor_code"
                                                                                    value="">
                                                                            </div>

                                                                            <div class="col-12 mb-3">
                                                                                <label for="approve_reason"
                                                                                    class="form-label">Reason<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text"
                                                                                    name="approve_reason"
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
                                                    @isset($purchaseOrderProducts[0])
                                                        <input type="hidden" name="purchase_order_id"
                                                            value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                    @endisset
                                                    <label for="vendor_code" class="form-label">Vendor Name
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="vendor_code" id="vendor_code">
                                                        <option selected disabled value="">-- Select --</option>
                                                        {{-- @foreach ($vendors as $vendor)
                                                            <option value="{{ $vendor }}">{{ $vendor }}
                                                            </option>
                                                        @endforeach --}}
                                                        @php
                                                            $vendors = $purchaseOrderProducts
                                                                ->pluck('vendor_code')
                                                                ->filter()
                                                                ->unique();
                                                        @endphp
                                                        @forelse($vendors as $vendor)
                                                            <option value="{{ $vendor }}">{{ $vendor }}
                                                            </option>
                                                        @empty
                                                            NA
                                                        @endforelse
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

                            <button class="btn btn-icon btn-sm border-2 border-primary me-1" id="exportData">
                                <i class="fa fa-file-excel-o"></i> Export to Excel
                            </button>
                            {{-- <a href="#" class="btn btn-icon btn-sm border-2 border-primary me-1"
                                id="exportData">Export to Excel</a> --}}

                            <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                <select class="form-select border-2 border-primary" id="vendorPOSelect"
                                    aria-label="Default select example">
                                    <option value="" selected>All Vendors</option>
                                    @php
                                        $vendors = $purchaseOrderProducts->pluck('vendor_code')->filter()->unique();
                                    @endphp
                                    @forelse($vendors as $vendor)
                                        <option value="{{ $vendor }}">{{ $vendor }}
                                        </option>
                                    @empty
                                        <option value="">NA</option>
                                    @endforelse
                                </select>
                            </ul>
                            <div class="ms-auto">
                                <div class="btn-group">
                                    <button type="button" class="btn border-2 border-primary">Action</button>
                                    <button type="button"
                                        class="btn border-2 border-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                        <a class="dropdown-item cursor-pointer" id="delete-selected">Delete All</a>
                                    </div>
                                </div>
                                {{-- <a href="{{ route('add-customer') }}" class="btn btn-primary px-4"><i
                                        class="bi bi-plus-lg me-2"></i>Add Customers</a> --}}
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
                                        <th>Order&nbsp;No</th>
                                        <th>Purchase&nbsp;Order&nbsp;No</th>
                                        <th>Vendor&nbsp;Code</th>
                                        <th>Portal&nbsp;Code</th>
                                        <th>SKU&nbsp;Code</th>
                                        <th>Title</th>
                                        <th>MRP</th>
                                        <th>Qty&nbsp;Requirement</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchaseOrderProducts as $order)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $order->id }}">
                                            </td>
                                            <td>{{ $order->purchase_order_id }}</td>
                                            <td>{{ $order->purchase_order_id }}</td>
                                            <td>{{ $order->vendor_code }}</td>
                                            <td>{{ $order->tempProduct->item_code ?? 'NA' }}</td>
                                            <td>{{ $order->tempProduct->sku ?? 'NA' }}</td>
                                            <td>{{ $order->tempProduct->description ?? 'NA' }}</td>
                                            <td>{{ $order->tempProduct->mrp ?? 'NA' }}</td>
                                            <td>{{ $order->ordered_quantity ?? 'NA' }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    {{-- 
                                                    <a aria-label="anchor" href="{{ route('order.view', $order->id) }}"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="View">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-eye text-primary">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a> 
                                                    --}}
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
                                                                <line x1="10" y1="11" x2="10"
                                                                    y2="17"></line>
                                                                <line x1="14" y1="11" x2="14"
                                                                    y2="17"></line>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
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
                                                        @isset($purchaseOrderProducts[0])
                                                            <input type="hidden" name="purchase_order_id"
                                                                value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                        @endisset
                                                        <label for="marital" class="form-label">Vendor Name
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="vendor_code" id="vendor_code">
                                                            <option selected disabled value="">-- Select --</option>
                                                            @forelse($facilityNames as $vendor)
                                                                <option value="{{ $vendor }}">{{ $vendor }}
                                                                </option>
                                                            @empty
                                                                <option value="">NA</option>
                                                            @endforelse
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
                                                        @isset($purchaseOrderProducts[0])
                                                            <input type="hidden" name="purchase_order_id"
                                                                value="{{ $purchaseOrderProducts[0]->purchase_order_id }}">
                                                        @endisset
                                                        <label for="marital" class="form-label">Vendor Name
                                                            <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="vendor_code" id="vendor_code">
                                                            <option selected disabled value="">-- Select --</option>
                                                            @forelse($facilityNames as $vendor)
                                                                <option value="{{ $vendor }}">{{ $vendor }}
                                                                </option>
                                                            @empty
                                                                <option value="">NA</option>
                                                            @endforelse
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
                                <ul class="nav nav-tabs" role="tablist">
                                    <select class="form-select border-2 border-primary" id="vendorSelect2"
                                        aria-label="Default select example">
                                        <option value="" selected>All Vendors</option>
                                        {{-- @php
                                            $vendors = $vendorPIs->pluck('vendor_code')->filter()->unique();
                                        @endphp --}}
                                        @forelse($facilityNames as $vendor)
                                            <option value="{{ $vendor }}">{{ $vendor }}</option>
                                        @empty
                                            <option value="">NA</option>
                                        @endforelse
                                    </select>
                                </ul>
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
                                            <th>MRP</th>
                                            <th>Quantity&nbsp;Requirement</th>
                                            <th>PI&nbsp;Quantity</th>
                                            <th>Quantity&nbsp;Received</th>
                                            <th>Purchase&nbsp;Rate&nbsp;Basic</th>
                                            <th>GST</th>
                                            <th>HSN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($vendorPIs as $vendorPI)
                                            @foreach ($vendorPI->products as $product)
                                                <tr>
                                                    <td>{{ $product->id }}</td>
                                                    <td>{{ $vendorPI->purchase_order_id }}</td>
                                                    <td>{{ $vendorPI->vendor_code }}</td>
                                                    <td>{{ $product->vendor_sku_code }}</td>
                                                    <td>{{ $product->product->brand_title }}</td>
                                                    <td>{{ $product->mrp }}</td>
                                                    <td>{{ $product->quantity_requirement }}</td>
                                                    <td>{{ $product->available_quantity }}</td>
                                                    <td>{{ $product->quantity_received ?? '' }}</td>
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
        </div>
    </main>
@endsection


@section('script')
    <script>
        $(document).on('click', '#exportData', function() {
            var purchaseOrderId = $("#purchase-order-id").text();
            var vendorCode = $("#vendorPOSelect").val();

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
