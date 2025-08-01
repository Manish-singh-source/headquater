@extends('layouts.master')

@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><b>Customers Details:</b></li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer Group Name</b></span>
                                    <span> <b>{{ $customerDetails->groupInfo->customerGroup->name ?? 'NA' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Client Name</b></span>
                                    <span> <b>{{ $customerDetails->client_name ?? 'NA' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Contact Name</b></span>
                                    <span> <b>{{ $customerDetails->contact_name ?? 'NA' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Contact Number</b></span>
                                    <span> <b>{{ $customerDetails->contact_no ?? 'NA' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Email</b></span>
                                    <span> <b>{{ $customerDetails->email ?? 'NA' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>GSTIN</b></span>
                                    <span> <b>{{ $customerDetails->gstin ?? 'NA' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>PAN</b></span>
                                    <span> <b>{{ $customerDetails->pan ?? 'NA' }}</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @isset($customerDetails->orders)
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Ordered Products<span class="fw-light ms-2">({{ $customerDetails->orders->count() }})</span></h5>
                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example" class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>SKU</th>
                                            <th>Brand</th>
                                            <th>Brand Title</th>
                                            <th>Ordered Quantity</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerDetails->orders as $order)
                                            <tr>
                                                <td>{{ $order->sales_order_id }}</td>
                                                <td>
                                                    {{ $order->sku }}
                                                </td>
                                                <td>
                                                    {{ $order->product->brand }}
                                                </td>
                                                <td>
                                                    {{ $order->product->brand_title }}
                                                </td>
                                                <td>
                                                    {{ $order->ordered_quantity }}
                                                </td>
                                                <td>
                                                    {{ $order->created_at->format('d-M-Y') }}
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
