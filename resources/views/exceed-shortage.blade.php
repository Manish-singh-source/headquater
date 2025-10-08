@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Product Issues</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="div d-flex my-2">
                                <div class="col">
                                    <h6 class="mb-3">Products Table</h6>
                                </div>
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
                                                <th>Issue&nbsp;From</th>
                                                <th>Issue</th>
                                                <th>Issue&nbsp;Items</th>
                                                <th>Issue&nbsp;Reason</th>
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
                                                    <td>{{ $order->product?->mrp }}</td>
                                                    <td>{{ $order->product?->gst }}</td>
                                                    <td>{{ $order->product?->hsn }}</td>
                                                    <td>{{ $order->quantity_requirement }}</td>
                                                    <td>{{ $order->available_quantity }}</td>
                                                    <td>{{ $order->purchase_rate }}</td>
                                                    <td>{{ $order->quantity_received }}</td>
                                                    <td>{{ ucfirst($order->issue_from) }}</td>
                                                    <td>{{ ucfirst($order->issue_reason) }}</td>
                                                    <td>{{ $order->issue_item }}</td>
                                                    <td>{{ $order->issue_description }}</td>
                                                    
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
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            var table3 = $('#shortage-exceed-table').DataTable({
                "columnDefs": [{
                        "orderable": false,
                    } 
                ],
                lengthChange: true,
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', 
                }]
            });

            $('#shortExceedSelect').on('change', function() {
                var selected = $(this).val().trim();
                table3.column(-4).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

        });
    </script>
@endsection
