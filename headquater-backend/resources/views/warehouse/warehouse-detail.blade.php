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
                            <li class="breadcrumb-item active" aria-current="page">Baroda Warehouse Products List</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Warehouse Id</b></span>
                                <span>#001</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Warehouse Location</b></span>
                                <span>{{ $warehouse->cities->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Contact Name</b></span>
                                <span>{{ $warehouse->contact_person_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> {{ $warehouse->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> {{ $warehouse->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>GST No</b></span>
                                <span> {{ $warehouse->gst_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Address</b></span>
                                <span> {{ $warehouse->address_line_1 }} {{ $warehouse->address_line_2  }}</span>
                            </li>
                        </ul>


                    </div>
                </div>

            </div><!--end row-->

            <div class="row g-3">
                <div class="col-12 col-md-2">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Search Products">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
                <div class="col-12 col-md-2 flex-grow-1 overflow-auto">
                    <div class="btn-group position-static">
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Sort
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Sort By SKU</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Price</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Quantity</a></li>
                            </ul>
                        </div>
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Available</a></li>
                                <li><a class="dropdown-item" href="javascript:;">On Hold</a></li>
                            </ul>
                        </div>
                        <!-- <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Date
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Inactive</a></li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button>
                        <a href="{{route('add-product') }}" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Add Product</a>
                    </div>
                </div>
            </div><!--end row-->

            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Available</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Available</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Available</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Hold</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Hold</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Hold</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Hold</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Hold</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
                                        <td>Hold</td>
                                        <td><a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a></td>

                                    </tr>

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

    
