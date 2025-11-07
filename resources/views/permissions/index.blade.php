@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Permission Groups</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <button type="button" class="btn btn-danger px-4" id="delete-selected" style="display:none;">
                                    <i class="bi bi-trash me-2"></i>Delete Selected
                                </button>
                                <a href="{{ route('permission.create') }}" class="btn btn-primary px-4">
                                    <i class="bi bi-plus-lg me-2"></i>Add Permission Group
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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

            @forelse($permissionGroups as $group)
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input group-checkbox" type="checkbox" name="ids[]" value="{{ $group->id }}" style="width: 20px; height: 20px;">
                                <div>
                                    <h5 class="mb-0">{{ $group->name }}</h5>
                                    @if($group->description)
                                        <small class="text-muted">{{ $group->description }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $group->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $group->status ? 'Active' : 'Inactive' }}
                                </span>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                           data-id="{{ $group->id }}"
                                           {{ $group->status ? 'checked' : '' }}
                                           style="width: 40px; height: 20px;">
                                </div>
                                <a href="{{ route('permission.edit', $group->id) }}" class="btn btn-sm btn-info">
                                    <i class="bx bx-pencil"></i> Edit
                                </a>
                                <form action="{{ route('permission.destroy', $group->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure? This will delete all permissions in this group.')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Permission Name</th>
                                        <th style="width:120px;">Guard</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($group->permissions as $index => $permission)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td><span class="badge bg-primary">{{ $permission->guard_name }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">No permissions in this group</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="text-center text-muted py-5">
                            <i class="bx bx-folder-open" style="font-size: 48px;"></i>
                            <p class="mt-3">No permission groups found</p>
                            <a href="{{ route('permission.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Create Your First Permission Group
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </main>
    <!--end main wrapper-->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle select all checkboxes
            const groupCheckboxes = document.querySelectorAll('.group-checkbox');
            const deleteSelectedBtn = document.getElementById('delete-selected');

            groupCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const anyChecked = Array.from(groupCheckboxes).some(cb => cb.checked);
                    deleteSelectedBtn.style.display = anyChecked ? 'inline-block' : 'none';
                });
            });

            // Handle delete selected
            deleteSelectedBtn.addEventListener('click', function() {
                const selectedIds = Array.from(groupCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selectedIds.length === 0) {
                    alert('Please select at least one permission group');
                    return;
                }

                if (confirm(`Are you sure you want to delete ${selectedIds.length} permission group(s)? This will also delete all permissions in these groups.`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("delete.selected.permission") }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Handle status toggle
            const statusToggles = document.querySelectorAll('.status-toggle');
            statusToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const groupId = this.dataset.id;
                    const status = this.checked ? 1 : 0;

                    fetch('{{ route("permission.toggleStatus") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: groupId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to update status');
                            this.checked = !this.checked;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred');
                        this.checked = !this.checked;
                    });
                });
            });
        });
    </script>
@endsection