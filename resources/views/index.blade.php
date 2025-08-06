@extends('layouts.master')

@section('main-content')
    <!--start main wrapper-->

    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Dashboard</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Analysis</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xxl-8 d-flex align-items-stretch">
                    <div class="card w-100 overflow-hidden rounded-4">
                        <div class="card-body position-relative p-4">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    @can('PermissionChecker', 'view_dashboard')
                                        <div class="d-flex align-items-center gap-3 mb-5">
                                            <img src="{{ Auth::user()->profile_image ?? Avatar::create(Auth::user()->fname)->toBase64() }}" class="rounded-circle bg-grd-info p-1"
                                                width="60" height="60" alt="user">
                                            <div class="">
                                                <p class="mb-0 fw-semibold">Welcome back</p>
                                                <h4 class="fw-semibold mb-0 fs-4">
                                                    {{ ucfirst(Auth::user()->fname) }}
                                                    {{ ucfirst(Auth::user()->lname) }}
                                                </h4>
                                            </div>
                                        </div>
                                    @endcan

                                    @can('PermissionChecker', 'view_dashboard_detail')
                                        <div class="d-flex align-items-center gap-5">
                                            <div class="">
                                                <h4 class="mb-1 fw-semibold d-flex align-content-center">₹65.4K<i
                                                        class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                                </h4>
                                                <p class="mb-3">Todays Sales</p>
                                                <div class="progress mb-0" style="height:5px;">
                                                    <div class="progress-bar bg-grd-success" role="progressbar"
                                                        style="width: 60%" aria-valuenow="25" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="vr"></div>
                                            <div class="">
                                                <h4 class="mb-1 fw-semibold d-flex align-content-center">78.4%<i
                                                        class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                                </h4>
                                                <p class="mb-3">Growth Rate</p>
                                                <div class="progress mb-0" style="height:5px;">
                                                    <div class="progress-bar bg-grd-danger" role="progressbar"
                                                        style="width: 60%" aria-valuenow="25" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>

                                @can('PermissionChecker', 'view_dashboard')
                                    <div class="col-12 col-sm-5">
                                        <div class="welcome-back-img pt-4">
                                            <img src="assets/images/gallery/welcome-back-3.png" height="180" alt="">
                                        </div>
                                    </div>
                                @endcan
                            </div><!--end row-->
                        </div>
                    </div>
                </div>


                <div class="col-xxl-4">
                    <div class="row">
                        @can('PermissionChecker', 'view_customer')
                            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                                <div class="card bg-white sale-widget flex-fill">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="ms-2">
                                            <p class="text-dark mb-1">Total Customers</p>
                                            <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                                <h4 class="text-dark">{{ $customersCount ?? '0' }}</h4>
                                                <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('PermissionChecker', 'view_customer')
                            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                                <div class="card bg-white sale-widget flex-fill">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="ms-2">
                                            <p class="text-dark mb-1">Total Customer Orders</p>
                                            <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                                <h4 class="text-dark">{{ $salesOrdersCount ?? '0' }}</h4>
                                                <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('PermissionChecker', 'view_vendor')
                            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                                <div class="card bg-white sale-widget flex-fill">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="ms-2">
                                            <p class="text-dark mb-1">Total Vendors</p>
                                            <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                                <h4 class="text-dark">{{ $vendorsCount ?? '0' }}</h4>
                                                <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('PermissionChecker', 'view_vendor')
                            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                                <div class="card bg-white sale-widget flex-fill">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="ms-2">
                                            <p class="text-dark mb-1">Total Vendor Orders</p>
                                            <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                                <h4 class="text-dark">{{ $purchaseOrdersCount ?? '0' }}</h4>
                                                <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>

                </div>

                <div class="col-xl-6 col-xxl-8 d-flex align-items-stretch">
                    <div class="card w-100 rounded-4">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Monthly Revenue</h6>
                            </div>
                            <div class="mt-4" id="chart5"></div>
                            <p>Average monthly sale</p>
                            <div class="d-flex align-items-center gap-3 mt-4">
                                <div class="">
                                    <h1 class="mb-0 text-primary">68.9%</h1>
                                </div>
                                <div class="d-flex align-items-center align-self-end">
                                    <p class="mb-0 text-success">34.5%</p>
                                    <span class="material-icons-outlined text-success">expand_less</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4">
                    <div class="row">
                        <div class="col-xl-6 col-sm-6 col-12 d-flex">
                            <div class="card bg-white sale-widget flex-fill">
                                <div class="card-body d-flex align-items-center">
                                    <div class="ms-2">
                                        <p class="text-dark mb-1">Total Products</p>
                                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                            <h4 class="text-dark">{{ $productsCount ?? '0' }}</h4>
                                            <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12 d-flex">
                            <div class="card bg-white sale-widget flex-fill">
                                <div class="card-body d-flex align-items-center">
                                    <div class="ms-2">
                                        <p class="text-dark mb-1">Total Warehouses</p>
                                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                            <h4 class="text-dark">{{ $warehouseCount ?? '0' }}</h4>
                                            <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12 d-flex">
                            <div class="card bg-white sale-widget flex-fill">
                                <div class="card-body d-flex align-items-center">
                                    <div class="ms-2">
                                        <p class="text-dark mb-1">Total Invoices</p>
                                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                            <h4 class="text-dark">0</h4>
                                            <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12 d-flex">
                            <div class="card bg-white sale-widget flex-fill">
                                <div class="card-body d-flex align-items-center">
                                    <div class="ms-2">
                                        <p class="text-dark mb-1">Packaging List</p>
                                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                            <h4 class="text-dark">{{ $readyToPackageOrdersCount ?? '0' }}</h4>
                                            <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 col-12 d-flex">
                            <div class="card bg-white sale-widget flex-fill">
                                <div class="card-body d-flex align-items-center">
                                    <div class="ms-2">
                                        <p class="text-dark mb-1">Ready To Ship</p>
                                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                            <h4 class="text-dark">{{ $readyToShipOrdersCount ?? '0' }}</h4>
                                            <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @can('PermissionChecker', 'view_vendor')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Recent Vendor Orders</h5>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Vendor Code</th>
                                                <th>Order Status</th>
                                                <th>Ordered Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($purchaseOrders as $order)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox">
                                                    </td>
                                                    <td>{{ 'ORDER-' . $order->id }}</td>
                                                    <td>
                                                        <p class="mb-0 customer-name fw-bold">
                                                            @forelse($vendorCodes as $vendor)
                                                                {{ $vendor . ',' }}
                                                            @empty
                                                                {{ 'NA' }}
                                                            @endforelse
                                                        </p>
                                                    </td>
                                                    <td>
                                                        {{ $statuses[$order->status] ?? 'On Hold' }}
                                                    </td>
                                                    <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            @can('PermissionChecker', 'view_purchase_order_detail')
                                                                <a aria-label="anchor"
                                                                    href="{{ route('purchase.order.view', $order->id) }}"
                                                                    class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                                    data-bs-toggle="tooltip" data-bs-original-title="View">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-eye text-primary">
                                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                                        </path>
                                                                        <circle cx="12" cy="12" r="3"></circle>
                                                                    </svg>
                                                                </a>
                                                            @endcan

                                                            {{-- @can('PermissionChecker', 'update_purchase_order')
                                                        <a aria-label="anchor" href="#"
                                                            class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-edit text-warning">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                </path>
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @endcan --}}

                                                            @can('PermissionChecker', 'delete_purchase_order')
                                                                <form action="{{ route('purchase.order.delete', $order->id) }}"
                                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-icon btn-sm bg-danger-subtle delete-row">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="feather feather-trash-2 text-danger">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path
                                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                            </path>
                                                                            <line x1="10" y1="11" x2="10"
                                                                                y2="17"></line>
                                                                            <line x1="14" y1="11" x2="14"
                                                                                y2="17"></line>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">No Records Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_customer')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Recent Customer Orders</h5>

                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Customer Group Name</th>
                                                <th>Order Status</th>
                                                <th>Ordered Date</th>
                                                {{-- <th>Warehouse</th> --}}
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $statuses = [
                                                    'pending' => 'Pending',
                                                    'blocked' => 'Blocked',
                                                    'completed' => 'Completed',
                                                    'ready_to_ship' => 'Ready To Ship',
                                                    'ready_to_package' => 'Ready To Package',
                                                ];
                                            @endphp
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox">
                                                    </td>
                                                    <td>{{ 'ORDER-' . $order->id }}</td>
                                                    <td>
                                                        <p class="mb-0 customer-name fw-bold">
                                                            {{ $order->customerGroup->name }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        {{ $statuses[$order->status] ?? 'On Hold' }}
                                                    </td>
                                                    <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            @can('PermissionChecker', 'view_sale-detail')
                                                                <a aria-label="anchor"
                                                                    href="{{ route('order.view', $order->id) }}"
                                                                    class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                                    data-bs-toggle="tooltip" data-bs-original-title="View">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-eye text-primary">
                                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                                        </path>
                                                                        <circle cx="12" cy="12" r="3"></circle>
                                                                    </svg>
                                                                </a>
                                                            @endcan

                                                            @can('PermissionChecker', 'update_sale')
                                                                <a aria-label="anchor" href="#"
                                                                    class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                                    data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-edit text-warning">
                                                                        <path
                                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                        </path>
                                                                        <path
                                                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                        </path>
                                                                    </svg>
                                                                </a>
                                                            @endcan
                                                            @can('PermissionChecker', 'delete_sale')
                                                                <form action="{{ route('order.delete', $order->id) }}"
                                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-icon btn-sm bg-danger-subtle delete-row">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="feather feather-trash-2 text-danger">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path
                                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                            </path>
                                                                            <line x1="10" y1="11" x2="10"
                                                                                y2="17"></line>
                                                                            <line x1="14" y1="11" x2="14"
                                                                                y2="17"></line>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_packaging_list')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Packaging List</h5>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Group Name</th>
                                                <th>Ordered Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $statuses = [
                                                    'pending' => 'Pending',
                                                    'blocked' => 'Blocked',
                                                    'completed' => 'Completed',
                                                    'ready_to_ship' => 'Ready To Ship',
                                                    'ready_to_package' => 'Ready To Package',
                                                ];
                                            @endphp
                                            @forelse ($packagingOrders as $order)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox">
                                                    </td>
                                                    <td>{{ 'ORDER-' . $order->id }}</td>
                                                    <td>
                                                        <p class="mb-0 customer-name fw-bold">
                                                            {{ $order->customerGroup->name }}
                                                        </p>
                                                    </td>
                                                    <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                                    <td>
                                                        {{ $statuses[$order->status] ?? 'On Hold' }}
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor"
                                                            href="{{ route('packing.products.view', $order->id) }}"
                                                            class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                            data-bs-toggle="tooltip" data-bs-original-title="View">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-eye text-primary">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No Orders Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_ready_to_ship')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Ready To Ship</h5>

                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Customer Group Name</th>
                                                <th>Ordered Date</th>
                                                <th>Delivery Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $statuses = [
                                                    'pending' => 'Pending',
                                                    'blocked' => 'Blocked',
                                                    'completed' => 'Completed',
                                                    'ready_to_ship' => 'Ready To Ship',
                                                    'ready_to_package' => 'Ready To Package',
                                                ];
                                            @endphp
                                            @forelse ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox">
                                                    </td>
                                                    <td>{{ 'ORDER-' . $order->id }}</td>
                                                    <td>
                                                        <p class="mb-0 customer-name fw-bold">
                                                            {{ $order->customerGroup->name }}
                                                        </p>
                                                    </td>
                                                    <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                                    <td>NA</td>
                                                    <td>
                                                        {{ $statuses[$order->status] ?? 'On Hold' }}
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor"
                                                            href="{{ route('readyToShip.view', $order->id) }}"
                                                            class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                            data-bs-toggle="tooltip" data-bs-original-title="View">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-eye text-primary">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No Records Found</td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_invoice')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Invoices</h5>

                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order&nbsp;Id</th>
                                                <th>Invoice&nbsp;No</th>
                                                <th>Customer&nbsp;Name</th>
                                                <th>Due&nbsp;Date</th>
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
                                                    <td>#{{ $invoice->sales_order_id }}</td>
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
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-eye text-primary">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_payment')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Payments</h5>

                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Customer Name</th>
                                                <th>Ordered Date</th>
                                                <th>Delivery Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#001</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">ABC</p>

                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-success">Completed</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#002</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">XYZ</p>
                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-primary">Delivered</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#003</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">EFG</p>
                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-Secondary">Out For Delivery</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_appointment')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">Appointments</h5>

                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Customer Name</th>
                                                <th>Ordered Date</th>
                                                <th>Delivery Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#001</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">ABC</p>

                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-success">Completed</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#002</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">XYZ</p>
                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-primary">Delivered</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#003</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">EFG</p>
                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-Secondary">Out For Delivery</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('PermissionChecker', 'view_grn')
                    <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
                        <div class="card w-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="">
                                        <h5 class="mb-0">GRNs</h5>

                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Order Id</th>
                                                <th>Customer Name</th>
                                                <th>Ordered Date</th>
                                                <th>Delivery Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#001</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">ABC</p>

                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-success">Completed</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#002</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">XYZ</p>
                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-primary">Delivered</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>#003</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">EFG</p>
                                                </td>
                                                <td>
                                                    2025-04-11
                                                </td>
                                                <td>
                                                    2025-05-15
                                                </td>
                                                <td class="text-Secondary">Out For Delivery</td>
                                                <td>
                                                    <a aria-label="anchor" href="ready-to-ship-detail.php"
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>


        </div>
    </main>
    <!--end main wrapper-->
@endsection

@section('script')
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <!--plugins-->
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/metismenu/metisMenu.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/peity/jquery.peity.min.js"></script>
    <script>
        $(".data-attributes span").peity("donut")
    </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/dashboard1.js"></script>
    <script>
        new PerfectScrollbar(".user-list")
    </script>
@endsection
