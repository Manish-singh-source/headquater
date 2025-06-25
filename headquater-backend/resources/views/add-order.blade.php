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
                                        <div class="col-12">
                                            <span><b>Group:</b></span>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="marital" class="form-label">Customer Group Name
                                                <span class="text-danger">*</span></label>
                                            <input type="text" name="" class="form-control"
                                                placeholder="Enter Group Name" id="groupName">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="document_image" class="form-label">Upload Excel <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image"
                                                class="form-control" value="" required=""
                                                placeholder="Upload ID Document" multiple>
                                        </div>
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
                                                    <tr>
                                                        <td>Blinkit</td>
                                                        <td>Mark</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Blinkit</td>
                                                        <td>Thornton</td>
                                                    </tr>
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
                                            <label for="document_image" class="form-label">Upload Excel <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image"
                                                class="form-control" value="" required=""
                                                placeholder="Upload ID Document" multiple>
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
                                                        <th>po_number</th>
                                                        <th>facility_name</th>
                                                        <th>manufacturer_name</th>
                                                        <th>entity_vendor_legal_name</th>
                                                        <th>vendor_name</th>
                                                        <th>order_date</th>
                                                        <th>appointment_date</th>
                                                        <th>expiry_date</th>
                                                        <th>po_state</th>
                                                        <th>item_id</th>
                                                        <th>name</th>
                                                        <th>uom_text</th>
                                                        <th>upc</th>
                                                        <th>units_ordered</th>
                                                        <th>remaining_quantity</th>
                                                        <th>landing_rate</th>
                                                        <th>cost_price</th>
                                                        <th>margin_percentage</th>
                                                        <th>cess_value</th>
                                                        <th>sgst_value</th>
                                                        <th>igst_value</th>
                                                        <th>cgst_value</th>
                                                        <th>tax_value</th>
                                                        <th>total_amount</th>
                                                        <th>mrp</th>
                                                        <th>Qty Available</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>2549210015180</td>
                                                        <td>Nagpur N1 - Feeder Warehouse</td>
                                                        <td>Ocean Glass Public Company Ltd.</td>
                                                        <td>Moonstone Ventures LLP</td>
                                                        <td>INOVIZ IDEAS PVT LTD</td>
                                                        <td>2025-06-19 06:14:26+00:00</td>
                                                        <td></td>
                                                        <td>2025-07-19T18:29:59Z</td>
                                                        <td>Created</td>
                                                        <td>10063772</td>
                                                        <td>Ocean Glass (290 ml, Transparent)(Pack)</td>
                                                        <td>1 unit</td>
                                                        <td>8.85022E+12</td>
                                                        <td>12</td>
                                                        <td>12</td>
                                                        <td>417.28</td>
                                                        <td>353.63</td>
                                                        <td>35.31</td>
                                                        <td>0</td>
                                                        <td>9</td>
                                                        <td>18</td>
                                                        <td>9</td>
                                                        <td>18</td>
                                                        <td>5007.36</td>
                                                        <td>645</td>
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
