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
                            <li class="breadcrumb-item active" aria-current="page">Ready to Ship Products Lists</li>
                        </ol>
                    </nav>
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
                                        <th>Order&nbsp;Id</th>
                                        <th>Customer&nbsp;Group&nbsp;Name</th>
                                        <th>Client&nbsp;Name</th>
                                        <th>Contact&nbsp;Name</th>
                                        <th>Products</th>
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
                                    @forelse ($customerInfo as $customerOrders)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>{{ $order->id }}</td>
                                            <td>
                                                <p class="mb-0 customer-name fw-bold">
                                                    {{ $customerOrders->groupInfo->customerGroup->name }}
                                                </p>
                                            </td>
                                            <td>{{ $customerOrders->client_name }}</td>
                                            <td>
                                                {{ $customerOrders->contact_name }}
                                            </td>
                                            <td>
                                                {{ $customerOrders->orders_count }}
                                            </td>
                                            <td>
                                                {{ $customerOrders->orders_count }}
                                            </td>
                                            <td>
                                                <a aria-label="anchor"
                                                    href="{{ route('readyToShip.view.detail', ['id' => $order->id, 'c_id' => $customerOrders->id]) }}"
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
                                            <td colspan="7" class="text-center">No Records Found</td>
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
