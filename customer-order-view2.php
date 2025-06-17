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




            <div class="div d-flex my-2">
                <div class="col">
                    <h5 class="mb-3">#2056</h5>
                </div>
                <div class="col-12 col-lg-1 text-end">
                    <select id="input9" class="form-select">
                        <option selected="" disabled>Status</option>
                        <option>Pending</option>
                        <option>Confirm</option>
                        <option>Receive</option>
                        <option>Completed</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">PO Table</h6>
                        </div>
                        <div class="col-12 col-lg-1 text-end">
                            <span class="badge bg-danger-subtle text-danger fw-semibold">Pending</span>
                        </div>
                    </div>
                    <div class="product-table" id="poTable">
                        <div class="table-responsive white-space-nowrap">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order No</th>
                                        <th>Portal Code</th>
                                        <th>SKU Code</th>
                                        <th>Title</th>
                                        <th>MRP</th>
                                        <th>Qty Requirement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCRDVF87G</td>
                                        <td>TP-260</td>
                                        <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                        <td>315</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCRDL1L94</td>
                                        <td>JR2KG</td>
                                        <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                        <td>330</td>
                                        <td>9</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCRDJH5YZ</td>
                                        <td>B9OFL</td>
                                        <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                        <td>280</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCR6N9ZL7</td>
                                        <td>TC8P17</td>
                                        <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                        <td>230</td>
                                        <td>144</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T2DJ6JR</td>
                                        <td>TS10-P0</td>
                                        <td>Yera Glass Tumbler Transparent 285 ml</td>
                                        <td>240</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T2D5P2L</td>
                                        <td>JS-4</td>
                                        <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                        <td>190</td>
                                        <td>144</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T1CN9SX</td>
                                        <td>T9AHB</td>
                                        <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                        <td>250</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T1CM6S3</td>
                                        <td>JR-3</td>
                                        <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                        <td>225</td>
                                        <td>360</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T1CM6N6</td>
                                        <td>CT9-P0</td>
                                        <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                        <td>340</td>
                                        <td>128</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07SZ867XZ</td>
                                        <td>JR-2</td>
                                        <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                        <td>185</td>
                                        <td>216</td>
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

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                <span class="selected-warehouse">
                                    Selected
                                </span>
                                <span class="select-warehouse">Select</span>
                                Warehouse
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3 selected-warehouse">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Warehouse Location:</label>
                            <b>
                                Baroda
                            </b>
                        </div>
                    </div>

                    <div class="row g-3 select-warehouse">
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
                            <button type="" id="select-warehouse" class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card vendor-section">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                <span class="selected-vendor">
                                    Selected
                                </span>
                                <span class="select-vendor">Select</span>
                                Vendor
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 selected-vendor">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor:</label>
                            <b>
                                Sarah
                            </b>
                        </div>
                    </div>
                    <div class="card-body select-vendor">
                        <div class="row g-3">
                            <div class="col-12 col-lg-3">
                                <label for="marital" class="form-label">Vendor Name
                                    <span class="text-danger">*</span></label>
                                <select class="form-control" name="marital" id="marital">
                                    <option selected="" disabled="" value="">-- Select --</option>
                                    <option value="Emily ">Emily </option>
                                    <option value="John ">John </option>
                                    <option value="Michael ">Michael </option>
                                    <option value="Sarah ">Sarah </option>
                                    <option value="Davis">Davis</option>
                                    <option value="Smith">Smith</option>
                                    <option value="Brown">Brown</option>
                                    <option value="Wilson">Wilson</option>

                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label>
                                <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                            </div>
                            <div class="col-12 col-lg-4 text-end">
                                <button type="" id="select-vendor" class="btn btn-success w-sm waves ripple-light text-center  mt-4">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
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
        $(document).ready(function() {
            // warehouse
            $(".selected-warehouse").hide();
            $(".vendor-section").hide();
            $("#select-warehouse").on("click", function() {
                $(".selected-warehouse").show();
                $(".vendor-section").show();
                $(".select-warehouse").hide();
            });
            // vendor
            $(".selected-vendor").hide();
            $("#select-vendor").on("click", function() {
                $(".selected-vendor").show();
                $(".select-vendor").hide();
            });
        });

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