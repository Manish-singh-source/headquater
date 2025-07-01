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
                            <h5 class="mb-4">Create Vendor</h5>
                            <form class="row g-3" action="{{ route('vendor.add') }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="col-md-6">
                                    <label for="input1" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                        value="{{ old('firstName') }}" id="input1" placeholder="First Name"
                                        name="firstName">
                                    @error('firstName')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                        value="{{ old('lastName') }}" id="input2" placeholder="Last Name"
                                        name="lastName">
                                    @error('lastName')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" id="input3" placeholder="Phone" name="phone">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" id="input4" placeholder="Email Id" name="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control @error('gstNo') is-invalid @enderror"
                                        value="{{ old('gstNo') }}" id="input5" placeholder="GST NO" name="gstNo">
                                    @error('gstNo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control @error('panNo') is-invalid @enderror"
                                        value="{{ old('panNo') }}" id="input6" placeholder="PAN NO" name="panNo">
                                    @error('panNo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="input11" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" id="input11"
                                        placeholder="Address ..." rows="3" name="address"></textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                                    <input type="text" class="form-control @error('pinCode') is-invalid @enderror"
                                        value="{{ old('pinCode') }}" id="input8" placeholder="Pin Code"
                                        name="pinCode">
                                    @error('pinCode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="input10" class="form-label">Account No</label>
                                    <input type="text" class="form-control @error('accountNo') is-invalid @enderror"
                                        value="{{ old('accountNo') }}" id="input10" placeholder="Account No"
                                        name="accountNo">
                                    @error('accountNo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">IFSC Code</label>
                                    <input type="text" class="form-control @error('ifscCode') is-invalid @enderror"
                                        value="{{ old('ifscCode') }}" id="input8" placeholder="IFSC Code"
                                        name="ifscCode">
                                    @error('ifscCode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control @error('bankName') is-invalid @enderror"
                                        value="{{ old('bankName') }}" id="input8" placeholder="Bank Name"
                                        name="bankName">
                                    @error('bankName')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" class="form-select" name="status">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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
