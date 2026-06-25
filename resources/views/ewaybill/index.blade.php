@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">E-Way Bill List</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="sales-order" role="tabpanel" aria-labelledby="sales-order-tab">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example" class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;"><input class="form-check-input" type="checkbox" id="selectAllSales"></th>
                                            <th>Sales&nbsp;Order&nbsp;ID</th>
                                            <th>Client&nbsp;Name</th>
                                            <th>Invoice&nbsp;No</th>
                                            <th>E-Way&nbsp;Bill&nbsp;No</th>
                                            <th>Invoice&nbsp;PDF</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($eWayBill as $ewaybill)
                                            <tr>
                                                <td><input class="form-check-input" type="checkbox"></td>
                                                <td>{{ $ewaybill->invoice->salesOrder->order_number }}</td>
                                                <td>{{ $ewaybill->invoice->customer->client_name ?? 'N/A' }}</td>
                                                <td>{{ $ewaybill->invoice->invoice_number ?? 'N/A' }}</td>
                                                <td>{{ $ewaybill->ewb_no ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($ewaybill->ewaybill_status === 'ACT')
                                                        <a href="{{ $ewaybill->ewaybill_pdf }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-sm bg-primary-subtle me-1">Download</a>
                                                    @else
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ewaybill->ewaybill_status)
                                                        <span class="badge {{ $ewaybill->ewaybill_status == 'ACT' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ ucfirst($ewaybill->ewaybill_status) == 'ACT' ? 'Active' : 'Cancelled' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">No sales order invoices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
