@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><b>Received Vendor Order:</b></li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Purchase Order Id</b></span>
                                    <span> <b><span
                                                id="purchase-order-id">{{ $vendorPIs->purchase_order_id }}</span></b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Name</b></span>
                                    <span> <b id="vendor-code">{{ $vendorPIs->vendor_code }}</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="col">
                            @isset($vendorPIs)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center my-2">
                                            <div class="div d-flex justify-content-end my-3 gap-2">
                                                <h6 class="mb-3">Update PI Products</h6>
                                            </div>
                                            <!-- Tabs Navigation -->
                                            <div class="div d-flex justify-content-end my-3 gap-2">
                                                {{-- @can('PermissionChecker', 'update_received_products') --}}
                                                @if ($vendorPIs->purchaseOrder->status != 'completed')
                                                    <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop1"
                                                        class="btn btn-sm border-2 border-primary">
                                                        Update PI Products
                                                    </button>
                                                @endif

                                                <!-- Modal -->
                                                <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('received.products.pi.update') }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update
                                                                        PI Products</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="col-12 mb-3">
                                                                        <input type="hidden" name="vendor_pi_id"
                                                                            value="{{ $vendorPIs->id }}">
                                                                    </div>

                                                                    <div class="col-12 mb-3">
                                                                        <label for="pi_excel" class="form-label">Updated Vendor
                                                                            PI
                                                                            (CSV/ELSX) <span
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
                                                {{-- @endcan --}}

                                                <button class="btn btn-sm border-2 border-primary" id="exportData">
                                                    <i class="fa fa-file-excel-o"></i> Export to Excel
                                                </button>
                                            </div>
                                        </div>

                                        <div class="product-table">
                                            <div class="table-responsive white-space-nowrap">
                                                <table id="example" class="table align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Order&nbsp;No</th>
                                                            <th>Purchase&nbsp;Order&nbsp;No</th>
                                                            <th>Vendor&nbsp;Code</th>
                                                            <th>Vendor&nbsp;SKU&nbsp;Code</th>
                                                            {{-- <th>Portal&nbsp;Code</th> --}}
                                                            <th>Title</th>
                                                            <th>MRP</th>
                                                            <th>PO&nbsp;Quantity</th>
                                                            <th>PI&nbsp;Quantity</th>
                                                            <th>Quantity&nbsp;Received</th>
                                                            <th>Issue&nbsp;Units</th>
                                                            <th>Issue&nbsp;Reason</th>
                                                            <th>Issue&nbsp;Description</th>
                                                            {{-- 
                                                            <th>Purchase&nbsp;Rate&nbsp;Basic</th>
                                                            <th>GST</th>
                                                            <th>HSN</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($vendorPIs->products as $product)
                                                            <tr>
                                                                <td>{{ $vendorPIs->id }}</td>
                                                                <td>{{ $vendorPIs->purchase_order_id }}</td>
                                                                <td>{{ $vendorPIs->vendor_code }}</td>
                                                                <td>{{ $product->vendor_sku_code }}</td>
                                                                <td>{{ $product->product?->brand_title ?? 'NA' }}</td>
                                                                <td>{{ $product->mrp }}</td>
                                                                <td>{{ $product->quantity_requirement }}</td>
                                                                <td>{{ $product->available_quantity }}</td>
                                                                <td>{{ $product->quantity_received }}</td>
                                                                @if ($product->issue_item)
                                                                    <td>{{ $product->issue_item ?? 0 }}</td>
                                                                    <td>{{ $product->issue_reason ?? '' }}</td>
                                                                    <td>{{ $product->issue_description ?? '' }}</td>
                                                                @else
                                                                    <td>0</td>
                                                                    <td>NA</td>
                                                                    <td>NA</td>
                                                                @endif
                                                                {{-- 
                                                                <td>{{ $product->purchase_rate }}</td>
                                                                <td>{{ $product->gst }}</td>
                                                                <td>{{ $product->hsn }}</td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($vendorPIs->purchaseOrder->status != 'completed')
                                    <div class="col-lg-12">
                                        <div class="row justify-content-between mb-3">
                                            <form class="col-12 text-end" action="{{ route('received.products.status') }}"
                                                method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="vendor_pi_id" value="{{ $vendorPIs->id }}">
                                                <button class="btn btn-sm border-2 border-primary"
                                                    type="submit">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection


@section('script')
    <script>
        $(document).on('click', '#exportData', function() {
            var purchaseOrderId = $("#purchase-order-id").text();
            var vendorCode = $("#vendor-code").text();

            // Construct download URL with parameters
            var downloadUrl = '{{ route('download.received-products.excel') }}' +
                '?purchaseOrderId=' + encodeURIComponent(purchaseOrderId) +
                '&vendorCode=' + encodeURIComponent(vendorCode);

            // Trigger browser download
            window.location.href = downloadUrl;
        });
    </script>
@endsection
