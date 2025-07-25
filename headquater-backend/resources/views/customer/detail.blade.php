@extends('layouts.master')

@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><b>Customers Group:</b>
                               {{ $salesOrder->customerGroup }} </li>
                        </ol>
                    </nav>
                </div>
            </div>


            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped cell-border">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Client Name</th>
                                        <th>Contact Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Billing City</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>
                                                <a class="d-flex align-items-center gap-3" href="#">
                                                    <p class="mb-0 customer-name fw-bold">{{ $customer->client_name }}</p>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;" class="font-text1">{{ $customer->contact_name }}</a>
                                            </td>
                                            <td>{{ $customer->contact_email }}</td>

                                            <td>{{ $customer->contact_phone }}</td>
                                            <td>{{ $customer->billing_city }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor"
                                                        {{-- href="{{ route('single-vendor-order-view', ['orderId' => $order->id, 'vendorCode' => $vendor->vendor_code]) }}" --}}
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

                                                    <a aria-label="anchor"
                                                        class="btn btn-icon btn-sm bg-danger-subtle delete-row"
                                                        data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
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
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No Record Found
                                            </td>
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
@endsection
