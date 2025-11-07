@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb mb-3">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Permission Groups</a></li>
                        <li class="breadcrumb-item active">Create</li>
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

            <form action="{{ route('permission.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Create Permission Group</h5>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="group_name" class="form-label">
                                Group Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="group_name" id="group_name"
                                   class="form-control @error('group_name') is-invalid @enderror"
                                   placeholder="Enter Group Name (e.g., Staff Management)"
                                   value="{{ old('group_name') }}" required>
                            @error('group_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter group description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label mb-0">
                                    Permissions <span class="text-danger">*</span>
                                </label>
                                <button type="button" class="btn btn-sm btn-primary" id="add-permission">
                                    <i class="bx bx-plus"></i> Add Permission
                                </button>
                            </div>
                            <small class="text-muted">Add permissions that belong to this group</small>
                        </div>

                        <div id="permissions-container">
                            <div class="permission-row mb-2">
                                <div class="input-group">
                                    <input type="text" name="permissions[]"
                                           class="form-control @error('permissions.0') is-invalid @enderror"
                                           placeholder="Enter permission name (e.g., View Staffs)"
                                           value="{{ old('permissions.0') }}" required>
                                    <button type="button" class="btn btn-danger remove-permission" disabled>
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                                @error('permissions.0')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="bx bx-save"></i> Create Permission Group
                            </button>
                            <a href="{{ route('permission.index') }}" class="btn btn-secondary">
                                <i class="bx bx-x"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <!--end main wrapper-->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const permissionsContainer = document.getElementById('permissions-container');
            const addPermissionBtn = document.getElementById('add-permission');

            addPermissionBtn.addEventListener('click', function() {
                const permissionRow = document.createElement('div');
                permissionRow.className = 'permission-row mb-2';
                permissionRow.innerHTML = `
                    <div class="input-group">
                        <input type="text" name="permissions[]" class="form-control"
                               placeholder="Enter permission name" required>
                        <button type="button" class="btn btn-danger remove-permission">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                `;
                permissionsContainer.appendChild(permissionRow);
                updateRemoveButtons();
            });

            permissionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-permission')) {
                    e.target.closest('.permission-row').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const rows = permissionsContainer.querySelectorAll('.permission-row');
                rows.forEach((row, index) => {
                    const removeBtn = row.querySelector('.remove-permission');
                    removeBtn.disabled = rows.length === 1;
                });
            }
        });
    </script>
@endsection