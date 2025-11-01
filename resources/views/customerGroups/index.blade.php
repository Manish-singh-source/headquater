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
                            <li class="breadcrumb-item active" aria-current="page">Customer Groups List</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <a href="{{ route('customer.groups.create') }}" class="btn btn-primary px-4"><i
                                        class="bi bi-plus-lg me-2"></i>Create Group</a>
                                <div class="ms-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn border-2 border-primary">Action</button>
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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Status Filter Tabs -->
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
                                href="{{ route('customer.groups.index', ['status' => 'all']) }}">
                                All Groups
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'active' ? 'active' : '' }}"
                                href="{{ route('customer.groups.index', ['status' => 'active']) }}">
                                Active
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $status === 'inactive' ? 'active' : '' }}"
                                href="{{ route('customer.groups.index', ['status' => 'inactive']) }}">
                                Inactive
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Customer Groups Cards -->
            {{-- 
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3 mb-4">
                @forelse ($customerGroups as $group)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0 fw-bold">{{ $group->name }}</h6>
                                    <span class="badge {{ $group->status == '1' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $group->status == '1' ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Customers:</span>
                                        <span class="fw-bold">{{ $group->total_customers ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Active:</span>
                                        <span class="text-success fw-bold">{{ $group->active_customers ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Inactive:</span>
                                        <span class="text-secondary fw-bold">{{ $group->inactive_customers ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('customer.groups.view', $group->id) }}"
                                        class="btn btn-sm btn-primary flex-fill">
                                        <i class="bx bx-show me-1"></i>View
                                    </a>
                                    <a href="{{ route('customer.groups.edit', $group->id) }}"
                                        class="btn btn-sm btn-warning flex-fill">
                                        <i class="bx bx-edit me-1"></i>Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <p class="text-muted mb-0">No customer groups found</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div> 
            --}}

            <!-- Customer Groups Table -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Sr.No</th>
                                        <th>Group Name</th>
                                        <th>Total Customers</th>
                                        <th>Active</th>
                                        <th>Inactive</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th style="width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customerGroups as $key => $group)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $group->id }}">
                                            </td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a class="d-flex align-items-center gap-3" href="#">
                                                    <p class="mb-0 customer-name fw-bold">
                                                        {{ $group->name }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td>{{ $group->total_customers ?? 0 }}</td>
                                            <td><span class="badge bg-success">{{ $group->active_customers ?? 0 }}</span>
                                            </td>
                                            <td><span
                                                    class="badge bg-secondary">{{ $group->inactive_customers ?? 0 }}</span>
                                            </td>
                                            <td>
                                                {{ $group->created_at->format('d-M-Y') }}
                                            </td>
                                            <td>
                                                <div class="form-switch form-check-success">
                                                    <input class="form-check-input status-switch" type="checkbox"
                                                        role="switch" data-customer-id="{{ $group->id }}"
                                                        {{ $group->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor"
                                                        href="{{ route('customer.groups.view', $group->id) }}"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="View">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-eye text-primary">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                            </path>
                                                            <circle cx="12" cy="12" r="3">
                                                            </circle>
                                                        </svg>
                                                    </a>

                                                    <a aria-label="anchor"
                                                        href="{{ route('customer.groups.edit', $group->id) }}"
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
                                                    {{-- <a aria-label="anchor"
                                                            href="{{ route('customer.edit', ['id' => $customer->customer->id, 'group_id' => $customer->customer_group_id]) }}"
                                                            class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-edit text-warning">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                </path>
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                </path>
                                                            </svg>
                                                        </a> --}}

                                                    <form action="{{ route('customer.groups.destroy', $group->id) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm bg-danger-subtle delete-row">
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
        $(document).on('change', '.status-switch', function() {
            var customerId = $(this).data('customer-id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('customer.groups.toggleStatus') }}',
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
        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            // Activate Selected functionality
            document.getElementById('activate-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length === 0) {
                    alert('Please select at least one customer group.');
                    return;
                }
                if (confirm('Are you sure you want to activate selected customer groups?')) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('customer.groups.bulkStatusChange') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                        <input type="hidden" name="status" value="1">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Deactivate Selected functionality
            document.getElementById('deactivate-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length === 0) {
                    alert('Please select at least one customer group.');
                    return;
                }
                if (confirm('Are you sure you want to deactivate selected customer groups?')) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('customer.groups.bulkStatusChange') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                        <input type="hidden" name="status" value="0">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Delete Selected functionality
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length === 0) {
                    alert('Please select at least one customer group.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected customer groups?')) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('delete.selected.customers.group') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection
