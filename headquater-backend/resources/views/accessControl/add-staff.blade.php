@extends('layouts.master')
@section('main-content')
<!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <form action="" method="post">
                        @csrf
                        @method('POST')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <h5 class="card-title mb-0">
                                                Role Access
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="marital" class="form-label">Role
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="role" id="marital">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Sales Person">Sales Person</option>
                                                <option value="Operation Manager">Operation Manager</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="marital" class="form-label">Status
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" id="marital">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
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
                                            <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="fname" id="firstname" class="form-control" value="" required="" placeholder="Enter First Name">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="lname" id="lastname" class="form-control" value="" required="" placeholder="Enter Last Name">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="phone" class="form-label">Phone number <span class="text-danger">*</span></label>
                                            <input type="text" required="" name="phone" id="phone" class="form-control" value="" placeholder="Enter Phone number">
                                        </div>


                                        <div class="col-12 col-md-6">
                                            <label for="email" class="form-label">E-mail address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control" value="" placeholder="Enter Email id" required="">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" name="dob" id="dob" class="form-control" value="" placeholder="Enter Date of Birth" required="">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="marital" class="form-label">Marital Status
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="marital" id="marital">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
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
                                            <label for="current-address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                            <textarea name="current_address" id="current_address" class="form-control" value="" placeholder="Enter Current Address"></textarea>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="permanent-address" class="form-label">Permanent Address </label>
                                            <textarea name="permanent_address" id="permanent_address" class="form-control" value="" placeholder="Enter Permanent Address"></textarea>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                            <input type="text" required="" name="city" id="city" class="form-control" value="" placeholder="Enter City">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                            <input type="text" name="state" id="state" class="form-control" value="" placeholder="Enter State" required="">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                            <input type="text" name="country" id="country" class="form-control" value="" required="" placeholder="Enter Country">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" name="pincode" id="pincode" class="form-control" value="" required="" placeholder="Enter Pincode">
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
    