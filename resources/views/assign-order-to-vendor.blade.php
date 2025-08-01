@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <h5 class="card-title mb-0">
                                                Add New Order
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-3">
                                            <label for="marital" class="form-label">Order id
                                                <span class="text-danger">*</span></label>
                                            <input disabled type="" name="" id=""
                                                class="form-control" value="" required="" placeholder="#001">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="marital" class="form-label">Vendor Name
                                                <span class="text-danger">*</span></label>
                                            <!-- <select class="form-control" name="marital" id="marital">
                                                    <option selected="" disabled="" value="">-- Select --</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Emily ">Emily </option>
                                                    <option value="John ">John </option>
                                                    <option value="Michael ">Michael </option>
                                                    <option value="Sarah ">Sarah </option>
                                                    <option value="Davis">Davis</option>
                                                    <option value="Smith">Smith</option>
                                                    <option value="Brown">Brown</option>
                                                    <option value="Wilson">Wilson</option>
                                                </select> -->
                                            <select class="form-select" id="ms1" multiple="multiple"
                                                style="width: 100%">
                                                <option value="0">Blinkit</option>
                                                <option value="1">Moonstone</option>
                                                <option value="2">Amazon</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <label for="document_image" class="form-label">Upload Excel <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image"
                                                class="form-control" value="" required=""
                                                placeholder="Upload ID Document">
                                        </div>
                                        <div class="col-12 col-lg-3 text-start text-md-end">
                                            <button type=""
                                                class="btn btn-success w-sm waves ripple-light text-center mt-md-4">
                                                Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-3">Products List</h5>
                                    <div class="product-table">
                                        <div class="table-responsive white-space-nowrap">
                                            <table class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Order No</th>
                                                        <th>Purchse order No</th>
                                                        <th>Portal</th>
                                                        <th>Customer Code</th>
                                                        <th>Vendor Sku Code</th>
                                                        <th>Title</th>
                                                        <th>MRP</th>
                                                        <th>Qty Requirement</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>BOCRDVF87G</td>
                                                        <td>CUST001</td>
                                                        <td>TP-260</td>
                                                        <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                                        <td>315</td>
                                                        <td>64</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>BOCRDL1L94</td>
                                                        <td>CUST001</td>
                                                        <td>JR2KG</td>
                                                        <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                        <td>330</td>
                                                        <td>9</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>BOCRDJH5YZ</td>
                                                        <td>CUST001</td>
                                                        <td>B9OFL</td>
                                                        <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                        <td>280</td>
                                                        <td>64</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>BOCR6N9ZL7</td>
                                                        <td>CUST001</td>
                                                        <td>TC8P17</td>
                                                        <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                                        <td>230</td>
                                                        <td>144</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>B07T2DJ6JR</td>
                                                        <td>CUST001</td>
                                                        <td>TS10-P0</td>
                                                        <td>Yera Glass Tumbler Transparent 285 ml</td>
                                                        <td>240</td>
                                                        <td>64</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>B07T2D5P2L</td>
                                                        <td>CUST001</td>
                                                        <td>JS-4</td>
                                                        <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                                        <td>190</td>
                                                        <td>144</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>B07T1CN9SX</td>
                                                        <td>CUST001</td>
                                                        <td>T9AHB</td>
                                                        <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                        <td>250</td>
                                                        <td>64</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>B07T1CM6S3</td>
                                                        <td>CUST001</td>
                                                        <td>JR-3</td>
                                                        <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                        <td>225</td>
                                                        <td>360</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>B07T1CM6N6</td>
                                                        <td>CUST001</td>
                                                        <td>CT9-P0</td>
                                                        <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                                        <td>340</td>
                                                        <td>128</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OPS/2025/2276</td>
                                                        <td>PO-001</td>
                                                        <td>B07SZ867XZ</td>
                                                        <td>CUST001</td>
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

                            <div class="col-lg-12">
                                <div class="text-end mb-3">
                                    <a href="{{ route('assign-order') }}" class="btn btn-success w-sm waves ripple-light">
                                        Place Order
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
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#ms1').select2({
                placeholder: "Select Vendors",
                allowClear: true
            });
        });
    </script>
@endsection
