@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card customer-inputs">
                                <div class="card-header border-bottom-dashed">
                                    <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                Create New Customer Product Return
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="card-body">
                                        <form action="{{ route('customer.returns.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')

                                            <div class="row g-3 align-items-end">
                                                <div class="col-12 col-lg-3">
                                                    <label for="salesOrder" class="form-label">Select Sales Order
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="sales_order_id" id="salesOrder">
                                                        <option selected="" disabled="" value="">-- Select --
                                                        </option>
                                                        @foreach ($salesOrders as $salesOrder)
                                                            <option value="{{ $salesOrder->id }}">{{ $salesOrder->id }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <label for="warehouseName" class="form-label">Warehouse Name
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="warehouse_id" id="warehouseName">
                                                        <option selected="" disabled="" value="">-- Select --
                                                        </option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <label for="productsExcel" class="form-label">Products List (CSV/XLSX)
                                                        <span class="text-danger">*</span></label>
                                                    <input type="file" name="excel_file" id="productsExcel"
                                                        class="form-control" value="" required="">
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <button class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
