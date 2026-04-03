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
                                        <th>Sales Order&nbsp;Id</th>
                                        <th>Batch&nbsp;Id</th>
                                        <th>Customer&nbsp;Group&nbsp;Name</th>
                                        <th>Facility&nbsp;Name</th>
                                        <th>Client&nbsp;Name</th>
                                        <th>Contact&nbsp;Name</th>
                                        <th>Date</th>
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
                                            'approval_pending' => 'Ready to Ship Approval Pending',
                                            'packaging' => 'Packaging',
                                            'packaged' => 'Packaged',
                                            'dispatched' => 'Dispatched',
                                            'partially_packaged' => 'Partially Packaged',
                                            'approved' => 'Approved',
                                            'cancelled' => 'Cancelled',
                                            'ready_to_ship' => 'Ready To Ship',
                                            'ready_to_package' => 'Ready To Package',
                                            'shipped' => 'Shipped',
                                            'delivered' => 'Delivered',
                                            'cancelled' => 'Cancelled',
                                        ];
                                    @endphp
                                    @forelse ($warehouseAllocations as $allocation)
                                        @if ($allocation->salesOrder->id && $allocation->customer->id)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>{{ $allocation->salesOrder->order_number }}</td>
                                                <td>{{ $allocation->rts_count_id }}</td>
                                                <td>
                                                    <p class="mb-0 customer-name fw-bold">
                                                        {{ $allocation->salesOrder?->customerGroup?->name }}
                                                    </p>
                                                </td>
                                                <td>
                                                    {{ $allocation?->customer?->facility_name }}
                                                </td>
                                                <td>{{ $allocation?->customer?->client_name }}</td>
                                                <td>
                                                    {{ $allocation?->customer?->contact_name }}
                                                </td>
                                                <td>
                                                    {{ $allocation->approved_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    {{ $statuses[$allocation->salesOrder->status] }}
                                                </td>
                                                <td>
                                                    <a aria-label="anchor"
                                                        href="{{ route('readyToShip.view.detail', ['id' => $allocation->salesOrder->id, 'c_id' => $allocation->customer->id, 'rts_count_id' => $allocation->rts_count_id]) }}"
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
                                        @endif
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
