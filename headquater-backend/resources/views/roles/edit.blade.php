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
                                <h5 class="card-title mb-0">Create Role</h5>
                            </div>

                            <div class="card-body">
                                <div>
                                    <label for="role_name" class="form-label">
                                        Role Name <span class="text-danger">*</span>
                                    </label>
                                    <input placeholder="Enter Role Name" name="role_name" id="role_name" required=""
                                        class="form-control" type="text" value="{{ $role->name }}">
                                </div>

                                <div class="mt-3">
                                    <h5>
                                        Permissions
                                    </h5>

                                    <div class="row g-3">
                                        {{-- Admin  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Admin
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    @php
                                                        $permissions = json_decode($role->permissions, true);
                                                    @endphp

                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Admin
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_admin"
                                                                    name="permission[view_admin]" class="form-check-input"
                                                                    id="view_admin"
                                                                    @if (isset($permissions['view_admin'])) checked @endif>
                                                                <label class="form-check-label" for="view_admin"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Profile
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_profile"
                                                                    name="permission[update_profile]"
                                                                    class="form-check-input" id="update_profile"
                                                                    @if (isset($permissions['update_profile'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_profile"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Admin
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_admin"
                                                                    name="permission[create_admin]" class="form-check-input"
                                                                    id="create_admin"
                                                                    @if (isset($permissions['create_admin'])) checked @endif>
                                                                <label class="form-check-label" for="create_admin"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Admin
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_admin"
                                                                    name="permission[update_admin]" class="form-check-input"
                                                                    id="update_admin"
                                                                    @if (isset($permissions['update_admin'])) checked @endif>
                                                                <label class="form-check-label" for="update_admin"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Admin
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_admin"
                                                                    name="permission[delete_admin]" class="form-check-input"
                                                                    id="delete_admin"
                                                                    @if (isset($permissions['delete_admin'])) checked @endif>
                                                                <label class="form-check-label" for="delete_admin"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dashboard --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Dashboard
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Dashboard
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_dashboard"
                                                                    name="permission[view_dashboard]"
                                                                    class="form-check-input" id="view_dashboard"
                                                                    @if (isset($permissions['view_dashboard'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_dashboard"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Role  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Role
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Roles
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_roles"
                                                                    name="permission[view_roles]" class="form-check-input"
                                                                    id="view_roles"
                                                                    @if (isset($permissions['view_roles'])) checked @endif>
                                                                <label class="form-check-label" for="view_roles"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Roles
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_roles"
                                                                    name="permission[create_roles]"
                                                                    class="form-check-input" id="create_roles"
                                                                    @if (isset($permissions['create_roles'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_roles"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Roles
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_roles"
                                                                    name="permission[update_roles]"
                                                                    class="form-check-input" id="update_roles"
                                                                    @if (isset($permissions['update_roles'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_roles"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Roles
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_roles"
                                                                    name="permission[delete_roles]"
                                                                    class="form-check-input" id="delete_roles"
                                                                    @if (isset($permissions['delete_roles'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_roles"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Staff  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Staff
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Staff
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_staff"
                                                                    name="permission[view_staff]" class="form-check-input"
                                                                    id="view_staff"
                                                                    @if (isset($permissions['view_staff'])) checked @endif>
                                                                <label class="form-check-label" for="view_staff"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Staff
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_staff"
                                                                    name="permission[create_staff]"
                                                                    class="form-check-input" id="create_staff"
                                                                    @if (isset($permissions['create_staff'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_staff"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Staff
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_staff"
                                                                    name="permission[update_staff]"
                                                                    class="form-check-input" id="update_staff"
                                                                    @if (isset($permissions['update_staff'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_staff"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Staff
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_staff"
                                                                    name="permission[delete_staff]"
                                                                    class="form-check-input" id="delete_staff"
                                                                    @if (isset($permissions['delete_staff'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_staff"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Master  --}}
                                        {{-- Vendor  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Vendor
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Vendor
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_vendor"
                                                                    name="permission[view_vendor]" class="form-check-input"
                                                                    id="view_vendor"
                                                                    @if (isset($permissions['view_vendor'])) checked @endif>
                                                                <label class="form-check-label" for="view_vendor"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Vendor Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_vendor-detail"
                                                                    name="permission[view_vendor-detail]"
                                                                    class="form-check-input" id="view_vendor-detail"
                                                                    @if (isset($permissions['view_vendor-detail'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_vendor-detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Vendor
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_vendor"
                                                                    name="permission[create_vendor]"
                                                                    class="form-check-input" id="create_vendor"
                                                                    @if (isset($permissions['create_vendor'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_vendor"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Vendor
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_vendor"
                                                                    name="permission[update_vendor]"
                                                                    class="form-check-input" id="update_vendor"
                                                                    @if (isset($permissions['update_vendor'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_vendor"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Vendor
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_vendor"
                                                                    name="permission[delete_vendor]]"
                                                                    class="form-check-input" id="delete_vendor"
                                                                    @if (isset($permissions['delete_vendor'])) checked @endif>
                                                                <label class="form-check-label" for="delete_vendor">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Customer  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Customer
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Customer
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_customer"
                                                                    name="permission[view_customer]"
                                                                    class="form-check-input" id="view_customer"
                                                                    @if (isset($permissions['view_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_customer"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Customer Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_customer-detail"
                                                                    name="permission[view_customer-detail]"
                                                                    class="form-check-input" id="view_customer-detail"
                                                                    @if (isset($permissions['view_customer-detail'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_customer-detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Customer
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_customer"
                                                                    name="permission[create_customer]"
                                                                    class="form-check-input" id="create_customer"
                                                                    @if (isset($permissions['create_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_customer"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Customer
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_customer"
                                                                    name="permission[update_customer]"
                                                                    class="form-check-input" id="update_customer"
                                                                    @if (isset($permissions['update_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_customer"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Customer
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_customer"
                                                                    name="permission[delete_customer]"
                                                                    class="form-check-input" id="delete_customer"
                                                                    @if (isset($permissions['delete_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_customer"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Warehouse  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Warehouse
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Warehouse
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_warehouse"
                                                                    name="permission[view_warehouse]"
                                                                    class="form-check-input" id="view_warehouse"
                                                                    @if (isset($permissions['view_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_warehouse"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Warehouse Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_warehouse-detail"
                                                                    name="permission[view_warehouse-detail]"
                                                                    class="form-check-input" id="view_warehouse-detail"
                                                                    @if (isset($permissions['view_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_warehouse-detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Warehouse
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_warehouse"
                                                                    name="permission[create_warehouse]"
                                                                    class="form-check-input" id="create_warehouse"
                                                                    @if (isset($permissions['create_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_warehouse"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Warehouse
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_warehouse"
                                                                    name="permission[update_warehouse]"
                                                                    class="form-check-input" id="update_warehouse"
                                                                    @if (isset($permissions['update_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_warehouse"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Warehouse
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_warehouse"
                                                                    name="permission[delete_warehouse]"
                                                                    class="form-check-input" id="delete_warehouse"
                                                                    @if (isset($permissions['delete_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_warehouse"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Products  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Products
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Products
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_product"
                                                                    name="permission[view_product]"
                                                                    class="form-check-input" id="view_product"
                                                                    @if (isset($permissions['view_product'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_product"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Products
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_product"
                                                                    name="permission[create_product]"
                                                                    class="form-check-input" id="create_product"
                                                                    @if (isset($permissions['create_product'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_product"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Products
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_product"
                                                                    name="permission[update_product]"
                                                                    class="form-check-input" id="update_product"
                                                                    @if (isset($permissions['update_product'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_product"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Products
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_product"
                                                                    name="permission[delete_product]"
                                                                    class="form-check-input" id="delete_product"
                                                                    @if (isset($permissions['delete_product'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_product"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Purchase Order  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Purchase Order
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Purchase Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_order"
                                                                    name="permission[view_order]" class="form-check-input"
                                                                    id="view_order"
                                                                    @if (isset($permissions['view_order'])) checked @endif>
                                                                <label class="form-check-label" for="view_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                               View Purchase Order Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_order"
                                                                    name="permission[view_order]" class="form-check-input"
                                                                    id="view_order"
                                                                    @if (isset($permissions['view_order'])) checked @endif>
                                                                <label class="form-check-label" for="view_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Purchase Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_order"
                                                                    name="permission[create_order]"
                                                                    class="form-check-input" id="create_order"
                                                                    @if (isset($permissions['create_order'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Purchase Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_order"
                                                                    name="permission[update_order]"
                                                                    class="form-check-input" id="update_order"
                                                                    @if (isset($permissions['update_order'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Purchase Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_order"
                                                                    name="permission[delete_order]"
                                                                    class="form-check-input" id="delete_order"
                                                                    @if (isset($permissions['delete_order'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sales  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Sales
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Sale
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_sale"
                                                                    name="permission[view_sale]" class="form-check-input"
                                                                    id="view_sale"
                                                                    @if (isset($permissions['view_sale'])) checked @endif>
                                                                <label class="form-check-label" for="view_sale"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Sale Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_sale-detail"
                                                                    name="permission[view_sale-detail]"
                                                                    class="form-check-input" id="view_sale-detail"
                                                                    @if (isset($permissions['view_sale-detail'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_sale-detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Sale
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_sale"
                                                                    name="permission[create_sale]"
                                                                    class="form-check-input" id="create_sale"
                                                                    @if (isset($permissions['create_sale'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_sale"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Sale
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_sale"
                                                                    name="permission[update_sale]"
                                                                    class="form-check-input" id="update_sale"
                                                                    @if (isset($permissions['update_sale'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_sale"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Sale
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_sale"
                                                                    name="permission[delete_sale]"
                                                                    class="form-check-input" id="delete_sale"
                                                                    @if (isset($permissions['delete_sale'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_sale"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Invoices  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Invoices
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Invoice
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_invoice"
                                                                    name="permission[view_invoice]" class="form-check-input"
                                                                    id="view_invoice"
                                                                    @if (isset($permissions['view_invoice'])) checked @endif>
                                                                <label class="form-check-label" for="view_invoice"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Invoice Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_invoice-detail"
                                                                    name="permission[view_invoice-detail]"
                                                                    class="form-check-input" id="view_invoice-detail"
                                                                    @if (isset($permissions['view_invoice-detail'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_invoice-detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Invoice
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_invoice"
                                                                    name="permission[create_invoice]"
                                                                    class="form-check-input" id="create_invoice"
                                                                    @if (isset($permissions['create_invoice'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_invoice"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Invoice
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_invoice"
                                                                    name="permission[update_invoice]"
                                                                    class="form-check-input" id="update_invoice"
                                                                    @if (isset($permissions['update_invoice'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_invoice"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Invoice
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_invoice"
                                                                    name="permission[delete_invoice]"
                                                                    class="form-check-input" id="delete_invoice"
                                                                    @if (isset($permissions['delete_invoice'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_invoice"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Received Products --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Received Products
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Received Products
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_dashboard"
                                                                    name="permission[view_dashboard]"
                                                                    class="form-check-input" id="view_dashboard"
                                                                    @if (isset($permissions['view_dashboard'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_dashboard"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success waves ripple-light" id="add-btn">
                                        Add
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
