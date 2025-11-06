@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <form action="{{ route('staff.store') }}" method="post">
                        @csrf
                        @method('POST')
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
                                                <label for="marital" class="form-label">Role
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-select @error('role') is-invalid @enderror"
                                                    name="role" id="marital">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>
                                            
                                            <div class="col-12 col-md-6">
                                                <label for="marital" class="form-label">Warehouse Name
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-select @error('warehouses_id') is-invalid @enderror"
                                                    name="warehouse_id" id="warehouses_id">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('warehouses_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

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
                                                <label for="firstname" class="form-label">First
                                                    Name <span class="text-danger">*</span></label>
                                                <input type="text" name="fname" id="firstname"
                                                    class="form-control @error('fname') is-invalid @enderror" value=""
                                                    placeholder="Enter First Name">
                                                @error('fname')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="lastname" class="form-label">Last Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="lname" id="lastname"
                                                    class="form-control @error('lname') is-invalid @enderror" value=""
                                                    placeholder="Enter Last Name">
                                                @error('lname')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="phone" class="form-label">Phone number <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="phone" id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror" value=""
                                                    placeholder="Enter Phone number">
                                                @error('phone')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="email" class="form-label">E-mail address <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" name="email" id="email"
                                                    class="form-control @error('email') is-invalid @enderror" value=""
                                                    placeholder="Enter Email id">
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="password" class="form-label">Password <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror" value=""
                                                    placeholder="Enter password id">
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="dob" class="form-label">Date of Birth <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="dob" id="dob"
                                                    class="form-control @error('dob') is-invalid @enderror" value=""
                                                    placeholder="Enter Date of Birth">
                                                @error('dob')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="gender" class="form-label">Gender <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control @error('gender') is-invalid @enderror"
                                                    name="gender" id="gender">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="marital" class="form-label">Marital Status
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control @error('marital') is-invalid @enderror"
                                                    name="marital" id="marital">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                                @error('marital')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

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
                                                <label for="current-address" class="form-label">Current Address <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="current_address" id="current_address"
                                                    class="form-control @error('current_address') is-invalid @enderror" value=""
                                                    placeholder="Enter Current Address"></textarea>
                                                @error('current_address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="permanent-address" class="form-label">Permanent Address
                                                </label>
                                                <textarea name="permanent_address" id="permanent_address"
                                                    class="form-control @error('permanent_address') is-invalid @enderror" value=""
                                                    placeholder="Enter Permanent Address"></textarea>
                                                @error('permanent_address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="city" class="form-label">City<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="city" id="city"
                                                    class="form-control @error('city') is-invalid @enderror"
                                                    value="" placeholder="Enter City">
                                                @error('city')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="state" class="form-label">State <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="state" id="state"
                                                    class="form-control @error('state') is-invalid @enderror"
                                                    value="" placeholder="Enter State">
                                                @error('state')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="country" class="form-label">Country <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="country" id="country"
                                                    class="form-control @error('country') is-invalid @enderror"
                                                    value="" placeholder="Enter Country">
                                                @error('country')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="pincode" class="form-label">Pincode<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="pincode" id="pincode"
                                                    class="form-control @error('pincode') is-invalid @enderror"
                                                    value="" placeholder="Enter Pincode">
                                                @error('pincode')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

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