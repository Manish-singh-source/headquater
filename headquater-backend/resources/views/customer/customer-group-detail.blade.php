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
                            <li class="breadcrumb-item active" aria-current="page">Amazon</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="row g-3">
                <div class="col-12 col-md-2">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Search Customers">
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
                        {{-- <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button> --}}
                        <a href="{{ route('export.customer.group') }}" class="btn btn-filter px-4"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a>
                    </div>
                </div>
            </div>
            <!--end row-->


            <div class="row">
                <div class="col-12">
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
                                                <th>Client Name</th>
                                                <th>Contact Name</th>
                                                <th>Email</th>
                                                <th>Contact Number</th>
                                                <th>Shipping Address</th>
                                                <th>Billing Address</th>
                                                <th>Orders</th>
                                                <th>Joined At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $customer)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox">
                                                    </td>
                                                    <td>
                                                        <a class="d-flex align-items-center gap-3"
                                                            href="customer-detail.php">
                                                            <p class="mb-0 customer-name fw-bold">
                                                                {{ $customer->company_name }}</p>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a class="d-flex align-items-center gap-3"
                                                            href="customer-detail.php">
                                                            <p class="mb-0 customer-name fw-bold">
                                                                {{ $customer->first_name }} {{ $customer->last_name }}</p>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;" class="font-text1">{{ $customer->email }}</a>
                                                    </td>
                                                    <td>{{ $customer->phone }}</td>
                                                    <td>{{ $customer->shipping_address }}</td>
                                                    <td>{{ $customer->billing_address }}</td>
                                                    <td>142</td>
                                                    <td>{{ $customer->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!--end row-->




        </div>
    </main>
    <!--end main wrapper-->
@endsection
