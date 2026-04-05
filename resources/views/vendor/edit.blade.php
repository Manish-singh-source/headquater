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

            @include('layouts.errors')

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Edit Vendor</h5>
                            <form class="row g-3" action="{{ route('vendor.update', $vendor->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    <label for="input1" class="form-label">Vendor Code</label>
                                    <input type="text" class="form-control @error('vendor_code') is-invalid @enderror"
                                        value="{{ old('vendor_code', $vendor->vendor_code) }}" id="input1"
                                        placeholder="Vendor Code" name="vendor_code">
                                    @error('vendor_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input1" class="form-label">Client Name</label>
                                    <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                        value="{{ old('client_name', $vendor->client_name) }}" id="input1"
                                        placeholder="Client Name" name="client_name">
                                    @error('client_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                        value="{{ old('contact_name', $vendor->contact_name) }}" id="input2"
                                        placeholder="Contact Name" name="contact_name">
                                    @error('contact_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        value="{{ old('phone_number', $vendor->phone_number) }}" id="input3"
                                        placeholder="Phone Number" name="phone_number">
                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $vendor->email) }}" id="input4" placeholder="Email Id"
                                        name="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control @error('gst_number') is-invalid @enderror"
                                        value="{{ old('gst_number', $vendor->gst_number) }}" id="input5"
                                        placeholder="GST NO" name="gst_number">
                                    @error('gst_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
                                        value="{{ old('pan_number', $vendor->pan_number) }}" id="input6"
                                        placeholder="PAN NO" name="pan_number">
                                    @error('pan_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="shipping_address" class="form-label">Shipping Address</label>
                                    <textarea class="form-control  @error('shipping_address') is-invalid @enderror" value="" id="shipping_address"
                                        placeholder="Enter Full Address" rows="3" name="shipping_address">{{ old('shipping_address', $vendor->shipping_address) }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="shipping_country" class="form-label">Country</label>
                                    <select id="shipping_country" class="form-select @error('shipping_country') is-invalid @enderror" name="shipping_country">
                                        <option value="">Select Country</option>
                                    </select>
                                    @error('shipping_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="shipping_state" class="form-label">State</label>
                                    <select id="shipping_state" class="form-select @error('shipping_state') is-invalid @enderror" name="shipping_state">
                                        <option value="">Select State</option>
                                    </select>
                                    @error('shipping_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="shipping_city" class="form-label">City</label>
                                    <select id="shipping_city" class="form-select @error('shipping_city') is-invalid @enderror" name="shipping_city">
                                        <option value="">Select City</option>
                                    </select>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="shipping_zip" class="form-label">Pin Code</label>
                                    <input type="text"
                                        class="form-control  @error('shipping_zip') is-invalid @enderror"
                                        value="{{ old('shipping_zip', $vendor->shipping_zip) }}" id="shipping_zip"
                                        placeholder="Enter Pin Code" name="shipping_zip">
                                    @error('shipping_zip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="billing_address" class="form-label">Billing Address</label>
                                    <textarea class="form-control  @error('billing_address') is-invalid @enderror" value="" id="billing_address"
                                        placeholder="Enter Full Address" rows="3" name="billing_address">{{ old('billing_address', $vendor->billing_address) }}</textarea>
                                    @error('billing_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="billing_country" class="form-label">Country</label>
                                    <select id="billing_country" class="form-select @error('billing_country') is-invalid @enderror" name="billing_country">
                                        <option value="">Select Country</option>
                                    </select>
                                    @error('billing_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="billing_state" class="form-label">State</label>
                                    <select id="billing_state" class="form-select @error('billing_state') is-invalid @enderror" name="billing_state">
                                        <option value="">Select State</option>
                                    </select>
                                    @error('billing_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="billing_city" class="form-label">City</label>
                                    <select id="billing_city" class="form-select @error('billing_city') is-invalid @enderror" name="billing_city">
                                        <option value="">Select City</option>
                                    </select>
                                    @error('billing_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="billing_zip" class="form-label">Pin Code</label>
                                    <input type="text"
                                        class="form-control  @error('billing_zip') is-invalid @enderror"
                                        value="{{ old('billing_zip', $vendor->billing_zip) }}" id="billing_zip"
                                        placeholder="Enter Pin Code" name="billing_zip">
                                    @error('billing_zip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" class="form-select @error('status') is-invalid @enderror" name="status">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="1" {{ old('status', $vendor->status) === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $vendor->status) === '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
            </div>
        </div>
    </main>
@endsection




@section('script')
    <script>
        $(document).ready(function() {
            const selectedLocations = {
                shipping: {
                    country: @json(old('shipping_country', $vendor->shipping_country)),
                    state: @json(old('shipping_state', $vendor->shipping_state)),
                    city: @json(old('shipping_city', $vendor->shipping_city))
                },
                billing: {
                    country: @json(old('billing_country', $vendor->billing_country)),
                    state: @json(old('billing_state', $vendor->billing_state)),
                    city: @json(old('billing_city', $vendor->billing_city))
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
                        data.data.map(function(item) {
                            $(id).append(
                                $('<option>', {
                                    value: item.id,
                                    text: item.name
                                })
                            );
                        });

                        const selectedId = selectMatchingOption(id, selectedValue);
                        if (typeof callback === 'function') {
                            callback(selectedId);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            function initializeAddressDropdowns(countryId, stateId, cityId, values) {
                getLocationData('/countries', countryId, 'Country', null, values.country, function(selectedCountryId) {
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

            initializeAddressDropdowns('#shipping_country', '#shipping_state', '#shipping_city', selectedLocations.shipping);
            initializeAddressDropdowns('#billing_country', '#billing_state', '#billing_city', selectedLocations.billing);

            $('#shipping_country').on('change', function() {
                let countryId = $(this).val();
                resetDropdown('#shipping_state', 'State');
                resetDropdown('#shipping_city', 'City');

                if (!countryId) {
                    return;
                }

                getLocationData('/states', '#shipping_state', 'State', {
                    countryId: countryId
                });
            });

            $('#shipping_state').on('change', function() {
                let stateId = $(this).val();
                resetDropdown('#shipping_city', 'City');

                if (!stateId) {
                    return;
                }

                getLocationData('/cities', '#shipping_city', 'City', {
                    stateId: stateId
                });
            });

            $('#billing_country').on('change', function() {
                let countryId = $(this).val();
                resetDropdown('#billing_state', 'State');
                resetDropdown('#billing_city', 'City');

                if (!countryId) {
                    return;
                }

                getLocationData('/states', '#billing_state', 'State', {
                    countryId: countryId
                });
            });

            $('#billing_state').on('change', function() {
                let stateId = $(this).val();
                resetDropdown('#billing_city', 'City');

                if (!stateId) {
                    return;
                }

                getLocationData('/cities', '#billing_city', 'City', {
                    stateId: stateId
                });
            });
        });
    </script>
@endsection
