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
                            <li class="breadcrumb-item active" aria-current="page">Edit Customer Details</li>
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
                            <form class="row g-3" action="{{ route('customer.update', $customer->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="group_id" value="{{ $group_id }}">

                                <div class="col-md-6">
                                    <label for="client_name" class="form-label">Client Name</label>
                                    <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                        value="{{ old('client_name', $customer->client_name) }}" id="client_name"
                                        name="client_name" placeholder="Enter Client Name">
                                    @error('client_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control  @error('contact_name') is-invalid @enderror"
                                        value="{{ old('contact_name', $customer->contact_name) }}" id="contact_name"
                                        placeholder="Enter Contact Name" name="contact_name">
                                    @error('contact_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control  @error('email') is-invalid @enderror"
                                        value="{{ old('email', $customer->email) }}" id="email"
                                        placeholder="Enter Email" name="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact_no" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control  @error('contact_no') is-invalid @enderror"
                                        value="{{ old('contact_no', $customer->contact_no) }}" id="contact_no"
                                        placeholder="Enter Contact Number" name="contact_no">
                                    @error('contact_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <h5 class="mt-4">Company details</h5>

                                <div class="col-md-6">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control  @error('companyName') is-invalid @enderror"
                                        value="{{ old('companyName', $customer->companyName) }}" id="companyName"
                                        placeholder="Enter Company Name" name="companyName">
                                    @error('companyName')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="gstin" class="form-label">GST Number</label>
                                    <input type="text" class="form-control  @error('gstin') is-invalid @enderror"
                                        value="{{ old('gstin', $customer->gstin) }}" id="gstin"
                                        placeholder="Enter GST Number" name="gstin">
                                    @error('gstin')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pan" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control  @error('pan') is-invalid @enderror"
                                        value="{{ old('pan', $customer->pan) }}" id="pan"
                                        placeholder="Enter PAN Number" name="pan">
                                    @error('pan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="shippingAddress" class="form-label">Shipping Address</label>
                                    <textarea class="form-control  @error('shippingAddress') is-invalid @enderror" value="" id="shippingAddress"
                                        placeholder="Enter Full Address" rows="3" name="shippingAddress">{{ old('shippingAddress', $customer->addresses->shipping_address) }}</textarea>
                                    @error('shippingAddress')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    <select id="shippingCountry" class="form-select" name="shippingCountry">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingState" class="form-label">State</label>
                                    <select id="shippingState" class="form-select" name="shippingState">
                                        <option value="">Select State</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCity" class="form-label">City</label>
                                    <select id="shippingCity" class="form-select" name="shippingCity">
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingPinCode" class="form-label">Pin Code</label>
                                    <input type="text"
                                        class="form-control  @error('shippingPinCode') is-invalid @enderror"
                                        value="{{ old('shippingPinCode', $customer->addresses->shippingPinCode) }}"
                                        id="shippingPinCode" placeholder="Enter Pin Code" name="shipping_zip">
                                    @error('shippingPinCode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="billingAddress" class="form-label">Billing Address</label>
                                    <textarea class="form-control  @error('billingAddress') is-invalid @enderror" value="" id="billingAddress"
                                        placeholder="Enter Full Address" rows="3" name="billingAddress">{{ old('billingAddress', $customer->addresses->billing_address) }}</textarea>
                                    @error('billingAddress')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCountry" class="form-label">Country</label>
                                    <select id="billingCountry" class="form-select" name="billingCountry">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingState" class="form-label">State</label>
                                    <select id="billingState" class="form-select" name="billingState">
                                        <option value="">Select State</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCity" class="form-label">City</label>
                                    <select id="billingCity" class="form-select" name="billingCity">
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingPinCode" class="form-label">Pin Code</label>
                                    <input type="text"
                                        class="form-control  @error('billingPinCode') is-invalid @enderror"
                                        value="{{ old('billingPinCode', $customer->addresses->billing_zip) }}" id="billingPinCode"
                                        placeholder="Enter Pin Code" name="billingPinCode">
                                    @error('billingPinCode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" class="form-select" name="status">
                                        <option @if($customer->status == '1') selected @endif value="1">Active</option>
                                        <option @if($customer->status == '0') selected @endif value="0">Inactive</option>
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
