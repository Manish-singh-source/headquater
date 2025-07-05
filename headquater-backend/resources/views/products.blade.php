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
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <!-- <div class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-medium flex-wrap font-text1">
                            <a href="javascript:;"><span class="me-1">All</span><span class="text-secondary">(88754)</span></a>
                            <a href="javascript:;"><span class="me-1">Published</span><span class="text-secondary">(56242)</span></a>
                            <a href="javascript:;"><span class="me-1">Drafts</span><span class="text-secondary">(17)</span></a>
                            <a href="javascript:;"><span class="me-1">On Discount</span><span class="text-secondary">(88754)</span></a>
                          </div> -->

            <div class="row g-3 justify-content-end">
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <a href="{{ route('add-product') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Add Product</a>
                    </div>
                </div>
            </div><!--end row-->

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
                                        <th>Warehouse</th>
                                        <th>Product&nbsp;Name</th>
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
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>{{ $product->warehouse->name }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">

                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->name }}</a>

                                                    </div>
                                                </div>
                                            </td>
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
                                    @endforeach
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
