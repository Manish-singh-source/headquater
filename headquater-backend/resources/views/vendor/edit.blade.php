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
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Edit Vendor</h5>
                            <form class="row g-3" action="{{ route('vendor.update', $vendor->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    <label for="input1" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="input1" placeholder="First Name"
                                        name="firstName" value="{{ $vendor->first_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="input2" placeholder="Last Name"
                                        name="lastName" value="{{ $vendor->last_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="input3" placeholder="Phone"
                                        name="phone" value="{{ $vendor->phone_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="input4" placeholder="Email Id"
                                        name="email" value="{{ $vendor->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control" id="input5" placeholder="GST NO"
                                        name="gstNo" value="{{ $vendor->gst_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control" id="input6" placeholder="PAN NO"
                                        name="panNo" value="{{ $vendor->pan_number }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="input11" class="form-label">Address</label>
                                    <textarea class="form-control" id="input11" placeholder="Address ..." rows="3" name="address">{{ $vendor->address }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    <select id="shippingCountry" class="form-select" name="country">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="shippingState" class="form-label">State</label>
                                    <select id="shippingState" class="form-select" name="state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="shippingCity" class="form-label">City</label>
                                    <select id="shippingCity" class="form-select" name="city">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="input8" placeholder="Pin Code"
                                        name="pinCode" value="{{ $vendor->pin_code }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="input10" class="form-label">Account No</label>
                                    <input type="text" class="form-control" id="input10" placeholder="Account No"
                                        name="accountNo" value="{{ $vendor->bank_account_number }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">IFSC Code</label>
                                    <input type="text" class="form-control" id="input8" placeholder="IFSC Code"
                                        name="ifscCode" value="{{ $vendor->ifsc_number }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" id="input8" placeholder="Bank Name"
                                        name="bankName" value="{{ $vendor->bank_number }}">
                                </div>


                                <div class="col-md-4">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" class="form-select" name="status">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="1" {{ $vendor->status == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $vendor->status == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary px-4">Update</button>
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
