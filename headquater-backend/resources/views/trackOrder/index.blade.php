@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Track Order
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('trackOrder.index') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="d-flex gap-3 justify-content-start align-items-end">
                            <div class="col-9 ">
                                <label for="order_id" class="form-label">Track Order Id<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="order_id" id="order_id" class="form-control" value="{{ request('order_id') ?? '' }}" placeholder="Enter Track Order Id">
                            </div>
                            <div class="col-2">
                                <button type="submit" id="track-order"
                                    class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                    Track
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @isset($salesOrder->id)
                <div id="order-status-section">
                    <div class="div my-2">
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
                                            <span> <b>{{ $salesOrder->customerGroup->name }}</b> </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Order Status</b></span>
                                            <span
                                                class="badge bg-danger-subtle text-danger fw-bold">{{ $salesOrder->status }}</span>
                                        </li>
                                        @if ($salesOrder->status == 'blocked')
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                                <span><b>Hold Reason</b></span>
                                                <span>Products Not Available</span>
                                            </li>
                                        @endif
                                        <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Order Invoice</b></span>
                                            <span>
                                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    aria-label="Pdf" data-bs-original-title="Pdf">PDF <img
                                                        src="assets/svg/pdf.svg" alt="img"></a>
                                            </span>
                                        </li>
                                        @if ($salesOrder->status == 'paid')
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                                <span><b>Order Payment Status</b></span>
                                                <span class="badge bg-primary-subtle text-primary fw-bold">Paid</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                                <th>Customer Name</th>
                                                <th>Vendor Code</th>
                                                <th>HSN</th>
                                                <th>Item Code</th>
                                                <th>SKU Code</th>
                                                <th>Title</th>
                                                <th>MRP</th>
                                                <th>Qty Requirement</th>
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
            @endisset
        </div>

    </main>
    <!--end main wrapper-->
@endsection
