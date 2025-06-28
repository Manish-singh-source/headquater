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
                            <li class="breadcrumb-item active" aria-current="page">Customer Form</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Customer details</h5>
                            <form class="row g-3" action="{{ route('update-customer', $customer->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName"
                                        placeholder="Enter First Name" value="{{ $customer->first_name }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name"
                                        name="lastName" value="{{ $customer->last_name }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email"
                                        name="email" value="{{ $customer->email }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone"
                                        placeholder="Enter Phone Number" name="phone" value="{{ $customer->phone }}">
                                </div>

                                <h5 class="mt-4">Company details</h5>

                                <div class="col-md-6">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="companyName"
                                        placeholder="Enter Company Name" name="companyName"
                                        value="{{ $customer->company_name }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="gstNumber" class="form-label">GST Number</label>
                                    <input type="text" class="form-control" id="gstNumber" placeholder="Enter GST Number"
                                        name="gstNo" value="{{ $customer->gst_number }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="panNumber" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" id="panNumber" placeholder="Enter PAN Number"
                                        name="panNo" value="{{ $customer->pan_number }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="shippingAddress" class="form-label">Shipping Address</label>
                                    <textarea class="form-control" id="shippingAddress" placeholder="Enter Full Address" rows="3"
                                        name="shippingAddress"> {{ $customer->shipping_address }}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    <select id="shippingCountry" class="form-select" name="country">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="shippingState" class="form-label">State</label>
                                    <select id="shippingState" class="form-select" name="state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="shippingCity" class="form-label">City</label>
                                    <select id="shippingCity" class="form-select" name="city">
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingPinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="shippingPinCode"
                                        placeholder="Enter Pin Code" name="shippingPinCode"
                                        value="{{ $customer->shipping_pincode }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="billingAddress" class="form-label">Billing Address</label>
                                    <textarea class="form-control" id="billingAddress" placeholder="Enter Full Address" rows="3"
                                        name="billingAddress"> {{ $customer->billing_address }}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCountry" class="form-label">Country</label>
                                    <select id="billingCountry" class="form-select" name="country">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="billingState" class="form-label">State</label>
                                    <select id="billingState" class="form-select" name="state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="billingCity" class="form-label">City</label>
                                    <select id="billingCity" class="form-select" name="city">
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingPinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="billingPinCode"
                                        placeholder="Enter Pin Code" name="billingPinCode"
                                        value="{{ $customer->billing_pincode }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" class="form-select" name="status">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="1" {{ $customer->status == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $customer->status == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <!-- <a href="{{ route('customers') }}" type="submit"
                                                            class="btn btn-primary px-4">Submit</a> -->
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
