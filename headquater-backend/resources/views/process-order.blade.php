@extends('layouts.master')
@section('main-content')

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="div d-flex">
                            <div class="col-12">
                                <i class="bx bx-home-alt"></i>
                                <h5 class="mb-3">Product Details</h5>
                            </div>
                        </div>
                        <!--end breadcrumb-->

                        <div class="col-12">
                            <div class="card w-100 d-flex  flex-sm-row flex-col">
                                <ul class="col-12 list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Customers Group Name</b></span>
                                        <span>Amazon</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Warehouse Name</b></span>
                                        <span>Baroda</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <h5 class="mb-3">Products Details</h5>
                                    {{-- @isset($fileData)
                                        <div>
                                            <a href="{{ route('download.order.excel') }}"
                                                class="btn btn-icon btn-sm border-2 border-primary text-primary me-1">Download</a>
                                        </div>
                                    @endisset --}}
                                    <button id="customExcelBtn" class="btn btn-sm border-2 border-primary">
                                        <i class="fa fa-file-excel-o"></i> Export to Excel
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="product-table">
                                        <div class="table-responsive white-space-nowrap">
                                            <table id="example" class="table align-middle">
                                                @isset($fileData)
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Customer&nbsp;Name</th>
                                                            <th>Po&nbsp;Number</th>
                                                            <th>SKU</th>
                                                            <th>Facility&nbsp;Name</th>
                                                            <th>Facility&nbsp;Location</th>
                                                            <th>PO&nbsp;Date</th>
                                                            <th>PO&nbsp;Expiry&nbsp;Date</th>
                                                            <th>HSN</th>
                                                            <th>Item&nbsp;Code</th>
                                                            <th>Description</th>
                                                            <th>Basic&nbsp;Rate</th>
                                                            <th>GST</th>
                                                            <th>Net&nbsp;Landing&nbsp;Rate</th>
                                                            <th>MRP</th>
                                                            <th>PO&nbsp;Quantity</th>
                                                            <th>Available</th>
                                                            <th>Unavailable&nbsp;Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($fileData as $data)
                                                            <tr>
                                                                <td>{{ $data['customer_name'] }}</td>
                                                                <td>{{ $data['po_number'] }}</td>
                                                                <td>{{ $data['sku'] }}</td>
                                                                <td>{{ $data['facility_name'] }}</td>
                                                                <td>{{ $data['facility_location'] }}</td>
                                                                <td>{{ $data['po_date'] }}</td>
                                                                <td>{{ $data['po_expiry_date'] }}</td>
                                                                <td>{{ $data['hsn'] }}</td>
                                                                <td>{{ $data['item_code'] }}</td>
                                                                <td>{{ $data['description'] }}</td>
                                                                <td>{{ $data['basic_rate'] }}</td>
                                                                <td>{{ $data['gst'] }}</td>
                                                                <td>{{ $data['net_landing_rate'] }}</td>
                                                                <td>{{ $data['mrp'] }}</td>
                                                                <td>{{ $data['po_qty'] }}</td>
                                                                <td>{!! $data['available_quantity'] !!}</td>
                                                                <td>{!! $data['unavailable_quantity'] !!}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td>No Data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                @endisset

                                                @isset($blockedData)
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th>Po&nbsp;number</th>
                                                            <th>SKU</th>
                                                            <th>Facility&nbsp;Name</th>
                                                            <th>Facility&nbsp;Location</th>
                                                            <th>PO&nbsp;Date</th>
                                                            <th>PO&nbsp;Expiry&nbsp;Date</th>
                                                            <th>HSN</th>
                                                            <th>Item&nbsp;Code</th>
                                                            <th>Description</th>
                                                            <th>Basic&nbsp;Rate</th>
                                                            <th>GST</th>
                                                            <th>Net&nbsp;Landing&nbsp;Rate</th>
                                                            <th>MRP</th>
                                                            <th>Rate&nbsp;Confirmation</th>
                                                            <th>Po&nbsp;Qty</th>
                                                            <th>Case&nbsp;Pack&nbsp;Qty</th>
                                                            <th>Available</th>
                                                            <th>Unavailable&nbsp;Qty</th>
                                                            <th>Block</th>
                                                            <th>Purchase&nbsp;Order&nbsp;Qty</th>
                                                            <th>Vendor&nbsp;Code</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($blockedData as $data)
                                                            <tr>
                                                                <td>{{ $data['customer_name'] }}</td>
                                                                <td>{{ $data['po_number'] }}</td>
                                                                <td>{{ $data['sku'] }}</td>
                                                                <td>{{ $data['facility_name'] }}</td>
                                                                <td>{{ $data['facility_location'] }}</td>
                                                                <td>{{ $data['po_date'] }}</td>
                                                                <td>{{ $data['po_expiry_date'] }}</td>
                                                                <td>{{ $data['hsn'] }}</td>
                                                                <td>{{ $data['item_code'] }}</td>
                                                                <td>{{ $data['description'] }}</td>
                                                                <td>{{ $data['basic_rate'] }}</td>
                                                                <td>{{ $data['gst'] }}</td>
                                                                <td>{{ $data['net_landing_rate'] }}</td>
                                                                <td>{{ $data['mrp'] }}</td>
                                                                <td>{{ $data['rate_confirmation'] }}</td>
                                                                <td>{{ $data['po_qty'] }}</td>
                                                                <td>{{ $data['case_pack_quantity'] }}</td>
                                                                <td>{{ $data['available_quantity'] }}</td>
                                                                <td>{{ $data['unavailable_quantity'] }}</td>
                                                                <td>{{ $data['block'] }}</td>
                                                                <td>{{ $data['purchase_order_quantity'] }}</td>
                                                                <td>{{ $data['vendor_code'] }}</td>
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

                                @isset($blockedData)
                                    <div class="card-body d-flex justify-content-end align-items-center">
                                        <div>
                                            <a href="{{ route('download.order.excel') }}"
                                                class="btn btn-icon btn-sm border-2 border-primary text-primary me-1">Submit</a>
                                        </div>
                                    </div>
                                @endisset
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
@endsection
