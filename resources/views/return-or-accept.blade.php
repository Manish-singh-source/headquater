@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Products Return</h5>
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
                                                <th>Purchase&nbsp;Rate</th>
                                                <th>GST</th>
                                                <th>HSN</th>
                                                <th>Quantity&nbsp;Requirement</th>
                                                {{-- <th>Available&nbsp;Quantity</th> --}}
                                                <th>Received&nbsp;Quantity</th>
                                                <th>Issue</th>
                                                <th>Returned&nbsp;Items</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($vendorOrders as $order)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('purchase.order.view', $order->purchase_order_id ?? 0) }}">{{ $order->purchase_order_id ?? 0 }}</a>
                                                    </td>
                                                    <td>{{ $order->product->brand_title }}</td>
                                                    <td>{{ $order->vendor_sku_code }}</td>
                                                    <td>{{ $order->mrp }}</td>
                                                    <td>{{ $order->purchase_rate }}</td>
                                                    <td>{{ $order->gst }}</td>
                                                    <td>{{ $order->hsn }}</td>
                                                    <td>{{ $order->quantity_requirement }}</td>
                                                    {{-- <td>{{ $order->available_quantity }}</td> --}}
                                                    <td>{{ $order->quantity_received }}</td>
                                                    <td>{{ ucfirst($order->issue_reason) }}</td>
                                                    <td>{{ $order->issue_item }}</td>
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
                table3.column(-3).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

        });
    </script>
@endsection
