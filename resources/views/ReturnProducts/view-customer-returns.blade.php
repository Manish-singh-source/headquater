@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            // 'accept' => 'Accept',
            // 'returned' => 'Returned',
            'completed' => 'Completed',
        ];
    @endphp

    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Return Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">

                                <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                    <form id="statusForm" action="{{ route('change.customer.return.status') }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="order_id" value="{{ $customerReturn->id }}">
                                        <select class="form-select border-2 border-primary" id="changeStatus"
                                            aria-label="Default select example" name="status">
                                            <option value="" disabled>Change Status</option>
                                            <option value="pending" @if ($customerReturn->return_status == 'pending') selected @endif
                                                @if (in_array($customerReturn->return_status, ['completed'])) disabled @endif>Pending</option>
                                            {{-- 
                                            <option value="accept"
                                                {{ $customerReturn->return_status == 'accept' ? 'selected' : '' }}>
                                                Accept</option>
                                            <option value="returned"
                                                {{ $customerReturn->return_status == 'returned' ? 'selected' : '' }}>
                                                Returned</option> 
                                            --}}
                                            <option value="completed"
                                                {{ $customerReturn->return_status == 'completed' ? 'selected' : '' }}
                                                @if ($customerReturn->return_status == 'completed') disabled @endif>
                                                Completed</option>
                                        </select>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Sales Order Id</b></span>
                                    <span>{{ $customerReturn->salesOrder->id ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Brand Title</b></span>
                                    <span>{{ $customerReturn->product->brand_title ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>SKU Code</b></span>
                                    <span>{{ $customerReturn->product->sku ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>MRP</b></span>
                                    <span>{{ $customerReturn->product->mrp ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>GST</b></span>
                                    <span>{{ $customerReturn->product->gst ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>HSN</b></span>
                                    <span>{{ $customerReturn->product->hsn ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Return Quantity</b></span>
                                    <span>{{ $customerReturn->return_quantity ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Return Reason</b></span>
                                    <span>{{ $customerReturn->return_reason ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Return Description</b></span>
                                    <span>{{ $customerReturn->return_description ?? 'NA' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Return Status</b></span>
                                    <span>{{ ucfirst($customerReturn->return_status) ?? 'NA' }}</span>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#changeStatus').change(function() {
                $('#statusForm').submit();
            });
        });
    </script>
@endsection
