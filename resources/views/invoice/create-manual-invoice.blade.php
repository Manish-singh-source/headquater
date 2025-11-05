@extends('layouts.master')
@section('main-content')
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices') }}">Invoices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Manual Invoice</li>
                </ol>
            </nav>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('invoices.manual.store') }}" method="POST" id="manualInvoiceForm">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Invoice Details</h5>
                        </div>
                        <div class="card-body">
                             <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Customers</h6>
                                 <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                           Add Customer
                                        </button>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->client_name }} - {{ $customer->facility_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                           value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                    @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">PO Number</label>
                                    <input type="text" name="po_number" class="form-control @error('po_number') is-invalid @enderror" 
                                           value="{{ old('po_number') }}" placeholder="Enter PO Number">
                                    @error('po_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Products</h6>
                                <button type="button" class="btn btn-sm btn-primary" id="addProductRow">
                                    <i class="bx bx-plus"></i> Add Product
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="productsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 12%;">Warehouse <span class="text-danger">*</span></th>
                                            <th style="width: 15%;">Product <span class="text-danger">*</span></th>
                                            <th style="width: 7%;">HSN</th>
                                            <th style="width: 5%;">Stock</th>
                                            <th style="width: 6%;">Qty <span class="text-danger">*</span></th>
                                            <th style="width: 6%;">Box Count</th>
                                            <th style="width: 6%;">Weight</th>
                                            <th style="width: 9%;">Rate <span class="text-danger">*</span></th>
                                            <th style="width: 7%;">Discount</th>
                                            <th style="width: 7%;">Tax</th>
                                            <th style="width: 10%;">Total</th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productRows">
                                        <!-- Product rows will be added here -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Summary</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end"><strong id="subtotalDisplay">₹0.00</strong></td>
                                </tr>
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end text-danger"><strong id="discountDisplay">₹0.00</strong></td>
                                </tr>
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end"><strong id="taxDisplay">₹0.00</strong></td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong id="totalDisplay">₹0.00</strong></td>
                                </tr>
                            </table>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-select">
                                    <option value="">Select Payment Mode</option>
                                    <option value="cash" {{ old('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_mode') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cheque" {{ old('payment_mode') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="upi" {{ old('payment_mode') == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="card" {{ old('payment_mode') == 'card' ? 'selected' : '' }}>Card</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amount Received</label>
                                <input type="number" name="paid_amount" id="paid_amount" class="form-control" 
                                       value="{{ old('paid_amount', 0) }}" step="0.01" min="0">
                            </div>

                            <table class="table table-sm">
                                <tr class="table-warning">
                                    <td><strong>Balance Due:</strong></td>
                                    <td class="text-end"><strong id="balanceDueDisplay">₹0.00</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td class="text-end"><span id="paymentStatusBadge" class="badge bg-secondary">Unpaid</span></td>
                                </tr>
                            </table>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bx bx-save"></i> Create Invoice
                                </button>
                                <a href="{{ route('invoices') }}" class="btn btn-secondary">
                                    <i class="bx bx-x"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add Customer Modal -->
        <div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="addCustomerForm" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="customerFormErrors">
                                <ul class="mb-0" id="customerErrorList"></ul>
                            </div>

                            <!-- Customer Type Selection -->
                            <div class="mb-3">
                                <label class="form-label">Customer Type <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="typeGroup" value="group" checked>
                                        <label class="form-check-label" for="typeGroup">
                                            Customer Group
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="customer_type" id="typeIndividual" value="individual">
                                        <label class="form-check-label" for="typeIndividual">
                                            Individual Customer
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Group Field (shown when group is selected) -->
                            <div class="mb-3" id="customerGroupField">
                                <label class="form-label">Customer Group <span class="text-danger">*</span></label>
                                <select name="group_id" id="group_id" class="form-select">
                                    <option value="">Select Customer Group</option>
                                    @foreach($customerGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Facility Name <span class="text-danger">*</span></label>
                                    <input type="text" name="facility_name" id="facility_name" class="form-control" placeholder="Enter Facility Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" name="client_name" id="client_name" class="form-control" placeholder="Enter Client Name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" id="contact_name" class="form-control" placeholder="Enter Contact Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Enter 10 digit number" maxlength="10">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GSTIN <span class="text-danger">*</span></label>
                                    <input type="text" name="gstin" id="gstin" class="form-control" placeholder="Enter GSTIN" maxlength="15">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN <span class="text-danger">*</span></label>
                                    <input type="text" name="pan" id="pan" class="form-control" placeholder="Enter PAN" maxlength="10">
                                </div>
                            </div>

                            <!-- Bill To Address Section -->
                            <h6 class="mt-4 mb-3 text-primary"><i class="bx bx-map"></i> Bill To Address</h6>

                            <div class="mb-3">
                                <label class="form-label">Billing Address</label>
                                <textarea name="billing_address" id="billing_address" class="form-control" rows="2" placeholder="Enter Billing Address"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Billing Country</label>
                                    <input type="text" name="billing_country" id="billing_country" class="form-control" placeholder="Enter Country">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Billing State</label>
                                    <input type="text" name="billing_state" id="billing_state" class="form-control" placeholder="Enter State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Billing City</label>
                                    <input type="text" name="billing_city" id="billing_city" class="form-control" placeholder="Enter City">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Billing ZIP</label>
                                <input type="text" name="billing_zip" id="billing_zip" class="form-control" placeholder="Enter ZIP Code">
                            </div>

                            <!-- Ship To Address Section -->
                            <h6 class="mt-4 mb-3 text-primary"><i class="bx bx-package"></i> Ship To Address</h6>

                            <div class="mb-3">
                                <label class="form-label">Shipping Address</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control" rows="2" placeholder="Enter Shipping Address"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Shipping Country</label>
                                    <input type="text" name="shipping_country" id="shipping_country" class="form-control" placeholder="Enter Country">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Shipping State</label>
                                    <input type="text" name="shipping_state" id="shipping_state" class="form-control" placeholder="Enter State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Shipping City</label>
                                    <input type="text" name="shipping_city" id="shipping_city" class="form-control" placeholder="Enter City">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shipping ZIP</label>
                                <input type="text" name="shipping_zip" id="shipping_zip" class="form-control" placeholder="Enter ZIP Code">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveCustomerBtn">
                                <i class="bx bx-save"></i> Save Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let rowIndex = 0;
const products = @json($products);
const warehouses = @json($warehouses);

document.addEventListener('DOMContentLoaded', function() {
    // Add first row automatically
    addProductRow();

    // Add product row button
    document.getElementById('addProductRow').addEventListener('click', addProductRow);

    // Paid amount change
    document.getElementById('paid_amount').addEventListener('input', calculateTotals);
});

function addProductRow() {
    rowIndex++;
    const tbody = document.getElementById('productRows');
    const row = document.createElement('tr');
    row.id = `row_${rowIndex}`;

    row.innerHTML = `
        <td>
            <select name="products[${rowIndex}][warehouse_id]" class="form-select form-select-sm warehouse-select" data-row="${rowIndex}" required>
                <option value="">Select Warehouse</option>
                ${warehouses.map(w => `<option value="${w.id}">${w.name}</option>`).join('')}
            </select>
        </td>
        <td>
            <select name="products[${rowIndex}][product_id]" class="form-select form-select-sm product-select" data-row="${rowIndex}" required>
                <option value="">Select Product</option>
                ${products.map(p => `<option value="${p.id}" data-sku="${p.sku}" data-price="${p.mrp}">${p.sku}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="text" name="products[${rowIndex}][hsn]" class="form-control form-control-sm hsn-input"
                   data-row="${rowIndex}" placeholder="HSN">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm stock-display" id="stock_${rowIndex}" readonly placeholder="0">
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][quantity]" class="form-control form-control-sm quantity-input"
                   data-row="${rowIndex}" step="0.01" min="0.01" required>
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][box_count]" class="form-control form-control-sm box-count-input"
                   data-row="${rowIndex}" step="1" min="0" placeholder="0">
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][weight]" class="form-control form-control-sm weight-input"
                   data-row="${rowIndex}" step="0.01" min="0" placeholder="0.00">
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][unit_price]" class="form-control form-control-sm price-input"
                   data-row="${rowIndex}" step="0.01" min="0" required>
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][discount]" class="form-control form-control-sm discount-input"
                   data-row="${rowIndex}" step="0.01" min="0" value="0">
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][tax]" class="form-control form-control-sm tax-input"
                   data-row="${rowIndex}" step="0.01" min="0" value="0">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm row-total" id="total_${rowIndex}" readonly value="0.00">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn" onclick="removeRow(${rowIndex})">
               <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                            </svg>
            </button>
        </td>
    `;

    tbody.appendChild(row);

    // Attach event listeners
    row.querySelector('.warehouse-select').addEventListener('change', function() {
        onWarehouseChange(this);
    });

    row.querySelector('.product-select').addEventListener('change', function() {
        onProductChange(this);
    });

    row.querySelectorAll('.quantity-input, .price-input, .discount-input, .tax-input').forEach(input => {
        input.addEventListener('input', calculateRowTotal);
    });
}

function onWarehouseChange(selectElement) {
    const rowId = selectElement.dataset.row;
    const warehouseId = selectElement.value;
    const productSelect = document.querySelector(`select[name="products[${rowId}][product_id]"]`);

    // Reset product selection and stock when warehouse changes
    if (productSelect.value) {
        productSelect.value = '';
        document.getElementById(`stock_${rowId}`).value = '0';
    }
}

function onProductChange(selectElement) {
    const rowId = selectElement.dataset.row;
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const productId = selectElement.value;
    const warehouseSelect = document.querySelector(`select[name="products[${rowId}][warehouse_id]"]`);
    const warehouseId = warehouseSelect ? warehouseSelect.value : '';

    if (!warehouseId) {
        alert('Please select a warehouse first');
        selectElement.value = '';
        return;
    }

    if (productId) {
        const price = selectedOption.dataset.price;
        document.querySelector(`input[name="products[${rowId}][unit_price]"]`).value = price;
        
        // Check stock
        fetch('{{ route("invoices.check-stock") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                warehouse_id: warehouseId,
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`stock_${rowId}`).value = data.available_quantity;
            }
        })
        .catch(error => console.error('Error:', error));
        
        calculateRowTotal({ target: selectElement });
    }
}

function calculateRowTotal(event) {
    const row = event.target.closest('tr');
    const rowId = event.target.dataset.row;
    
    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
    const tax = parseFloat(row.querySelector('.tax-input').value) || 0;
    
    const amount = quantity * price;
    const total = amount - discount + tax;
    
    document.getElementById(`total_${rowId}`).value = total.toFixed(2);
    
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    
    document.querySelectorAll('#productRows tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        const discount = parseFloat(row.querySelector('.discount-input')?.value) || 0;
        const tax = parseFloat(row.querySelector('.tax-input')?.value) || 0;
        
        subtotal += (quantity * price);
        totalDiscount += discount;
        totalTax += tax;
    });
    
    const total = subtotal - totalDiscount + totalTax;
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const balanceDue = total - paidAmount;
    
    document.getElementById('subtotalDisplay').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('discountDisplay').textContent = '₹' + totalDiscount.toFixed(2);
    document.getElementById('taxDisplay').textContent = '₹' + totalTax.toFixed(2);
    document.getElementById('totalDisplay').textContent = '₹' + total.toFixed(2);
    document.getElementById('balanceDueDisplay').textContent = '₹' + balanceDue.toFixed(2);
    
    // Update payment status badge
    const statusBadge = document.getElementById('paymentStatusBadge');
    if (paidAmount >= total && total > 0) {
        statusBadge.textContent = 'Paid';
        statusBadge.className = 'badge bg-success';
    } else if (paidAmount > 0) {
        statusBadge.textContent = 'Partial';
        statusBadge.className = 'badge bg-warning';
    } else {
        statusBadge.textContent = 'Unpaid';
        statusBadge.className = 'badge bg-secondary';
    }
}

function removeRow(rowId) {
    const row = document.getElementById(`row_${rowId}`);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

// Customer Modal Handling
document.addEventListener('DOMContentLoaded', function() {
    const customerTypeRadios = document.querySelectorAll('input[name="customer_type"]');
    const customerGroupField = document.getElementById('customerGroupField');
    const groupIdSelect = document.getElementById('group_id');

    // Toggle customer group field based on type selection
    customerTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'group') {
                customerGroupField.style.display = 'block';
                groupIdSelect.required = true;
            } else {
                customerGroupField.style.display = 'none';
                groupIdSelect.required = false;
                groupIdSelect.value = '';
            }
        });
    });

    // Handle customer form submission
    const addCustomerForm = document.getElementById('addCustomerForm');
    const saveCustomerBtn = document.getElementById('saveCustomerBtn');
    const customerFormErrors = document.getElementById('customerFormErrors');
    const customerErrorList = document.getElementById('customerErrorList');

    addCustomerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Hide previous errors
        customerFormErrors.classList.add('d-none');
        customerErrorList.innerHTML = '';

        // Disable submit button
        saveCustomerBtn.disabled = true;
        saveCustomerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        const formData = new FormData(addCustomerForm);
        const customerType = formData.get('customer_type');

        // Set the appropriate route based on customer type
        const route = customerType === 'individual'
            ? '{{ route("customer.store.individual") }}'
            : '{{ route("customer.store") }}';

        fetch(route, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new customer to dropdown
                const customerSelect = document.getElementById('customer_id');
                const newOption = new Option(
                    data.customer.client_name + ' - ' + data.customer.facility_name,
                    data.customer.id,
                    true,
                    true
                );
                customerSelect.add(newOption);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                modal.hide();

                // Reset form
                addCustomerForm.reset();

                // Show success message
                alert('Customer added successfully!');
            } else {
                // Show errors
                if (data.errors) {
                    customerFormErrors.classList.remove('d-none');
                    for (let field in data.errors) {
                        data.errors[field].forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            customerErrorList.appendChild(li);
                        });
                    }
                } else if (data.message) {
                    customerFormErrors.classList.remove('d-none');
                    const li = document.createElement('li');
                    li.textContent = data.message;
                    customerErrorList.appendChild(li);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            customerFormErrors.classList.remove('d-none');
            const li = document.createElement('li');
            li.textContent = 'An error occurred. Please try again.';
            customerErrorList.appendChild(li);
        })
        .finally(() => {
            // Re-enable submit button
            saveCustomerBtn.disabled = false;
            saveCustomerBtn.innerHTML = '<i class="bx bx-save"></i> Save Customer';
        });
    });

    // Reset modal when closed
    document.getElementById('addCustomerModal').addEventListener('hidden.bs.modal', function() {
        addCustomerForm.reset();
        customerFormErrors.classList.add('d-none');
        customerErrorList.innerHTML = '';
        customerGroupField.style.display = 'block';
        groupIdSelect.required = true;
        document.getElementById('typeGroup').checked = true;
    });
});
</script>
@endsection

