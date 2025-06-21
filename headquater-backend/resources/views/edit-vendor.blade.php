@extends('layouts.master')
@section('main-content')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Enter Vendor Details</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Settings</button>
                    <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
                        <a class="dropdown-item" href="javascript:;">Another action</a>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                        <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Create Vendor</h5>
                        <form class="row g-3" action="{{ route('update-vendor', $vendor->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <label for="input1" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="input1" placeholder="First Name" name="firstName" value="{{ $vendor->first_name }}">
                            </div>
                            <div class="col-md-6">
                                <label for="input2" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="input2" placeholder="Last Name" name="lastName" value="{{ $vendor->last_name }}">
                            </div>
                            <div class="col-md-6">
                                <label for="input3" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="input3" placeholder="Phone" name="phone" value="{{ $vendor->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label for="input4" class="form-label">Email</label>
                                <input type="email" class="form-control" id="input4" placeholder="Email Id" name="email" value="{{ $vendor->email }}">
                            </div>
                            <div class="col-md-6">
                                <label for="input5" class="form-label">GST NO</label>
                                <input type="text" class="form-control" id="input5" placeholder="GST NO" name="gstNo" value="{{ $vendor->gstNo }}">
                            </div>
                            <div class="col-md-6">
                                <label for="input6" class="form-label">PAN NO</label>
                                <input type="text" class="form-control" id="input6" placeholder="PAN NO" name="panNo" value="{{ $vendor->panNo }}">
                            </div>
                            <div class="col-md-12">
                                <label for="input11" class="form-label">Address</label>
                                <textarea class="form-control" id="input11" placeholder="Address ..." rows="3" name="address" >{{ $vendor->address }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="input9" class="form-label">State</label>
                                <select id="input9" class="form-select" name="state" value="{{ $vendor->state }}">
                                    <option selected="" disabled>Choose...</option>
                                    <option>One</option>
                                    <option>Two</option>
                                    <option>Three</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input8" class="form-label">City</label>
                                <input type="text" class="form-control" id="input8" placeholder="City" name="city" value="{{ $vendor->city }}">
                            </div>
                            <div class="col-md-4">
                                <label for="input8" class="form-label">Pin Code</label>
                                <input type="text" class="form-control" id="input8" placeholder="Pin Code" name="pinCode" value="{{ $vendor->pin_code }}">
                            </div>
                            <div class="col-md-4">
                                <label for="input10" class="form-label">Account No</label>
                                <input type="text" class="form-control" id="input10" placeholder="Account No" name="accountNo" value="{{ $vendor->account_no }}">
                            </div>
                            <div class="col-md-4">
                                <label for="input8" class="form-label">IFSC Code</label>
                                <input type="text" class="form-control" id="input8" placeholder="IFSC Code" name="ifscCode" value="{{ $vendor->ifsc_code }}">
                            </div>
                            <div class="col-md-4">
                                <label for="input8" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="input8" placeholder="Bank Name" name="bankName" value="{{ $vendor->bank_name }}">
                            </div>


                            <div class="col-md-4">
                                <label for="input9" class="form-label">Status</label>
                                <select id="input9" class="form-select" name="status" value="{{ $vendor->status }}">
                                    <option selected="" disabled>Choose any one</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <!-- <a href="{{ route('vendor') }}" type="submit" class="btn btn-primary px-4">Submit</a> -->
                                    <button type="submit"  class="btn btn-primary px-4">Update</button>
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