@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Invoices List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->



            <div class="row g-3">
                <div class="col-12 col-md-2">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Search Invoices No">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
                <div class="col-12 col-md-2 flex-grow-1 overflow-auto">
                    <div class="btn-group position-static">
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Sort
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Sort By Name</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Email</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Orders</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Location</a></li>
                            </ul>
                        </div>
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Inactive</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <a href="{{ route('create-invoice') }}"><button class="btn btn-primary px-4"><i
                                    class="bi bi-plus-lg me-2"></i>Create Invoice</button></a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Order Id</th>
                                        <th>Invoice No</th>
                                        <th>Customer Name</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>#{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->client_name }}</td>
                                            <td>
                                                {{ $invoice->invoice_date }}
                                            </td>
                                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>
                                                <a aria-label="anchor"
                                                    href="{{ route('invoice.downloadPdf', $invoice->id) }}"
                                                    class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                    data-bs-toggle="tooltip" data-bs-original-title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-eye text-primary">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <!-- <a type="button" class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit text-warning">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                        </path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                    <img width="15" height="15"
                                                        src="https://img.icons8.com/ios/50/document--v1.png"
                                                        alt="bank-card-back-side--v1" />
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                    <img width="15" height="15"
                                                        src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png"
                                                        alt="bank-card-back-side--v1" />
                                                </a> -->
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>#002</td>
                                            <td>INV0002</td>
                                            <td>EFG</td>
                                            <td>
                                                2025-04-11
                                            </td>
                                            <td>10,000</td>
                                            <td>8,000</td>
                                            <td>2,000</td>
                                            <td class="text-primary">Pending</td>
                                            <td>
                                                <a aria-label="anchor" href="invoices-details.php"
                                                    class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                    data-bs-toggle="tooltip" data-bs-original-title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-eye text-primary">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit text-warning">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                        </path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                    <img width="15" height="15" src="https://img.icons8.com/ios/50/document--v1.png" alt="bank-card-back-side--v1" />
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                    <img width="15" height="15" src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png" alt="bank-card-back-side--v1" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>#003</td>
                                            <td>INV0003</td>
                                            <td>XYZ</td>
                                            <td>
                                                2025-04-11
                                            </td>
                                            <td>10,000</td>
                                            <td>8,000</td>
                                            <td>2,000</td>
                                            <td class="text-danger">Issue</td>
                                            <td>
                                                <a aria-label="anchor" href="invoices-details.php"
                                                    class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                    data-bs-toggle="tooltip" data-bs-original-title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-eye text-primary">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit text-warning">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                        </path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                    <img width="15" height="15" src="https://img.icons8.com/ios/50/document--v1.png" alt="bank-card-back-side--v1" />
                                                </a>
                                                <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                    <img width="15" height="15" src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png" alt="bank-card-back-side--v1" />
                                                </a>
                                            </td>
                                        </tr> -->
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
