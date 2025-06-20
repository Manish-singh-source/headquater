@extends('layouts.master')
@section('main-content')

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>

                                    <span>#2056</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer Name</b></span>
                                    <span> <b>Blinkit</b>, <b>Big Bazar</b>, <b>Amazon</b> </span>
                                </li>
                            </ul>


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
                        <div class="col-6 col-lg-1 text-end">
                            <span class="badge bg-danger-subtle text-danger fw-semibold">Pending</span>
                        </div>
                    </div>
                    <!-- Tabs Navigation -->
                    <div class="div d-flex my-3">
                        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active bg-success text-white mx-1" id="customer1-tab" data-bs-toggle="tab" data-bs-target="#customer1" type="button" role="tab">Customer 1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link bg-success text-white mx-1" id="customer2-tab" data-bs-toggle="tab" data-bs-target="#customer2" type="button" role="tab">Customer 2</button>
                            </li>
                        </ul>
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
                                Appointments Date
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label for="dn amount" class="form-label">Appointments Date<span class="text-danger">*</span></label>
                            <input type="date" name="dn amount" id="dn amount" class="form-control" value="" required="" placeholder="Enter DN Amount">
                        </div>
                        <div class="col-12 col-md-4 text-start">
                            <button type="" class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                Save
                            </button>
                        </div>
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

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Upload Invoices and E-Way Bill
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3 py-2">
                        <!-- <div class="col-12 col-lg-2">
                            <th>
                                <input id="checkbox3" class="form-check-input" type="checkbox">
                                <label for="checkbox3" class="form-label">Upload Invoice</label>

                            </th>
                        </div> -->
                        <div class="col-12 col-lg-3">
                            <!-- <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label> -->
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <a href="invoices-details.php" type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </a>
                        </div>
                    </div>
                    <div class="row g-3 py-2">
                        <!-- <div class="col-12 col-lg-2">
                            <th>
                                <input id="checkbox3" class="form-check-input" type="checkbox">
                                <label for="checkbox3" class="form-label">Upload E-Way Bill</label>

                            </th>
                        </div> -->
                        <div class="col-12 col-lg-3">
                            <!-- <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label> -->
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <a href="invoices-details.php" type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Delivery Note
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-2">
                            <label for="dn amount" class="form-label">DN Amount<span class="text-danger">*</span></label>
                            <input type="text" name="dn amount" id="dn amount" class="form-control" value="" required="" placeholder="Enter DN Amount">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="dn reason" class="form-label">DN Reason<span class="text-danger">*</span></label>
                            <input type="text" name="dn reason" id="dn reason" class="form-control" value="" required="" placeholder="Enter DN Reason">
                        </div>
                        <div class="col-12 col-md-4 text-start">
                            <button type="" class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Get POD
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- <div class="col-12 col-lg-2">
                            <th>
                                <input id="checkbox1" class="form-check-input" type="checkbox">
                                <label for="checkbox1" class="form-label">Upload POD</label>

                            </th>
                        </div> -->
                        <div class="col-12 col-lg-4">
                            <label for="document_image" class="form-label">Upload POD <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-6 text-start">
                            <button type="" class="btn btn-success w-sm waves ripple-light text-end mt-4">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add GRN
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <!-- <div class="col-12 col-lg-2">
                            <th>
                                <input id="checkbox3" class="form-check-input" type="checkbox">
                                <label for="checkbox3" class="form-label">Upload GRN</label>

                            </th>
                        </div> -->
                        <div class="col-12 col-lg-3">
                            <!-- <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label> -->
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Pervious
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Payment Status
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-2 text-end">
                            <select id="input9" class="form-select">
                                <option selected="" disabled>Payment Status</option>
                                <option>Pending</option>
                                <option>Rejected</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
    <!--end main wrapper-->

@endsection