@extends('layouts.master')
@section('main-content')


    @php
        $statusBadges = [
            'pending' => 'bg-secondary',
            'packaging' => 'bg-warning',
            'packaged' => 'bg-info',
            'partially_packaged' => 'bg-warning',
            'ready_to_ship' => 'bg-success',
            'dispatched' => 'bg-primary',
            'shipped' => 'bg-dark',
            'approval_pending' => 'bg-secondary',
            'completed' => 'bg-success',
        ];
        $statusLabels = [
            'pending' => 'Pending',
            'packaging' => 'Packaging',
            'packaged' => 'Packaged',
            'partially_packaged' => 'Partially Packaged',
            'ready_to_ship' => 'Ready to Ship',
            'dispatched' => 'Dispatched',
            'shipped' => 'Shipped',
            'approval_pending' => 'Ready to Ship Approval Pending',
            'completed' => 'Completed',
        ];

        $allocationStatusBadges = [
            'pending' => 'bg-secondary',
            'packaging' => 'bg-warning',
            'partially_packaged' => 'bg-warning',
            'approval_pending' => 'bg-secondary',
            'packaged' => 'bg-info',
            'shipped' => 'bg-dark',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        $allocationStatusLabels = [
            'pending' => 'Pending',
            'packaging' => 'Packaging',
            'partially_packaged' => 'Partially Packaged',
            'approval_pending' => 'Ready to Ship Approval Pending',
            'packaged' => 'Packaged',
            'shipped' => 'Shipped',
            'completed' => 'Ready to Ship',
            'cancelled' => 'Cancelled',
        ];
    
        $currectStatus = 'ready_to_ship';    
        $totalAllocations = $warehouseAllocations->count(); 
        $statuscounts = 0; 

        $allIds = $warehouseAllocations->pluck('id')->toArray();
    @endphp

    @foreach ($warehouseAllocations as $order)
        @php
            if ($order->shipping_status == 'shipped') {
                $statuscounts++;
            }
        @endphp
    @endforeach

    @php  
        if ($statuscounts == $totalAllocations && $totalAllocations > 0) {
            $currentStatus = 'shipped';
        } else {
            $currentStatus = $currectStatus;
        }
    @endphp 

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Delivery Details</h5>
                </div>
                <div class="col-6 d-flex justify-content-end text-end my-2 ">
                    <div>
                        <form id="statusForm" action="{{ route('change.status.shipped') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                            <input type="hidden" name="customer_id" value="{{ $customerInfo->id }}">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="all_ids" value="{{ implode(',', $allIds) }}">
                            <select class="form-select border-2 border-primary" id="changeStatus"
                                aria-label="Default select example" name="status">
                                <option value="" selected disabled>Change Status</option>

                                <option value="shipped" @if ($currentStatus == 'shipped') selected @endif>
                                    Shipped</option>
                                {{-- <option value="delivered" @if ($currentStatus == 'delivered') selected @endif>
                                    Delivered</option>    
                                <option value="completed" @if ($currentStatus == 'completed') selected @endif>
                                    Completed</option>     --}}
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Sales Order Id</b></span>

                                <span>{{ $salesOrder->order_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Status</b></span>

                                <span
                                    class="badge {{ $statusBadges[$currentStatus] ?? 'bg-secondary' }}">{{ $statusLabels[$currentStatus] ?? 'NA' }}</span>
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
                                <span> {{ $customerInfo->billing_address ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b> Shipping Address</b></span>
                                <span> {{ $customerInfo->shipping_address ?? 'NA' }}</span>
                            </li>
                            {{-- 
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Actions</b></span>
                                <span>

                                </span>
                            </li> 
                            --}}
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="div d-flex my-2">
                                <div class="col">
                                    <h6 class="mb-3">PO Table</h6>
                                </div>
                            </div>
                            <div class="product-table" id="poTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table id="customerPOTableList" class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Customer&nbsp;Name</th>
                                                {{-- <th>PO&nbsp;Number</th> --}}
                                                <th>SKU&nbsp;Code</th>
                                                <th>Facility&nbsp;Name</th>
                                                <th>Facility&nbsp;Location</th>
                                                <th>PO&nbsp;Date</th>
                                                <th>PO&nbsp;Expiry&nbsp;Date</th>
                                                <th>HSN</th>
                                                <th>Item&nbsp;Code</th>
                                                <th>Description</th>
                                                <th>GST</th>
                                                <th>Basic&nbsp;Rate</th>
                                                <th>Net&nbsp;Landing&nbsp;Rate</th>
                                                <th>MRP</th>
                                                <th>PO&nbsp;Quantity</th>
                                                <th>Purchase&nbsp;Order&nbsp;Quantity</th>
                                                <th>Warehouse&nbsp;Name</th>
                                                <th>Warehouse&nbsp;Allocation</th>
                                                {{-- <th>PI&nbsp;Quantity</th> --}}
                                                <th>Purchase&nbsp;Order&nbsp;No</th>
                                                <th>Total&nbsp;Dispatch&nbsp;Qty</th>
                                                <th>Final&nbsp;Dispatch&nbsp;Qty</th>
                                                <th>Case&nbsp;Pack&nbsp;Quantity</th>
                                                <th>Box&nbsp;Count</th>
                                                <th>Weight</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($warehouseAllocations as $order)
                                                @php
                                                    $warehouseName = '';
                                                    $warehouseAllocation = 0;
                                                    $totalDispatchedQty = 0;
                                                    $finalDispatchedQty = 0;
                                                    $boxCount = 0;
                                                    $weight = 0;
                                                    if ($isSuperAdmin ?? false) {
                                                        // warehouse names
                                                        $warehouseName = 'All';

                                                        // warehouse allocation
                                                        $warehouseAllocation =
                                                            $order->warehouse->name .
                                                            ' :' .
                                                            $order->allocated_quantity;

                                                        // total dispatched qty
                                                        $totalDispatchedQty =
                                                            $order->warehouse->name .
                                                                ' :' .
                                                                $order->allocated_quantity ??
                                                            0;

                                                        // final dispatched qty
                                                        $finalDispatchedQty =
                                                            $order->warehouse->name .
                                                                ' :' .
                                                                $order->final_dispatched_quantity ??
                                                            0;

                                                        // box count
                                                        $boxCount = $order->box_count ?? 0;

                                                        // weight
                                                        $weight = $order->weight ?? 0;
                                                    } else {
                                                        // warehouse names
                                                        $warehouseName = $user->warehouse->name;

                                                        if ($user->warehouse_id == $order->warehouse_id) {
                                                            $warehouseAllocation =
                                                                $user->warehouse->name .
                                                                ' :' .
                                                                $order->allocated_quantity;
                                                            $totalDispatchedQty = $order->allocated_quantity ?? 0;
                                                            $finalDispatchedQty =
                                                                $order->final_dispatched_quantity ?? 0;
                                                            $boxCount = $order->box_count ?? 0;
                                                            $weight = $order->weight ?? 0;
                                                        }
                                                    }

                                                @endphp

                                                <tr>
                                                    <td>{{ $order->customer->contact_name }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->sku }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->facility_name }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->facility_location }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->po_date }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->po_expiry_date }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->hsn }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->item_code }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->description }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->gst }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->basic_rate }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->net_landing_rate }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->mrp }}</td>
                                                    <td>{{ $order->salesOrderProduct->ordered_quantity }}</td>
                                                    <td>{{ $order->salesOrderProduct->purchase_ordered_quantity }}</td>
                                                    <td>{{ $warehouseName }}</td>
                                                    <td>{{ $warehouseAllocation }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->po_number }}</td>
                                                    <td>{{ $totalDispatchedQty }}</td>
                                                    <td>{{ $finalDispatchedQty }}</td>
                                                    <td>{{ $order->salesOrderProduct->tempOrder->case_pack_quantity }}</td>
                                                    <td>{{ $boxCount }}</td>
                                                    <td>{{ $weight }}</td>
                                                    <td>
                                                        @if ($order->salesOrderProduct->status == 'ready_to_ship')
                                                            @if ($isSuperAdmin ?? false)
                                                                <span
                                                                    class="badge {{ $statusBadges[$order->salesOrderProduct->status] ?? 'bg-secondary' }}">
                                                                    {{-- {{ $user->warehouse->name }}: --}}
                                                                    {{ $statusLabels[$order->salesOrderProduct->status] ?? 'Ready to Ship' }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="badge {{ $statusBadges[$order->salesOrderProduct->status] ?? 'bg-secondary' }}">
                                                                    {{ $statusLabels[$order->salesOrderProduct->status] ?? 'Ready to Ship' }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            @if ($isSuperAdmin ?? false)
                                                                @if ($order->shipping_status == 'shipped')
                                                                    @php $order->product_status = 'shipped'; @endphp
                                                                @endif
                                                                @if ($order->final_dispatched_quantity > 0)
                                                                    <span
                                                                        class="badge {{ $allocationStatusBadges[$order->product_status] ?? 'bg-secondary' }}">
                                                                        {{ $order->warehouse->name }}:
                                                                        {{ $allocationStatusLabels[$order->product_status] ?? 'Unknown' }}
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="badge {{ $allocationStatusBadges[$order->product_status] ?? 'bg-secondary' }}">
                                                                        {{ $order->warehouse->name }}:
                                                                        {{ $allocationStatusLabels[$order->product_status] ?? 'Unknown' }}
                                                                    </span>
                                                                @endif
                                                            @else
                                                                @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                    @if ($order->shipping_status == 'shipped')
                                                                        @php $order->product_status = 'shipped'; @endphp
                                                                    @endif
                                                                    @if ($order->final_dispatched_quantity > 0)
                                                                        <span
                                                                            class="badge {{ $allocationStatusBadges[$order->product_status] ?? 'bg-secondary' }}">
                                                                            {{ $allocationStatusLabels[$order->product_status] ?? 'Unknown' }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge {{ $allocationStatusBadges[$order->product_status] ?? 'bg-secondary' }}">
                                                                            {{ $allocationStatusLabels[$order->product_status] ?? 'Unknown' }}
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="23" class="text-center">No records found. Please update
                                                        or
                                                        upload
                                                        a PO to see data.</td>
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
            var table1 = $('#customerPOTableList').DataTable({
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
        });
        document.getElementById('changeStatus').addEventListener('change', function() {
            if (confirm('Are you sure you want to change status for order?')) {
                document.getElementById('statusForm').submit();
            }
        });
    </script>
@endsection
