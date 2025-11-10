@extends('layouts.master')
@section('main-content')
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
                                    @php
                                        $warehouseButtons = [];
                                        foreach($displayProducts as $product) {
                                            $warehouseName = $product['warehouse_name'];
                                            if($warehouseName !== 'N/A' && $warehouseName !== 'All' && !in_array($warehouseName, $warehouseButtons)) {
                                                $warehouseButtons[] = $warehouseName;
                                            }
                                        }
                                    @endphp

                                    @if(count($warehouseButtons) > 1)
                                        @if($invoice)
                                            <a href="{{ route('invoices.view', $salesOrder->id) }}" class="btn btn-sm btn-primary me-1">View All Invoices</a>
                                        @endif
                                        @foreach(array_unique($warehouseButtons) as $whName)
                                            @php
                                                $warehouse = \App\Models\Warehouse::where('name', $whName)->first();
                                                if($warehouse) {
                                                    $hasProducts = collect($displayProducts)->contains('warehouse_name', $whName);
                                                    $warehouseInvoice = \App\Models\Invoice::where('sales_order_id', $salesOrder->id)
                                                        ->where('customer_id', $customerInfo->id)
                                                        ->where('warehouse_id', $warehouse->id)
                                                        ->first();
                                                    if($hasProducts) {
                                                        // Check if user can see this invoice
                                                        $canViewInvoice = true;
                                                        if(!$isSuperAdmin && !$isAdmin && $userWarehouseId && $warehouse->id != $userWarehouseId) {
                                                            $canViewInvoice = false;
                                                        }
                                                        if($canViewInvoice) {
                                            @endphp
                                                @if($warehouseInvoice)
                                                    <a href="{{ route('invoices-details', $warehouseInvoice->id) }}"
                                                       class="btn btn-sm btn-info me-1 mb-1">
                                                        View {{ $whName }} Invoice
                                                    </a>
                                                @else
                                                    @if($isSuperAdmin || $isAdmin || (!$userWarehouseId || $warehouse->id == $userWarehouseId))
                                                    <a href="{{ route('ready.to.ship.generate.warehouse.invoice', ['orderId' => $salesOrder->id, 'customerId' => $customerInfo->id, 'warehouseId' => $warehouse->id]) }}"
                                                       class="btn btn-sm btn-success me-1 mb-1"
                                                       onclick="return confirm('Generate invoice for {{ $whName }} warehouse?')">
                                                        Generate {{ $whName }} Invoice
                                                    </a>
                                                    @endif
                                                @endif
                                            @php
                                                        }
                                                    }
                                                }
                                            @endphp
                                        @endforeach
                                    @else
                                        @if($invoice)
                                            <a href="{{ route('invoices.view', $salesOrder->id) }}" class="btn btn-sm btn-primary">View Invoice</a>
                                        @else
                                            <a href="{{ route('ready.to.ship.generate.warehouse.invoice', ['orderId' => $salesOrder->id, 'customerId' => $customerInfo->id, 'warehouseId' => 0]) }}"
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Generate invoice for this order?')">
                                                Generate Invoice
                                            </a>
                                        @endif
                                    @endif
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
                                    <table id="example" class="table align-middle">
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
                                                <th>Basic&nbsp;Rate</th>
                                                <th>GST</th>
                                                <th>Net&nbsp;Landing&nbsp;Rate</th>
                                                <th>MRP</th>
                                                <th>PO&nbsp;Quantity</th>
                                                <th>Warehouse&nbsp;Name</th>
                                                <th>Warehouse&nbsp;Allocation</th>
                                                <th>Warehouse&nbsp;Stock</th>
                                                {{-- <th>PI&nbsp;Qty</th> --}}
                                                <th>Purchase&nbsp;Order&nbsp;No</th>
                                                <th>Total&nbsp;Dispatch&nbsp;Qty</th>
                                                <th>Final&nbsp;Dispatch&nbsp;Qty</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $statuses = [
                                                    'pending' => 'Pending',
                                                    'blocked' => 'Blocked',
                                                    'completed' => 'Completed',
                                                    'ready_to_ship' => 'Ready To Ship',
                                                    'ready_to_package' => 'Ready To Package',
                                                    'shipped' => 'Shipped'
                                                ];
                                            @endphp
                                            @forelse($displayProducts as $displayProduct)
                                                @php
                                                    $order = $displayProduct['order'];
                                                @endphp
                                                <tr>
                                                    <td>{{ $order->customer->contact_name }}</td>
                                                    {{-- <td>{{ $order->tempOrder->po_number }}</td> --}}
                                                    <td>{{ $order->tempOrder->sku }}</td>
                                                    <td>{{ $order->tempOrder->facility_name }}</td>
                                                    <td>{{ $order->tempOrder->facility_location }}</td>
                                                    <td>{{ $order->tempOrder->po_date }}</td>
                                                    <td>{{ $order->tempOrder->po_expiry_date }}</td>
                                                    <td>{{ $order->tempOrder->hsn }}</td>
                                                    <td>{{ $order->tempOrder->item_code }}</td>
                                                    <td>{{ $order->tempOrder->description }}</td>
                                                    <td>{{ $order->tempOrder->basic_rate }}</td>
                                                    <td>{{ $order->tempOrder->gst }}</td>
                                                    <td>{{ $order->tempOrder->net_landing_rate }}</td>
                                                    <td>{{ $order->tempOrder->mrp }}</td>
                                                    <td>{{ $order->tempOrder->po_qty }}</td>

                                                    {{-- Warehouse Name --}}
                                                    <td>{{ $displayProduct['warehouse_name'] ?? 'N/A' }}</td>

                                                    {{-- Warehouse Allocation --}}
                                                    <td>
                                                        @php
                                                            $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;
                                                        @endphp
                                                        @if($hasAllocations)
                                                            @if($isSuperAdmin ?? false)
                                                                {{ $displayProduct['warehouse_name'] }}: {{ $displayProduct['allocated_quantity'] }}
                                                            @elseif($isAdmin ?? false)
                                                                @foreach($order->warehouseAllocations as $allocation)
                                                                    {{ $allocation->warehouse->name ?? 'N/A' }}: {{ $allocation->allocated_quantity }}@if(!$loop->last), @endif
                                                                @endforeach
                                                            @else
                                                                @if(isset($displayProduct['allocated_quantity']) && $displayProduct['allocated_quantity'] !== null)
                                                                    {{ $displayProduct['warehouse_name'] }}: {{ $displayProduct['allocated_quantity'] }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if(isset($displayProduct['allocated_quantity']) && $displayProduct['allocated_quantity'] !== null)
                                                                {{ $displayProduct['warehouse_name'] }}: {{ $displayProduct['allocated_quantity'] }}
                                                            @else
                                                                {{ $order->tempOrder->block ?? 0 }}
                                                            @endif
                                                        @endif
                                                    </td>

                                                    {{-- Warehouse Stock --}}
                                                    <td>{{ $order->warehouseStock->original_quantity ?? '0' }}</td>

                                                    <td>{{ $order->tempOrder->po_number }}</td>

                                                    {{-- Total Dispatch Qty --}}
                                                    <td>{{ $order->dispatched_quantity ?? 0 }}</td>

                                                    {{-- Final Dispatch Qty --}}
                                                    <td>{{ $displayProduct['final_dispatched_quantity'] ?? 0 }}</td>

                                                    {{-- Product Status --}}
                                                    <td>
                                                        @php
                                                            $productStatus = $order->status ?? 'pending';
                                                            $statusBadges = [
                                                                'pending' => 'bg-secondary',
                                                                'packaging' => 'bg-warning',
                                                                'packaged' => 'bg-info',
                                                                'ready_to_ship' => 'bg-success',
                                                                'dispatched' => 'bg-primary',
                                                                'shipped' => 'bg-dark',
                                                                'completed' => 'bg-success',
                                                            ];
                                                        @endphp
                                                        <span class="badge {{ $statusBadges[$productStatus] ?? 'bg-secondary' }}">
                                                            {{ $statuses[$productStatus] ?? 'Unknown' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="22">No Records Found</td>
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
