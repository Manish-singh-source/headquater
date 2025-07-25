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
                                                Add New Products
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('received-products.view') }}" method="GET">
                                        @csrf
                                        @method('GET')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12 col-lg-2">
                                                <label for="purchase_order_id" class="form-label">Order id
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="purchase_order_id"
                                                    id="purchase_order_id">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($purchaseOrders as $order)
                                                        <option
                                                            {{ request('purchase_order_id') == $order->id ? 'selected' : '' }}
                                                            value="{{ $order->id }}">{{ $order->id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <label for="vendor_code" class="form-label">Vendor Name
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="vendor_code" id="vendor_code">
                                                    <option selected disabled value="">-- Select --
                                                    </option>
                                                    @foreach ($vendors as $vendor)
                                                        <option {{ request('vendor_code') == $vendor ? 'selected' : '' }}
                                                            value="{{ $vendor }}">{{ $vendor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <button type="submit"
                                                    class="btn btn-success w-sm waves ripple-light text-center  mt-md-4">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @isset($vendorPIs)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center my-2">
                                            <div class="div d-flex justify-content-end my-3 gap-2">
                                                <h6 class="mb-3">Vendor Products</h6>
                                            </div>
                                            <!-- Tabs Navigation -->
                                            <div class="div d-flex justify-content-end my-3 gap-2">
                                                @can('PermissionChecker', 'update_received_products')
                                                    <a type="button" class="btn btn-sm border-2 border-primary"
                                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                                        Update Products
                                                    </a>
                                                @endcan

                                                <!-- Modal -->
                                                <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('check.order.stock') }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update
                                                                        Products</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="col-12 mb-3">
                                                                        <label for="document_image" class="form-label">Updated
                                                                            Vendor PI (CSV/XLSX) <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="file" name="csv_file" id="csv_file"
                                                                            class="form-control" value="" required=""
                                                                            placeholder="Upload ID Document" multiple>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" id="holdOrder"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button id="customExcelBtn" class="btn btn-sm border-2 border-primary">
                                                    <i class="fa fa-file-excel-o"></i> Export to Excel
                                                </button>
                                            </div>
                                        </div>

                                        <div class="product-table">
                                            <div class="table-responsive white-space-nowrap">
                                                <table id="example" class="table align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Order No</th>
                                                            <th>Vendor Code</th>
                                                            <th>Purchase Order No</th>
                                                            <th>Vendor SKU Code</th>
                                                            <th>Title</th>
                                                            <th>MRP</th>
                                                            <th>Quantity Requirement</th>
                                                            <th>Available Quantity</th>
                                                            <th>Purchase Rate Basic</th>
                                                            <th>GST</th>
                                                            <th>HSN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($vendorPIs as $product)
                                                            <tr>
                                                                <td>{{ $product->id }}</td>
                                                                <td>{{ $product->vendor_code }}</td>
                                                                <td>{{ $product->purchase_order_id }}</td>
                                                                <td>{{ $product->vendor_sku_code }}</td>
                                                                <td>{{ $product->vendor_sku_code }}</td>
                                                                <td>{{ $product->mrp }}</td>
                                                                <td>{{ $product->quantity_requirement }}</td>
                                                                <td>{{ $product->available_quantity }}</td>
                                                                <td>{{ $product->purchase_rate }}</td>
                                                                <td>{{ $product->gst }}</td>
                                                                <td>{{ $product->hsn }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row justify-content-between mb-3">
                                        <form class="col-12 text-end" action="{{ route('received-products.update') }}"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="purchase_order_id"
                                                value="{{ request('purchase_order_id') }}">
                                            <input type="hidden" name="vendor_code" value="{{ request('vendor_code') }}">
                                            <button class="btn btn-sm border-2 border-primary" type="submit">Submit</button>
                                        </form>
                                        {{-- 
                                        <div class="col-12 text-end"><a href="products.php" type=""
                                                class="btn btn-success w-sm waves ripple-light">
                                                Submit
                                            </a></div> 
                                            --}}
                                    </div>
                                </div>
                            @endisset

                            @isset($var)
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Available Products</h5>
                                        <div class="product-table">
                                            <div class="table-responsive white-space-nowrap">
                                                <table id="example" class="table align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Order No</th>
                                                            <th>Portal Code</th>
                                                            <th>SKU Code</th>
                                                            <th>Title</th>
                                                            <th>MRP</th>
                                                            <th>Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>BOCRDVF87G</td>
                                                            <td>TP-260</td>
                                                            <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                                            <td>315</td>
                                                            <td>40</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>BOCRDL1L94</td>
                                                            <td>JR2KG</td>
                                                            <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                            <td>330</td>
                                                            <td>0</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>BOCRDJH5YZ</td>
                                                            <td>B9OFL</td>
                                                            <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                            <td>280</td>
                                                            <td>50</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>BOCR6N9ZL7</td>
                                                            <td>TC8P17</td>
                                                            <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                                            <td>230</td>
                                                            <td>100</td>
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
                                                            <td>14</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>B07T1CN9SX</td>
                                                            <td>T9AHB</td>
                                                            <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                            <td>250</td>
                                                            <td>34</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>B07T1CM6S3</td>
                                                            <td>JR-3</td>
                                                            <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                            <td>225</td>
                                                            <td>200</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>B07T1CM6N6</td>
                                                            <td>CT9-P0</td>
                                                            <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                                            <td>340</td>
                                                            <td>0</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OPS/2025/2276</td>
                                                            <td>B07SZ867XZ</td>
                                                            <td>JR-2</td>
                                                            <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                            <td>185</td>
                                                            <td>100</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-12">
                                    <div class="row justify-content-between mb-3">
                                        <form class="col-12 text-end" action="{{ route('received-products.update') }}"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="purchase_order_id"
                                                value="{{ request('purchase_order_id') }}">
                                            <input type="hidden" name="vendor_code" value="{{ request('vendor_code') }}">
                                            <button class="btn btn-sm border-2 border-primary" type="submit">Submit</button>
                                        </form>
                                        {{-- 
                                        <div class="col-12 text-end"><a href="products.php" type=""
                                                class="btn btn-success w-sm waves ripple-light">
                                                Submit
                                            </a></div> 
                                            --}}
                                    </div>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection
