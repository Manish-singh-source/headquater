@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            'blocked' => 'Blocked',
            'completed' => 'Completed',
            'ready_to_ship' => 'Ready To Ship',
            'ready_to_package' => 'Ready To Package',
        ];
    @endphp

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>

                                    <span id="orderId">{{ '#' . $salesOrder->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer Group Name</b></span>
                                    <span> <b>{{ $salesOrder->customerGroup->name }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Status</b></span>
                                    <span> <b>{{ $statuses[$salesOrder->status] ?? 'On Hold' }}</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">Customer PO Table</h6>
                        </div>

                        <!-- Tabs Navigation -->
                        <div class="div d-flex justify-content-end my-3 gap-2">
                            <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                <form id="statusForm" action="{{ route('change.order.status') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                                    <select class="form-select border-2 border-primary" id="changeStatus"
                                        aria-label="Default select example" name="status">
                                        <option value="" selected disabled>Change Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="blocked">Blocked</option>
                                        <option value="ready_to_package">Ready To Package</option>
                                        <option value="ready_to_ship">Ready To Ship</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </form>
                            </ul>
                        </div>
                    </div>
                    <div class="product-table" id="poTable">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Customer&nbsp;Name</th>
                                        <th>Vendor&nbsp;Code</th>
                                        <th>HSN</th>
                                        <th>Item&nbsp;Code</th>
                                        <th>SKU&nbsp;Code</th>
                                        <th>Title</th>
                                        <th>MRP</th>
                                        <th>Qty&nbsp;Requirement</th>
                                        <th>Qty&nbsp;Fullfilled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statuses = [
                                            'pending' => 'Pending',
                                            'blocked' => 'Blocked',
                                            'completed' => 'Completed',
                                            'ready_to_ship' => 'Ready To Ship',
                                        ];
                                    @endphp
                                    @forelse($salesOrder->orderedProducts as $order)
                                        <tr>
                                            <td>{{ $order->tempOrder->customer_name }}</td>
                                            <td>{{ $order->tempOrder->vendor_code }}</td>
                                            <td>{{ $order->tempOrder->hsn }}</td>
                                            <td>{{ $order->tempOrder->item_code }}</td>
                                            <td>{{ $order->tempOrder->sku }}</td>
                                            <td>{{ $order->tempOrder->description }}</td>
                                            <td>{{ $order->tempOrder->mrp }}</td>
                                            <td>{{ $order->ordered_quantity }}</td>
                                            @isset($order->vendorPIProduct?->available_quantity)
                                                <td>
                                                    @if (($order->vendorPIProduct?->available_quantity + $order->product->sets_ctn) >= $order->ordered_quantity)
                                                        <span class="badge text-success bg-success-subtle">{{ $order->vendorPIProduct?->available_quantity + $order->product->sets_ctn }}</span>
                                                    @else
                                                        <span class="badge text-danger bg-danger-subtle">{{ $order->vendorPIProduct?->available_quantity + $order->product->sets_ctn }}</span>
                                                    @endif
                                                </td>
                                            @endisset
                                        </tr>
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
