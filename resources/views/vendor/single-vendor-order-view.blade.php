@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div my-2">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <h5 class="card-title mb-0">
                                            Vendor Details
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <ul class="col-12 list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Order Id</b></span>
                                            <span>{{ $orders[0]->id }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Vendor Name</b></span>
                                            <span>
                                                <b>
                                                    {{ $vendor->client_name ?? '' }}
                                                </b>
                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Vendor Email</b></span>
                                            <span>
                                                <b>
                                                    {{ $vendor->email ?? '' }}
                                                </b>
                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Vendor Contact No.</b></span>
                                            <span>
                                                <b>
                                                    {{ $vendor->phone_number ?? '' }}
                                                </b>
                                            </span>
                                        </li>

                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                            <span><b>Vendor Address</b></span>
                                            <span>
                                                <b>
                                                    {{ $vendor->shipping_address ?? '' }}
                                                </b>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

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
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Purchase Order No</th>
                                        <th>Vendor Name</th>
                                        <th>SKU Code</th>
                                        <th>Brand</th>
                                        <th>Brand Title</th>
                                        <th>MRP</th>
                                        <th>Qty Requirement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        @if ($order->ordered_quantity > 0)
                                            <tr>
                                                <td>{{ $order->purchase_order_id }}</td>
                                                <td>{{ $vendor->client_name }}</td>
                                                <td>{{ $order->sku }}</td>
                                                <td>{{ $order->product->brand }}</td>
                                                <td>{{ $order->product->brand_title }}</td>
                                                <td>{{ $order->product->mrp }}</td>
                                                <td>{{ $order->ordered_quantity }}</td>
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
    </main>
    <!--end main wrapper-->
@endsection
