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
                            <li class="breadcrumb-item active" aria-current="page">Customer Sales Invoices Level</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('layouts.errors')

            {{-- Total Records  --}}
            <div class="row">
                {{-- Total Ivoices --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">
                                <i class="ti ti-package fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Invoices</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">{{ $totalInvoices }}</h4>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
                {{-- Total Taxable Amount --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">
                                <i class="ti ti-file-text fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Taxable Amount</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">₹{{ number_format($totalTaxableAmount, 2) }}</h4>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
                {{-- Total Amount  --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <span class="sale-icon bg-white text-primary">  
                                <i class="ti ti-file-text fs-24"></i>
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Amount</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">₹{{ number_format($totalAmount, 2) }}</h4>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
                {{-- Total Amount Paid --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">   
                            <span class="sale-icon bg-white text-primary">  
                                <i class="ti ti-file-text fs-24"></i>   
                            </span>
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Amount Paid</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">₹{{ number_format($totalAmountPaid, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Balance Amount --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">   
                            <span class="sale-icon bg-white text-primary">  
                                <i class="ti ti-file-text fs-24"></i>   
                            </span>
                            <div class="ms-2"> 
                                <p class="text-dark mb-1">Total Balance Amount</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">₹{{ number_format($totalBalanceAmount, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Customer --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-white sale-widget flex-fill">
                        <div class="card-body d-flex align-items-center">   
                            <span class="sale-icon bg-white text-primary">  
                                <i class="ti ti-file-text fs-24"></i>   
                            </span> 
                            <div class="ms-2">
                                <p class="text-dark mb-1">Total Customers</p>
                                <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                    <h4 class="text-dark">{{ $totalCustomers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold"><i class="bx bx-filter-alt me-2"></i>Filter Options</h6>
                    <form method="GET" action="{{ route('customer-sales-invoices') }}" id="filterForm">
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

                                    <!-- Payment Status Filter -->
                                    {{-- 
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
                                    --}}

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
                                    <div class="col-md-2">
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

                                    <!-- Appointment Date Filter -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Appointment Date</label>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    type="button" id="appointmentDateDropdown"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-filter-alt me-1"></i>
                                                    <span id="appointmentDateDropdownText">
                                                        @if (is_array($filters['appointment_date'] ?? null) && count($filters['appointment_date']) > 0)
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
                                                                    {{ in_array($date, (array) ($filters['appointment_date'] ?? [])) ? 'checked' : '' }}>
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
                    <h5 class="mb-0">Customer Sales Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customerSalesTable" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sales&nbsp;Order&nbsp;No</th>
                                    <th>Customer&nbsp;Group&nbsp;Name</th>
                                    <th>Customer&nbsp;Name</th>
                                    <th>Invoice&nbsp;No</th>
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
                                    <th>DN&nbsp;Amount</th>
                                    <th>DN&nbsp;Reciept</th>
                                    <th>LR</th>
                                    <th>Currency</th>
                                    <th>HSN</th>
                                    <th>Ordered&nbsp;Quantity</th>
                                    <th>Dispatched&nbsp;Quantity</th>
                                    <th>Box&nbsp;Count</th>
                                    <th>Weight</th>
                                    <th>Taxable&nbsp;Value</th>
                                    <th>GST</th>
                                    <th>GST&nbsp;Value</th>
                                    <th>Total</th>
                                    <th>Invoice&nbsp;Status</th>
                                    <th>Amount&nbsp;Paid</th>
                                    <th>Balance</th>
                                    <th>Date&nbsp;Of&nbsp;Payment</th>
                                    <th>Payment&nbsp;Mode</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>IGST</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($salesOrders as $salesOrder)
                                    @foreach ($salesOrder->invoices as $invoice)
                                        @php
                                            // Calculate totals from invoice details and salesOrderProduct
                                            $totalBoxCount = 0;
                                            $totalWeight = 0;
                                            $taxableValue = 0;
                                            $gstAmount = 0;
                                            $cgstAmount = 0;
                                            $sgstAmount = 0;
                                            $igstAmount = 0;
                                            $firstGstRate = 0;

                                            foreach ($invoice->details as $detail) {
                                                // Box count - use invoice_details or fallback to salesOrderProduct
                                                $totalBoxCount +=
                                                    $detail->box_count ?? ($detail->salesOrderProduct?->box_count ?? 0);

                                                // Weight - use invoice_details or fallback to salesOrderProduct
                                                $totalWeight +=
                                                    $detail->weight ?? ($detail->salesOrderProduct?->weight ?? 0);

                                                // Taxable value is the 'amount' field
                                                $taxableValue += $detail->amount ?? 0;

                                                // GST amount calculation: (amount * tax) / 100
                                                $detailGstAmount = ($detail->amount * $detail->tax) / 100;
                                                $gstAmount += $detailGstAmount;

                                                // Store first GST rate for display
                                                if ($firstGstRate == 0 && $detail->tax > 0) {
                                                    $firstGstRate = $detail->tax;
                                                }

                                                // Calculate CGST/SGST/IGST based on customer state
                                                // If same state: CGST + SGST, else: IGST
                                                $customerState =
                                                    $invoice->customer?->shipping_state ??
                                                    $invoice->customer?->billing_state;
                                                $warehouseState = $invoice->warehouse?->state ?? 'Maharashtra'; // Default warehouse state

                                                if (
                                                    $customerState &&
                                                    $warehouseState &&
                                                    strtolower($customerState) === strtolower($warehouseState)
                                                ) {
                                                    // Intra-state: CGST + SGST (split GST equally)
                                                    $cgstAmount += $detailGstAmount / 2;
                                                    $sgstAmount += $detailGstAmount / 2;
                                                } else {
                                                    // Inter-state: IGST
                                                    // these two are optional
                                                    $cgstAmount += $detailGstAmount / 2;
                                                    $sgstAmount += $detailGstAmount / 2;
                                                    $igstAmount += $detailGstAmount;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $salesOrder->order_number ?? 'N/A' }}</td>
                                            <td>{{ $salesOrder->customerGroup->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->client_name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->contact_no ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->email ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->shipping_city ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->shipping_state ?? 'N/A' }}</td>
                                            <td>{{ $invoice->po_number ?? 'N/A' }}</td>
                                            <td>{{ $invoice->created_at->format('d-m-Y') ?? 'N/A' }}</td>
                                            <td>{{ $invoice->appointment?->appointment_date?->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->appointment?->appointment_date?->addMonth()->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->appointment?->pod ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->appointment?->grn ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->dns?->first()?->dn_amount ? $invoice->dns?->first()?->dn_amount : 0 }}</td>
                                            <td>{{ $invoice->dns?->first()?->dn_receipt ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->lr ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->currency ?? 'INR' }}</td>
                                            <td>{{ $invoice->details->first()->hsn ?? 'N/A' }}</td>
                                            <td>{{ $invoice->details->sum('quantity') ?? 0 }}</td>
                                            <td>{{ $invoice->details->sum('quantity') ?? 0 }}</td>
                                            <td>{{ number_format($totalBoxCount, 0) }}</td>
                                            <td>{{ number_format($totalWeight, 2) }}</td>
                                            <td>₹{{ number_format($taxableValue, 2) }}</td>
                                            <td>{{ $firstGstRate }}%</td>
                                            <td>₹{{ number_format($gstAmount, 2) }}</td>
                                            <td>₹{{ number_format($gstAmount + $taxableValue ?? 0, 2) }}</td>
                                            <td>{{ ucfirst($invoice->payment_status ?? 'N/A') }}</td>
                                            <td>₹{{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                                            <td>₹{{ number_format($invoice->balance_due ?? 0, 2) }}</td>
                                            <td>{{ $invoice->payments?->first()?->created_at?->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->payments->first()->payment_method ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($cgstAmount, 2) }}</td>
                                            <td>₹{{ number_format($sgstAmount, 2) }}</td>
                                            <td>₹{{ number_format($igstAmount, 2) }}</td>
                                            <td>₹0.00</td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="35" class="text-center text-muted py-4">
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


            // $(document).on('change', '.payment-status-checkbox', function() {
            //     updateDropdownText('.payment-status-checkbox', 'paymentStatusDropdown',
            //         'paymentStatusDropdownText', 'Select Status');
            // });

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
                //$('.payment-status-checkbox').prop('checked', false);
                $('.customer-type-checkbox').prop('checked', false);
                $('.invoice-no-checkbox').prop('checked', false);
                $('.po-no-checkbox').prop('checked', false);
                $('.appointment-date-checkbox').prop('checked', false);

                // Redirect to base URL without filters
                window.location.href = '{{ route('customer-sales-invoices') }}';
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
                // var paymentStatus = $('input[name="payment_status[]"]:checked').map(function() {
                //     return this.value;
                // }).get();
                var customerType = $('input[name="customer_type[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var invoiceNo = $('input[name="invoice_no[]"]:checked').map(function() {
                    return this.value;
                }).get();
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
                // if (paymentStatus.length > 0) paymentStatus.forEach(function(val) {
                //     params.push('payment_status[]=' + encodeURIComponent(val));
                // });
                if (customerType.length > 0) customerType.forEach(function(val) {
                    params.push('customer_type[]=' + encodeURIComponent(val));
                });
                if (invoiceNo.length > 0) invoiceNo.forEach(function(val) {
                    params.push('invoice_no[]=' + encodeURIComponent(val));
                });
                if (poNo.length > 0) poNo.forEach(function(val) {
                    params.push('po_no[]=' + encodeURIComponent(val));
                });
                if (appointmentDate.length > 0) appointmentDate.forEach(function(val) {
                    params.push('appointment_date[]=' + encodeURIComponent(val));
                });

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
                var customerId = $('input[name="customer_id[]"]:checked').map(function() {
                    return this.value;
                }).get();
                // var paymentStatus = $('input[name="payment_status[]"]:checked').map(function() {
                //     return this.value;
                // }).get();
                var customerType = $('input[name="customer_type[]"]:checked').map(function() {
                    return this.value;
                }).get();
                var invoiceNo = $('input[name="invoice_no[]"]:checked').map(function() {
                    return this.value;
                }).get();
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
                // if (paymentStatus.length > 0) paymentStatus.forEach(function(val) {
                //     params.push('payment_status[]=' + encodeURIComponent(val));
                // });
                if (customerType.length > 0) customerType.forEach(function(val) {
                    params.push('customer_type[]=' + encodeURIComponent(val));
                });
                if (invoiceNo.length > 0) invoiceNo.forEach(function(val) {
                    params.push('invoice_no[]=' + encodeURIComponent(val));
                });
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
