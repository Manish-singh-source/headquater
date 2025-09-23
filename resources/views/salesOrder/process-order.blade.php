@extends('layouts.master')
@section('main-content')

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="bx bx-home-alt"></i>
                                <h5 class="mb-3">Product Details</h5>
                            </div>
                            <div>
                                <a href="{{ route('sales.order.create') }}" class="btn btn-sm btn-primary">Back</a>
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
                                    {{-- 
                                    @isset($fileData)
                                        <div>
                                            <a href="{{ route('download.sales.order.excel') }}"
                                                class="btn btn-icon btn-sm border-2 border-primary text-primary me-1">Download</a>
                                        </div>
                                    @endisset --}}
                                    <!-- <button id="customExcelBtn" class="btn btn-sm border-2 border-primary">
                                        <i class="fa fa-file-excel-o"></i> Export to Excel
                                    </button> -->
                                    <a href="{{ route('download.sales.order.excel') }}"
                                        class="btn btn-icon btn-sm border-2 border-primary text-primary me-1">Export to Excel</a>

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
                                                            <th>SKU&nbsp;Code</th>
                                                            <th>Facility&nbsp;Name</th>
                                                            <th>Facility&nbsp;Location</th>
                                                            <th>PO&nbsp;Date</th>
                                                            <th>PO&nbsp;Expiry&nbsp;Date</th>
                                                            <th>HSN</th>
                                                            <th>Item&nbsp;Code</th>
                                                            <th>Description</th>
                                                            <th>GST</th>
                                                            <th>Basic&nbsp;Rate</th>
                                                            <th>Product&nbsp;Basic&nbsp;Rate</th>
                                                            <th>Basic&nbsp;Rate&nbsp;Confirmation</th>
                                                            <th>Net&nbsp;Landing&nbsp;Rate</th>
                                                            <th>Product&nbsp;Net&nbsp;Landing&nbsp;Rate</th>
                                                            <th>Net&nbsp;Landing&nbsp;Rate&nbsp;Confirmation</th>
                                                            <th>PO&nbsp;MRP</th>
                                                            <th>Product&nbsp;MRP</th>
                                                            <th>MRP&nbsp;Confirmation</th>
                                                            <th>Case&nbsp;Pack&nbsp;Quantity</th>
                                                            <th>PO&nbsp;Quantity</th>
                                                            <th>Available</th>
                                                            <th>Unavailable&nbsp;Qty</th>
                                                            <th>Reason</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($fileData as $data)
                                                            <tr>
                                                                {{-- 
                                                                'block' => $record['Block'],
                                                                'purchase_order_quantity' => $record['Purchase Order Quantity'] ?? '',
                                                                'vendor_code' => $record['Vendor Code'], 
                                                                --}}
                                                                <td>{{ $data['Customer Name'] }}</td>
                                                                <td>{{ $data['PO Number'] }}</td>
                                                                <td>{{ $data['SKU Code'] }}</td>
                                                                <td>{{ $data['Facility Name'] }}</td>
                                                                <td>{{ $data['Facility Location'] }}</td>
                                                                <td>{{ $data['PO Date'] }}</td>
                                                                <td>{{ $data['PO Expiry Date'] }}</td>
                                                                <td>{{ $data['HSN'] }}</td>
                                                                <td>{{ $data['Item Code'] }}</td>
                                                                <td>{{ $data['Description'] }}</td>
                                                                <td>{{ $data['GST'] }}</td>
                                                                <td>{{ $data['Basic Rate'] }}</td>
                                                                <td>{{ $data['Product Basic Rate'] }}</td>
                                                                <td>{{ $data['Basic Rate Confirmation'] }}</td>
                                                                <td>{{ $data['Net Landing Rate'] }}</td>
                                                                <td>{{ $data['Product Net Landing Rate'] }}</td>
                                                                <td>{{ $data['Net Landing Rate Confirmation'] }}</td>
                                                                <td>{{ $data['MRP'] }}</td>
                                                                <td>{{ $data['Product MRP'] }}</td>
                                                                <td>{{ $data['MRP Confirmation'] }}</td>
                                                                <td>{{ $data['Case Pack Quantity'] }}</td>
                                                                <td>{{ $data['PO Quantity'] }}</td>
                                                                <td>{!! $data['Available Quantity'] !!}</td>
                                                                <td>{!! $data['Unavailable Quantity'] !!}</td>  
                                                                <td>{!! $data['Reason'] !!}</td>  
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td>No Data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                @endisset
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

