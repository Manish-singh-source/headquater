@extends('layouts.master')
@section('main-content')
<!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">Create Role</h5>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div>
                                <label for="name" class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>

                                <input placeholder="Enter Name" name="name" value="" id="name" required="" class="form-control" type="text">
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
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Admin
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_admin" name="permission[admin][view_admin] " class="form-check-input" id="view_admin">
                                                            <label class="form-check-label" for="view_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Profile
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_profile" name="permission[admin][update_profile] " class="form-check-input" id="update_profile">
                                                            <label class="form-check-label" for="update_profile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Admin
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_admin" name="permission[admin][create_admin] " class="form-check-input" id="create_admin">
                                                            <label class="form-check-label" for="create_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Admin
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_admin" name="permission[admin][update_admin] " class="form-check-input" id="update_admin">
                                                            <label class="form-check-label" for="update_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Admin
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_admin" name="permission[admin][delete_admin] " class="form-check-input" id="delete_admin">
                                                            <label class="form-check-label" for="delete_admin"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Vendor
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_admin" name="permission[admin][view_admin] " class="form-check-input" id="view_admin">
                                                            <label class="form-check-label" for="view_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Vendor
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_profile" name="permission[admin][update_profile] " class="form-check-input" id="update_profile">
                                                            <label class="form-check-label" for="update_profile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Vendor
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_admin" name="permission[admin][create_admin] " class="form-check-input" id="create_admin">
                                                            <label class="form-check-label" for="create_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Vendor
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_admin" name="permission[admin][delete_admin] " class="form-check-input" id="delete_admin">
                                                            <label class="form-check-label" for="delete_admin"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Customer
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_admin" name="permission[admin][view_admin] " class="form-check-input" id="view_admin">
                                                            <label class="form-check-label" for="view_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Customer
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_profile" name="permission[admin][update_profile] " class="form-check-input" id="update_profile">
                                                            <label class="form-check-label" for="update_profile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Customer
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_admin" name="permission[admin][create_admin] " class="form-check-input" id="create_admin">
                                                            <label class="form-check-label" for="create_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Customer
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_admin" name="permission[admin][delete_admin] " class="form-check-input" id="delete_admin">
                                                            <label class="form-check-label" for="delete_admin"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Warehouse
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_admin" name="permission[admin][view_admin] " class="form-check-input" id="view_admin">
                                                            <label class="form-check-label" for="view_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Warehouse
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_profile" name="permission[admin][update_profile] " class="form-check-input" id="update_profile">
                                                            <label class="form-check-label" for="update_profile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Warehouse
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_admin" name="permission[admin][create_admin] " class="form-check-input" id="create_admin">
                                                            <label class="form-check-label" for="create_admin"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Warehouse
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_admin" name="permission[admin][delete_admin] " class="form-check-input" id="delete_admin">
                                                            <label class="form-check-label" for="delete_admin"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Products
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_languages" name="permission[language][view_languages] " class="form-check-input" id="view_languages">
                                                            <label class="form-check-label" for="view_languages"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Products
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_languages" name="permission[language][create_languages] " class="form-check-input" id="create_languages">
                                                            <label class="form-check-label" for="create_languages"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Products
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_languages" name="permission[language][update_languages] " class="form-check-input" id="update_languages">
                                                            <label class="form-check-label" for="update_languages"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Products
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_languages" name="permission[language][delete_languages] " class="form-check-input" id="delete_languages">
                                                            <label class="form-check-label" for="delete_languages"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Order
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_languages" name="permission[language][view_languages] " class="form-check-input" id="view_languages">
                                                            <label class="form-check-label" for="view_languages"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Order
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_languages" name="permission[language][create_languages] " class="form-check-input" id="create_languages">
                                                            <label class="form-check-label" for="create_languages"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Order
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_languages" name="permission[language][update_languages] " class="form-check-input" id="update_languages">
                                                            <label class="form-check-label" for="update_languages"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Order
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_languages" name="permission[language][delete_languages] " class="form-check-input" id="delete_languages">
                                                            <label class="form-check-label" for="delete_languages"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Roles
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_roles" name="permission[role][view_roles] " class="form-check-input" id="view_roles">
                                                            <label class="form-check-label" for="view_roles"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Create Roles
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="create_roles" name="permission[role][create_roles] " class="form-check-input" id="create_roles">
                                                            <label class="form-check-label" for="create_roles"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Update Roles
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="update_roles" name="permission[role][update_roles] " class="form-check-input" id="update_roles">
                                                            <label class="form-check-label" for="update_roles"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            Delete Roles
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="delete_roles" name="permission[role][delete_roles] " class="form-check-input" id="delete_roles">
                                                            <label class="form-check-label" for="delete_roles"></label>
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
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Dashboard
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" value="view_dashboard" name="permission[dashboard][view_dashboard] " class="form-check-input" id="view_dashboard">
                                                            <label class="form-check-label" for="view_dashboard"></label>
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
        </div>
    </main>
    <!--end main wrapper-->
@endsection

    
