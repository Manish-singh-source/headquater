@extends('layouts.master')
@section('main-content')


    @php
        $statusBadges = [
            'pending' => 'bg-secondary',
            'packaging' => 'bg-warning',
            'packaged' => 'bg-info',
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
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        $allocationStatusLabels = [
            'pending' => 'Pending',
            'packaging' => 'Packaging',
            'partially_packaged' => 'Partially Packaged',
            'approval_pending' => 'Ready to Ship Approval Pending',
            'packaged' => 'Packaged',
            'completed' => 'Ready to Ship',
            'cancelled' => 'Cancelled',
        ];
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
                        <form id="statusForm" action="{{ route('change.sales.order.status') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                            <input type="hidden" name="customer_id" value="{{ $customerInfo->id }}">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <select class="form-select border-2 border-primary" id="changeStatus"
                                aria-label="Default select example" name="status">
                                <option value="" selected disabled>Change Status</option>

                                <option value="shipped" @if ($salesOrder->status == 'shipped') selected @endif>
                                    Shipped</option>
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
                                <span> {{ $customerInfo->billing_address ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b> Shipping Address</b></span>
                                <span> {{ $customerInfo->shipping_address ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Actions</b></span>
                                <span>

                                </span>
                            </li>
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
                                                <th>Box&nbsp;Count</th>
                                                <th>Weight</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($salesOrder->orderedProducts as $order)
                                                <tr>
                                                    <td>{{ $order->customer->contact_name }}</td>
                                                    <td>{{ $order->tempOrder->sku }}</td>
                                                    <td>{{ $order->tempOrder->facility_name }}</td>
                                                    <td>{{ $order->tempOrder->facility_location }}</td>
                                                    <td>{{ $order->tempOrder->po_date }}</td>
                                                    <td>{{ $order->tempOrder->po_expiry_date }}</td>
                                                    <td>{{ $order->tempOrder->hsn }}</td>
                                                    <td>{{ $order->tempOrder->item_code }}</td>
                                                    <td>{{ $order->tempOrder->description }}</td>
                                                    <td>{{ $order->tempOrder->gst }}</td>
                                                    <td>{{ $order->tempOrder->basic_rate }}</td>
                                                    <td>{{ $order->tempOrder->net_landing_rate }}</td>
                                                    <td>{{ $order->tempOrder->mrp }}</td>
                                                    <td>{{ $order->ordered_quantity }}</td>
                                                    <td>{{ $order->purchase_ordered_quantity }}</td>
                                                    @if ($isSuperAdmin)
                                                        <td>All</td>
                                                    @else
                                                        <td>{{ $user->warehouse->name }}</td>
                                                    @endif
                                                    <td>
                                                        @if ($order->warehouseAllocations->count() >= 1)
                                                            @foreach ($order->warehouseAllocations as $allocation)
                                                                @if ($isSuperAdmin ?? false)
                                                                    <div>
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $allocation->allocated_quantity }}
                                                                    </div>
                                                                @else
                                                                    @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                        <div>
                                                                            {{ $user->warehouse->name }}:
                                                                            {{ $allocation->allocated_quantity ?? 0 }}
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $order->dispatched_quantity ?? 0 }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $order->tempOrder->po_number }}</td>
                                                    <td>
                                                        @if ($order->warehouseAllocations->count() >= 1)
                                                            @foreach ($order->warehouseAllocations as $allocation)
                                                                @if ($isSuperAdmin ?? false)
                                                                    <div>
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $allocation->allocated_quantity ?? 0 }}
                                                                    </div>
                                                                @else
                                                                    @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                        <div>
                                                                            {{ $allocation->allocated_quantity ?? 0 }}
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $order->dispatched_quantity ?? 0 }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->warehouseAllocations->count() >= 1)
                                                            @foreach ($order->warehouseAllocations as $allocation)
                                                                @if ($isSuperAdmin ?? false)
                                                                    <div>
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $allocation->final_dispatched_quantity ?? 0 }}
                                                                    </div>
                                                                @else
                                                                    @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                        <div>
                                                                            {{ $allocation->final_dispatched_quantity ?? 0 }}
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $order->final_dispatched_quantity ?? 0 }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->warehouseAllocations->count() >= 1)
                                                            @foreach ($order->warehouseAllocations as $allocation)
                                                                @if ($isSuperAdmin ?? false)
                                                                    <div>
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $allocation->box_count ?? 0 }}
                                                                    </div>
                                                                @else
                                                                    @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                        <div>
                                                                            {{ $allocation->box_count ?? 0 }}
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $order->box_count ?? 0 }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->warehouseAllocations->count() >= 1)
                                                            @foreach ($order->warehouseAllocations as $allocation)
                                                                @if ($isSuperAdmin ?? false)
                                                                    <div>
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $allocation->weight ?? 0 }}
                                                                    </div>
                                                                @else
                                                                    @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                        <div>
                                                                            {{ $allocation->weight ?? 0 }}
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $order->weight ?? 0 }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'ready_to_ship')
                                                            @if ($isSuperAdmin ?? false)
                                                                <span
                                                                    class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                                    {{-- {{ $user->warehouse->name }}: --}}
                                                                    {{ $statusLabels[$order->status] ?? 'Ready to Ship' }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                                    {{ $statusLabels[$order->status] ?? 'Ready to Ship' }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            @if ($order->warehouseAllocations->count() >= 1)
                                                                @foreach ($order->warehouseAllocations as $allocation)
                                                                    @if ($isSuperAdmin ?? false)
                                                                        @if ($allocation->final_dispatched_quantity > 0)
                                                                            <span
                                                                                class="badge {{ $allocationStatusBadges[$allocation->product_status] ?? 'bg-secondary' }}">
                                                                                {{ $allocation->warehouse->name }}:
                                                                                {{ $allocationStatusLabels[$allocation->product_status] ?? 'Unknown' }}
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="badge {{ $allocationStatusBadges[$allocation->product_status] ?? 'bg-secondary' }}">
                                                                                {{ $allocation->warehouse->name }}:
                                                                                {{ $allocationStatusLabels[$allocation->product_status] ?? 'Unknown' }}
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                            @if ($allocation->final_dispatched_quantity > 0)
                                                                                <span
                                                                                    class="badge {{ $allocationStatusBadges[$allocation->product_status] ?? 'bg-secondary' }}">
                                                                                    {{ $allocationStatusLabels[$allocation->product_status] ?? 'Unknown' }}
                                                                                </span>
                                                                            @else
                                                                                <span
                                                                                    class="badge {{ $allocationStatusBadges[$allocation->product_status] ?? 'bg-secondary' }}">
                                                                                    {{ $allocationStatusLabels[$allocation->product_status] ?? 'Unknown' }}
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <span
                                                                    class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                                    {{-- {{ $order->warehouse->name }}: --}}
                                                                    {{ $statusLabels[$order->status] ?? 'Unknown' }}
                                                                </span>
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
        document.getElementById('changeStatus').addEventListener('change', function() {
            if (confirm('Are you sure you want to change status for order?')) {
                document.getElementById('statusForm').submit();
            }
        });
    </script>
@endsection
