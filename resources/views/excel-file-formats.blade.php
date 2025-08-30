@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Excel File Formats</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="div my-2">

                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">

                                customer groups 
                                products 
                                update products 
                                sku mapping 
                                check availability 
                                customer po (blocking) 
                                update po 
                                manual purchase order 
                                vendor pi 
                                update vendor pi 

                                update po (packaging list)


                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customers Group</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/customers-group.xlsx') }}"
                                            download="customers-group.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Products</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/product-master.xlsx') }}"
                                            download="product-master.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer PO</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/customers-po.xlsx') }}"
                                            download="customers-po.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li>

                                {{-- 
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer PO Availibility Check</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/customer-po-availibility-check.xlsx') }}"
                                            download="customer-po-availibility-check.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li> 
                                --}}
                                
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer PO Block Quantity</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/customer-po-block-quantity.xlsx') }}"
                                            download="customer-po-block-quantity.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor PO</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/vendor-po.xlsx') }}"
                                            download="vendor-po.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor PI</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/vendor-pi.xlsx') }}"
                                            download="vendor-pi.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li>
                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Update Vendor Received Products</b></span>
                                    <span>
                                        <a href="{{ asset('uploads/excel-formats/customers-group.xlsx') }}"
                                            download="customers-group.xlsx" class="btn btn-sm border-2 border-primary">
                                            <i class="fas fa-file-excel me-1"></i> Download
                                        </a>
                                    </span>
                                </li> --}}

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
