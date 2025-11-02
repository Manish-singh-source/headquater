@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <form action="{{ route('staff.update', $staff->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-0">
                                                    Role Access
                                                </h5>
                                            </div>
                                            <div>
                                                <a href="{{ url()->previous() }}"
                                                    class="btn btn-primary float-end mt-n1">Back</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label for="role" class="form-label">Role
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="role" id="role">
                                                    <option disabled value="">-- Select --</option>
                                                    @foreach ($roles as $role)
                                                        @if ($role->id === $staff->role_id)
                                                            <option selected value="{{ $role->id }}">{{ $role->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="status" class="form-label">Status
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="status" id="status">
                                                    <option disabled value="">-- Select --</option>
                                                    @if ($staff->status == '1')
                                                        <option selected value="1">Active</option>
                                                    @else
                                                        <option selected value="0">Inactive</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <div class="row g-4 align-items-center">
                                            <div class="col-sm">
                                                <h5 class="card-title mb-0">
                                                    Personal Information
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label for="firstname" class="form-label">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="fname" id="firstname" class="form-control"
                                                    value="{{ $staff->fname }}" required=""
                                                    placeholder="Enter First Name">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="lastname" class="form-label">Last Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="lname" id="lastname" class="form-control"
                                                    value="{{ $staff->lname }}" required=""
                                                    placeholder="Enter Last Name">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="phone" class="form-label">Phone number <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required="" name="phone" id="phone"
                                                    class="form-control" value="{{ $staff->phone }}"
                                                    placeholder="Enter Phone number">
                                            </div>


                                            <div class="col-12 col-md-6">
                                                <label for="email" class="form-label">E-mail address <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ $staff->email }}" placeholder="Enter Email id" required="">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="dob" class="form-label">Date of Birth <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="dob" id="dob" class="form-control"
                                                    value="{{ $staff->dob }}" placeholder="Enter Date of Birth"
                                                    required="">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="gender" class="form-label">Gender <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="gender" id="gender">
                                                    <option disabled value="">-- Select --</option>
                                                    <option value="Male"
                                                        {{ $staff->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female"
                                                        {{ $staff->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Other"
                                                        {{ $staff->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="marital" class="form-label">Marital Status
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="marital" id="marital">
                                                    <option disabled value="">-- Select --</option>
                                                    <option value="Yes"
                                                        {{ $staff->marital == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No"
                                                        {{ $staff->marital == 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card pb-4">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="card-title mb-0">
                                            Address Details
                                        </h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label for="current_address" class="form-label">Current Address <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="current_address" id="current_address" class="form-control" value=""
                                                    placeholder="Enter Current Address"> {{ $staff->current_address }}</textarea>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="permanent-address" class="form-label">Permanent Address
                                                </label>
                                                <textarea name="permanent_address" id="permanent_address" class="form-control" value=""
                                                    placeholder="Enter Permanent Address">{{ $staff->current_address }}</textarea>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="city" class="form-label">City<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required="" name="city" id="city"
                                                    class="form-control" value="{{ $staff->city }}"
                                                    placeholder="Enter City">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="state" class="form-label">State <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="state" id="state" class="form-control"
                                                    value="{{ $staff->state }}" placeholder="Enter State"
                                                    required="">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="country" class="form-label">Country <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="country" id="country" class="form-control"
                                                    value="{{ $staff->country }}" required=""
                                                    placeholder="Enter Country">
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="pincode" class="form-label">Pincode<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="pincode" id="pincode" class="form-control"
                                                    value="{{ $staff->pincode }}" required=""
                                                    placeholder="Enter Pincode">
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>


                            <div class="col-lg-12">
                                <div class="text-start mb-3">
                                    <button type="submit" class="btn btn-success w-sm waves ripple-light">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection