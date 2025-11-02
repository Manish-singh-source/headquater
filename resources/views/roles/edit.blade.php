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
                                    <h5>
                                        Permissions
                                    </h5>

                                    <div class="row g-3">
                                        {{-- Dashboard --}}
                                        <div class="col-xl-12">
                                            <div class="border rounded p-3">
                                                <div class="row g-3">

                                                    @foreach ($permissions as $permission)
                                                        <div class="col-md-6">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                                <label class="mb-0">
                                                                    {{ ucfirst($permission->name) }}
                                                                </label>
                                                                <div class="form-check form-switch">
                                                                    <input type="checkbox" value="{{ $permission->name }}"
                                                                        name="permissions[]" class="form-check-input"
                                                                        id="permission-{{ $permission->id }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : ''  }}>
                                                                    <label class="form-check-label"
                                                                        for="permission-{{ $permission->id }}"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>

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
@endsection