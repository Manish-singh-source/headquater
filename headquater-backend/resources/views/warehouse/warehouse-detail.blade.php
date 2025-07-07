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
                            <li class="breadcrumb-item active" aria-current="page"><b>Warehouse:</b> {{ ucfirst($warehouse->name) }} </li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Warehouse Id</b></span>
                                <span>#001</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Warehouse Location</b></span>
                                <span>{{ $warehouse->cities->name }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Contact Name</b></span>
                                <span>{{ $warehouse->contact_person_name }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> {{ $warehouse->phone }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> {{ $warehouse->email }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>GST No</b></span>
                                <span> {{ $warehouse->gst_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Address</b></span>
                                <span> {{ $warehouse->address_line_1 }} {{ $warehouse->address_line_2 }}</span>
                            </li>
                        </ul>


                    </div>
                </div>

            </div><!--end row-->

            <div class="row g-3 justify-content-end">
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button>
                        <a href="{{ route('add-product') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Add Product</a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>    
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Product&nbsp;Name</th>
                                        <th>SKU</th>
                                        <th>item&nbsp;id</th>
                                        <th>vendor&nbsp;name</th>
                                        <th>vendor&nbsp;legal&nbsp;name </th>
                                        <th>manufacturer&nbsp;name</th>
                                        <th>facility&nbsp;name</th>
                                        <th>units</th>
                                        <th>units&nbsp;ordered</th>
                                        <th>landing&nbsp;rate</th>
                                        <th>cost&nbsp;price</th>
                                        <th>total&nbsp;amount</th>
                                        <th>mrp</th>
                                        <th>po&nbsp;status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($warehouse->products as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->name }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->item_id }}</td>
                                            <td>{{ $product->vendor_name }}</td>
                                            <td>{{ $product->entity_vendor_legal_name }}</td>
                                            <td>{{ $product->manufacturer_name }}</td>
                                            <td>{{ $product->facility_name }}</td>
                                            <td>{{ $product->units }}</td>
                                            <td>{{ $product->units_ordered }}</td>
                                            <td>{{ $product->landing_rate }}</td>
                                            <td>{{ $product->cost_price }}</td>
                                            <td>{{ $product->total_amount }}</td>
                                            <td>{{ $product->mrp }}</td>
                                            <td>{{ $product->po_status }}</td>
                                            <td>{{ $product->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center">No Products Found</td>
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
