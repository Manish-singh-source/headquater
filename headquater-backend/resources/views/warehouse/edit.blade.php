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
            </div>
            

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
                                    <input type="text" class="form-control" name="name" id="input1"
                                        value="{{ $warehouse->name }}" placeholder="Warehouse Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="input9" class="form-label">Warehouse Type</label>
                                    <select id="input9" class="form-select" name="type">
                                        <option disabled>Choose...</option>
                                        <option value="storage hub"
                                            {{ $warehouse->type == 'storage hub' ? 'selected' : '' }}>Storage Hub
                                        </option>
                                        <option value="return center"
                                            {{ $warehouse->type == 'return center' ? 'selected' : '' }}>Return Center
                                        </option>
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
                                        value="{{ $warehouse->phone }}" placeholder="Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Alternate Phone Number</label>
                                    <input type="text" class="form-control" name="contact_person_alt_phone_no"
                                        value="{{ $warehouse->alt_phone }}" id="input3"
                                        placeholder="Alternate Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="contact_person_email" id="input4"
                                        value="{{ $warehouse->email }}" placeholder="Email Id">
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control" name="gst_no" id="input5"
                                        value="{{ $warehouse->gst_number }}" placeholder="GST NO">
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control" name="pan_no" id="input6"
                                        value="{{ $warehouse->pan_number }}" placeholder="PAN NO">
                                </div>
                                <div class="col-md-6">
                                    <label for="input11" class="form-label">Address Line 1</label>
                                    <textarea class="form-control" id="input11" name="address_line_1" placeholder="Address Line 1" rows="3">{{ $warehouse->address_line_1 ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="input11" class="form-label">Address Line 2</label>
                                    <textarea class="form-control" id="input11" name="address_line_2" placeholder="Address Line 2" rows="3">{{ $warehouse->address_line_2 ?? '' }}</textarea>
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
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    <select id="shippingCountry" class="form-select" name="country_id">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="shippingState" class="form-label">State</label>
                                    <select id="shippingState" class="form-select" name="state_id">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="shippingCity" class="form-label">City</label>
                                    <select id="shippingCity" class="form-select" name="city_id">
                                        <option value="">Select City</option>
                                    </select>
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
                                        <option value="1" {{ $warehouse->status == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $warehouse->status == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="input9" class="form-label">Supported Operations</label>
                                    <select id="input9" name="supported_operations" class="form-select">
                                        <option selected="" disabled>Choose any one</option>
                                        <option value="inbound"
                                            {{ $warehouse->operations == 'inbound' ? 'selected' : '' }}>Inbound</option>
                                        <option value="outbound"
                                            {{ $warehouse->operations == 'outbound' ? 'selected' : '' }}>Outbound</option>
                                        <option value="return" {{ $warehouse->operations == 'return' ? 'selected' : '' }}>
                                            Return</option>
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
