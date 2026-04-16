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

            @include('layouts.errors')

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Customer details</h5>
                            <form class="row g-3" action="{{ route('customer.update', $customer->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="group_id" value="{{ $group_id ?? '' }}">

                                <div class="col-md-6">
                                    <label for="facility_name" class="form-label">Facility Name</label>
                                    <input type="text"
                                        class="form-control @if ($errors->has('facility_name')) is-invalid @endif"
                                        value="{{ old('facility_name', $customer->facility_name) }}" id="facility_name"
                                        name="facility_name" placeholder="Enter Facility Name">
                                    @if ($errors->has('facility_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('facility_name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="client_name" class="form-label">Client Name</label>
                                    <input type="text"
                                        class="form-control @if ($errors->has('client_name')) is-invalid @endif"
                                        value="{{ old('client_name', $customer->client_name) }}" id="client_name"
                                        name="client_name" placeholder="Enter Client Name">
                                    @if ($errors->has('client_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('client_name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label">Contact Name</label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('contact_name')) is-invalid @endif"
                                        value="{{ old('contact_name', $customer->contact_name) }}" id="contact_name"
                                        placeholder="Enter Contact Name" name="contact_name">
                                    @if ($errors->has('contact_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('contact_name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email"
                                        class="form-control  @if ($errors->has('email')) is-invalid @endif"
                                        value="{{ old('email', $customer->email) }}" id="email"
                                        placeholder="Enter Email" name="email">
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="contact_no" class="form-label">Contact Number</label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('contact_no')) is-invalid @endif"
                                        value="{{ old('contact_no', $customer->contact_no) }}" id="contact_no"
                                        placeholder="Enter Contact Number" name="contact_no">
                                    @if ($errors->has('contact_no'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('contact_no') }}
                                        </div>
                                    @endif
                                </div>

                                <h5 class="mt-4">Company details</h5>

                                <div class="col-md-6">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('company_name')) is-invalid @endif"
                                        value="{{ old('company_name', $customer->company_name) }}" id="companyName"
                                        placeholder="Enter Company Name" name="company_name">
                                    @if ($errors->has('company_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('company_name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="gstin" class="form-label">GST Number</label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('gstin')) is-invalid @endif"
                                        value="{{ old('gstin', $customer->gstin) }}" id="gstin"
                                        placeholder="Enter GST Number" name="gstin">
                                    @if ($errors->has('gstin'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('gstin') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="pan" class="form-label">PAN Number</label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('pan')) is-invalid @endif"
                                        value="{{ old('pan', $customer->pan) }}" id="pan"
                                        placeholder="Enter PAN Number" name="pan">
                                    @if ($errors->has('pan'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('pan') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="shippingAddress" class="form-label">Shipping Address <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control  @if ($errors->has('shipping_address')) is-invalid @endif" value=""
                                        id="shippingAddress" placeholder="Enter Full Address" rows="3" name="shipping_address">{{ old('shipping_address', $customer->shipping_address) }}</textarea>
                                    @if ($errors->has('shipping_address'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_address') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCountry" class="form-label">Country <span
                                            class="text-danger">*</span></label>
                                    <select id="shippingCountry"
                                        class="form-select @if ($errors->has('shipping_country')) is-invalid @endif"
                                        name="shipping_country">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('shipping_country', $customer->shipping_country) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('shipping_country'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_country') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingState" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <select id="shippingState"
                                        class="form-select @if ($errors->has('shipping_state')) is-invalid @endif"
                                        name="shipping_state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                {{ old('shipping_state', $customer->shipping_state) == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('shipping_state'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_state') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCity" class="form-label">City <span
                                            class="text-danger">*</span></label>
                                    <select id="shippingCity"
                                        class="form-select @if ($errors->has('shipping_city')) is-invalid @endif"
                                        name="shipping_city">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('shipping_city', $customer->shipping_city) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('shipping_city'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_city') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingPinCode" class="form-label">Pin Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('shipping_zip')) is-invalid @endif"
                                        value="{{ old('shipping_zip', $customer->shipping_zip) }}" id="shippingPinCode"
                                        placeholder="Enter Pin Code" name="shipping_zip">
                                    @if ($errors->has('shipping_zip'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_zip') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="billingAddress" class="form-label">Billing Address <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control  @if ($errors->has('billing_address')) is-invalid @endif" value=""
                                        id="billingAddress" placeholder="Enter Full Address" rows="3" name="billing_address">{{ old('billing_address', $customer->billing_address) }}</textarea>
                                    @if ($errors->has('billing_address'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('billing_address') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCountry" class="form-label">Country <span
                                            class="text-danger">*</span></label>
                                    <select id="billingCountry"
                                        class="form-select @if ($errors->has('billing_country')) is-invalid @endif"
                                        name="billing_country">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('billing_country', $customer->billing_country) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('billing_country'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('billing_country') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="billingState" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <select id="billingState"
                                        class="form-select @if ($errors->has('billing_state')) is-invalid @endif"
                                        name="billing_state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                {{ old('billing_state', $customer->billing_state) == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('billing_state'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('billing_state') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCity" class="form-label">City <span
                                            class="text-danger">*</span></label>
                                    <select id="billingCity"
                                        class="form-select @if ($errors->has('billing_city')) is-invalid @endif"
                                        name="billing_city">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('billing_city', $customer->billing_city) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('billing_city'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('billing_city') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="billingPinCode" class="form-label">Pin Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control  @if ($errors->has('billing_zip')) is-invalid @endif"
                                        value="{{ old('billing_zip', $customer->billing_zip) }}" id="billingPinCode"
                                        placeholder="Enter Pin Code" name="billing_zip">
                                    @if ($errors->has('billing_zip'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('billing_zip') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" class="form-select" name="status">
                                        <option @if ($customer->status == '1') selected @endif value="1">Active
                                        </option>
                                        <option @if ($customer->status == '0') selected @endif value="0">Inactive
                                        </option>
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
            const selectedLocations = {
                shipping: {
                    country: @json(old('shipping_country', $customer->shipping_country)),
                    state: @json(old('shipping_state', $customer->shipping_state)),
                    city: @json(old('shipping_city', $customer->shipping_city))
                },
                billing: {
                    country: @json(old('billing_country', $customer->billing_country)),
                    state: @json(old('billing_state', $customer->billing_state)),
                    city: @json(old('billing_city', $customer->billing_city))
                }
            };

            function resetDropdown(id, tag) {
                $(id).empty().append(`<option value="">Select ${tag}</option>`);
            }

            function selectMatchingOption(id, selectedValue) {
                if (!selectedValue) {
                    return '';
                }

                const normalizedValue = String(selectedValue).trim().toLowerCase();
                const matchingOption = $(id).find('option').filter(function() {
                    return String($(this).val()).trim() === String(selectedValue).trim() ||
                        $(this).text().trim().toLowerCase() === normalizedValue;
                }).first();

                if (!matchingOption.length) {
                    return '';
                }

                $(id).val(matchingOption.val());
                return matchingOption.val();
            }

            function getLocationData(url, id, tag, data = null, selectedValue = null, callback = null) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
                    success: function(data) {
                        resetDropdown(id, tag);
                        data.data.map(function(country) {
                            $(id).append(
                                $('<option>', {
                                    value: country.id,
                                    text: country.name
                                })
                            );
                        });

                        const resolvedValue = selectMatchingOption(id, selectedValue);

                        if (typeof callback === 'function') {
                            callback(resolvedValue);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            function initializeAddressDropdowns(countryId, stateId, cityId, values) {
                getLocationData('/countries', countryId, 'Country', null, values.country, function(
                    selectedCountryId) {
                    resetDropdown(stateId, 'State');
                    resetDropdown(cityId, 'City');

                    if (!selectedCountryId) {
                        return;
                    }

                    getLocationData('/states', stateId, 'State', {
                        countryId: selectedCountryId
                    }, values.state, function(selectedStateId) {
                        resetDropdown(cityId, 'City');

                        if (!selectedStateId) {
                            return;
                        }

                        getLocationData('/cities', cityId, 'City', {
                            stateId: selectedStateId
                        }, values.city);
                    });
                });
            }

            initializeAddressDropdowns('#shippingCountry', '#shippingState', '#shippingCity', selectedLocations
                .shipping);
            initializeAddressDropdowns('#billingCountry', '#billingState', '#billingCity', selectedLocations
                .billing);

            $('#shippingCountry').on('change', function() {
                let countryId = $(this).val();
                resetDropdown('#shippingState', 'State');
                resetDropdown('#shippingCity', 'City');

                if (!countryId) {
                    return;
                }

                getLocationData('/states', '#shippingState', 'State', {
                    countryId: countryId
                });
            });

            $('#shippingState').on('change', function() {
                let stateId = $(this).val();
                resetDropdown('#shippingCity', 'City');

                if (!stateId) {
                    return;
                }

                getLocationData('/cities', '#shippingCity', 'City', {
                    stateId: stateId
                });
            });

            $('#billingCountry').on('change', function() {
                let countryId = $(this).val();
                resetDropdown('#billingState', 'State');
                resetDropdown('#billingCity', 'City');

                if (!countryId) {
                    return;
                }

                getLocationData('/states', '#billingState', 'State', {
                    countryId: countryId
                });
            });

            $('#billingState').on('change', function() {
                let stateId = $(this).val();
                resetDropdown('#billingCity', 'City');

                if (!stateId) {
                    return;
                }

                getLocationData('/cities', '#billingCity', 'City', {
                    stateId: stateId
                });
            });
        });
    </script>
@endsection
