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
                            <form class="row g-3" action="{{ route('warehouse.store') }}" method="POST">
                                @csrf
                                @method('POST')

                                <div class="col-md-6">
                                    <label for="input1" class="form-label">Warehouse Name</label>
                                    <input type="text" class="form-control @error('warehouse_name') is-invalid @enderror"
                                        name="warehouse_name" id="input1" placeholder="Warehouse Name"
                                        value="{{ old('warehouse_name') }}">
                                    @error('warehouse_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input9" class="form-label">Warehouse Type</label>
                                    <select id="input9" class="form-select" name="warehouse_type">
                                        <option selected="" disabled>Choose...</option>
                                        <option value="storage hub">Storage Hub</option>
                                        <option value="return center">Return Center</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Contact Person Name</label>
                                    <input type="text"
                                        class="form-control @error('contact_person_name') is-invalid @enderror"
                                        name="contact_person_name" id="input2" value="{{ old('contact_person_name') }}"
                                        placeholder="Contact Person Name">
                                    @error('contact_person_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone Number</label>
                                    <input type="text"
                                        class="form-control @error('contact_person_phone_no') is-invalid @enderror"
                                        name="contact_person_phone_no" value="{{ old('contact_person_phone_no') }}"
                                        id="input3" placeholder="Phone Number">
                                    @error('contact_person_phone_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Alternate Phone Number</label>
                                    <input type="text"
                                        class="form-control @error('contact_person_alt_phone_no') is-invalid @enderror"
                                        name="contact_person_alt_phone_no"  value="{{ old('contact_person_alt_phone_no') }}" id="input3"
                                        placeholder="Alternate Phone Number">
                                    @error('contact_person_alt_phone_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email"
                                        class="form-control @error('contact_person_email') is-invalid @enderror"
                                        name="contact_person_email" value="{{ old('contact_person_email') }}" id="input4" placeholder="Email Id">
                                    @error('contact_person_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control @error('gst_no') is-invalid @enderror"
                                        name="gst_no" value="{{ old('gst_no') }}" id="input5" placeholder="GST NO">
                                    @error('gst_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control @error('pan_no') is-invalid @enderror"
                                        name="pan_no" value="{{ old('pan_no') }}" id="input6" placeholder="PAN NO">
                                    @error('pan_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input11" class="form-label">Address Line 1</label>
                                    <textarea class="form-control @error('address_line_1') is-invalid @enderror" id="input11" name="address_line_1"
                                        placeholder="Address Line 1" rows="3">{{ old('address_line_1') }}</textarea>
                                    @error('address_line_1')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input11" class="form-label">Address Line 2</label>
                                    <textarea class="form-control @error('address_line_2') is-invalid @enderror" id="input11" name="address_line_2"
                                        placeholder="Address Line 2" rows="3">{{ old('address_line_2') }}</textarea>
                                    @error('address_line_2')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-6">
                                    <label for="input6" class="form-label">Upload Licence Document</label>
                                    <input type="file" class="form-control @error('licence_doc') is-invalid @enderror"
                                        name="licence_doc" id="input6" placeholder="Upload Licence Document">
                                    @error('licence_doc')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div> --}}
                                <div class="col-md-6">
                                    <label for="input8" class="form-label">Max storage capacity</label>
                                    <input type="number"
                                        class="form-control @error('max_storage_capacity') is-invalid @enderror"
                                        name="max_storage_capacity" id="input8" value="{{ old('max_storage_capacity') }}" placeholder="Max storage capacity">
                                    @error('max_storage_capacity')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    <select id="shippingCountry" class="form-select" name="country">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="shippingState" class="form-label">State</label>
                                    <select id="shippingState" class="form-select" name="state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="shippingCity" class="form-label">City</label>
                                    <select id="shippingCity" class="form-select" name="city">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="input8" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control @error('pincode') is-invalid @enderror"
                                        name="pincode" id="input8" value="{{ old('pincode') }}" placeholder="Pin Code">
                                    @error('pincode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" name="status" class="form-select">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="input9" class="form-label">Supported Operations</label>
                                    <select id="input9" name="supported_operations" class="form-select">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="inbound">Inbound</option>
                                        <option value="outbount">Outbound</option>
                                        <option value="return">Return</option>
                                    </select>
                                </div>
                                {{-- 
                                <div class="col-md-2">
                                    <input class="form-check-input" name="default_warehouse" type="checkbox">
                                    <label for="input8"class="form-label">Default Warehouse</label>
                                </div> 
                                --}}
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


@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {

            function getLocationData(url, id, tag, data = null) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
                    success: function(data) {
                        console.log(data.data);
                        $(id).empty().append(
                            `<option value="">Select ${tag}</option>`);
                        data.data.map(function(country) {
                            $(id).append(
                                $('<option>', {
                                    value: country.id,
                                    text: country.name
                                })
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            getLocationData("/countries", '#shippingCountry', "Country");

            $("#shippingCountry").on("change", function() {
                let countryId = $(this).val();
                console.log(countryId);
                getLocationData("/states", "#shippingState", "State", {
                    countryId: countryId
                });
            });

            $("#shippingState").on("change", function() {
                let stateId = $(this).val();
                console.log(stateId);
                getLocationData("/cities", "#shippingCity", "City", {
                    stateId: stateId
                });
            });

            getLocationData("/countries", '#billingCountry', "Country");

            $("#billingCountry").on("change", function() {
                let countryId = $(this).val();
                console.log(countryId);
                getLocationData("/states", "#billingState", "State", {
                    countryId: countryId
                });
            });

            $("#billingState").on("change", function() {
                let stateId = $(this).val();
                console.log(stateId);
                getLocationData("/cities", "#billingCity", "City", {
                    stateId: stateId
                });
            });

        });
    </script>
@endsection
