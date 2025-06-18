<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <style>
        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            background-color: #333;
            transform: translateX(-50%);
            transform: translateY(-50%);
            font-weight: bold;
            color: #fff;
            text-align: center;
            padding: 10px;
            z-index: 222;
        }
    </style>

    <!-- Loader Element -->
    <div class="loader">Loading...</div>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="col">
                            <div class="card customer-inputs">
                                <div class="card-header border-bottom-dashed">
                                    <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                Add New Order
                                            </h5>
                                        </div>
                                        <div>
                                            <b>
                                                #0081
                                            </b>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-12">
                                            <span><b>Group:</b></span>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="marital" class="form-label">Customer Group Name
                                                <span class="text-danger">*</span></label>
                                            <input type="text" name="" class="form-control" placeholder="Enter Group Name" id="groupName">
                                        </div>
                                        <div class="col-12">
                                            <span><b>Select Customers</b></span>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="customerName" class="form-label">Customer Name
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="customerName" id="customerName">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Blinkit">Blinkit</option>
                                                <option value="Moonstone">Moonstone</option>
                                                <option value="Amazon">Amazon</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="subCustomerName" class="form-label">Sub Customer Name
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="subCustomerName" id="subCustomerName">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Abc">Abc</option>
                                                <option value="Xyz">Xyz</option>
                                            </select>
                                        </div>
                                        <!-- 
                                        <div class="col-12 col-lg-3">
                                            <label for="pick-date" class="form-label">Ordered Date</label>
                                            <input type="date" class="form-control" name="orderedDate" id="pick-date">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                        </div>
                                        -->
                                        <!-- <div class="col-12 col-lg-1">
                                            <button class="btn btn-primary" id="orderStatus">Submit</button>
                                        </div> -->
                                        <div class="col-12 col-lg-1">
                                            <button class="btn btn-primary" id="add-customer">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card customer-groups">
                                <div class="card-body">
                                    <h5 class="mb-3">Customers Group - <span id="groupTitle">Blinkit</span></h5>
                                    <div class="product-table">
                                        <div class="table-responsive white-space-nowrap">
                                            <table id="customerGroupTable" class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Customer Name</th>
                                                        <th>Sub Customer Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" id="save-customers">Save</button>
                                    </div>

                                </div>
                            </div>

                            <div class="card po-uploads">
                                <div class="card-body">
                                    <div class="row align-items-end">
                                        <div class="col-12 col-lg-3">
                                            <label for="pick-date" class="form-label">Ordered Date</label>
                                            <input type="date" class="form-control" name="orderedDate" id="pick-date">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document" multiple>
                                        </div>

                                        <div class="col-12 col-lg-1">
                                            <button class="btn btn-primary" id="upload-excel">Submit</button>
                                        </div>
                                        <!-- <div class="col-12 col-lg-1">
                                            <button class="btn btn-primary" id="orderStatus">Submit</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            <div class="card available-product">
                                <div class="card-body">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="#">Blinkit</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Big Bazar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Amazon</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <h5 class="mb-3">Available Products</h5>
                                    <div class="product-table">
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
                                                        <th>Qty Available</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCRDVF87G</td>
                                                        <td contenteditable="true">TP-260</td>
                                                        <td contenteditable="true">Yera 260ml Glass Parabolic Tumbler Set</td>
                                                        <td contenteditable="true">315</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">40</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCRDL1L94</td>
                                                        <td contenteditable="true">JR2KG</td>
                                                        <td contenteditable="true">Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                        <td contenteditable="true">330</td>
                                                        <td contenteditable="true">9</td>
                                                        <td contenteditable="true">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCRDJH5YZ</td>
                                                        <td contenteditable="true">B9OFL</td>
                                                        <td contenteditable="true">Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                        <td contenteditable="true">280</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">50</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCR6N9ZL7</td>
                                                        <td contenteditable="true">TC8P17</td>
                                                        <td contenteditable="true">Yera Conical Glass Tumbler Set - 215 ml</td>
                                                        <td contenteditable="true">230</td>
                                                        <td contenteditable="true">144</td>
                                                        <td contenteditable="true">100</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T2DJ6JR</td>
                                                        <td contenteditable="true">TS10-P0</td>
                                                        <td contenteditable="true">Yera Glass Tumbler Transparent 285 ml</td>
                                                        <td contenteditable="true">240</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">64</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T2D5P2L</td>
                                                        <td contenteditable="true">JS-4</td>
                                                        <td contenteditable="true">Yera Glass Aahaar Jars, 1800 ml</td>
                                                        <td contenteditable="true">190</td>
                                                        <td contenteditable="true">144</td>
                                                        <td contenteditable="true">14</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T1CN9SX</td>
                                                        <td contenteditable="true">T9AHB</td>
                                                        <td contenteditable="true">Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                        <td contenteditable="true">250</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">34</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T1CM6S3</td>
                                                        <td contenteditable="true">JR-3</td>
                                                        <td contenteditable="true">Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                        <td contenteditable="true">225</td>
                                                        <td contenteditable="true">360</td>
                                                        <td contenteditable="true">200</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T1CM6N6</td>
                                                        <td contenteditable="true">CT9-P0</td>
                                                        <td contenteditable="true">Yera Transparent Glass Mug with Handle 240 ml</td>
                                                        <td contenteditable="true">340</td>
                                                        <td contenteditable="true">128</td>
                                                        <td contenteditable="true">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07SZ867XZ</td>
                                                        <td contenteditable="true">JR-2</td>
                                                        <td contenteditable="true">Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                        <td contenteditable="true">185</td>
                                                        <td contenteditable="true">216</td>
                                                        <td contenteditable="true">100</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 available-product">
                                <div class="text-end mb-3">
                                    <button type="" onclick="exportTableToExcel('unavailable-product-list')" class="btn btn-success w-sm waves ripple-light">
                                        Download Excel File
                                    </button>
                                    <button type="button" class="btn btn-success w-sm waves ripple-light" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Hold Products
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hold Products</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form>
                                                        <div class="mb-3">
                                                            <label for="message-text" class="d-flex">Reason:</label>
                                                            <textarea class="form-control" id="message-text"></textarea>
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
                                    <button type="" id="submitOrder" class="btn btn-success w-sm waves ripple-light">
                                        Submit
                                    </button>
                                </div>
                            </div>

                            <div class="card unavailable-product">
                                <div class="card-body">
                                    <h5 class="mb-3">Unavailable Products</h5>
                                    <div class="product-table">
                                        <div class="table-responsive white-space-nowrap">
                                            <table id="unavailable-product-list" class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Order No</th>
                                                        <th>Portal Code</th>
                                                        <th>SKU Code</th>
                                                        <th>Title</th>
                                                        <th>MRP</th>
                                                        <th>Qty Requirement</th>
                                                        <th>Qty Unavailable</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCRDVF87G</td>
                                                        <td contenteditable="true">TP-260</td>
                                                        <td contenteditable="true">Yera 260ml Glass Parabolic Tumbler Set</td>
                                                        <td contenteditable="true">315</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">24</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCRDL1L94</td>
                                                        <td contenteditable="true">JR2KG</td>
                                                        <td contenteditable="true">Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                        <td contenteditable="true">330</td>
                                                        <td contenteditable="true">9</td>
                                                        <td contenteditable="true">9</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCRDJH5YZ</td>
                                                        <td contenteditable="true">B9OFL</td>
                                                        <td contenteditable="true">Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                        <td contenteditable="true">280</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">14</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">BOCR6N9ZL7</td>
                                                        <td contenteditable="true">TC8P17</td>
                                                        <td contenteditable="true">Yera Conical Glass Tumbler Set - 215 ml</td>
                                                        <td contenteditable="true">230</td>
                                                        <td contenteditable="true">144</td>
                                                        <td contenteditable="true">44</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T2DJ6JR</td>
                                                        <td contenteditable="true">TS10-P0</td>
                                                        <td contenteditable="true">Yera Glass Tumbler Transparent 285 ml</td>
                                                        <td contenteditable="true">240</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T2D5P2L</td>
                                                        <td contenteditable="true">JS-4</td>
                                                        <td contenteditable="true">Yera Glass Aahaar Jars, 1800 ml</td>
                                                        <td contenteditable="true">190</td>
                                                        <td contenteditable="true">144</td>
                                                        <td contenteditable="true">130</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T1CN9SX</td>
                                                        <td contenteditable="true">T9AHB</td>
                                                        <td contenteditable="true">Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                        <td contenteditable="true">250</td>
                                                        <td contenteditable="true">64</td>
                                                        <td contenteditable="true">30</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T1CM6S3</td>
                                                        <td contenteditable="true">JR-3</td>
                                                        <td contenteditable="true">Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                        <td contenteditable="true">225</td>
                                                        <td contenteditable="true">360</td>
                                                        <td contenteditable="true">160</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07T1CM6N6</td>
                                                        <td contenteditable="true">CT9-P0</td>
                                                        <td contenteditable="true">Yera Transparent Glass Mug with Handle 240 ml</td>
                                                        <td contenteditable="true">340</td>
                                                        <td contenteditable="true">128</td>
                                                        <td contenteditable="true">128</td>
                                                    </tr>
                                                    <tr>
                                                        <td contenteditable="true">OPS/2025/2276</td>
                                                        <td contenteditable="true">B07SZ867XZ</td>
                                                        <td contenteditable="true">JR-2</td>
                                                        <td contenteditable="true">Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                        <td contenteditable="true">185</td>
                                                        <td contenteditable="true">216</td>
                                                        <td contenteditable="true">116</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 available-product">
                                <div class="text-end mb-3">
                                    <button type="" onclick="exportTableToExcel('unavailable-product-list')" class="btn btn-success w-sm waves ripple-light">
                                        Download Excel File
                                    </button>
                                    <!-- 
                                    <button type="" id="holdOrder" class="btn btn-success w-sm waves ripple-light">
                                        Hold Products
                                    </button>
                                    <button type="" id="submitOrder" class="btn btn-success w-sm waves ripple-light">
                                        Submit
                                    </button>
                                    -->
                                </div>
                            </div>

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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $(".available-product").hide();
            $(".unavailable-product").hide();
            $(".loader").hide();

            $("#orderStatus").on("click", function() {
                $(".loader").show();

                setTimeout(function() {
                    $(".loader").hide();
                    $(".available-product").show();
                    $(".unavailable-product").show();
                }, 3000);
            });

            $("#holdOrder").on("click", function() {
                $(".loader").show();
                setTimeout(function() {
                    $(".loader").hide();
                    location.pathname = '/headquater/order.php';
                }, 2000);
            });

            $("#submitOrder").on("click", function() {
                $(".loader").show();
                setTimeout(function() {
                    $(".loader").hide();
                    location.pathname = '/headquater/order.php';
                }, 2000);
            });

            $(".customer-groups").hide();
            // $("#add-customer").on("click", function() {
            //     $(".customer-groups").show();
            //     let groupName = $("#groupName").val();
            //     let customerName = $("#customerName").val();
            //     let subCustomerName = $("#subCustomerName").val();

            //     let row = document.createElement("tr");
            //     let td = document.createElement("td");
            //     let table = $("#customerGroupTable tbody").append(row).append(td).html(groupName);
            // });

            $("#add-customer").on("click", function() {
                $(".customer-groups").show();

                let groupName = $("#groupName").val();
                let customerName = $("#customerName").val();
                let subCustomerName = $("#subCustomerName").val();

                // Create table row with 3 td cells
                let row = `
                        <tr>
                            <td>${customerName}</td>
                            <td>${subCustomerName}</td>
                        </tr>
                    `;

                // Append to table body
                $("#groupTitle").html(groupName);
                $("#customerGroupTable tbody").append(row);
            });

            $("#upload-excel").on("click", function() {
                $(".customer-groups").show();

                let orderedDate = $("#pick-date").val();
                let document_image = $("#document_image").val();

                console.log(orderedDate);
                console.log(document_image);
                // Create table row with 3 td cells
                let rowHeading = `
                        <th>Ordered Date</th>
                        <th>Document Image</th>
                    `;
                let row = `
                        <td>${orderedDate}</td>
                        <td>${document_image}</td>
                    `;

                // Append to table bod
                $("#customerGroupTable thead tr").append(rowHeading);
                $("#customerGroupTable tbody tr").append(row);

                $(".loader").show();

                setTimeout(function() {
                    $(".po-uploads").hide();
                    $(".loader").hide();
                    $(".available-product").show();
                    $(".unavailable-product").show();
                }, 3000);
            });

            $("#orderStatus").hide();
            $(".po-uploads").hide();
            $("#save-customers").on("click", function() {
                $(".customer-inputs").hide();
                $(".po-uploads").show();
                $("#orderStatus").show();
                $(this).hide();
            });

        });
    </script>

    <script>
        function exportTableToExcel(tableID, filename = 'table_data.xls') {
            const dataType = 'application/vnd.ms-excel';
            const tableSelect = document.getElementById(tableID);
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Create download link element
            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>

</body>


</html>