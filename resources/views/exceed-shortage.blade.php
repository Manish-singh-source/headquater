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

                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="issuesTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-issues" type="button"
                                        data-status="all">All</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" type="button" id="vendor-issues" data-status="Vendor">Vendor
                                        Issues</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" type="button" id="warehouse-issues"
                                        data-status="Warehouse">Warehouse Issues</button>
                                </li>
                            </ul>


                            <div class="product-table mt-3" id="poTable">
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
                                                            href="{{ route('purchase.order.view', $order->purchaseOrder ?? 0) }}">{{ $order->purchaseOrder->order_number ?? 0 }}</a>
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
                                                    <td>
                                                        <div data-status="issue-reason">{{ ucfirst($order->issue_from) }}</div>
                                                    </td>
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
                }],
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


            // Function to filter table based on status
            function filterWarehouse(status) {
                table3.rows().every(function() {

                    if(status === 'all') {
                        table3.column(-4).search('').draw();
                        return;
                    }
                    table3.column(-4).search(status ? '^' + status + '$' : '', true, false).draw();

                    // var checkbox = $(this.node()).find('.issue-reason');
                    // console.log(checkbox)
                    // var isChecked = $checkbox.data('status'); // Get the data-status attribute value
                    // console.log('Filtering warehouse:', status, isChecked);

                    // if (status === 'all') {
                    //     $(this.node()).show();
                    // } else if (isChecked == status) {
                    //     $(this.node()).show();
                    // } else {
                    //     $(this.node()).hide();
                    // }
                });
            }

            // Tab click event
            $('#issuesTabs button').on('click', function() {
                $('#issuesTabs button').removeClass('active');
                $(this).addClass('active');

                var status = $(this).data('status'); // all / 1 / 0 
                filterWarehouse(status);
            });

            // Initial load: show all
            filterWarehouse('all');
        });
    </script>

@endsection
