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
                                <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                    <select class="form-select border-2 border-primary" id="shortExceedSelect"
                                        aria-label="Default select example">
                                        <option value="" selected>All Products</option>
                                        <option value="Shortage">Shortage Products</option>
                                        <option value="Exceed">Exceed Products</option>
                                    </select>
                                </ul>
                            </div>
                            <div class="product-table" id="poTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table id="shortage-exceed-table" class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Purchase Order Id</th>
                                                <th>Brand Title</th>
                                                <th>SKU Code</th>
                                                <th>MRP</th>
                                                <th>GST</th>
                                                <th>HSN</th>
                                                <th>Quantity Requirement</th>
                                                <th>Available Quantity</th>
                                                <th>Purchase Rate</th>
                                                <th>Received Quantity</th>
                                                <th>Issue</th>
                                                <th>Issue Items</th>
                                                <th>Issue Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($vendorOrders as $order)
                                                @if ($order->quantity_requirement > $order->quantity_received)
                                                    <tr>
                                                        <td><a
                                                                href="{{ route('purchase.order.view', $order->purchase_order_id ?? 0) }}">{{ $order->purchase_order_id }}</a>
                                                        </td>
                                                        <td>{{ $order->product->brand_title }}</td>
                                                        <td>{{ $order->vendor_sku_code }}</td>
                                                        <td>{{ $order->mrp }}</td>
                                                        <td>{{ $order->gst }}</td>
                                                        <td>{{ $order->hsn }}</td>
                                                        <td>{{ $order->quantity_requirement }}</td>
                                                        <td>{{ $order->available_quantity }}</td>
                                                        <td>{{ $order->purchase_rate }}</td>
                                                        <td>{{ $order->quantity_received }}</td>
                                                        <td>Shortage</td>
                                                        <td>{{ $order->issue_item }}</td>
                                                        <td>{{ ucfirst($order->issue_reason) }}</td>
                                                    </tr>
                                                @elseif($order->quantity_requirement < $order->quantity_received)
                                                    <tr>
                                                        <td>{{ $order->purchase_order_id }}</td>
                                                        <td>{{ $order->product->brand_title }}</td>
                                                        <td>{{ $order->vendor_sku_code }}</td>
                                                        <td>{{ $order->mrp }}</td>
                                                        <td>{{ $order->gst }}</td>
                                                        <td>{{ $order->hsn }}</td>
                                                        <td>{{ $order->quantity_requirement }}</td>
                                                        <td>{{ $order->available_quantity }}</td>
                                                        <td>{{ $order->purchase_rate }}</td>
                                                        <td>{{ $order->quantity_received }}</td>
                                                        <td>Exceed</td>
                                                        <td>{{ $order->issue_item }}</td>
                                                        <td>{{ ucfirst($order->issue_reason) }}</td>
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
                table3.column(-1).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

        });
    </script>
@endsection
