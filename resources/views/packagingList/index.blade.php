@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            'approve' => 'Sent For Approval',
            'reject' => 'Rejected',
            'completed' => 'Completed',
        ];
    @endphp

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Packaging List:</li>
                        </ol>
                    </nav>
                </div>
            </div>
            @include('layouts.errors')

            <div class="card mt-4">
                <div class="card-body">
                    
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
                                href="{{ route('packaging.list.index', ['status' => 'all']) }}">
                                All Orders
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'ready_to_package' ? 'active' : '' }}"
                                href="{{ route('packaging.list.index', ['status' => 'ready_to_package']) }}">
                                Ready To Package
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'partial_packaged' ? 'active' : '' }}"
                                href="{{ route('packaging.list.index', ['status' => 'partial_packaged']) }}">
                                Partial Packaged
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'all_packaged' ? 'active' : '' }}"
                                href="{{ route('packaging.list.index', ['status' => 'all_packaged']) }}">
                                All Packaged
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'ready_to_ship' ? 'active' : '' }}"
                                href="{{ route('packaging.list.index', ['status' => 'ready_to_ship']) }}">
                                Ready To Ship
                            </a>
                        </li>
                    </ul> 
                   

                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Sales Order Id</th>
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
                                            'packaging' => 'Packaging',
                                            'partial_packaged' => 'Partial Packaged',
                                            'all_packaged' => 'All Packaged',
                                            'packaged' => 'Packaged',
                                            'shipped' => 'Shipped',
                                            'approval_pending' => 'Approval Pending',
                                            'ready_to_ship' => 'Ready To Ship',
                                            'partially_shipped' => 'Partially Shipped',
                                            'ready_to_package' => 'Ready To Package',
                                        ];
                                    @endphp
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>{{ $order->order_number }}</td>
                                            <td>
                                                <p class="mb-0 customer-name fw-bold">
                                                    {{ $order->customerGroup->name }}
                                                </p>
                                            </td>
                                            <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                {{ $statuses[$order->status] ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <a aria-label="anchor"
                                                    href="{{ route('packing.products.view', $order->id) }}"
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
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No Records Found</td>
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