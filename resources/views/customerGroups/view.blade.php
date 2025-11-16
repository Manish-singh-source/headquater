@extends('layouts.master')

@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.groups.index') }}">Customer Groups</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $customerGroup->name }}</li>
                            <li class="hidden" style="display:none" id="customerGroupId">{{ $customerGroup->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <a href="{{ route('customer.groups.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                                <a type="button" class="btn border-2 border-primary" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop1">
                                    Add Customer(Bulk)
                                </a>
                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('customer.store.bulk', $customerGroup->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Customers</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="alert alert-info mb-3">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        <strong>Download Template:</strong>
                                                        <a href="{{ asset('uploads/excel-formats/customers-bulk.xlsx') }}"
                                                            download="customers-bulk.xlsx" class="alert-link">
                                                            Click here to download the Excel format template
                                                        </a>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label for="csv_file" class="form-label">Customers
                                                            List (XLSX/XLS) <span class="text-danger">*</span></label>
                                                        <input type="file" name="csv_file" id="csv_file"
                                                            class="form-control" accept=".xlsx,.xls" required="">
                                                        <small class="text-muted">Please upload an Excel file (.xlsx or
                                                            .xls) with customer data. Make sure the first row contains
                                                            column headers including "Facility Name".</small>
                                                    </div>
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

                                <a href="{{ route('customer.create', $customerGroup->id) }}"><button
                                        class="btn border-2 border-primary"><i class="bi bi-plus-lg me-2"></i>Add
                                        Customer(Single)</button></a>
                                <div class="ms-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary">Action</button>
                                        <button type="button"
                                            class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item cursor-pointer" id="activate-selected">Activate
                                                Selected</a>
                                            <a class="dropdown-item cursor-pointer" id="deactivate-selected">Deactivate
                                                Selected</a>
                                            <a class="dropdown-item cursor-pointer" id="delete-selected">Delete Selected</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.errors')

            <!-- Statistics Cards -->
            <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Total Customers</p>
                                    <h4 class="mb-0">{{ $customerGroup->customers_count ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                            <i class="bx bx-group"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Active Customers</p>
                                    <h4 class="mb-0 text-success">{{ $customerGroup->active_customers_count ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                            <i class="bx bx-check-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Inactive Customers</p>
                                    <h4 class="mb-0 text-secondary">{{ $customerGroup->inactive_customers_count ?? 0 }}
                                    </h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-3">
                                            <i class="bx bx-x-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-4">
                <div class="card-body">

                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
                                href="{{ route('customer.groups.view', ['status' => 'all', 'id' => $customerGroup->id]) }}">
                                All Customers
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'active' ? 'active' : '' }}"
                                href="{{ route('customer.groups.view', ['status' => 'active', 'id' => $customerGroup->id]) }}">
                                Active
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'inactive' ? 'active' : '' }}"
                                href="{{ route('customer.groups.view', ['status' => 'inactive', 'id' => $customerGroup->id]) }}">
                                Inactive
                            </a>
                        </li>
                    </ul>

                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped cell-border">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Facility&nbsp;Name</th>
                                        <th>Client&nbsp;Name</th>
                                        <th>Contact&nbsp;Name</th>
                                        <th>Email</th>
                                        <th>Contact&nbsp;Number</th>
                                        <th>GSTIN</th>
                                        <th>PAN</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customerGroup->customers as $customer)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $customer->id }}">
                                            </td>
                                            <td>
                                                <span class="mb-0 customer-name fw-bold">
                                                    {{ $customer->facility_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="mb-0 customer-name fw-bold">
                                                    {{ $customer->client_name }}</span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);"
                                                    class="font-text1">{{ $customer->contact_name }}</a>
                                            </td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->contact_no }}</td>
                                            <td>{{ $customer->gstin }}</td>
                                            <td>{{ $customer->pan }}</td>
                                            <td>
                                                <div class="form-switch form-check-success">
                                                    <input class="form-check-input customer-status-switch" type="checkbox"
                                                        role="switch" data-customer-id="{{ $customer->id }}"
                                                        {{ $customer->status == '1' ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor"
                                                        href="{{ route('customer.detail', $customer->id) }}"
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

                                                    <a aria-label="anchor"
                                                        href="{{ route('customer.edit', ['id' => $customer->id, 'group_id' => $customerGroup->id]) }}"
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

                                                    <form action="{{ route('customer.delete', $customer->id) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" aria-label="anchor"
                                                            class="btn btn-icon btn-sm bg-danger-subtle delete-row"
                                                            data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-trash-2 text-danger">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                                <line x1="10" y1="11" x2="10"
                                                                    y2="17"></line>
                                                                <line x1="14" y1="11" x2="14"
                                                                    y2="17"></line>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Customer status toggle
        $(document).on('change', '.customer-status-switch', function() {
            var customerId = $(this).data('customer-id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('customer.toggleStatus') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: customerId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Failed to update status.');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Status update failed!');
                    location.reload();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            const groupId = $('#customerGroupId').text();

            // Select All
            $('#select-all').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            // Function to get selected IDs
            function getSelected() {
                return $('.row-checkbox:checked').map(function() {
                    return this.value;
                }).get();
            }

            // Function to submit form
            function submitForm(action, data) {
                let form = $('<form>', {
                    method: 'POST',
                    action: action
                });

                form.append(`@csrf`);
                $.each(data, function(key, value) {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: key,
                        value: value
                    }));
                });

                $('body').append(form);
                form.submit();
            }

            // Activate Selected
            $('#activate-selected').on('click', function() {
                const selected = getSelected();

                if (selected.length === 0) {
                    alert('Please select at least one customer.');
                    return;
                }

                if (confirm('Are you sure you want to activate selected customers?')) {
                    submitForm("{{ route('customer.bulkStatusChange') }}", {
                        ids: selected.join(','),
                        status: 1
                    });
                }
            });

            // Deactivate Selected
            $('#deactivate-selected').on('click', function() {
                const selected = getSelected();

                if (selected.length === 0) {
                    alert('Please select at least one customer.');
                    return;
                }

                if (confirm('Are you sure you want to deactivate selected customers?')) {
                    submitForm("{{ route('customer.bulkStatusChange') }}", {
                        ids: selected.join(','),
                        status: 0
                    });
                }
            });

            // Delete Selected
            $('#delete-selected').on('click', function() {
                const selected = getSelected();

                if (selected.length === 0) {
                    alert('Please select at least one customer.');
                    return;
                }

                if (confirm('Are you sure you want to delete selected customers?')) {
                    submitForm("{{ route('delete.selected.customers') }}", {
                        _method: 'DELETE',
                        ids: selected.join(','),
                        groupId: groupId
                    });
                }
            });

        });
    </script>
@endsection
