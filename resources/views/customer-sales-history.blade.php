@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <!-- Breadcrumb Navigation -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Sales History</li>
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

            <!-- Statistics Cards -->
            <div class="row">
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
                                    <h4 class="mb-0 fw-bold">{{ $invoices->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-dollar-circle fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Revenue</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalRevenue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="bx bx-time-five fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Pending Payments</p>
                                    <h4 class="mb-0 fw-bold">₹{{ number_format($totalPendingPayments, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <h4 class="mb-0 fw-bold">{{ $customers->count() }}</h4>
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
                    <form method="GET" action="{{ route('customer-sales-history') }}" id="filterForm">
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
                                                        @if(is_array($filters['customer_id'] ?? null) && count($filters['customer_id']) > 0)
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
                                                                    {{ in_array($customer['id'], (array)($filters['customer_id'] ?? [])) ? 'checked' : '' }}>
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

                                    <!-- Region Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Region</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="regionDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="regionDropdownText">
                                                        @if(is_array($filters['region'] ?? null) && count($filters['region']) > 0)
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
                                                                    {{ in_array($region, (array)($filters['region'] ?? [])) ? 'checked' : '' }}>
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

                                    <!-- Payment Status Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Status</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="paymentStatusDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="paymentStatusDropdownText">
                                                        @if(is_array($filters['payment_status'] ?? null) && count($filters['payment_status']) > 0)
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
                                                                type="checkbox" name="payment_status[]"
                                                                value="paid"
                                                                id="payment_status_paid"
                                                                {{ in_array('paid', (array)($filters['payment_status'] ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_paid">
                                                                Paid
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="checkbox" name="payment_status[]"
                                                                value="partial"
                                                                id="payment_status_partial"
                                                                {{ in_array('partial', (array)($filters['payment_status'] ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100 cursor-pointer"
                                                                for="payment_status_partial">
                                                                Partial
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="px-2 py-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input payment-status-checkbox"
                                                                type="checkbox" name="payment_status[]"
                                                                value="unpaid"
                                                                id="payment_status_unpaid"
                                                                {{ in_array('unpaid', (array)($filters['payment_status'] ?? [])) ? 'checked' : '' }}>
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
                                            <label class="form-label">Customer Type</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="customerTypeDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="customerTypeDropdownText">
                                                        @if(is_array($filters['customer_type'] ?? null) && count($filters['customer_type']) > 0)
                                                            {{ count($filters['customer_type']) }} selected
                                                        @else
                                                            Select Type
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
                                                                    {{ in_array($group->id, (array)($filters['customer_type'] ?? [])) ? 'checked' : '' }}>
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
                                </div>

                                <!-- Second Row of Filters -->
                                <div class="row">

                                    <!-- Invoice No Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Invoice No</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="invoiceNoDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="invoiceNoDropdownText">
                                                        @if(is_array($filters['invoice_no'] ?? null) && count($filters['invoice_no']) > 0)
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
                                                                    {{ in_array($invoiceNo, (array)($filters['invoice_no'] ?? [])) ? 'checked' : '' }}>
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
                                    </div>

                                    <!-- PO No Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">PO No</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="poNoDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="poNoDropdownText">
                                                        @if(is_array($filters['po_no'] ?? null) && count($filters['po_no']) > 0)
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
                                                                    {{ in_array($poNo, (array)($filters['po_no'] ?? [])) ? 'checked' : '' }}>
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

                                    <!-- Appointment Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Appointment Date</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="appointmentDateDropdown" data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="appointmentDateDropdownText">
                                                        @if(is_array($filters['appointment_date'] ?? null) && count($filters['appointment_date']) > 0)
                                                            {{ count($filters['appointment_date']) }} selected
                                                        @else
                                                            Select Date
                                                        @endif
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu w-100" id="appointmentDateCheckboxList"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($appointmentDates as $date)
                                                        <li class="px-2 py-1">
                                                            <div class="form-check">
                                                                <input class="form-check-input appointment-date-checkbox"
                                                                    type="checkbox" name="appointment_date[]"
                                                                    value="{{ $date }}"
                                                                    id="appointment_date_{{ $loop->index }}"
                                                                    {{ in_array($date, (array)($filters['appointment_date'] ?? [])) ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100 cursor-pointer"
                                                                    for="appointment_date_{{ $loop->index }}">
                                                                    {{ $date }}
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
                                                <button type="button" id="generateExcelReport" class="btn btn-success flex-fill">
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
                    <h5 class="mb-0">Customer Sales Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customerSalesTable" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer&nbsp;Group&nbsp;Name</th>
                                    <th>Customer&nbsp;Name</th>
                                    <th>Customer&nbsp;GSTIN</th>
                                    <th>Invoice&nbsp;No</th>
                                    <th>Creator&nbsp;Name</th>
                                    <th>Customer&nbsp;Phone&nbsp;No</th>
                                    <th>Customer&nbsp;Email</th>
                                    <th>Customer&nbsp;City</th>
                                    <th>Customer&nbsp;State</th>
                                    <th>PO&nbsp;No</th>
                                    <th>PO&nbsp;Date</th>
                                    <th>Appointment&nbsp;Date</th>
                                    <th>Due&nbsp;Date</th>
                                    <th>POD</th>
                                    <th>GRN</th>
                                    <th>DN</th>
                                    <th>DN&nbsp;Reciept</th>
                                    <th>LR</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Amount&nbsp;Paid</th>
                                    <th>Balance</th>
                                    <th>Date&nbsp;Of&nbsp;Payment</th>
                                    <th>Payment&nbsp;Mode</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>IGST</th>
                                    <th>Cess</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                    @php
                                        $customer = $invoice->customer;
                                        $customerGroup = $customer->groupInfo->customerGroup ?? null;
                                        $salesOrder = $invoice->salesOrder;
                                        $payments = $invoice->payments;
                                        $totalPaid = $payments->sum('amount');
                                        $balance = $invoice->total_amount - $totalPaid;
                                        $appointment = $invoice->appointment;
                                        $dns = $invoice->dns;

                                        // Calculate tax breakdown (assuming GST is stored in invoice details)
                                        $cgst = 0;
                                        $sgst = 0;
                                        $igst = 0;
                                        $cess = 0;
                                        $taxAmount = 0;

                                        foreach ($invoice->details as $detail) {
                                            $gstRate = $detail->tax ?? 0;
                                            $taxAmount += $detail->tax ?? 0;
                                            $cess += $detail->cess ?? 0;

                                            // Assuming CGST/SGST split for intra-state, IGST for inter-state
                                            if ($customer->billing_state === $customer->shipping_state) {
                                                $cgst += $gstRate / 2;
                                                $sgst += $gstRate / 2;
                                            } else {
                                                $igst += $gstRate;
                                            }
                                        }

                                        // Get latest payment details
                                        $latestPayment = $payments->sortByDesc('created_at')->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $customerGroup->name ?? 'N/A' }}</td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">{{ $customer->client_name ?? 'N/A' }}</span>
                                                <br><small class="text-muted">{{ $customer->company_name ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $customer->gstin ?? 'N/A' }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $invoice->invoice_number }}</span>
                                        </td>
                                        <td>System</td>
                                        <td>{{ $customer->contact_no ?? 'N/A' }}</td>
                                        <td>{{ $customer->email ?? 'N/A' }}</td>
                                        <td>{{ $customer->billing_city ?? $customer->shipping_city ?? 'N/A' }}</td>
                                        <td>{{ $customer->billing_state ?? $customer->shipping_state ?? 'N/A' }}</td>
                                        <td>{{ $invoice->po_number ?? $salesOrder->po_number ?? 'N/A' }}</td>
                                        <td>{{ $salesOrder ? $salesOrder->created_at->format('d-m-Y') : 'N/A' }}</td>
                                        <td>
                                            @if($appointment && $appointment->appointment_date)
                                                {{ is_string($appointment->appointment_date) ? \Carbon\Carbon::parse($appointment->appointment_date)->format('d-m-Y') : $appointment->appointment_date->format('d-m-Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>INR</td>
                                        <td>₹{{ number_format($invoice->subtotal ?? ($invoice->total_amount - $taxAmount), 2) }}</td>
                                        <td>₹{{ number_format($taxAmount, 2) }}</td>
                                        <td>
                                            <span class="fw-semibold">₹{{ number_format($invoice->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($balance <= 0)
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($totalPaid > 0)
                                                <span class="badge bg-warning">Partial</span>
                                            @else
                                                <span class="badge bg-danger">Unpaid</span>
                                            @endif
                                        </td>
                                        <td>₹{{ number_format($totalPaid, 2) }}</td>
                                        <td>
                                            <span class="fw-semibold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                                ₹{{ number_format($balance, 2) }}
                                            </span>
                                        </td>
                                        <td>{{ $latestPayment ? $latestPayment->created_at->format('d-m-Y') : 'N/A' }}</td>
                                        <td>{{ $latestPayment ? $latestPayment->payment_method : 'N/A' }}</td>
                                        <td>₹{{ number_format($cgst, 2) }}</td>
                                        <td>₹{{ number_format($sgst, 2) }}</td>
                                        <td>₹{{ number_format($igst, 2) }}</td>
                                        <td>₹{{ number_format($cess, 2) }}</td>
                                        <td>
                                            <a href="{{ route('invoices.view', $invoice->id) }}" target="_blank"
                                                class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                data-bs-toggle="tooltip" title="View Invoice">
                                                <i class="bx bx-show text-primary"></i>
                                            </a>
                                            <a href="{{ route('invoice.downloadPdf', $invoice->id) }}" target="_blank"
                                                class="btn btn-icon btn-sm bg-success-subtle"
                                                data-bs-toggle="tooltip" title="Download PDF">
                                                <i class="bx bx-download text-success"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="27" class="text-center text-muted py-4">
                                            <i class="bx bx-info-circle fs-4 d-block mb-2"></i>
                                            No customer sales records found
                                            @if ($filters['from_date'] || $filters['to_date'] || $filters['customer_id'] || $filters['region'] || $filters['payment_status'] || $filters['customer_type'])
                                                for the selected filters.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($invoices->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    @endif
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
            $('#customerSalesTable').DataTable({
                "pageLength": 25,
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                "ordering": true,
                "searching": true,
                "order": [[10, "desc"]], // Default sort by Invoice Date descending
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries"
                },
                "columnDefs": [
                    {
                        "targets": [14, 15, 16, 19, 23, 24, 25, 26], // Amount columns
                        "type": "num-fmt",
                        "render": function(data, type, row) {
                            if (type === 'sort') {
                                return parseFloat(data.replace(/[^\d.-]/g, ''));
                            }
                            return data;
                        }
                    },
                    {
                        "targets": [10, 11, 12, 21], // Date columns
                        "type": "date",
                        "render": function(data, type, row) {
                            if (type === 'sort') {
                                return data ? new Date(data.split('-').reverse().join('-')).getTime() : 0;
                            }
                            return data;
                        }
                    }
                ],
                "paging": false, // Disable DataTables pagination since we're using Laravel pagination
                "info": false // Disable info since we're using Laravel pagination
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
                updateDropdownText('.customer-checkbox', 'customerDropdown', 'customerDropdownText', 'Select Customer');
            });

            $(document).on('change', '.region-checkbox', function() {
                updateDropdownText('.region-checkbox', 'regionDropdown', 'regionDropdownText', 'Select Region');
            });

            $(document).on('change', '.payment-status-checkbox', function() {
                updateDropdownText('.payment-status-checkbox', 'paymentStatusDropdown', 'paymentStatusDropdownText', 'Select Status');
            });

            $(document).on('change', '.customer-type-checkbox', function() {
                updateDropdownText('.customer-type-checkbox', 'customerTypeDropdown', 'customerTypeDropdownText', 'Select Type');
            });

            $(document).on('change', '.invoice-no-checkbox', function() {
                updateDropdownText('.invoice-no-checkbox', 'invoiceNoDropdown', 'invoiceNoDropdownText', 'Select Invoice');
            });

            $(document).on('change', '.po-no-checkbox', function() {
                updateDropdownText('.po-no-checkbox', 'poNoDropdown', 'poNoDropdownText', 'Select PO');
            });

            $(document).on('change', '.appointment-date-checkbox', function() {
                updateDropdownText('.appointment-date-checkbox', 'appointmentDateDropdown', 'appointmentDateDropdownText', 'Select Date');
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
                $('.region-checkbox').prop('checked', false);
                $('.payment-status-checkbox').prop('checked', false);
                $('.customer-type-checkbox').prop('checked', false);
                $('.invoice-no-checkbox').prop('checked', false);
                $('.po-no-checkbox').prop('checked', false);
                $('.appointment-date-checkbox').prop('checked', false);

                // Redirect to base URL without filters
                window.location.href = '{{ route('customer-sales-history') }}';
            });

            /**
             * Generate Excel Report Functionality
             */
            $('#generateExcelReport').on('click', function() {
                // Get current filter values
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('input[name="customer_id[]"]:checked').map(function() { return this.value; }).get();
                var region = $('input[name="region[]"]:checked').map(function() { return this.value; }).get();
                var paymentStatus = $('input[name="payment_status[]"]:checked').map(function() { return this.value; }).get();
                var customerType = $('input[name="customer_type[]"]:checked').map(function() { return this.value; }).get();
                var invoiceNo = $('input[name="invoice_no[]"]:checked').map(function() { return this.value; }).get();
                var poNo = $('input[name="po_no[]"]:checked').map(function() { return this.value; }).get();
                var appointmentDate = $('input[name="appointment_date[]"]:checked').map(function() { return this.value; }).get();

                // Build query parameters array
                var params = [];

                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (customerId.length > 0) customerId.forEach(function(val) { params.push('customer_id[]=' + encodeURIComponent(val)); });
                if (region.length > 0) region.forEach(function(val) { params.push('region[]=' + encodeURIComponent(val)); });
                if (paymentStatus.length > 0) paymentStatus.forEach(function(val) { params.push('payment_status[]=' + encodeURIComponent(val)); });
                if (customerType.length > 0) customerType.forEach(function(val) { params.push('customer_type[]=' + encodeURIComponent(val)); });
                if (invoiceNo.length > 0) invoiceNo.forEach(function(val) { params.push('invoice_no[]=' + encodeURIComponent(val)); });
                if (poNo.length > 0) poNo.forEach(function(val) { params.push('po_no[]=' + encodeURIComponent(val)); });
                if (appointmentDate.length > 0) appointmentDate.forEach(function(val) { params.push('appointment_date[]=' + encodeURIComponent(val)); });

                // Construct download URL with filter parameters
                var queryString = params.length ? '?' + params.join('&') : '';
                var downloadUrl = '{{ route('customer.sales.history.excel') }}' + queryString;

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
                var customerId = $('input[name="customer_id[]"]:checked').map(function() { return this.value; }).get();
                var region = $('input[name="region[]"]:checked').map(function() { return this.value; }).get();
                var paymentStatus = $('input[name="payment_status[]"]:checked').map(function() { return this.value; }).get();
                var customerType = $('input[name="customer_type[]"]:checked').map(function() { return this.value; }).get();
                var invoiceNo = $('input[name="invoice_no[]"]:checked').map(function() { return this.value; }).get();
                var poNo = $('input[name="po_no[]"]:checked').map(function() { return this.value; }).get();
                var appointmentDate = $('input[name="appointment_date[]"]:checked').map(function() { return this.value; }).get();

                // Build query parameters array
                var params = [];

                if (fromDate) params.push('from_date=' + encodeURIComponent(fromDate));
                if (toDate) params.push('to_date=' + encodeURIComponent(toDate));
                if (customerId.length > 0) customerId.forEach(function(val) { params.push('customer_id[]=' + encodeURIComponent(val)); });
                if (region.length > 0) region.forEach(function(val) { params.push('region[]=' + encodeURIComponent(val)); });
                if (paymentStatus.length > 0) paymentStatus.forEach(function(val) { params.push('payment_status[]=' + encodeURIComponent(val)); });
                if (customerType.length > 0) customerType.forEach(function(val) { params.push('customer_type[]=' + encodeURIComponent(val)); });
                if (invoiceNo.length > 0) invoiceNo.forEach(function(val) { params.push('invoice_no[]=' + encodeURIComponent(val)); });
                if (poNo.length > 0) poNo.forEach(function(val) { params.push('po_no[]=' + encodeURIComponent(val)); });
                if (appointmentDate.length > 0) appointmentDate.forEach(function(val) { params.push('appointment_date[]=' + encodeURIComponent(val)); });

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
