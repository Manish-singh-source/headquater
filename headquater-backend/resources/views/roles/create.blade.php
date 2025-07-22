@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <form action="{{ route('role.store') }}" method="POST">
                @csrf 
                @method('POST')

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
                                    <input placeholder="Enter Role Name" name="role_name" value="" id="role_name"
                                        required="" class="form-control" type="text">
                                </div>

                                <div class="mt-3">
                                    <h5>
                                        Permissions
                                    </h5>

                                    <div class="row g-3">
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
                                                                    class="form-check-input" id="view_dashboard">
                                                                <label class="form-check-label"
                                                                    for="view_dashboard"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Dashboard Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_dashboard_detail"
                                                                    name="permission[view_dashboard_detail]"
                                                                    class="form-check-input" id="view_dashboard_detail">
                                                                <label class="form-check-label"
                                                                    for="view_dashboard_detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Dashboard Tables
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_dashboard_table"
                                                                    name="permission[view_dashboard_table]"
                                                                    class="form-check-input" id="view_dashboard_table">
                                                                <label class="form-check-label"
                                                                    for="view_dashboard_table"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Admin  --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Admin
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    

                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Admin
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_admin"
                                                                    name="permission[view_admin]" class="form-check-input"
                                                                    id="view_admin">
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
                                                                    class="form-check-input" id="update_profile">
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
                                                                    id="create_admin">
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
                                                                    id="update_admin">
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
                                                                    id="delete_admin">
                                                                <label class="form-check-label" for="delete_admin"></label>
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
                                                                    id="view_staff">
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
                                                                    class="form-check-input" id="create_staff">
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
                                                                    class="form-check-input" id="update_staff">
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
                                                                    class="form-check-input" id="delete_staff">
                                                                <label class="form-check-label"
                                                                    for="delete_staff"></label>
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
                                                                    id="view_roles">
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
                                                                    class="form-check-input" id="create_roles">
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
                                                                    class="form-check-input" id="update_roles">
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
                                                                    class="form-check-input" id="delete_roles">
                                                                <label class="form-check-label"
                                                                    for="delete_roles"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Master  --}}
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
                                                                    class="form-check-input" id="view_customer">
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
                                                                    >
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
                                                                    class="form-check-input" id="create_customer">
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
                                                                    class="form-check-input" id="update_customer">
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
                                                                    class="form-check-input" id="delete_customer">
                                                                <label class="form-check-label"
                                                                    for="delete_customer"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

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
                                                                    id="view_vendor">
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
                                                                    >
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
                                                                    class="form-check-input" id="create_vendor">
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
                                                                    class="form-check-input" id="update_vendor">
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
                                                                    class="form-check-input" id="delete_vendor">
                                                                <label class="form-check-label" for="delete_vendor">
                                                                </label>
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
                                                                    class="form-check-input" id="view_product">
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
                                                                    class="form-check-input" id="create_product">
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
                                                                    class="form-check-input" id="update_product">
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
                                                                    class="form-check-input" id="delete_product">
                                                                <label class="form-check-label"
                                                                    for="delete_product"></label>
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
                                                                    class="form-check-input" id="view_warehouse">
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
                                                                    >
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
                                                                    class="form-check-input" id="create_warehouse">
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
                                                                    class="form-check-input" id="update_warehouse">
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
                                                                    class="form-check-input" id="delete_warehouse">
                                                                <label class="form-check-label"
                                                                    for="delete_warehouse"></label>
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
                                                                <input type="checkbox" value="view_purchase_order"
                                                                    name="permission[view_purchase_order]" class="form-check-input"
                                                                    id="view_order">
                                                                <label class="form-check-label" for="view_purchase_order"></label>
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
                                                                <input type="checkbox" value="view_purchase_order_detail"
                                                                    name="permission[view_purchase_order_detail]" class="form-check-input"
                                                                    id="view_order">
                                                                <label class="form-check-label" for="view_purchase_order_detail"></label>
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
                                                                <input type="checkbox" value="create_purchase_order"
                                                                    name="permission[create_purchase_order]"
                                                                    class="form-check-input" id="create_purchase_order">
                                                                <label class="form-check-label"
                                                                    for="create_purchase_order"></label>
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
                                                                <input type="checkbox" value="update_purchase_order"
                                                                    name="permission[update_purchase_order]"
                                                                    class="form-check-input" id="update_purchase_order">
                                                                <label class="form-check-label"
                                                                    for="update_purchase_order"></label>
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
                                                                <input type="checkbox" value="delete_purchase_order"
                                                                    name="permission[delete_purchase_order]"
                                                                    class="form-check-input" id="delete_purchase_order">
                                                                <label class="form-check-label"
                                                                    for="delete_purchase_order"></label>
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
                                                                    id="view_sale">
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
                                                                    >
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
                                                                    class="form-check-input" id="create_sale">
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
                                                                    class="form-check-input" id="update_sale">
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
                                                                    class="form-check-input" id="delete_sale">
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
                                                                    id="view_invoice">
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
                                                                    >
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
                                                                    class="form-check-input" id="create_invoice">
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
                                                                    class="form-check-input" id="update_invoice">
                                                                <label class="form-check-label"
                                                                    for="update_invoice"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Payment Invoice
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="payment_invoice"
                                                                    name="permission[payment_invoice]"
                                                                    class="form-check-input" id="payment_invoice">
                                                                <label class="form-check-label"
                                                                    for="payment_invoice"></label>
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
                                                                <input type="checkbox" value="view_received_products"
                                                                    name="permission[view_received_products]"
                                                                    class="form-check-input" id="view_received_products">
                                                                <label class="form-check-label"
                                                                    for="view_received_products"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Received Products
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_received_products"
                                                                    name="permission[update_received_products]"
                                                                    class="form-check-input" id="update_received_products">
                                                                <label class="form-check-label"
                                                                    for="update_received_products"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Packaging List --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Packaging List
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Packaging List
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_packaging_list"
                                                                    name="permission[view_packaging_list]"
                                                                    class="form-check-input" id="view_packaging_list">
                                                                <label class="form-check-label"
                                                                    for="view_packaging_list"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Packaging List Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_packaging_detail"
                                                                    name="permission[view_packaging_detail]"
                                                                    class="form-check-input" id="view_packaging_detail">
                                                                <label class="form-check-label"
                                                                    for="view_packaging_detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Ready to Ship --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Ready to Ship
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Ready to Ship
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_ready_to_ship"
                                                                    name="permission[view_ready_to_ship]"
                                                                    class="form-check-input" id="view_ready_to_ship">
                                                                <label class="form-check-label"
                                                                    for="view_ready_to_ship"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Ready to Ship Detail
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_ready_to_ship_detail"
                                                                    name="permission[view_ready_to_ship_detail]"
                                                                    class="form-check-input" id="view_ready_to_ship_detail">
                                                                <label class="form-check-label"
                                                                    for="view_ready_to_ship_detail"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Track Order --}}
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Track Order
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Track Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_track_order"
                                                                    name="permission[view_track_order]"
                                                                    class="form-check-input" id="view_track_order">
                                                                <label class="form-check-label"
                                                                    for="view_track_order"></label>
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
