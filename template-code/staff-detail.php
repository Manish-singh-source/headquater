<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <div class="d-flex">
                                <h5 class="card-title flex-grow-1 mb-0">
                                    Staff Details
                                </h5>
                                <!-- <div>
                                    <a href="add-staff.php" class="btn btn-sm btn-primary">Edit</a>
                                </div> -->
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Name :
                                    </span>
                                    <span>
                                        Sarah Wilson
                                    </span>
                                </li>

                                <li class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Role :
                                    </span>
                                    <span>
                                        Admin
                                    </span>
                                </li>

                                <li class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Status :
                                    </span>
                                    <span class="badge bg-success-subtle text-success fw-semibold">
                                        Active
                                    </span>
                                </li>

                                <li class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">Contact no :
                                    </span>
                                    <span>
                                        8080721003
                                    </span>
                                </li>

                                <li class="list-group-item border-0 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="fw-semibold text-break">E-mail :
                                    </span>
                                    <span>
                                        abcexample@gmail.com
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
                                <!-- <div>
                                    <a href="add-staff.php" class="btn btn-sm btn-primary">Edit</a>
                                </div> -->
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="mt-3">

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
                                                    Language
                                                </h6>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 form-control p-2">
                                                        <label class="mb-0">
                                                            View Languages
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
                                                            Create Languages
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
                                                            Update Languages
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
                                                            Delete Languages
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
                        </div>
                    </div>
                </div>

            </div><!--end row-->
        </div>
    </main>
    <!--end main wrapper-->


    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    <!--start footer-->
    <footer class="page-footer">
        <p class="mb-0">Copyright © 2025. All right reserved.</p>
    </footer>
    <!--top footer-->

    <!--bootstrap js-->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <!--plugins-->
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/metismenu/metisMenu.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        new PerfectScrollbar(".customer-notes")
    </script>


</body>


</html>