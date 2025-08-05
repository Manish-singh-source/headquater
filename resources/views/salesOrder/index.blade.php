@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Order List</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-3 justify-content-end">
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button>
                        @can('PermissionChecker', 'create_sale')
                            <a href="{{ route('order.create') }}"><button class="btn btn-primary px-4"><i
                                        class="bi bi-plus-lg me-2"></i>New Order</button></a>
                        @endcan

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
                                                        <a aria-label="anchor" href="{{ route('order.view', $order->id) }}"
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
                                                    @endcan

                                                    @can('PermissionChecker', 'update_sale')
                                                        <a aria-label="anchor" href="{{ route('order.edit', $order->id) }}"
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
                                                    @endcan
                                                    @can('PermissionChecker', 'delete_sale')
                                                        <form action="{{ route('order.delete', $order->id) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure?')">
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
        </div>
    </main>
@endsection
