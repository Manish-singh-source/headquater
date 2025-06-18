<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Purchase Report</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Customer Orders</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">3</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Amount</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">3K</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Paid Amount</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">20K</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Due Amount</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">2K</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4">
                <div class="card-body pb-1">
                    <form action="customer-report.html">
                        <div class="row align-items-end">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Choose Date</label>
                                            <div class="input-icon-start position-relative">
                                                <input type="text" class="form-control date-range bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
                                                <span class="input-icon-left">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Name</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Carl</option>
                                                <option>Minerva</option>
                                                <option>Robert </option>
                                                <option>Evans</option>
                                                <option>Rameriz</option>
                                                <option>Lamon</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Cash</option>
                                                <option>Paypal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Status</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>All</option>
                                                <option>Paid</option>
                                                <option>Unpaid </option>
                                                <option>Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <a href="#" class="btn btn-danger w-100" type="">Generate Report</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


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
                                        <th>Reference</th>
                                        <th>Customer Id</th>
                                        <th>Customer Name</th>
                                        <th>Ordered Date</th>
                                        <th>Delivery Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>INV0001</td>
                                        <td>#001</td>
                                        <td>
                                            <p class="mb-0 customer-name fw-bold">ABC</p>

                                        </td>
                                        <td>
                                            2025-04-11
                                        </td>
                                        <td>
                                            2025-05-15
                                        </td>
                                        <td>₹ 10,000</td>
                                        <td>₹ 10,000</td>
                                        <td>₹ 0</td>
                                        <td class="text-success">Paid</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>INV0002</td>
                                        <td>#002</td>
                                        <td>
                                            <p class="mb-0 customer-name fw-bold">XYZ</p>
                                        </td>
                                        <td>
                                            2025-04-11
                                        </td>
                                        <td>
                                            2025-05-15
                                        </td>
                                        <td>₹ 10,000</td>
                                        <td>₹ 8,000</td>
                                        <td>₹ 2,000</td>
                                        <td class="text-danger">Unpaid</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>INV0003</td>
                                        <td>#003</td>
                                        <td>
                                            <p class="mb-0 customer-name fw-bold">EFG</p>
                                        </td>
                                        <td>
                                            2025-04-11
                                        </td>
                                        <td>
                                            2025-05-15
                                        </td>
                                        <td>₹ 10,000</td>
                                        <td>₹ 10,000</td>
                                        <td>₹ 0</td>
                                        <td class="text-success">Paid</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

    <footer class="page-footer">
        <p class="mb-0">Copyright © 2025. All right reserved.</p>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>


    <script src="assets/js/jquery.min.js"></script>

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