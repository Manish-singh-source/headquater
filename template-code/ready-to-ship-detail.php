<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Delivery Details</h5>
                </div>
                <div class="col-6 d-flex justify-content-end text-end my-2 ">
                    <div>
                        <select id="input9" class="form-select">
                            <option selected="" disabled>Status</option>
                            <option>Out For Delivery</option>
                            <option>Delivered</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Order Id</b></span>

                                <span>#001</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Customer Name</b></span>
                                <span> Manish</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> +91 123 456 7789 </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> manish@gmail.com</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Ordered Date</b></span>
                                <span> 2025-04-11</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Delivery Date</b></span>
                                <span> 2025-05-15</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Billing Address</b></span>
                                <span> Office No. 501, 5th Floor, Ghanshyam Enclave, Next To Laljipada Police Station, Laljipada, Link Road, Kandivali (West), Mumbai - 400067. Maharashtra - India</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b> Shipping Address</b></span>
                                <span> Office No. 501, 5th Floor, Ghanshyam Enclave, Next To Laljipada Police Station, Laljipada, Link Road, Kandivali (West), Mumbai - 400067. Maharashtra - India</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Invoices PDF</b></span>
                                <span> BK159.pdf</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="div d-flex my-3 gap-3">
                                <div class="col">
                                    <h6 class="mb-3">PO Table</h6>
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


                            </div>
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