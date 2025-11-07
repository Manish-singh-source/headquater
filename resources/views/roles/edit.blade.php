@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <form action="{{ route('role.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">Edit Role</h5>
                            </div>

                            <div class="card-body">
                                <div>
                                    <label for="role_name" class="form-label">
                                        Role Name <span class="text-danger">*</span>
                                    </label>
                                    <input placeholder="Enter Role Name" name="name" id="role_name" required=""
                                        class="form-control" type="text" value="{{ $role->name }}">
                                </div>

                                <div class="mt-3">
                                    <h5>Permissions</h5>
                                    <small class="text-muted">Select permissions for this role (grouped by category)</small>

                                    <div class="row g-3 mt-2">
                                        @forelse($permissionGroups as $group)
                                            <div class="col-xl-12">
                                                <div class="border rounded p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="mb-0">
                                                            <i class="bx bx-folder me-2"></i>{{ $group->name }}
                                                            @if($group->description)
                                                                <small class="text-muted d-block mt-1">{{ $group->description }}</small>
                                                            @endif
                                                        </h6>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input select-all-group"
                                                                   data-group="{{ $group->id }}"
                                                                   id="select-all-{{ $group->id }}">
                                                            <label class="form-check-label" for="select-all-{{ $group->id }}">
                                                                Select All
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        @forelse($group->permissions as $permission)
                                                            <div class="col-md-6">
                                                                <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                                    <label class="mb-0">
                                                                        {{ ucfirst($permission->name) }}
                                                                    </label>
                                                                    <div class="form-check form-switch">
                                                                        <input type="checkbox" value="{{ $permission->name }}"
                                                                            name="permissions[]" class="form-check-input permission-checkbox"
                                                                            data-group="{{ $group->id }}"
                                                                            id="permission-{{ $permission->id }}"
                                                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="permission-{{ $permission->id }}"></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="col-12">
                                                                <p class="text-muted mb-0">No permissions in this group</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    No permission groups found. Please create permission groups first.
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success waves ripple-light" id="add-btn">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </main>
    <!--end main wrapper-->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle select all for each group
            document.querySelectorAll('.select-all-group').forEach(selectAll => {
                selectAll.addEventListener('change', function() {
                    const groupId = this.dataset.group;
                    const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                // Update select all state based on individual checkboxes
                const groupId = selectAll.dataset.group;
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        selectAll.checked = allChecked;
                        selectAll.indeterminate = anyChecked && !allChecked;
                    });
                });

                // Initialize select all state
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = anyChecked && !allChecked;
            });
        });
    </script>
@endsection