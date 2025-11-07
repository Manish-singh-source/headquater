@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">
                                        Staff Details
                                    </h5>
                                </div>
                                <div>
                                    <a href="{{ url()->previous() }}" class="btn btn-primary float-end mt-n1">Back</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="list-group list-group-flush">

                                <li
                                    class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Name :
                                    </span>
                                    <span>
                                        {{ $staff->fname }}
                                    </span>
                                </li>

                                <li
                                    class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Role :
                                    </span>
                                    <span>
                                        @if ($staff->roles->isEmpty())
                                            Not Assigned
                                        @else
                                            {{ $staff->roles[0]?->name }}
                                        @endif
                                    </span>
                                </li>

                                <li
                                    class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Status :
                                    </span>
                                    <span class="badge bg-success-subtle text-success fw-semibold">
                                        {{ $staff->status == '1' ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>

                                <li
                                    class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Contact no :
                                    </span>
                                    <span>
                                        {{ $staff->phone }}
                                    </span>
                                </li>

                                <li
                                    class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">E-mail :
                                    </span>
                                    <span>
                                        {{ $staff->email }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <div class="d-flex">
                                <h5 class="card-title flex-grow-1 mb-0">
                                    Role Permissions
                                </h5>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="mt-3">
                                <small class="text-muted">Permissions assigned to this staff member (grouped by category)</small>

                                <div class="row g-3 mt-2">
                                    @forelse($permissionGroups as $group)
                                        <div class="col-xl-12">
                                            <div class="border rounded p-3">
                                                <div class="mb-3">
                                                    <h6 class="mb-0">
                                                        <i class="bx bx-folder me-2"></i>{{ $group->name }}
                                                        @if($group->description)
                                                            <small class="text-muted d-block mt-1">{{ $group->description }}</small>
                                                        @endif
                                                    </h6>
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
                                                                        class="form-check-input"
                                                                        id="permission-{{ $permission->id }}"
                                                                        {{ $staffPermissions->contains('name', $permission->name) ? 'checked' : '' }}
                                                                        disabled>
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
                                                No permission groups found.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection