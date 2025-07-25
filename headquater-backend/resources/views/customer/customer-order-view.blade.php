@extends('layouts.master')
@section('main-content')
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

                                    <span>{{ '#' . $order->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer Group Name</b></span>
                                    <span> <b>{{ $order->group->group_name }}</b></span>
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
                            <h6 class="mb-3">PO Table</h6>
                        </div>
                        @php
                            $statuses = [
                                0 => 'Pending',
                                1 => 'Completed',
                                2 => 'On Hold',
                            ];
                        @endphp
                        <div class="col-6 col-lg-1 text-end">
                            <span
                                class="badge bg-danger-subtle text-danger fw-semibold"></span>
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
                                    @forelse($order->items as $order)
                                        <tr>
                                            <td>{{ $order->products->customer_name }}</td>
                                            <td>{{ $order->products->vendor_code }}</td>
                                            <td>{{ $order->products->hsn }}</td>
                                            <td>{{ $order->products->item_code }}</td>
                                            <td>{{ $order->product_id }}</td>
                                            <td>{{ $order->products->description }}</td>
                                            <td>{{ $order->products->mrp }}</td>
                                            <td>{{ $order->quantity }}</td>
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

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Appointments Date
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label for="dn amount" class="form-label">Appointments Date<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="dn amount" id="dn amount" class="form-control" value=""
                                required="" placeholder="Enter DN Amount">
                        </div>
                        <div class="col-12 col-md-4 text-start">
                            <button type="" class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Delivery Note
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-2">
                            <label for="dn amount" class="form-label">DN Amount<span class="text-danger">*</span></label>
                            <p>10,000 Rupess</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="dn reason" class="form-label">DN Reason<span class="text-danger">*</span></label>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. mollitia possimus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
