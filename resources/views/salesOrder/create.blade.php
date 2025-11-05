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
                                                Create New Customer Order
                                            </h5>
                                        </div>
                                        <div>
                                            <a type="button" class="btn btn-icon btn-sm bg-primary me-1 text-white"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                Check Availibility
                                            </a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('check.sales.order.stock') }}" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Check Availibility Of Products</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="col-12 mb-3">
                                                                    <label for="customerGroup" class="form-label">Select
                                                                        Customer Group
                                                                        <span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="customer_group_id"
                                                                        id="customerGroup">
                                                                        <option selected="" disabled=""
                                                                            value="">-- Select --
                                                                        </option>
                                                                        @foreach ($customerGroup as $group)
                                                                            <option value="{{ $group->id }}">
                                                                                {{ $group->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label for="warehouseName" class="form-label">Warehouse
                                                                        Name
                                                                        <span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="warehouse_id"
                                                                        id="warehouseName">
                                                                        <option selected="" disabled=""
                                                                            value="">-- Select --
                                                                        </option>
                                                                        <option value="auto" class="text-primary fw-bold">🔄 Auto Allocate (All Warehouses)</option>
                                                                        @foreach ($warehouses as $warehouse)
                                                                            <option value="{{ $warehouse->id }}">
                                                                                {{ $warehouse->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-muted d-block mt-1">Select "Auto Allocate" to check stock across all warehouses</small>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label for="document_image" class="form-label">Customer
                                                                        PO (CSV/XLSX) <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="file" name="csv_file" id="csv_file"
                                                                        class="form-control" value="" required=""
                                                                        placeholder="Upload ID Document" multiple>
                                                                </div>
                                                                {{-- <div class="col-12 mb-3">
                                                                    <button class="btn btn-primary"
                                                                        id="upload-excel">Submit</button>
                                                                </div> --}}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" id="holdOrder"
                                                                    class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="card-body">
                                        <form action="{{ route('sales.order.store') }}" method="POST" onsubmit="return confirm('Have You Checked Availibility?')"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="row g-3 align-items-end">
                                                <div class="col-12 col-lg-3">
                                                    <label for="customerGroup" class="form-label">Select Customer Group
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="customer_group_id"
                                                        id="customerGroup">
                                                        <option selected="" disabled="" value="">-- Select --
                                                        </option>
                                                        @foreach ($customerGroup as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name }}
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
                                                        <option value="auto" class="text-primary fw-bold">🔄 Auto Allocate (All Warehouses)</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">Select "Auto Allocate" to distribute stock from multiple warehouses automatically</small>
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <label for="document_image" class="form-label">Customer PO (CSV/XLSX)
                                                        <span class="text-danger">*</span></label>
                                                    <input type="file" name="csv_file" id="csv_file"
                                                        class="form-control" value="" required="">
                                                </div>
                                                <div class="col-12 col-lg-1">
                                                    <button class="btn btn-primary" id="upload-excel">Submit</button>
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
