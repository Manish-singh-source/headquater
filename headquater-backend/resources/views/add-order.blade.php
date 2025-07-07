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
                                    <form action="{{ route('process.order') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12 col-lg-3">
                                                <label for="customerGroup" class="form-label">Select Customer Group
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="customerGroup" id="customerGroup">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($customerGroup as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="warehouseName" class="form-label">Warehouse Name
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="warehouseName" id="warehouseName">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="document_image" class="form-label">Customer PO (CSV/XLSX) <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="csv_file" id="csv_file" class="form-control"
                                                    value="" required="" placeholder="Upload ID Document" multiple>
                                            </div>
                                            <div class="col-12 col-lg-1">
                                                <button class="btn btn-primary" id="upload-excel">Submit</button>
                                            </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h5 class="mb-3">Products Details</h5>
                                <div>
                                    <button class="btn btn-icon btn-sm bg-primary me-1 text-white" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop1">Upload Block Sheet</button>

                                    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('process.block.order') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update
                                                            Customer PO Sheet</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-12 mb-3">
                                                            <label for="document_image" class="form-label">Customer PO
                                                                (CSV/XLSX) <span class="text-danger">*</span></label>
                                                            <input type="file" name="csv_file" id="csv_file"
                                                                class="form-control" value="" required=""
                                                                placeholder="Upload ID Document" multiple>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="product-table">
                                    <div class="table-responsive white-space-nowrap">
                                        <table id="example" class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    {{-- <th>Order_No</th> --}}
                                                    <th>Customer</th>
                                                    <th>Po&nbsp;number</th>
                                                    <th>Facility&nbsp;Name</th>
                                                    <th>Facility&nbsp;Location</th>
                                                    <th>PO&nbsp;Date</th>
                                                    <th>PO&nbsp;Expiry&nbsp;Date</th>
                                                    <th>HSN</th>
                                                    <th>Item&nbsp;Code</th>
                                                    <th>Description</th>
                                                    <th>Basic&nbsp;Rate</th>
                                                    {{-- <th>GST</th> --}}
                                                    <th>Net&nbsp;Landing&nbsp;Rate</th>
                                                    <th>MRP</th>
                                                    <th>Ordered&nbsp;Quantity</th>
                                                    <th>Available</th>
                                                    <th>Unavailable&nbsp;Qty</th>
                                                    @isset($blockedData)
                                                        <th>Rate&nbsp;Confirmation</th>
                                                        <th>Po&nbsp;Qty</th>
                                                        <th>Case&nbsp;Pack&nbsp;Qty</th>
                                                        <th>Block</th>
                                                        <th>Purchase&nbsp;Order&nbsp;Qty</th>
                                                        <th>Vendor&nbsp;Code</th>
                                                    @endisset
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @isset($fileData)
                                                    @forelse($fileData as $data)
                                                        <tr>
                                                            <td>{{ $data['Customer'] }}</td>
                                                            <td>{{ $data['po_number'] }}</td>
                                                            <td>{{ $data['facility_name'] }}</td>
                                                            <td>{{ $data['facility_Location'] }}</td>
                                                            <td>{{ $data['po_date'] }}</td>
                                                            <td>{{ $data['po_expiry_date'] }}</td>
                                                            <td>{{ $data['HSN'] }}</td>
                                                            <td>{{ $data['Item_Code'] }}</td>
                                                            <td>{{ $data['Description'] }}</td>
                                                            <td>{{ $data['Basic_rate'] }}</td>
                                                            <td>{{ $data['Net_Landing_rate'] }}</td>
                                                            <td>{{ $data['MRP'] }}</td>
                                                            <td>{{ $data['po_qty'] }}</td>
                                                            <td>{{ $data['available_quantity'] }}</td>
                                                            <td>{{ $data['unavailable_quantity'] }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td>No Data</td>
                                                        </tr>
                                                    @endforelse
                                                @endisset

                                                @isset($blockedData)
                                                    @forelse($blockedData as $data)
                                                        <tr>
                                                            <td>{{ $data['Customer'] }}</td>
                                                            <td>{{ $data['po_number'] }}</td>
                                                            <td>{{ $data['facility_name'] }}</td>
                                                            <td>{{ $data['facility_Location'] }}</td>
                                                            <td>{{ $data['po_date'] }}</td>
                                                            <td>{{ $data['po_expiry_date'] }}</td>
                                                            <td>{{ $data['HSN'] }}</td>
                                                            <td>{{ $data['Item_Code'] }}</td>
                                                            <td>{{ $data['Description'] }}</td>
                                                            <td>{{ $data['Basic_rate'] }}</td>
                                                            <td>{{ $data['Net_Landing_rate'] }}</td>
                                                            <td>{{ $data['MRP'] }}</td>
                                                            <td>{{ $data['Rate_Confirmation'] }}</td>
                                                            <td>{{ $data['po_qty'] }}</td>
                                                            <td>{{ $data['Case_pack_Qty'] }}</td>
                                                            <td>{{ $data['Block'] }}</td>
                                                            <td>{{ $data['Purchase_Order_Qty'] }}</td>
                                                            <td>{{ $data['Vendor_Code'] }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td>No Data</td>
                                                        </tr>
                                                    @endforelse
                                                @endisset

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
