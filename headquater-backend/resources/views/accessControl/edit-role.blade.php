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
                                                                    name="permission[admin][view_admin]"
                                                                    class="form-check-input" id="view_admin" @if(isset($permissions['admin']['view_admin'])) checked @endif>
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
                                                                    name="permission[admin][update_profile]"
                                                                    class="form-check-input" id="update_profile" @if(isset($permissions['admin']['update_profile'])) checked @endif>
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
                                                                    name="permission[admin][create_admin]"
                                                                    class="form-check-input" id="create_admin">
                                                                <label class="form-check-label" for="create_admin" @if(isset($permissions['admin']['create_admin'])) checked @endif></label>
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
                                                                    name="permission[admin][update_admin]"
                                                                    class="form-check-input" id="update_admin">
                                                                <label class="form-check-label" for="update_admin" @if(isset($permissions['admin']['update_admin'])) checked @endif></label>
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
                                                                    name="permission[admin][delete_admin]"
                                                                    class="form-check-input" id="delete_admin">
                                                                <label class="form-check-label" for="delete_admin" @if(isset($permissions['admin']['delete_admin'])) checked @endif></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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
                                                                    name="permission[vendor][view_vendor]"
                                                                    class="form-check-input" id="view_vendor" @if(isset($permissions['vendor']['view_vendor'])) checked @endif>
                                                                <label class="form-check-label" for="view_vendor"></label>
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
                                                                    name="permission[vendor][update_vendor]"
                                                                    class="form-check-input" id="update_vendor" @if(isset($permissions['vendor']['update_vendor'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_vendor"></label>
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
                                                                    name="permission[vendor][create_vendor]"
                                                                    class="form-check-input" id="create_vendor" @if(isset($permissions['vendor']['create_vendor'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_vendor"></label>
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
                                                                    name="permission[vendor][delete_vendor]]"
                                                                    class="form-check-input" id="delete_vendor" @if(isset($permissions['vendor']['delete_vendor'])) checked @endif>
                                                                <label class="form-check-label" for="delete_vendor">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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
                                                                    name="permission[customer][view_customer]"
                                                                    class="form-check-input" id="view_customer" @if(isset($permissions['customer']['view_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_customer"></label>
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
                                                                    name="permission[customer][update_customer]"
                                                                    class="form-check-input" id="update_customer" @if(isset($permissions['customer']['update_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_customer"></label>
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
                                                                    name="permission[customer][create_customer]"
                                                                    class="form-check-input" id="create_customer" @if(isset($permissions['customer']['create_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_customer"></label>
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
                                                                    name="permission[customer][delete_customer]"
                                                                    class="form-check-input" id="delete_customer" @if(isset($permissions['customer']['delete_customer'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_customer"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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
                                                                    name="permission[warehouse][view_warehouse]"
                                                                    class="form-check-input" id="view_warehouse" @if(isset($permissions['warehouse']['view_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="view_warehouse"></label>
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
                                                                    name="permission[warehouse][update_warehouse]"
                                                                    class="form-check-input" id="update_warehouse" @if(isset($permissions['warehouse']['update_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_warehouse"></label>
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
                                                                    name="permission[warehouse][create_warehouse]"
                                                                    class="form-check-input" id="create_warehouse" @if(isset($permissions['warehouse']['create_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_warehouse"></label>
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
                                                                    name="permission[warehouse][delete_warehouse]"
                                                                    class="form-check-input" id="delete_warehouse" @if(isset($permissions['warehouse']['delete_warehouse'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_warehouse"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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
                                                                    name="permission[products][view_product]"
                                                                    class="form-check-input" id="view_product" @if(isset($permissions['products']['view_product'])) checked @endif>
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
                                                                    name="permission[products][create_product]"
                                                                    class="form-check-input" id="create_product" @if(isset($permissions['products']['create_product'])) checked @endif>
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
                                                                    name="permission[products][update_product]"
                                                                    class="form-check-input" id="update_product" @if(isset($permissions['products']['update_product'])) checked @endif>
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
                                                                    name="permission[products][delete_product]"
                                                                    class="form-check-input" id="delete_product" @if(isset($permissions['products']['delete_product'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_product"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="mb-0">
                                                        Order
                                                    </h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                View Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="view_order"
                                                                    name="permission[order][view_order]"
                                                                    class="form-check-input" id="view_order" @if(isset($permissions['order']['view_order'])) checked @endif>
                                                                <label class="form-check-label" for="view_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Create Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="create_order"
                                                                    name="permission[order][create_order]"
                                                                    class="form-check-input" id="create_order" @if(isset($permissions['order']['create_order'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="create_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Update Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="update_order"
                                                                    name="permission[order][update_order]"
                                                                    class="form-check-input" id="update_order" @if(isset($permissions['order']['update_order'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="update_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                            <label class="mb-0">
                                                                Delete Order
                                                            </label>
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" value="delete_order"
                                                                    name="permission[order][delete_order]"
                                                                    class="form-check-input" id="delete_order" @if(isset($permissions['order']['delete_order'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_order"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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
                                                                    name="permission[role][view_roles]"
                                                                    class="form-check-input" id="view_roles" @if(isset($permissions['role']['view_roles'])) checked @endif>
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
                                                                    name="permission[role][create_roles]"
                                                                    class="form-check-input" id="create_roles" @if(isset($permissions['role']['create_roles'])) checked @endif>
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
                                                                    name="permission[role][update_roles]"
                                                                    class="form-check-input" id="update_roles" @if(isset($permissions['role']['update_roles'])) checked @endif>
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
                                                                    name="permission[role][delete_roles]"
                                                                    class="form-check-input" id="delete_roles" @if(isset($permissions['role']['delete_roles'])) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="delete_roles"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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
                                                                    name="permission[dashboard][view_dashboard]"
                                                                    class="form-check-input" id="view_dashboard" @if(isset($permissions['dashboard']['view_dashboard'])) checked @endif>
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
