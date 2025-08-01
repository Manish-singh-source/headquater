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
                                    <span>{{ 'ORDER-' . $order->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Name</b></span>
                                    <span>
                                        <b>
                                            @foreach ($vendors as $vendor)
                                                {{ $vendor->first_name ?? '' }}
                                            @endforeach
                                        </b>
                                    </span>
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
                    </div>
                    <!-- Tabs Navigation -->
                    <div class="div d-flex justify-content-end my-3">
                        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                            <select class="form-select"  id="departmentFilter" aria-label="Default select example">
                                <option value="" selected>All Vendors</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->first_name }}">{{ $vendor->first_name }}</option>
                                @endforeach
                            </select>
                            {{-- 
                            @foreach ($vendors as $vendor)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link bg-success text-white mx-1" id="customer1-tab"
                                        data-bs-toggle="tab" data-bs-target="#customer1" type="button"
                                        role="tab">{{ $vendor->first_name ?? '' }}
                                    </button>
                                </li>
                            @endforeach 
                            --}}
                        </ul>
                    </div>

                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Purchase Order No</th>
                                        <th>Vendor Name</th>
                                        <th>Portal Code</th>
                                        <th>SKU Code</th>
                                        <th>Title</th>
                                        <th>MRP</th>
                                        <th>Qty Requirement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>{{ 'PO-' . $order->id }}</td>
                                            <td>{{ $order->vendorInfo->first_name }}</td>
                                            <td>{{ $order->po_number }}</td>
                                            <td>{{ $order->sku }}</td>
                                            <td>{{ $order->description }}</td>
                                            <td>{{ $order->mrp }}</td>
                                            <td>{{ $order->po_qty }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No Records Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="col-12 text-end">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Save
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add PI
                            </h5>
                        </div>
                        <div class="col-12 col-lg-2 gap-2 pi-view-show">
                            <button type="button" class="btn btn-sm btn-success w-sm add">
                                Add More
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pi-add">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload PI Excel <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light upload">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pi-view-show">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name</label>
                            <p><b>Emily</b></p>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">PI Excel</label>
                            <p> <b>ABC.xls</b> </p>
                        </div>
                        <!-- <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                                                                                    <button type="" class="btn btn-success w-sm waves ripple-light add">
                                                                                        Add More
                                                                                    </button>
                                                                                </div> -->
                    </div>
                </div>

                <div class="card-body pi2-add">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload PI Excel <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light upload2">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pi2-view-show">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name</label>
                            <p><b>Emily</b></p>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">PI Excel</label>
                            <p> <b>ABC.xls</b> </p>
                        </div>
                        <!-- <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                                                                                    <button type="" class="btn btn-success w-sm waves ripple-light">
                                                                                        Add More
                                                                                    </button>
                                                                                </div> -->
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-body">
                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">PI Table</h6>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <div class="div d-flex my-3">
                        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active bg-success text-white mx-1" id="vendor1-tab"
                                    data-bs-toggle="tab" data-bs-target="#vendor1" type="button" role="tab">Vendor
                                    1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link bg-success text-white mx-1" id="vendor2-tab" data-bs-toggle="tab"
                                    data-bs-target="#vendor2" type="button" role="tab">Vendor 2</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="vendorTabsContent">
                        <!-- Vendor 1 Table -->
                        <div class="tab-pane fade show active" id="vendor1" role="tabpanel"
                            aria-labelledby="vendor1-tab">
                            <div class="product-table" id="piTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Vendor Sku Code</th>
                                                <th>Title</th>
                                                <th>MRP</th>
                                                <th>Qty Requirement</th>
                                                <th>Available Qty</th>
                                                <th>Purchase rate Basic</th>
                                                <th>GST</th>
                                                <th>HSN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>TP-260</td>
                                                <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                                <td>315</td>
                                                <td>64</td>
                                                <td>60</td>
                                                <td>280</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JR2KG</td>
                                                <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                <td>330</td>
                                                <td>9</td>
                                                <td>10</td>
                                                <td>295</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>B9OFL</td>
                                                <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                <td>280</td>
                                                <td>64</td>
                                                <td>50</td>
                                                <td>240</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>TC8P17</td>
                                                <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                                <td>230</td>
                                                <td>144</td>
                                                <td>140</td>
                                                <td>200</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>TS10-P0</td>
                                                <td>Yera Glass Tumbler Transparent 285 ml</td>
                                                <td>240</td>
                                                <td>64</td>
                                                <td>60</td>
                                                <td>210</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JS-4</td>
                                                <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                                <td>190</td>
                                                <td>144</td>
                                                <td>140</td>
                                                <td>160</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>T9AHB</td>
                                                <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                <td>250</td>
                                                <td>64</td>
                                                <td>64</td>
                                                <td>215</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JR-3</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                <td>225</td>
                                                <td>360</td>
                                                <td>350</td>
                                                <td>200</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>CT9-P0</td>
                                                <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                                <td>340</td>
                                                <td>128</td>
                                                <td>120</td>
                                                <td>300</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JR-2</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                <td>185</td>
                                                <td>216</td>
                                                <td>210</td>
                                                <td>160</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-success w-sm">Save</button>
                            </div>
                        </div>

                        <!-- Vendor 2 Table -->
                        <div class="tab-pane fade" id="vendor2" role="tabpanel" aria-labelledby="vendor2-tab">
                            <div class="product-table" id="piTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Vendor Sku Code</th>
                                                <th>Title</th>
                                                <th>MRP</th>
                                                <th>Qty Requirement</th>
                                                <th>Available Qty</th>
                                                <th>Purchase rate Basic</th>
                                                <th>GST</th>
                                                <th>HSN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>TP-260</td>
                                                <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                                <td>315</td>
                                                <td>64</td>
                                                <td>60</td>
                                                <td>280</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JR2KG</td>
                                                <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                <td>330</td>
                                                <td>9</td>
                                                <td>10</td>
                                                <td>295</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>B9OFL</td>
                                                <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                <td>280</td>
                                                <td>64</td>
                                                <td>50</td>
                                                <td>240</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>TC8P17</td>
                                                <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                                <td>230</td>
                                                <td>144</td>
                                                <td>140</td>
                                                <td>200</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>TS10-P0</td>
                                                <td>Yera Glass Tumbler Transparent 285 ml</td>
                                                <td>240</td>
                                                <td>64</td>
                                                <td>60</td>
                                                <td>210</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JS-4</td>
                                                <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                                <td>190</td>
                                                <td>144</td>
                                                <td>140</td>
                                                <td>160</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>T9AHB</td>
                                                <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                <td>250</td>
                                                <td>64</td>
                                                <td>64</td>
                                                <td>215</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JR-3</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                <td>225</td>
                                                <td>360</td>
                                                <td>350</td>
                                                <td>200</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>CT9-P0</td>
                                                <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                                <td>340</td>
                                                <td>128</td>
                                                <td>120</td>
                                                <td>300</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                            <tr>
                                                <td>JR-2</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                <td>185</td>
                                                <td>216</td>
                                                <td>210</td>
                                                <td>160</td>
                                                <td>12%</td>
                                                <td>7013</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-success w-sm">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add Invoice
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload Invoice <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload Invoice <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
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
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload GRN <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload GRN <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control"
                                value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
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
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-2">
                            <label for="document_image" class="form-label">Update Payment Status<span
                                    class="text-danger">*</span></label>
                            <select id="input9" class="form-select">
                                <option selected="" disabled>Payment Status</option>
                                <option>Pending</option>
                                <option>Rejected</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
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
                            </select>
                        </div>
                        <div class="col-12 col-lg-2">
                            <label for="document_image" class="form-label">Update Payment Status<span
                                    class="text-danger">*</span></label>
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

            <div class="col-12 my-3 d-flex justify-content-end align-items-end gap-2">
                <a href="{{ route('assign-order') }}" class="btn btn-success w-sm waves ripple-light">
                    Close Order
                </a>
            </div>


        </div>
    </main>
    <!--end main wrapper-->
@endsection
