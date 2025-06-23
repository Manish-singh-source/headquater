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
                            <li class="breadcrumb-item active" aria-current="page">Enter Warehouse Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Settings</button>
                        <button type="button"
                            class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                                href="javascript:;">Action</a>
                            <a class="dropdown-item" href="javascript:;">Another action</a>
                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated
                                link</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Create Warehouse</h5>
                            <form class="row g-3" action="{{ route('warehouse.update', $warehouse->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="col-md-6">
                                    <label for="input1" class="form-label">Warehouse Name</label>
                                    <input type="text" class="form-control" name="warehouse_name" id="input1"
                                        value="{{ $warehouse->warehouse_name }}" placeholder="Warehouse Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="input9" class="form-label">Warehouse Type</label>
                                    <select id="input9" class="form-select" name="warehouse_type">
                                        <option selected="" disabled>Choose...</option>
                                        <option>Storage Hub</option>
                                        <option>Return Center</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Contact Person Name</label>
                                    <input type="text" class="form-control" name="contact_person_name" id="input2"
                                        value="{{ $warehouse->contact_person_name }}" placeholder="Contact Person Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="contact_person_phone_no" id="input3"
                                        value="{{ $warehouse->contact_person_phone_no }}" placeholder="Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Alternate Phone Number</label>
                                    <input type="text" class="form-control" name="contact_person_alt_phone_no"
                                        value="{{ $warehouse->contact_person_alt_phone_no }}" id="input3"
                                        placeholder="Alternate Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="contact_person_email" id="input4"
                                        value="{{ $warehouse->contact_person_email }}" placeholder="Email Id">
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control" name="gst_no" id="input5"
                                        value="{{ $warehouse->gst_no }}" placeholder="GST NO">
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control" name="pan_no" id="input6"
                                        value="{{ $warehouse->pan_no }}" placeholder="PAN NO">
                                </div>
                                <div class="col-md-6">
                                    <label for="input11" class="form-label">Address Line 1</label>
                                    <textarea class="form-control" id="input11" name="address_line_1" placeholder="Address Line 1" rows="3">
                                            {{ $warehouse->address_line_1 }}
                                        </textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="input11" class="form-label">Address Line 2</label>
                                    <textarea class="form-control" id="input11" name="address_line_2" placeholder="Address Line 2" rows="3">{{ $warehouse->address_line_2 }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">Upload Licence Document</label>
                                    <input type="file" class="form-control" name="licence_doc" id="input6"
                                        placeholder="Upload Licence Document">
                                </div>
                                <div class="col-md-6">
                                    <label for="input8" class="form-label">Max storage capacity</label>
                                    <input type="number" class="form-control" name="max_storage_capacity"
                                        value="{{ $warehouse->max_storage_capacity }}" id="input8"
                                        placeholder="Max storage capacity">
                                </div>
                                <div class="col-md-2">
                                    <label for="input8" class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" id="input8"
                                        value="{{ $warehouse->city }}" placeholder="City">
                                </div>
                                <div class="col-md-2">
                                    <label for="input8" class="form-label">State </label>
                                    <input type="text" class="form-control" name="state" id="input8"
                                        value="{{ $warehouse->state }}" placeholder="State ">
                                </div>
                                <div class="col-md-2">
                                    <label for="input8" class="form-label">Country </label>
                                    <input type="text" class="form-control" name="country" id="input8"
                                        value="{{ $warehouse->country }}" placeholder="Country  ">
                                </div>
                                <div class="col-md-2">
                                    <label for="input8" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" name="pincode" id="input8"
                                        value="{{ $warehouse->pincode }}" placeholder="Pin Code">
                                </div>
                                <div class="col-md-2">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" name="status" class="form-select">
                                        <option selected="" disabled>Choose any one</option>
                                        <option>Active</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="input9" class="form-label">Supported Operations</label>
                                    <select id="input9" name="supported_operations" class="form-select">
                                        <option selected="" disabled>Choose any one</option>
                                        <option>Inbound</option>
                                        <option>Outbound</option>
                                        <option>Return</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" name="default_warehouse" type="checkbox">
                                    <label for="input8"class="form-label">Default Warehouse</label>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary px-4">Submit</button>
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
