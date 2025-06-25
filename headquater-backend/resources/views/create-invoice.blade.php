@extends('layouts.master')
@section('main-content')


    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Create Invoices</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Invoice details</h5>
                            <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="pono" class="form-label">PO Number</label>
                                    <input type="text" class="form-control" id="pono" placeholder="Enter PO Number">
                                </div>

                                <div class="col-md-6">
                                    <label for="pos" class="form-label">Place of Supply</label>
                                    <input type="text" class="form-control" id="pos" placeholder="Enter Place of Supply">
                                </div>

                                <div class="col-md-6">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="customerName" placeholder="Enter Company Name">
                                </div>

                                <div class="col-md-6">
                                    <label for="gstNumber" class="form-label">GST Number</label>
                                    <input type="text" class="form-control" id="gstNumber" placeholder="Enter GST Number">
                                </div>

                                <div class="col-md-6">
                                    <label for="panNumber" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" id="panNumber" placeholder="Enter PAN Number">
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label">Billing Address</label>
                                    <textarea class="form-control" id="address" placeholder="Enter Full Address" rows="3"></textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="customerCode" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="customerCode" placeholder="Enter Country Name">
                                </div>

                                <div class="col-md-3">
                                    <label for="customerCode" class="form-label">State</label>
                                    <input type="text" class="form-control" id="customerCode" placeholder="Enter State Name">
                                </div>

                                <div class="col-md-3">
                                    <label for="customerCode" class="form-label">City</label>
                                    <input type="text" class="form-control" id="customerCode" placeholder="Enter City Name">
                                </div>

                                <div class="col-md-3">
                                    <label for="pinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="pinCode" placeholder="Enter Pin Code">
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label">Shipping Address</label>
                                    <textarea class="form-control" id="address" placeholder="Enter Full Address" rows="3"></textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="customerCode" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="customerCode" placeholder="Enter Country Name">
                                </div>

                                <div class="col-md-3">
                                    <label for="customerCode" class="form-label">State</label>
                                    <input type="text" class="form-control" id="customerCode" placeholder="Enter State Name">
                                </div>

                                <div class="col-md-3">
                                    <label for="customerCode" class="form-label">City</label>
                                    <input type="text" class="form-control" id="customerCode" placeholder="Enter City Name">
                                </div>

                                <div class="col-md-3">
                                    <label for="pinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="pinCode" placeholder="Enter Pin Code">
                                </div>

                                <div class="col-md-4">
                                    <label for="document_image" class="form-label">Upload Excel <span class="text-danger">*</span></label>
                                    <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <a href="{{route('invoices-details')}}" type="submit" class="btn btn-primary px-4">Submit</a>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div><!--end row-->
        </div>
    </main>
    <!--end main wrapper-->


    @endsection
