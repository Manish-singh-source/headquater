@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Permissions List</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <a href="{{ route('permission.create') }}" class="btn btn-primary px-4"><i
                                        class="bi bi-plus-lg me-2"></i>Add
                                    Permission</a>
                                <div class="ms-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary">Action</button>
                                        <button type="button"
                                            class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item cursor-pointer" id="delete-selected-permissions">Delete All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Permission Statistics Card -->
            <div class="row mt-3">
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="card rounded-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bx bx-lock-alt fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-secondary">Total Permissions</p>
                                    <h4 class="mb-0 fw-bold">{{ $permissions->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permissions as $permission)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $permission->id }}">
                                            </td>
                                            <td>
                                                <a class="d-flex align-items-center gap-3" href="#">
                                                    <p class="mb-0 customer-name fw-bold">{{ $permission->name }}</p>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor" href="{{ route('permission.edit', $permission->id) }}"
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
                                                    <form action="{{ route('permission.destroy', $permission->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure?')">
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
                                            <td class="text-center" colspan="5">No Roles Found</td>
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
    <!--end main wrapper-->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Select all checkboxes
        $('#select-all').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Update select-all checkbox when individual checkboxes change
        $('.row-checkbox').on('change', function() {
            if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });

        // Bulk delete permissions
        $('#delete-selected-permissions').on('click', function() {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one permission to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' permission(s)?')) {
                $.ajax({
                    url: '{{ route('delete.selected.permission') }}',
                    type: 'DELETE',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message || 'Error deleting permissions');
                        }
                    },
                    error: function(xhr) {
                        alert('Error deleting permissions: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            }
        });
    });
</script>
@endsection