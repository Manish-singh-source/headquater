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
                                        name="gstNo" value="{{ $customer->gst_no }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="panNumber" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" id="panNumber" placeholder="Enter PAN Number"
                                        name="panNo" value="{{ $customer->pan_no }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="shippingAddress" class="form-label">Shipping Address</label>
                                    <textarea class="form-control" id="shippingAddress" placeholder="Enter Full Address" rows="3"
                                        name="shippingAddress"> {{ $customer->shipping_address }}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCountry" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="shippingCountry"
                                        placeholder="Enter Country Name" name="shippingCountry"
                                        value="{{ $customer->shipping_country }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingState" class="form-label">State</label>
                                    <input type="text" class="form-control" id="shippingState"
                                        placeholder="Enter State Name" name="shippingState"
                                        value="{{ $customer->shipping_state }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingCity" class="form-label">City</label>
                                    <input type="text" class="form-control" id="shippingCity"
                                        placeholder="Enter City Name" name="shippingCity"
                                        value="{{ $customer->shipping_city }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="shippingPinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="shippingPinCode"
                                        placeholder="Enter Pin Code" name="shippingPinCode"
                                        value="{{ $customer->shipping_pin_code }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="billingAddress" class="form-label">Billing Address</label>
                                    <textarea class="form-control" id="billingAddress" placeholder="Enter Full Address" rows="3"
                                        name="billingAddress"> {{ $customer->billing_address }}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCountry" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="billingCountry"
                                        placeholder="Enter Country Name" name="billingCountry"
                                        value="{{ $customer->billing_country }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="billingState" class="form-label">State</label>
                                    <input type="text" class="form-control" id="billingState"
                                        placeholder="Enter State Name" name="billingState"
                                        value="{{ $customer->billing_state }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="billingCity" class="form-label">City</label>
                                    <input type="text" class="form-control" id="billingCity"
                                        placeholder="Enter City Name" name="billingCity"
                                        value="{{ $customer->billing_city }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="billingPinCode" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="billingPinCode"
                                        placeholder="Enter Pin Code" name="billingPinCode"
                                        value="{{ $customer->billing_pin_code }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" class="form-select" name="status"
                                        value="{{ $customer->status }}">
                                        <option selected>Active</option>
                                        <option>Inactive</option>
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
