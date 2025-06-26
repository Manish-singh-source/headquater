<head>
    <style>
        #hideTable {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        
                        <div class="card">
                    <div class="card-header border-bottom-dashed">
                        <div class="d-flex">
                            <h5 class="card-title flex-grow-1 mb-0">
                                Order Details
                            </h5>
                            <div class="fw-bold text-dark">
                                #1001
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">


                            <div class="col-lg-6">
                                <ul class="list-group list-group-flush ">

                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">Customer Name :
                                        </span>
                                        <span>
                                            Blinkit, Big Bazar, Amazon
                                        </span>
                                    </li>

                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">Appointments Date :
                                        </span>
                                        <span>
                                            25/04/2005
                                        </span>
                                    </li>

                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">Invoices and E-Way Bill :
                                        </span>
                                        <span>
                                            <a href="invoices-details.php" type="" class="btn btn-primary w-sm waves ripple-light">
                                View
                            </a>
                                        </span>
                                    </li>
                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">DN Amount :
                                        </span>
                                        <span>
                                            45000
                                        </span>
                                    </li>


                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-group list-group-flush ">

                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">DN Reason :
                                        </span>
                                        <span>
                                            Damaged Goods
                                        </span>
                                    </li>

                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">POD :
                                        </span>
                                        <span>
                                            Lalji Pada , Kandivali West, Mumbai, Maharashtra 400067
                                        </span>
                                    </li>

                                    <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">GRN :
                                        </span>
                                        <span>
                                           <button type="" class="btn btn-primary w-sm waves ripple-light">
                                View
                            </button>
                                        </span>
                                    </li>
                                     <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">status :
                                        </span>
                                        <span>
                                            <span class="badge bg-danger-subtle text-danger fw-semibold">Hold</span>
                                        </span>
                                    </li>
                                    <!-- <li class="list-group-item border-0 d-flex align-items-center gap-3 flex-wrap">
                                        <span class="fw-semibold text-break">PO :
                                        </span>
                                        <span>
                                            <button class="btn btn-success btn-sm">View</button>
                                        </span>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">


                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">PO Table</h6>
                        </div>
                    </div>
                    <!-- Tabs Navigation -->
                   
                    <div class="product-table" id="poTable">
                        <div class="table-responsive white-space-nowrap">
                           <table class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Order_No</th>
                                                        <th>Customer</th>
                                                        <th>Po_number</th>
                                                        <th>Facility_Name</th>
                                                        <th>Facility_Location</th>
                                                        <th>Po_Date</th>
                                                        <th>Po_Expiry_Date</th>
                                                        <th>HSN</th>
                                                        <th>Item_Code</th>
                                                        <th>Description</th>
                                                        <th>Basic_Rate</th>
                                                        <th>GST</th>
                                                        <th>Net_Landing_Rate</th>
                                                        <th>MRP</th>
                                                        <th>Rate_Confirmation</th>
                                                        <th>Po_Qty</th>
                                                        <th>Case_Pack_Qty</th>
                                                        <th>Available</th>
                                                        <th>Unavailable_Qty</th>
                                                        <th>Block</th>
                                                        <th>Purchase_Order_Qty</th>
                                                        <th>Vendor_Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>ORD12345</td>
                                                        <td>John Doe Enterprises</td>
                                                        <td>PO987654</td> 
                                                        <td>Sunrise Facility</td>
                                                        <td>Mumbai</td> 
                                                        <td>2025-06-01</td>
                                                        <td>2025-12-01</td> 
                                                        <td>30049011</td> 
                                                        <td>ITM001</td> 
                                                        <td>Sanitizer 500ml</td> 
                                                        <td>75.00</td> 
                                                        <td>18%</td> 
                                                        <td>88.50</td> 
                                                        <td>120.00</td> 
                                                        <td>Confirmed</td> 
                                                        <td>1000</td>
                                                        <td>24</td> 
                                                        <td>800</td>
                                                        <td>200</td> 
                                                        <td>No</td> 
                                                        <td>1000</td> 
                                                        <td>VEND123</td> 
                                                    </tr>

                                                </tbody>
                                            </table>
                        </div>

                        <!-- <div class="col-12 text-end">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Save
                            </button>
                        </div> -->
                    </div>
                </div>
            </div>

            

            <!-- <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Order Sended to Warehouse / Vendor
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Warehouse Location
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Baroda">Baroda</option>
                                <option value="Mumbai ">Mumbai </option>
                                <option value="Up ">Up </option>
                                <option value="Bihar ">Bihar </option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-4 text-end">
                            <button type="" class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->

          

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

    <script>
        function poTable() {
            var table = document.getElementById("poTable");
            if (table.style.display === "none") {
                table.style.display = ""; // Or "" also works
            } else {
                table.style.display = "none";
            }
        }
    </script>
    <script>
        function piTable() {
            var table = document.getElementById("piTable");
            if (table.style.display === "none") {
                table.style.display = ""; // Or "" also works
            } else {
                table.style.display = "none";
            }
        }
    </script>
    <script>
        function invoiceTable() {
            var table = document.getElementById("invoiceTable");
            if (table.style.display === "none") {
                table.style.display = ""; // Or "" also works
            } else {
                table.style.display = "none";
            }
        }
    </script>


</body>


</html>