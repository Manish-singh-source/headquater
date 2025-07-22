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

        <!-- <div class="col-md-6">
            <label for="input3" class="form-label">Phone Number</label>
            <input type="text" class="form-control @error('contact_person_phone_no') is-invalid @enderror" name="contact_person_phone_no" id="input3"
                placeholder="Phone Number">
            @error('contact_person_phone_no')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror      
        </div> -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Customer details</h5>
                        <form class="row g-3" action="{{ route('store_customer') }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('firstName') is-invalid @enderror" value="{{ old('firstName') }}" id="firstName" name="firstName"
                                    placeholder="Enter First Name">
                                @error('firstName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control  @error('lastName') is-invalid @enderror" value="{{ old('lastName') }}" id="lastName" placeholder="Enter Last Name"
                                    name="lastName">
                                @error('lastName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control  @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" placeholder="Enter Email"
                                    name="email">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control  @error('phone') is-invalid @enderror" value="{{ old('phone') }}" id="phone"
                                    placeholder="Enter Phone Number" name="phone">
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <h5 class="mt-4">Company details</h5>

                            <div class="col-md-6">
                                <label for="companyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control  @error('companyName') is-invalid @enderror" value="{{ old('companyName') }}" id="companyName"
                                    placeholder="Enter Company Name" name="companyName">
                                @error('companyName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <div class="col-md-6">
                                <label for="gstNumber" class="form-label">GST Number</label>
                                <input type="text" class="form-control  @error('gstNo') is-invalid @enderror" value="{{ old('gstNo') }}" id="gstNumber" placeholder="Enter GST Number"
                                    name="gstNo">
                                @error('gstNo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <div class="col-md-6">
                                <label for="panNumber" class="form-label">PAN Number</label>
                                <input type="text" class="form-control  @error('panNo') is-invalid @enderror" value="{{ old('panNo') }}" id="panNumber" placeholder="Enter PAN Number"
                                    name="panNo">
                                @error('panNo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <div class="col-md-12">
                                <label for="shippingAddress" class="form-label">Shipping Address</label>
                                <textarea class="form-control  @error('shippingAddress') is-invalid @enderror" value="" id="shippingAddress" placeholder="Enter Full Address" rows="3"
                                    name="shippingAddress">{{ old('shippingAddress') }}</textarea>
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
                                <input type="text" class="form-control  @error('shippingPinCode') is-invalid @enderror" value="{{ old('shippingPinCode') }}" id="shippingPinCode"
                                    placeholder="Enter Pin Code" name="shippingPinCode">
                                @error('shippingPinCode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror    
                            </div>

                            <div class="col-md-12">
                                <label for="billingAddress" class="form-label">Billing Address</label>
                                <textarea class="form-control  @error('billingAddress') is-invalid @enderror" value="" id="billingAddress" placeholder="Enter Full Address" rows="3"
                                    name="billingAddress">{{ old('billingAddress') }}</textarea>
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
                                <input type="text" class="form-control  @error('billingPinCode') is-invalid @enderror" value="{{ old('billingPinCode') }}" id="billingPinCode"
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
                                    <option selected value="1">Active</option>
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