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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Invoice Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->client_name }} - {{ $customer->facility_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Warehouse <span class="text-danger">*</span></label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                            <th style="width: 25%;">Product <span class="text-danger">*</span></th>
                                            <th style="width: 10%;">Stock</th>
                                            <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                                            <th style="width: 12%;">Rate <span class="text-danger">*</span></th>
                                            <th style="width: 10%;">Discount</th>
                                            <th style="width: 10%;">Tax</th>
                                            <th style="width: 13%;">Total</th>
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

                <div class="col-lg-4">
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
    </div>
</main>

<script>
let rowIndex = 0;
const products = @json($products);

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
            <select name="products[${rowIndex}][product_id]" class="form-select form-select-sm product-select" data-row="${rowIndex}" required>
                <option value="">Select Product</option>
                ${products.map(p => `<option value="${p.id}" data-sku="${p.sku}" data-price="${p.mrp}">${p.product_name} (${p.sku})</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm stock-display" id="stock_${rowIndex}" readonly placeholder="0">
        </td>
        <td>
            <input type="number" name="products[${rowIndex}][quantity]" class="form-control form-control-sm quantity-input" 
                   data-row="${rowIndex}" step="0.01" min="0.01" required>
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
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(${rowIndex})">
                <i class="bx bx-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Attach event listeners
    row.querySelector('.product-select').addEventListener('change', function() {
        onProductChange(this);
    });
    
    row.querySelectorAll('.quantity-input, .price-input, .discount-input, .tax-input').forEach(input => {
        input.addEventListener('input', calculateRowTotal);
    });
}

function onProductChange(selectElement) {
    const rowId = selectElement.dataset.row;
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const productId = selectElement.value;
    const warehouseId = document.getElementById('warehouse_id').value;
    
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
</script>
@endsection

