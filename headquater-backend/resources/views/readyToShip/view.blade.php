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
                        <select id="input9" class="form-select">
                            <option selected="" disabled>Status</option>
                            <option>Out For Delivery</option>
                            <option>Delivered</option>
                            <option>Completed</option>
                        </select>
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
                                <span>{{ $salesOrder->customerGroup->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> +91 123 456 7789 </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> manish@gmail.com</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Ordered Date</b></span>
                                <span> 2025-04-11</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Delivery Date</b></span>
                                <span> 2025-05-15</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Billing Address</b></span>
                                <span> Office No. 501, 5th Floor, Ghanshyam Enclave, Next To Laljipada Police Station,
                                    Laljipada, Link Road, Kandivali (West), Mumbai - 400067. Maharashtra - India</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b> Shipping Address</b></span>
                                <span> Office No. 501, 5th Floor, Ghanshyam Enclave, Next To Laljipada Police Station,
                                    Laljipada, Link Road, Kandivali (West), Mumbai - 400067. Maharashtra - India</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Invoices PDF</b></span>
                                <span> BK159.pdf</span>
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
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection
