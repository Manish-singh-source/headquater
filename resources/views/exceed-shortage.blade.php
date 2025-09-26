@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Product Issues</h5>
                </div>
                {{-- <div class="col-6 d-flex justify-content-end text-end my-2 ">
                    <div>
                        <select id="input9" class="form-select">
                            <option selected="" disabled>Status</option>
                            <option>Out For Delivery</option>
                            <option>Delivered</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div> --}}
            </div>
            <!--end breadcrumb-->

            {{-- <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Order Id</b></span>

                                <span>{{ $salesOrder->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Customer Group Name</b></span>
                                <span>{{ $salesOrder->customerGroup->name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> {{ $customerInfo->contact_no ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> {{ $customerInfo->email ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Ordered Date</b></span>
                                <span> NA</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Delivery Date</b></span>
                                <span> NA</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Billing Address</b></span>
                                <span> {{ $customerInfo->address->billing_address ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b> Shipping Address</b></span>
                                <span> {{ $customerInfo->address->shipping_address ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Invoices PDF</b></span>
                                <span>
                                    <a aria-label="anchor" href="{{ route('invoice.downloadPdf', $invoice->id) }}"
                                        class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip"
                                        data-bs-original-title="View">
                                        Download
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="div d-flex my-2">
                                <div class="col">
                                    <h6 class="mb-3">Products Table</h6>
                                </div>
                                {{-- <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                    <select class="form-select border-2 border-primary" id="shortExceedSelect"
                                        aria-label="Default select example">
                                        <option value="" selected>All Products</option>
                                        <option value="Shortage">Shortage Products</option>
                                        <option value="Exceed">Exceed Products</option>
                                    </select>
                                </ul> --}}
                            </div>
                            <div class="product-table" id="poTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table id="shortage-exceed-table" class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Purchase&nbsp;Order&nbsp;Id</th>
                                                <th>Brand&nbsp;Title</th>
                                                <th>SKU&nbsp;Code</th>
                                                <th>MRP</th>
                                                <th>GST</th>
                                                <th>HSN</th>
                                                <th>Quantity&nbsp;Requirement</th>
                                                <th>Available&nbsp;Quantity</th>
                                                <th>Purchase&nbsp;Rate</th>
                                                <th>Received&nbsp;Quantity</th>
                                                <th>Issue</th>
                                                <th>Issue&nbsp;Items</th>
                                                <th>Issue&nbsp;Reason</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($vendorOrders as $order)
                                                <tr>
                                                    <td><a
                                                            href="{{ route('purchase.order.view', $order->purchase_order_id ?? 0) }}">{{ $order->purchase_order_id ?? 0 }}</a>
                                                    </td>
                                                    <td>{{ $order->product->brand_title ?? 'NA' }}</td>
                                                    <td>{{ $order->vendor_sku_code }}</td>
                                                    <td>{{ $order->mrp }}</td>
                                                    <td>{{ $order->gst }}</td>
                                                    <td>{{ $order->hsn }}</td>
                                                    <td>{{ $order->quantity_requirement }}</td>
                                                    <td>{{ $order->available_quantity }}</td>
                                                    <td>{{ $order->purchase_rate }}</td>
                                                    <td>{{ $order->quantity_received }}</td>
                                                    <td>{{ ucfirst($order->issue_reason) }}</td>
                                                    <td>{{ $order->issue_item }}</td>
                                                    <td>{{ $order->issue_description }}</td>
                                                    {{-- <td>
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            @if ($order->issue_reason === 'Exceed')
                                                                <a aria-label="return"
                                                                    href="{{ route('vendor.product.return', $order->id) }}"
                                                                    class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Return">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-corner-up-left text-warning">
                                                                        <polyline points="9 14 4 9 9 4"></polyline>
                                                                        <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                                                                    </svg>
                                                                </a>
                                                            @endif

                                                            <a aria-label="accept"
                                                                href="{{ route('vendor.product.accept', $order->id) }}"
                                                                class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                                data-bs-toggle="tooltip" data-bs-original-title="Accept">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                    height="13" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-check text-success">
                                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="14" class="text-center">No Records Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
        $(document).ready(function() {
            var table3 = $('#shortage-exceed-table').DataTable({
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

            $('#shortExceedSelect').on('change', function() {
                var selected = $(this).val().trim();

                // Use regex for exact match
                table3.column(-4).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

        });
    </script>
@endsection
