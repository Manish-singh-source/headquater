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
                                    <form action="{{ route('store.products') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row g-3">
                                            <div class="col-12 col-lg-3">
                                                <label for="warehouse" class="form-label">Warehouse
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="warehouse_id" id="warehouse">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="products_excel" class="form-label">Products Sheet <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="products_excel" id="products_excel"
                                                    class="form-control" multiple>
                                            </div>
                                            <div class="col-12 col-lg-3 text-start">
                                                <button type=""
                                                    class="btn btn-success w-sm waves ripple-light text-center mt-md-4">
                                                    Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-3">Available Products</h5>
                                    <div class="product-table">
                                        <div class="table-responsive white-space-nowrap">
                                            <table id ="example" class="table align-middle">
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
                                <div class="text-end mb-3">

                                    <a href="{{ route('products') }}" type=""
                                        class="btn btn-success w-sm waves ripple-light">
                                        Save
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
