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
                    <div class="card">
                        <div class="card-header">
                            <h5>Address Details</h5>
                        </div>
                        <div class="card-body">
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
                                    <span><b>GST Treatment</b></span>
                                    <span>{{ $vendor->gst_treatment ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>PAN No</b></span>
                                    <span>{{ $vendor->pan_number ?? 'NA' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Address Details</h5>
                        </div>
                        <div class="card-body">
                            <ul class="col-12 list-group list-group-flush">
                                
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


                                <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                    <span><b>Billing Address</b></span>
                                    <span>{{ $vendor->billing_address ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                    <span><b>Billing Country</b></span>
                                    <span>{{ $vendor->billingCountry->name ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                    <span><b>Billing State</b></span>
                                    <span>{{ $vendor->billingState->name ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                    <span><b>Billing City</b></span>
                                    <span>{{ $vendor->billingCity->name ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                    <span><b>Billing Zip</b></span>
                                    <span>{{ $vendor->billing_zip ?? 'NA' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @isset($vendor->orders)
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Orders<span class="fw-light ms-2">({{ $vendor->orders->count() }})</span></h5>
                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example" class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Purchase Order No</th>
                                            <th>Vendor Name</th>
                                            <th>Order Status</th>
                                            <th>Ordered Date</th>
                                            <th>Product Count</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vendor->orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $vendor->client_name }}</td>
                                                <td>{{ $order->status }}</td>
                                                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $order->purchaseOrderProducts->count() }}</td>
                                                <td>
                                                    <a href="{{ route('purchase.order.view', $order->id) }}>"
                                                        class="btn btn-sm btn-primary">View</a>
                                                </td>
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
            @endisset

        </div>
    </main>
@endsection
