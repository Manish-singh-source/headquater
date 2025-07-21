<head>
    <style>
        #hideTable {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">




            <div class="div d-flex my-2">
                <div class="col">
                    <h5 class="mb-3">#2056</h5>
                </div>
            </div>

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

                        <div class="col-12 text-end">
                            <button type="" class="btn btn-success w-sm waves ripple-light" onclick="window.print()">
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 my-2">
                <div class="text-end">
                    <a href="#" class="btn btn-success w-sm waves ripple-light">
                        Download Excel File
                    </a>
                </div>
                <div class="text-end">
                    <a href="{{ route('invoices-details') }}" class="btn btn-success w-sm waves ripple-light">
                        Generate Invoice
                    </a>
                </div>
                <div class="text-end">
                    <a href="{{ route('ready-to-ship') }}" class="btn btn-success w-sm waves ripple-light">
                        Ready to Ship
                    </a>
                </div>
            </div>
            <div class="card ">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload Excel <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document" multiple>
                        </div>
                        <div class="col-12 col-lg-1">
                            <button class="btn btn-primary" id="upload-excel">Submit</button>
                        </div>
                    </div>
                </div>
                
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
            <div class="col-12 text-end">
                <button type="{{ route('packaging-list') }}"
                    class="btn btn-success w-sm waves ripple-light">
                    Save
                </button>
                <a href="{{ route('invoices-details') }}" class="btn btn-success w-sm waves ripple-light">
                    Generate Invoice
                </a>
                <button type="{{ route('ready-to-ship') }}" class="btn btn-success w-sm waves ripple-light">
                    Ready to Ship
                </button>
            </div>


        </div>
    </main>
    <!--end main wrapper-->
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
@endsection
