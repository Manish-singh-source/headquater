@extends('layouts.master')
@section('main-content')
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active">Customer Form</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                        @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Customer details</h5>
                        <form class="row g-3" action="{{ route('customer.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $group_id }}">
                            <div class="col-md-6">
                                <label for="facility_name" class="form-label">Facility Name</label>
                                <input type="text" class="form-control @error('facility_name') is-invalid @enderror"
                                    value="{{ old('facility_name') }}" id="facility_name" name="facility_name" placeholder="Enter Facility Name">
                                @error('facility_name') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="client_name" class="form-label">Client Name</label>
                                <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                    value="{{ old('client_name') }}" id="client_name" name="client_name" placeholder="Enter Client Name">
                                @error('client_name') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                    value="{{ old('contact_name') }}" id="contact_name" name="contact_name" placeholder="Enter Contact Name">
                                @error('contact_name') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" id="email" name="email" placeholder="Enter Email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_no" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('contact_no') is-invalid @enderror"
                                    value="{{ old('contact_no') }}" id="contact_no" name="contact_no" placeholder="Enter Contact Number">
                                @error('contact_no') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <h5 class="mt-4">Company details</h5>

                            <div class="col-md-6">
                                <label for="companyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('companyName') is-invalid @enderror"
                                    value="{{ old('companyName') }}" id="companyName" name="companyName" placeholder="Enter Company Name">
                                @error('companyName') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gstin" class="form-label">GST Number</label>
                                <input type="text" class="form-control @error('gstin') is-invalid @enderror"
                                    value="{{ old('gstin') }}" id="gstin" name="gstin" placeholder="Enter GST Number">
                                @error('gstin') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pan" class="form-label">PAN Number</label>
                                <input type="text" class="form-control @error('pan') is-invalid @enderror"
                                    value="{{ old('pan') }}" id="pan" name="pan" placeholder="Enter PAN Number">
                                @error('pan') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label for="shippingAddress" class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('shippingAddress') is-invalid @enderror" id="shippingAddress"
                                    name="shippingAddress" rows="3" placeholder="Enter Full Address">{{ old('shippingAddress') }}</textarea>
                                @error('shippingAddress') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="shippingCountry" class="form-label">Country</label>
                                <select id="shippingCountry" class="form-select" name="shippingCountry">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('shippingCountry') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="shippingState" class="form-label">State</label>
                                <select id="shippingState" class="form-select" name="shippingState">
                                    <option value="">Select State</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}" {{ old('shippingState') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="shippingCity" class="form-label">City</label>
                                <select id="shippingCity" class="form-select" name="shippingCity">
                                    <option value="">Select City</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('shippingCity') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="shippingPinCode" class="form-label">Pin Code</label>
                                <input type="text" class="form-control @error('shippingPinCode') is-invalid @enderror"
                                    value="{{ old('shippingPinCode') }}" id="shippingPinCode" name="shippingPinCode" placeholder="Enter Pin Code">
                                @error('shippingPinCode') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label for="billingAddress" class="form-label">Billing Address</label>
                                <textarea class="form-control @error('billingAddress') is-invalid @enderror" id="billingAddress"
                                    name="billingAddress" rows="3" placeholder="Enter Full Address">{{ old('billingAddress') }}</textarea>
                                @error('billingAddress') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="billingCountry" class="form-label">Country</label>
                                <select id="billingCountry" class="form-select" name="billingCountry">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('billingCountry') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="billingState" class="form-label">State</label>
                                <select id="billingState" class="form-select" name="billingState">
                                    <option value="">Select State</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}" {{ old('billingState') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="billingCity" class="form-label">City</label>
                                <select id="billingCity" class="form-select" name="billingCity">
                                    <option value="">Select City</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('billingCity') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="billingPinCode" class="form-label">Pin Code</label>
                                <input type="text" class="form-control @error('billingPinCode') is-invalid @enderror"
                                    value="{{ old('billingPinCode') }}" id="billingPinCode" name="billingPinCode" placeholder="Enter Pin Code">
                                @error('billingPinCode') <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" class="form-select" name="status">
                                    <option value="1" {{ old('status', 1) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
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
