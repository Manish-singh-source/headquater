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
                        <a href="{{ route('add-product')}}" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Add Product</a>
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
                                        <th>Warehouse</th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Total Quantity</th>
                                        <th>Available Quantity</th>
                                        <th>Hold Quantity</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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
                                        <td>Baroda</td>
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
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>
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