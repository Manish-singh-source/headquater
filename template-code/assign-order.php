<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Order List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->



            <div class="row g-3">
                <div class="col-12 col-md-2">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Search Order">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
                <div class="col-12 col-md-2 flex-grow-1 overflow-auto">
                    <div class="btn-group position-static">
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Sort
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Sort By Name</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Email</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Orders</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Sort By Location</a></li>
                            </ul>
                        </div>
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Inactive</a></li>
                            </ul>
                        </div>
                        <!-- <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Date
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Inactive</a></li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button>
                        <!-- <a href="assign-order-to-vendor.php"><button class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Purches Order</button></a> -->
                    </div>
                </div>
            </div><!--end row-->

            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example2" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Order Id</th>
                                        <th>Vendor Name</th>
                                        <th>Order Status</th>
                                        <th>Ordered Date</th>
                                        <th>Warehouse</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>#001</td>
                                        <td>
                                            <p class="mb-0 customer-name fw-bold">Blinkit, Moonstone</p>

                                        </td>
                                        <td>
                                            Completed
                                        </td>
                                        <td>2025-04-11</td>
                                        <td>Baroda</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="vendor-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a aria-label="anchor" href="#" class="btn btn-icon btn-sm bg-warning-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit text-warning">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>#002</td>
                                        <td>
                                            <p class="mb-0 customer-name fw-bold">Amazon</p>
                                        </td>
                                        <td>
                                            On Hold
                                        </td>
                                        <td>2025-04-11</td>
                                        <td>Baroda</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="vendor-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a aria-label="anchor" href="#" class="btn btn-icon btn-sm bg-warning-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit text-warning">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>#003</td>
                                        <td>
                                            <p class="mb-0 customer-name fw-bold">Blinkit</p>
                                        </td>
                                        <td>
                                            Pending
                                        </td>
                                        <td>2025-04-11</td>
                                        <td>Baroda</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="vendor-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a aria-label="anchor" href="#" class="btn btn-icon btn-sm bg-warning-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit text-warning">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
    <!--end main wrapper-->

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
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <!-- <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['excel']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script> -->
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace()
    </script>


</body>



</html>