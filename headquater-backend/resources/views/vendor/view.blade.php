@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Details</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Client Name</b></span>
                                <span>{{ $vendor->client_name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Contact Name</b></span>
                                <span>{{ $vendor->contact_name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span>{{ $vendor->phone_number ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span>{{ $vendor->email ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>GST No</b></span>
                                <span>{{ $vendor->gst_number ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>PAN No</b></span>
                                <span>{{ $vendor->pan_number ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Shipping Address</b></span>
                                <span>{{ $vendor->shipping_address ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Shipping Country</b></span>
                                <span>{{ $vendor->country->name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Shipping State</b></span>
                                <span>{{ $vendor->state->name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Shipping City</b></span>
                                <span>{{ $vendor->city->name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Shipping Zip</b></span>
                                <span>{{ $vendor->shipping_zip ?? 'NA' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            @isset($orders)
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Orders<span class="fw-light ms-2">({{ $orders->count() }})</span></h5>
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
                                                    <td>{{ 'PO-' . $order->purchase_order_id }}</td>
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
            @endisset

        </div>
    </main>
@endsection
