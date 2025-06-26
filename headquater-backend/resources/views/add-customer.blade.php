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
                            <form class="row g-3" action="{{ route('store_customer') }}" method="POST">
                                @csrf
                                @method('POST')

                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName"
                                        placeholder="Enter First Name">
                                </div>

                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name"
                                        name="lastName">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email"
                                        name="email">
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone"
                                        placeholder="Enter Phone Number" name="phone">
                                </div>

                                <h5 class="mt-4">Company details</h5>

                                <div class="col-md-6">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="companyName"
                                        placeholder="Enter Company Name" name="companyName">
                                </div>

                                <div class="col-md-6">
                                    <label for="gstNumber" class="form-label">GST Number</label>
                                    <input type="text" class="form-control" id="gstNumber" placeholder="Enter GST Number"
                                        name="gstNo">
                                </div>

                                <div class="col-md-6">
                                    <label for="panNumber" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" id="panNumber" placeholder="Enter PAN Number"
                                        name="panNo">
                                </div>

                                <div class="col-md-12">
                                    <label for="shippingAddress" class="form-label">Shipping Address</label>
                                    <textarea class="form-control" id="shippingAddress" placeholder="Enter Full Address" rows="3"
                                        name="shippingAddress"></textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    {{-- <input type="text" class="form-control" id="shippingCountry"
                                        placeholder="Enter Country Name" name="shippingCountry"> --}}
                                    <select id="shippingCountry" class="form-select" name="shippingCountry">
                                        <option selected disabled>Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingState" class="form-label">State</label>
                                    {{-- <input type="text" class="form-control" id="shippingState"
                                        placeholder="Enter State Name" name="shippingState"> --}}
                                    <select id="shippingState" class="form-select" name="shippingState">
                                        <option selected disabled>Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCity" class="form-label">City</label>
                                    {{-- <input type="text" class="form-control" id="shippingCity"
                                        placeholder="Enter City Name" name="shippingCity"> --}}
                                    <select id="shippingCity" class="form-select" name="shippingCity">
                                        <option selected disabled>Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingPinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="shippingPinCode"
                                        placeholder="Enter Pin Code" name="shippingPinCode">
                                </div>

                                <div class="col-md-12">
                                    <label for="billingAddress" class="form-label">Billing Address</label>
                                    <textarea class="form-control" id="billingAddress" placeholder="Enter Full Address" rows="3"
                                        name="billingAddress"></textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCountry" class="form-label">Country</label>
                                    {{-- <input type="text" class="form-control" id="billingCountry"
                                        placeholder="Enter Country Name" name="billingCountry"> --}}
                                    <select id="billingCountry" class="form-select" name="billingCountry">
                                        <option selected disabled>Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingState" class="form-label">State</label>
                                    {{-- <input type="text" class="form-control" id="billingState"
                                        placeholder="Enter State Name" name="billingState"> --}}
                                    <select id="billingState" class="form-select" name="billingState">
                                        <option selected disabled>Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCity" class="form-label">City</label>
                                    {{-- <input type="text" class="form-control" id="billingCity"
                                        placeholder="Enter City Name" name="billingCity"> --}}
                                    <select id="billingCity" class="form-select" name="billingCity">
                                        <option selected disabled>Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingPinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="billingPinCode"
                                        placeholder="Enter Pin Code" name="billingPinCode">
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
