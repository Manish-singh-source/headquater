@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            'blocked' => 'Blocked',
            'shipped' => 'Shipped',
            'completed' => 'Complete',
            'ready_to_ship' => 'Ready To Ship',
            'ready_to_package' => 'Ready To Package',
        ];
    @endphp
    <main class="main-wrapper">
        <div class="main-content">
            <!-- Breadcrumb Navigation -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Sales SKU Level</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bx bx-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Total Records  --}}
            <div class="row">
                {{-- Total Invoices --}}
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-file fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Invoices</p>
                                    <h4 class="mb-0 fw-bold">{{ $totalInvoices }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Customer --}}
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-user fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Customers</p>
                                    <h4 class="mb-0 fw-bold">{{ $totalCustomers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Taxable Amount --}}
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-dollar-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Taxable Amount</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalTaxableAmount, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Invoice Amount --}}
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-dollar-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Invoice Amount</p> 
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalInvoiceAmount, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Purchase Order --}}
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-package fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Purchase Order</p>
                                    <h4 class="mb-0 fw-bold">{{ $totalPurchaseOrder }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Purchase Order Amount --}}
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-dollar-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Purchase Order Amount</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalPurchaseOrderAmount, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold"><i class="bx bx-filter-alt me-2"></i>Filter Options</h6>
                    <form method="GET" action="{{ route('customer-sales-sku') }}" id="filterForm">
                        <div class="row align-items-start">
                            <div class="col-lg-10">
                                <div class="row">
                                    <!-- From Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date"
                                                value="{{ $filters['from_date'] ?? '' }}" placeholder="Select from date">
                                        </div>
                                    </div>

                                    <!-- To Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date"
                                                value="{{ $filters['to_date'] ?? '' }}" placeholder="Select to date">
                                        </div>
                                    </div>

                                    <!-- Customer Name Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Name</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="customerDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="customerDropdownText">
                                                        @if (is_array($filters['customer_id'] ?? null) && count($filters['customer_id']) > 0)
                                                            {{ count($filters['customer_id']) }} selected
                                                        @else
                                                            Select Customer
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="customerCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($customers as $customer)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input customer-checkbox"
                                                                    type="checkbox" name="customer_id[]"
                                                                    value="{{ $customer['id'] }}"
                                                                    id="customer_{{ $customer['id'] }}"
                                                                    {{ in_array($customer['id'], (array) ($filters['customer_id'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="customer_{{ $customer['id'] }}">
                                                                    {{ $customer['name'] }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Warehouse Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Warehouse</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="warehouseDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="warehouseDropdownText">
                                                        @if (is_array($filters['warehouse_id'] ?? null) && count($filters['warehouse_id']) > 0)
                                                            {{ count($filters['warehouse_id']) }} selected
                                                        @else
                                                            Select Warehouse
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="warehouseCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($warehouses as $warehouse)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input warehouse-checkbox"
                                                                    type="checkbox" name="warehouse_id[]"
                                                                    value="{{ $warehouse->id }}"
                                                                    id="warehouse_{{ $warehouse->id }}"
                                                                    {{ in_array($warehouse->id, (array) ($filters['warehouse_id'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="warehouse_{{ $warehouse->id }}">
                                                                    {{ $warehouse->name }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Region Filter -->
                                    {{-- 
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Region</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="regionDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="regionDropdownText">
                                                        @if (is_array($filters['region'] ?? null) && count($filters['region']) > 0)
                                                            {{ count($filters['region']) }} selected
                                                        @else
                                                            Select Region
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="regionCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($regions as $region)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input region-checkbox"
                                                                    type="checkbox" name="region[]"
                                                                    value="{{ $region }}"
                                                                    id="region_{{ $loop->index }}"
                                                                    {{ in_array($region, (array) ($filters['region'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="region_{{ $loop->index }}">
                                                                    {{ $region }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div> 
                                    --}}

                                    <!-- Payment Status Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Status</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="paymentStatusDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="paymentStatusDropdownText">
                                                        @if (is_array($filters['payment_status'] ?? null) && count($filters['payment_status']) > 0)
                                                            {{ count($filters['payment_status']) }} selected
                                                        @else
                                                            Select Status
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="paymentStatusCheckboxList">
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="checkbox" name="payment_status[]" value="paid"
                                                                id="payment_status_paid"
                                                                {{ in_array('paid', (array) ($filters['payment_status'] ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_paid">
                                                                Paid
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="checkbox" name="payment_status[]" value="partial"
                                                                id="payment_status_partial"
                                                                {{ in_array('partial', (array) ($filters['payment_status'] ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_partial">
                                                                Partial
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="checkbox" name="payment_status[]" value="unpaid"
                                                                id="payment_status_unpaid"
                                                                {{ in_array('unpaid', (array) ($filters['payment_status'] ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_unpaid">
                                                                Unpaid
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Type Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Group</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="customerTypeDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="customerTypeDropdownText">
                                                        @if (is_array($filters['customer_type'] ?? null) && count($filters['customer_type']) > 0)
                                                            {{ count($filters['customer_type']) }} selected
                                                        @else
                                                            Select Group Name
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="customerTypeCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($customerGroups as $group)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input customer-type-checkbox"
                                                                    type="checkbox" name="customer_type[]"
                                                                    value="{{ $group->id }}"
                                                                    id="customer_type_{{ $group->id }}"
                                                                    {{ in_array($group->id, (array) ($filters['customer_type'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="customer_type_{{ $group->id }}">
                                                                    {{ $group->name }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Invoice No Filter -->
                                    {{-- <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Invoice No</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="invoiceNoDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="invoiceNoDropdownText">
                                                        @if (is_array($filters['invoice_no'] ?? null) && count($filters['invoice_no']) > 0)
                                                            {{ count($filters['invoice_no']) }} selected
                                                        @else
                                                            Select Invoice
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="invoiceNoCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($invoiceNumbers as $invoiceNo)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input invoice-no-checkbox"
                                                                    type="checkbox" name="invoice_no[]"
                                                                    value="{{ $invoiceNo }}"
                                                                    id="invoice_no_{{ $loop->index }}"
                                                                    {{ in_array($invoiceNo, (array) ($filters['invoice_no'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="invoice_no_{{ $loop->index }}">
                                                                    {{ $invoiceNo }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <!-- PO No Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">PO No</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="poNoDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="poNoDropdownText">
                                                        @if (is_array($filters['po_no'] ?? null) && count($filters['po_no']) > 0)
                                                            {{ count($filters['po_no']) }} selected
                                                        @else
                                                            Select PO
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="poNoCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($poNumbers as $poNo)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input po-no-checkbox"
                                                                    type="checkbox" name="po_no[]"
                                                                    value="{{ $poNo }}"
                                                                    id="po_no_{{ $loop->index }}"
                                                                    {{ in_array($poNo, (array) ($filters['po_no'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="po_no_{{ $loop->index }}">
                                                                    {{ $poNo }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Buttons Column -->
                            <div class="col-lg-2">
                                <div class="row">
                                    <!-- Apply Filter Button -->
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            {{-- <label class="form-label">&nbsp;</label> --}}
                                            <button type="submit" id="filterData" class="btn btn-primary w-100">
                                                <i class="bx bx-filter-alt me-1"></i>Apply Filter
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Reset Filter Button -->
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            {{-- <label class="form-label">&nbsp;</label> --}}
                                            <button type="button" id="resetFilters" class="btn btn-secondary w-100">
                                                <i class="bx bx-reset me-1"></i>Reset Filters
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            {{-- <label class="form-label">&nbsp;</label> --}}
                                            <div class="d-flex gap-2">
                                                <!-- Generate Excel Report Button -->
                                                <button type="button" id="generateExcelReport"
                                                    class="btn btn-success flex-fill">
                                                    <i class="bx bx-download me-1"></i>Export Excel
                                                </button>
                                                <!-- Generate PDF Report Button -->
                                                {{-- <button type="button" id="generatePdfReport" class="btn btn-danger flex-fill">
                                                    <i class="bx bx-file-pdf me-1"></i>Export PDF
                                                </button> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer Sales Summary Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Sales SKU Level</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customerSalesTable" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sales&nbsp;Order&nbsp;No</th>
                                    <th>Customer&nbsp;Group&nbsp;Name</th>
                                    <th>Warehouse&nbsp;Name</th>
                                    <th>Customer&nbsp;Name</th>
                                    <th>Invoice&nbsp;No</th>
                                    <th>Customer&nbsp;Phone&nbsp;No</th>
                                    <th>Customer&nbsp;Email</th>
                                    <th>Customer&nbsp;City</th>
                                    <th>Customer&nbsp;State</th>

                                    <th>PO&nbsp;No</th>
                                    <th>PO&nbsp;SKU</th>
                                    <th>Title</th>
                                    <th>Brand</th>
                                    <th>HSN</th>
                                    <th>Ordered&nbsp;Quantity</th>
                                    <th>Dispatched&nbsp;Quantity</th>
                                    <th>Box&nbsp;Count</th>
                                    <th>Weight</th>
                                    <th>Unit&nbsp;Price</th>
                                    <th>Taxable&nbsp;Amount</th>
                                    <th>GST</th>
                                    <th>GST&nbsp;Amount</th>
                                    <th>Invoice&nbsp;Amount</th>
                                    <th>Purchase&nbsp;Order&nbsp;No</th>
                                    <th>Purchase&nbsp;Rate</th>
                                    <th>Subtotal</th>
                                    <th>GST</th>
                                    <th>GST&nbsp;Amount</th>
                                    <th>Total&nbsp;Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $salesOrder)
                                    @foreach ($salesOrder->orderedProducts as $product)
                                        @if ($product->warehouseAllocations->count() > 0)
                                            @foreach ($product->warehouseAllocations as $allocation)
                                                <tr>
                                                    <td>{{ $salesOrder->order_number ?? 'N/A' }}</td>
                                                    <td>{{ $salesOrder->customerGroup->name ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->warehouse->name ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->client_name ?? 'N/A' }}</td>
                                                    <td>
                                                        @php
                                                            $invoiceNumber = 'N/A';
                                                            if ($salesOrder->invoices->count() > 0) {
                                                                foreach ($salesOrder->invoices as $invoice) {
                                                                    if (
                                                                        $invoice->warehouse_id ==
                                                                        $allocation->warehouse_id
                                                                    ) {
                                                                        $invoiceNumber =
                                                                            $invoice->invoice_number ?? 'N/A';
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $invoiceNumber }}
                                                    </td>
                                                    <td>{{ $product->customer->contact_no ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->email ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->shipping_city ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->shipping_state ?? 'N/A' }}</td>
                                                    <td>{{ $product->tempOrder->po_number ?? 'N/A' }}</td>
                                                    <td>{{ $product->tempOrder->sku }}</td>
                                                    <td>{{ $product->product->brand_title }}</td>
                                                    <td>{{ $product->product->brand }}</td>
                                                    <td>{{ $product->product->hsn }}</td>
                                                    <td>{{ $allocation->allocated_quantity ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->final_dispatched_quantity ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->box_count ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->weight ?? 'N/A' }}</td>
                                                    <td>{{ $product->price ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->final_dispatched_quantity * $product->price ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $product->tempOrder->gst ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->final_dispatched_quantity * $product->price * ($product->tempOrder->gst / 100) ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $allocation->final_dispatched_quantity * $product->price * (1 + $product->tempOrder->gst / 100) ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $product->purchase_ordered_quantity ?? 0 }}</td>
                                                    <td>{{ $product->vendorPIProduct?->purchase_rate ?? 0 }}</td>
                                                    <td>
                                                        {{ $subtotal = $product->purchase_ordered_quantity * ($product->vendorPIProduct?->purchase_rate ?? 0) }}
                                                    </td>
                                                    <td>{{ $product->vendorPIProduct?->gst ?? 0 }}</td>
                                                    <td>
                                                        {{ $gstAmount = $subtotal * (($product->vendorPIProduct?->gst ?? 0) / 100) }}
                                                    </td>
                                                    <td>
                                                        {{ $subtotal + $gstAmount }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="32" class="text-center text-muted py-4">
                                            <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                            No customer sales records found for the selected criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tbody class="d-none">
                                @forelse ($invoices as $salesOrder)
                                    @foreach ($salesOrder->orderedProducts as $product)
                                        @if ($product->warehouseAllocations->count() > 0)
                                            @foreach ($product->warehouseAllocations as $allocation)
                                                <tr>
                                                    <td>{{ $salesOrder->customerGroup->name ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->warehouse->name ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->client_name ?? 'N/A' }}</td>
                                                    {{-- <td>{{ $product->tempOrder->gst ?? 'N/A' }}</td> --}}
                                                    <td>
                                                        @php
                                                            $invoiceNumber = 'N/A';
                                                            if ($salesOrder->invoices->count() > 0) {
                                                                foreach ($salesOrder->invoices as $invoice) {
                                                                    if (
                                                                        $invoice->warehouse_id ==
                                                                        $allocation->warehouse_id
                                                                    ) {
                                                                        $invoiceNumber =
                                                                            $invoice->invoice_number ?? 'N/A';
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $invoiceNumber }}
                                                    </td>
                                                    {{-- <td>{{ $salesOrder->invoices->first()->invoice_number ??   'N/A' }}</td> --}}
                                                    {{-- <td>{{ $salesOrder->created_by ?? 'N/A' }}</td> --}}
                                                    <td>{{ $product->customer->contact_no ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->email ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->shipping_city ?? 'N/A' }}</td>
                                                    <td>{{ $product->customer->shipping_state ?? 'N/A' }}</td>
                                                    <td>{{ $product->tempOrder->po_number ?? 'N/A' }}</td>
                                                    <td>{{ $product->tempOrder->sku }}</td>
                                                    <td>{{ $product->product->brand_title }}</td>
                                                    <td>{{ $product->product->brand }}</td>

                                                    {{-- <td>{{ $product->tempOrder->po_date ?? 'N/A' }}</td> --}}

                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->appointment?->appointment_date->format('d-m-Y') ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->appointment?->appointment_date->addMonth()->format('d-m-Y') ?? 'N/A' }}
                                                    </td>

                                                    {{-- <td>{{ $salesOrder->due_date ?? 'N/A' }}</td>   --}}
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->appointment?->pod ? 'Yes' : 'No' }}
                                                    </td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->appointment?->grn ? 'Yes' : 'No' }}
                                                    </td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->dns?->dn_amount ?? 0 }}
                                                    </td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->dns?->dn_receipt ? 'Yes' : 'No' }}
                                                    </td>
                                                    <td>{{ $salesOrder->appointment?->lr ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $salesOrder->invoices->first()->currency ?? 'INR' }}</td>

                                                    <td>{{ $product->product->brand ?? 'N/A' }}</td>

                                                    <td>{{ $product->tempOrder->hsn ?? 'N/A' }}</td>
                                                    <td>{{ $product->ordered_quantity ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->final_dispatched_quantity ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->box_count ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->weight ?? 'N/A' }}</td>
                                                    <td>{{ $product->price ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->final_dispatched_quantity * $product->price ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $product->tempOrder->gst ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->final_dispatched_quantity * $product->price * (1 + $product->tempOrder->gst / 100) ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $statuses[$salesOrder->status] ?? 'N/A' }}</td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->total_amount ?? 'N/A' }}
                                                        {{-- @if ($loop->first) --}}
                                                        {{-- <td rowspan="{{ $product->warehouseAllocations->count() }}"> --}}
                                                    <td>
                                                        {{ $product->invoiceDetails->first()?->invoice?->paid_amount ?? 'N/A' }}
                                                    </td>
                                                    {{-- @endif --}}
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->balance_due ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->payments?->first()?->created_at->format('d-m-Y') ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice?->payments?->first()?->payment_method ?? 'N/A' }}
                                                    </td>

                                                    <td>{{ $product->tempOrder->gst / 2 ?? 'N/A' }}</td>
                                                    <td>{{ $product->tempOrder->gst / 2 ?? 'N/A' }}</td>
                                                    <td>{{ $product->tempOrder->gst ?? 'N/A' }}</td>
                                                    <td>{{ $product->invoiceDetails->first()?->invoice->cess ?? 'N/A' }}
                                                    </td>
                                                    {{-- 
                                                    <td>
                                                        <a href=""
                                                            class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                            data-bs-toggle="tooltip" title="View Sales Order">
                                                            <i class="bx bx-show text-primary"></i>
                                                        </a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="32" class="text-center text-muted py-4">
                                            <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                            No customer sales records found for the selected criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            /**
             * Prevent dropdown from closing when clicking on checkboxes
             */
            $(document).on('click', '.dropdown-menu', function(e) {
                e.stopPropagation();
            });

            /**
             * Add cursor pointer styling to checkbox labels
             */
            $('.form-check-label').css('cursor', 'pointer');

            // Initialize DataTable for customer sales table with custom sorting
            // Initialize DataTable for customer sales table with custom sorting
            var inventoryStockTable = $('#customerSalesTable').DataTable({
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0] // Disable sorting for checkbox column
                }],
                lengthChange: true,
                pageLength: 10,
                order: [
                    [10, 'desc']
                ], // Sort by Date column (index 14) in descending order
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            /**
             * Helper function to update dropdown text
             */
            function updateDropdownText(checkboxClass, dropdownId, spanId, defaultText) {
                var checkedBoxes = $(checkboxClass + ':checked');
                var count = checkedBoxes.length;
                var text = count > 0 ? count + ' selected' : defaultText;
                $('#' + spanId).text(text);
            }

            /**
             * Update dropdown button text when checkboxes change
             */
            $(document).on('change', '.customer-checkbox', function() {
                updateDropdownText('.customer-checkbox', 'customerDropdown', 'customerDropdownText',
                    'Select Customer');
            });

            $(document).on('change', '.warehouse-checkbox', function() {
                updateDropdownText('.warehouse-checkbox', 'warehouseDropdown', 'warehouseDropdownText',
                    'Select Warehouse');
            });

            // $(document).on('change', '.region-checkbox', function() {
            //     updateDropdownText('.region-checkbox', 'regionDropdown', 'regionDropdownText',
            //         'Select Region');
            // });

            $(document).on('change', '.payment-status-checkbox', function() {
                updateDropdownText('.payment-status-checkbox', 'paymentStatusDropdown',
                    'paymentStatusDropdownText', 'Select Status');
            });

            $(document).on('change', '.customer-type-checkbox', function() {
                updateDropdownText('.customer-type-checkbox', 'customerTypeDropdown',
                    'customerTypeDropdownText', 'Select Type');
            });

            $(document).on('change', '.invoice-no-checkbox', function() {
                updateDropdownText('.invoice-no-checkbox', 'invoiceNoDropdown', 'invoiceNoDropdownText',
                    'Select Invoice');
            });

            $(document).on('change', '.po-no-checkbox', function() {
                updateDropdownText('.po-no-checkbox', 'poNoDropdown', 'poNoDropdownText', 'Select PO');
            });

            $(document).on('change', '.appointment-date-checkbox', function() {
                updateDropdownText('.appointment-date-checkbox', 'appointmentDateDropdown',
                    'appointmentDateDropdownText', 'Select Date');
            });

            /**
             * Reset Filter Button Click Handler
             */
            $(document).on('click', '#resetFilters', function(e) {
                e.preventDefault();

                // Clear all filter inputs
                $('#from_date').val('');
                $('#to_date').val('');
                $('.customer-checkbox').prop('checked', false);
                $('.warehouse-checkbox').prop('checked', false);
                // $('.region-checkbox').prop('checked', false);
                $('.payment-status-checkbox').prop('checked', false);
                $('.customer-type-checkbox').prop('checked', false);
                $('.invoice-no-checkbox').prop('checked', false);
                $('.po-no-checkbox').prop('checked', false);
                $('.appointment-date-checkbox').prop('checked', false);

                // Redirect to base URL without filters
                window.location.href = '{{ route('customer-sales-sku') }}';
            });

            /**
             * Generate Excel Report Functionality
             */
            $('#generateExcelReport').on('click', function() {
                // Get current filter values
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('input[name="customer_id[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var warehouseId = $('input[name="warehouse_id[]"]:checked').map(function() {
                    return this.value;
                }).get();
                // var region = $('input[name="region[]"]:checked').map(function() {
                //     return this.value;
                // }).get();
                var paymentStatus = $('input[name="payment_status[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var customerType = $('input[name="customer_type[]"]:checked').map(function() {
                    return this.value;
                }).get();
                // var invoiceNo = $('input[name="invoice_no[]"]:checked').map(function() {
                //     return this.value;
                // }).get();
                var poNo = $('input[name="po_no[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var appointmentDate = $('input[name="appointment_date[]"]:checked').map(function() {
                    return this.value;
                }).get();

                // Build query parameters array
                var params = [];

                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (customerId.length > 0) customerId.forEach(function(val) {
                    params.push('customer_id[]=' + encodeURIComponent(val));
                });
                if (warehouseId.length > 0) warehouseId.forEach(function(val) {
                    params.push('warehouse_id[]=' + encodeURIComponent(val));
                });
                // if (region.length > 0) region.forEach(function(val) {
                //     params.push('region[]=' + encodeURIComponent(val));
                // });
                if (paymentStatus.length > 0) paymentStatus.forEach(function(val) {
                    params.push('payment_status[]=' + encodeURIComponent(val));
                });
                if (customerType.length > 0) customerType.forEach(function(val) {
                    params.push('customer_type[]=' + encodeURIComponent(val));
                });
                // if (invoiceNo.length > 0) invoiceNo.forEach(function(val) {
                //     params.push('invoice_no[]=' + encodeURIComponent(val));
                // });
                if (poNo.length > 0) poNo.forEach(function(val) {
                    params.push('po_no[]=' + encodeURIComponent(val));
                });
                if (appointmentDate.length > 0) appointmentDate.forEach(function(val) {
                    params.push('appointment_date[]=' + encodeURIComponent(val));
                });

                // Construct download URL with filter parameters
                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('customer.sales.history.excel1') }}' + queryString;

                // Show loading indicator
                var originalText = $(this).html();
                $(this).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
                $(this).prop('disabled', true);

                // Trigger browser download
                window.location.href = downloadUrl;

                // Reset button after a short delay
                setTimeout(function() {
                    $('#generateExcelReport').html(originalText);
                    $('#generateExcelReport').prop('disabled', false);
                }, 2000);
            });

            /**
             * Generate PDF Report Functionality
             */
            $('#generatePdfReport').on('click', function() {
                // Get current filter values
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('input[name="customer_id[]"]:checked').map(function() {
                    return this.value;
                }).get();
                // var region = $('input[name="region[]"]:checked').map(function() {
                //     return this.value;
                // }).get();
                var paymentStatus = $('input[name="payment_status[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var customerType = $('input[name="customer_type[]"]:checked').map(function() {
                    return this.value;
                }).get();
                // var invoiceNo = $('input[name="invoice_no[]"]:checked').map(function() {
                //     return this.value;
                // }).get();
                var poNo = $('input[name="po_no[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var appointmentDate = $('input[name="appointment_date[]"]:checked').map(function() {
                    return this.value;
                }).get();

                // Build query parameters array
                var params = [];

                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (customerId.length > 0) customerId.forEach(function(val) {
                    params.push('customer_id[]=' + encodeURIComponent(val));
                });
                // if (region.length > 0) region.forEach(function(val) {
                //     params.push('region[]=' + encodeURIComponent(val));
                // });
                if (paymentStatus.length > 0) paymentStatus.forEach(function(val) {
                    params.push('payment_status[]=' + encodeURIComponent(val));
                });
                if (customerType.length > 0) customerType.forEach(function(val) {
                    params.push('customer_type[]=' + encodeURIComponent(val));
                });
                // if (invoiceNo.length > 0) invoiceNo.forEach(function(val) {
                //     params.push('invoice_no[]=' + encodeURIComponent(val));
                // });
                if (poNo.length > 0) poNo.forEach(function(val) {
                    params.push('po_no[]=' + encodeURIComponent(val));
                });
                if (appointmentDate.length > 0) appointmentDate.forEach(function(val) {
                    params.push('appointment_date[]=' + encodeURIComponent(val));
                });

                // Construct download URL with filter parameters
                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('customer.sales.history.pdf') }}' + queryString;

                // Show loading indicator
                var originalText = $(this).html();
                $(this).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Generating...');
                $(this).prop('disabled', true);

                // Trigger browser download
                window.location.href = downloadUrl;

                // Reset button after a short delay
                setTimeout(function() {
                    $('#generatePdfReport').html(originalText);
                    $('#generatePdfReport').prop('disabled', false);
                }, 2000);
            });

            /**
             * Initialize Bootstrap Tooltips
             */
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        /**
         * View Customer Details Function
         */
        function viewCustomerDetails(customerId) {
            // Open customer detail page in new tab
            window.open('{{ route('customer.detail', ':id') }}'.replace(':id', customerId), '_blank');
        }
    </script>
@endsection
