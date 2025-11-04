@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customers</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                    <i class="bi bi-plus-lg me-2"></i>Create Customer
                                </button>
                                <div class="ms-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary">Action</button>
                                        <button type="button"
                                            class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <button class="dropdown-item cursor-pointer" type="button"
                                                id="openBulkStatusModal">Change Status</button>
                                            <a class="dropdown-item cursor-pointer" id="delete-selected">Delete All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <ul class="nav nav-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('customer.index') }}" class="nav-link {{ is_null($status) ? 'active' : '' }}">
                                All
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="{{ route('customer.index', ['status' => 1]) }}"
                                class="nav-link {{ $status === '1' ? 'active' : '' }}">
                                Active
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="{{ route('customer.index', ['status' => 0]) }}"
                                class="nav-link {{ $status === '0' ? 'active' : '' }}">
                                Inactive
                            </a>
                        </li>
                    </ul>

                    <div class="customer-table mt-3">
                        <div class="table-responsive white-space-nowrap">
                            <table id="customerTable" class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>SR.No</th>
                                        <th>Facility Name</th>
                                        <th>Client Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Customer Group</th>
                                        <th>Joined At</th>
                                        <th>Status</th>
                                        <th style="width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $key => $customer)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $customer->id }}">
                                            </td>
                                            <td>{{ $customers->firstItem() + $key }}</td>
                                            <td>
                                                <a class="d-flex align-items-center gap-3"
                                                    href="{{ route('customer.detail', $customer->id) }}">
                                                    <p class="mb-0 customer-name fw-bold">{{ $customer->facility_name }}</p>
                                                </a>
                                            </td>
                                            <td>{{ $customer->client_name }}</td>
                                            <td>
                                                <a href="mailto:{{ $customer->email }}" class="font-text1">{{ $customer->email }}</a>
                                            </td>
                                            <td>{{ $customer->contact_no }}</td>
                                            <td>
                                                @if($customer->groupInfo && $customer->groupInfo->customerGroup)
                                                    <span class="badge bg-info">{{ $customer->groupInfo->customerGroup->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Individual</span>
                                                @endif
                                            </td>
                                            <td>{{ $customer->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="form-switch form-check-success">
                                                    <input class="form-check-input status-switch" type="checkbox"
                                                        role="switch" data-customer-id="{{ $customer->id }}"
                                                        {{ $customer->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor" href="{{ route('customer.detail', $customer->id) }}"
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

                                                    <a aria-label="anchor" href="{{ route('customer.edit', [$customer->id, $customer->groupInfo->customer_group_id ?? 0]) }}"
                                                        class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-edit text-warning">
                                                            <path
                                                                d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                            </path>
                                                            <path
                                                                d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                            </path>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('customer.delete', $customer->id) }}" method="POST"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" aria-label="button"
                                                            class="btn btn-icon btn-sm bg-danger-subtle delete-btn"
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
                                                                <line x1="10" y1="11" x2="10" y2="17">
                                                                </line>
                                                                <line x1="14" y1="11" x2="14" y2="17">
                                                                </line>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="bx bx-user-x" style="font-size: 48px;"></i>
                                                <p class="mt-2">No customers found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries
                            </div>
                            <div>
                                {{ $customers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Customer Modal -->
        <div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
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

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Facility Name <span class="text-danger">*</span></label>
                                    <input type="text" name="facility_name" id="facility_name" class="form-control" placeholder="Enter Facility Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" name="client_name" id="client_name" class="form-control" placeholder="Enter Client Name" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" id="contact_name" class="form-control" placeholder="Enter Contact Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Enter 10 digit number" maxlength="10" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GSTIN <span class="text-danger">*</span></label>
                                    <input type="text" name="gstin" id="gstin" class="form-control" placeholder="Enter GSTIN" maxlength="15" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN <span class="text-danger">*</span></label>
                                    <input type="text" name="pan" id="pan" class="form-control" placeholder="Enter PAN" maxlength="10" required>
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
    </main>
    <!--end main wrapper-->

    <script>
    // Customer Modal Handling
    document.addEventListener('DOMContentLoaded', function() {
        const addCustomerForm = document.getElementById('addCustomerForm');
        const saveCustomerBtn = document.getElementById('saveCustomerBtn');
        const customerFormErrors = document.getElementById('customerFormErrors');
        const customerErrorList = document.getElementById('customerErrorList');

        // Handle customer form submission
        addCustomerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Hide previous errors
            customerFormErrors.classList.add('d-none');
            customerErrorList.innerHTML = '';

            // Disable submit button
            saveCustomerBtn.disabled = true;
            saveCustomerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            const formData = new FormData(addCustomerForm);

            fetch('{{ route("customer.store.individual") }}', {
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
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                    modal.hide();

                    // Reset form
                    addCustomerForm.reset();

                    // Reload page to show new customer
                    window.location.reload();
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
        });

        // Status toggle
        document.querySelectorAll('.status-switch').forEach(function(switchElement) {
            switchElement.addEventListener('change', function() {
                const customerId = this.getAttribute('data-customer-id');
                const status = this.checked ? 1 : 0;

                fetch('{{ route("customer.toggleStatus") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: customerId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        this.checked = !this.checked;
                        alert('Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked;
                    alert('An error occurred');
                });
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this customer?')) {
                    this.closest('form').submit();
                }
            });
        });

        // Select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Delete selected
        document.getElementById('delete-selected').addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one customer to delete');
                return;
            }

            if (confirm(`Are you sure you want to delete ${selectedIds.length} customer(s)?`)) {
                fetch('{{ route("delete.selected.customers") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to delete customers');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        });

        // Bulk status change
        document.getElementById('openBulkStatusModal').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one customer');
                return;
            }

            const status = confirm('Click OK to activate, Cancel to deactivate') ? 1 : 0;

            fetch('{{ route("customer.bulkStatusChange") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selectedIds,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        });
    });
    </script>
@endsection
