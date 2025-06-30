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
                            <li class="breadcrumb-item active" aria-current="page">Invoices List</li>
                        </ol>
                    </nav>
                </div>
                <!-- <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Settings</button>
                        <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
                            <a class="dropdown-item" href="javascript:;">Another action</a>
                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                        </div>
                    </div>
                </div> -->
            </div>
            <!--end breadcrumb-->



            <div class="row g-3">
                <div class="col-12 col-md-2">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Search Invoices No">
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
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <a href="create-invoice.php"><button class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Create Invoice</button></a>
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
                                        <th>Invoice No</th>
                                        <th>Customer Name</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Amount Due</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>#001</td>
                                        <td>INV0001</td>
                                        <td>ABC</td>
                                        </td>
                                        <td>
                                            2025-04-11
                                        </td>
                                        <td>10,000</td>
                                        <td>8,000</td>
                                        <td>2,000</td>
                                        <td class="text-success">Completed</td>
                                        <td>
                                            <a aria-label="anchor" href="invoices-details.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a type="button" class="btn btn-icon btn-sm bg-warning-subtle me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit text-warning">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                              <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                <img width="15" height="15" src="https://img.icons8.com/ios/50/document--v1.png" alt="bank-card-back-side--v1"/>
                                            </a>
                                            <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                <img width="15" height="15" src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png" alt="bank-card-back-side--v1"/>
                                            </a>
                                        </td>

                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Inovice Details</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form>
                                                            <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                                                <input type="date" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">Upload POD <span class="text-danger">*</span></label>
                                                                <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">Upload GRN <span class="text-danger">*</span></label>
                                                                <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">Upload E-Invoice <span class="text-danger">*</span></label>
                                                                <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                                            </div>
                                                             <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">DN Amount<span class="text-danger">*</span></label>
                                                                <input type="text" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload Amount">
                                                            </div>
                                                             <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">DN Reason<span class="text-danger">*</span></label>
                                                                <input type="text" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="DN Reason">
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">Upload E-Way Bill<span class="text-danger">*</span></label>
                                                                <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" id="holdOrder" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Payment Details</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form>
                                                            <div class="col-12 mb-3">
                                                                <label for="utr_no" class="form-label">UTR No<span class="text-danger">*</span></label>
                                                                <input type="text" name="document_image" id="utr_no" class="form-control" value="" required="" placeholder="UTR No">
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label for="payment_method" class="form-label">Update Payment Method<span class="text-danger">*</span></label>
                                                                <select id="payment_method" class="form-select">
                                                                    <option selected="" disabled>Payment Method</option>
                                                                    <option>Cash</option>
                                                                    <option>Bank Transfers</option>
                                                                    <option>Checks</option>
                                                                    <option>Mobile Payments</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label for="document_image" class="form-label">Update Payment Status<span class="text-danger">*</span></label>
                                                                <select id="input9" class="form-select">
                                                                    <option selected="" disabled>Payment Status</option>
                                                                    <option>Pending</option>
                                                                    <option>Rejected</option>
                                                                    <option>Completed</option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" id="holdOrder" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>#002</td>
                                        <td>INV0002</td>
                                        <td>EFG</td>
                                        <td>
                                            2025-04-11
                                        </td>
                                        <td>10,000</td>
                                        <td>8,000</td>
                                        <td>2,000</td>
                                        <td class="text-primary">Pending</td>
                                        <td>
                                            <a aria-label="anchor" href="invoices-details.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a type="button" class="btn btn-icon btn-sm bg-warning-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit text-warning">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                <img width="15" height="15" src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png" alt="bank-card-back-side--v1"/>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>#003</td>
                                        <td>INV0003</td>
                                        <td>XYZ</td>
                                        <td>
                                            2025-04-11
                                        </td>
                                        <td>10,000</td>
                                        <td>8,000</td>
                                        <td>2,000</td>
                                        <td class="text-danger">Issue</td>
                                        <td>
                                            <a aria-label="anchor" href="invoices-details.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a type="button" class="btn btn-icon btn-sm bg-warning-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit text-warning">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <a type="button" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                <img width="15" height="15" src="https://img.icons8.com/ios/50/bank-card-back-side--v1.png" alt="bank-card-back-side--v1"/>
                                            </a>
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
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace()
    </script>


</body>



</html>