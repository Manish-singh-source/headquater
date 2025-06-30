@extends('layouts.master')
@section('main-content')
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
                                        <div class="col-12 col-lg-3">
                                            <label for="warehouseLocation" class="form-label">Select Group
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="warehouseLocation" id="warehouseLocation">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Baroda">Amazon</option>
                                                <option value="Mumbai">Big Basket</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="warehouseLocation" class="form-label">Warehouse Location
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="warehouseLocation" id="warehouseLocation">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Baroda">Baroda</option>
                                                <option value="Mumbai">Mumbai</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label for="pick-date" class="form-label">Ordered Date</label>
                                            <input type="date" class="form-control" name="orderedDate" id="pick-date">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="document_image" class="form-label">Upload Excel <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image"
                                                class="form-control" value="" required=""
                                                placeholder="Upload ID Document" multiple>
                                        </div>
                                        <div class="col-12 col-lg-1">
                                            <button class="btn btn-primary" id="upload-excel">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card available-product">
                                <div class="card-body">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="#">Blinkit</a>
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 available-product">
                                <div class="text-end mb-3">
                                    <button type="" onclick="exportTableToExcel('unavailable-product-list')"
                                        class="btn btn-success w-sm waves ripple-light">
                                        Download Excel File
                                    </button>
                                    <button type="button" class="btn btn-success w-sm waves ripple-light"
                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Hold Products
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hold Products
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
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
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" id="holdOrder"
                                                        class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('order') }}" id="submitOrder"
                                        class="btn btn-success w-sm waves ripple-light">
                                        Submit
                                    </a>
                                </div>
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="row align-items-end">
                                            <div class="col-12 col-lg-3">
                                                <label for="document_image" class="form-label">Updated Excel Upload <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="document_image" id="document_image"
                                                    class="form-control" value="" required=""
                                                    placeholder="Upload ID Document" multiple>
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
                                <div class="text-end mb-3">

                                    <button type="button" class="btn btn-success w-sm waves ripple-light"
                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Updated Hold Products
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hold Products
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
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
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" id="holdOrder"
                                                        class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="" id="submitOrder"
                                        class="btn btn-success w-sm waves ripple-light">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection



@section('script')
    <script>
        new PerfectScrollbar(".customer-notes")
    </script>


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
                //let row = `
            //        <tr>
            //            <td>${customerName}</td>
            //            <td>${subCustomerName}</td>
            //        </tr>
            //    `;

                // Append to table body
                $("#groupTitle").html(groupName);
                $("#customerGroupTable tbody").append(row);
            });

            $("#upload-excel").on("click", function() {
                $(".customer-groups").show();

                let document_image = $("#document_image").val();
                let warehouseLocation = $("#warehouseLocation").val();

                console.log(document_image);
                console.log(warehouseLocation);
                // Create table row with 3 td cells
                let rowHeading = `
                        <th>Document File</th>
                        <th>Warehouse Location</th>
                    `;
                let row = `
                        <td>${document_image}</td>
                        <td>${warehouseLocation}</td>
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
@endsection
