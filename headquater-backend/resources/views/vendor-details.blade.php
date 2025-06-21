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
            <!-- <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Settings</button>
                        <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                                href="javascript:;">Action</a>
                            <a class="dropdown-item" href="javascript:;">Another action</a>
                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                        </div>
                    </div>
                </div> -->
        </div>
        <!--end breadcrumb-->


        <div class="row">
            <div class="col-12">
                <div class="card w-100 d-flex flex-sm-row flex-col">
                    <div class="col card-body d-flex">
                        <div class="position-relative justify-content-center">
                            <img src="assets/images/avatars/02.png" class="img-fluid rounded" alt="">
                            <!-- <div class="position-absolute top-100 start-50 translate-middle">
                                    <img src="assets/images/avatars/02.png" width="100" height="100"
                                        class="rounded-circle raised p-1 bg-white" alt="">
                                </div> -->
                            <div class="text-center my-2 pt-2">
                                <h4 class="mb-1">Manish Carry</h4>
                                <p class="mb-0">Marketing Excutive</p>
                            </div>
                        </div>


                    </div>
                    <ul class="col-10 list-group list-group-flush">
                        <li class="list-group-item">
                            <b>Phone No</b>
                            <br>
                            {{ $vendor->phone }}
                        </li>
                        <li class="list-group-item">
                            <b>Email</b>
                            <br>
                            {{ $vendor->email }}
                        </li>
                        <li class="list-group-item">
                            <b>GST No</b>
                            <br>
                            {{ $vendor->gst_no }}
                        </li>
                        <li class="list-group-item">
                            <b>PAN No</b>
                            <br>
                            {{ $vendor->pan_no }}
                        </li>
                        <li class="list-group-item border-top">
                            <b>Address</b>
                            <br>
                            {{ $vendor->address }}
                        </li>
                        <li class="list-group-item border-top">
                            <b>Bank Name</b>
                            <br>
                            {{ $vendor->bank_name }}
                        </li>
                        <li class="list-group-item border-top">
                            <b>Account No</b>
                            <br>
                            {{ $vendor->account_no }}
                        </li>
                        <li class="list-group-item border-top">
                            <b>IFSC Code</b>
                            <br>
                            {{ $vendor->ifsc_code }}
                        </li>
                    </ul>

                </div>
            </div>

        </div><!--end row-->


        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Orders<span class="fw-light ms-2">(98)</span></h5>
                <div class="product-table">
                    <div class="table-responsive white-space-nowrap">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Delivery Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#2453</td>
                                    <td><span
                                            class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Paid<i
                                                class="bi bi-check2 ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                                                class="bi bi-check2 ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>$427</td>
                                    <td><span
                                            class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                class="bi bi-x-lg ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                                                class="bi bi-check2 ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#9635</td>
                                    <td><span
                                            class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                                                class="bi bi-info-circle ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                class="bi bi-x-lg ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#2415</td>
                                    <td><span
                                            class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                                                class="bi bi-check2-all ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                                                class="bi bi-info-circle ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#3526</td>
                                    <td><span
                                            class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                class="bi bi-x-lg ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                                                class="bi bi-check2 ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#7845</td>
                                    <td><span
                                            class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Paid<i
                                                class="bi bi-check2 ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                class="bi bi-x-lg ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#256</td>
                                    <td><span
                                            class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                                                class="bi bi-info-circle ms-2"></i></span></td>
                                    <td><span
                                            class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                                                class="bi bi-check2-all ms-2"></i></span></td>
                                    <td>Cash on delivery</td>
                                    <td>Jun 12, 12:56 PM</td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
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