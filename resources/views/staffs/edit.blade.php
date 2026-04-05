@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
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
                                                <select class="form-control @error('role') is-invalid @enderror" name="role" id="role">
                                                    <option disabled value="">-- Select --</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" {{ old('role', $currentRole->id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="status" class="form-label">Status
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                                    <option disabled value="">-- Select --</option>
                                                    <option value="1" {{ old('status', $staff->status) == '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status', $staff->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('status')
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
                                                <label for="firstname" class="form-label">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="fname" id="firstname" class="form-control @error('fname') is-invalid @enderror"
                                                    value="{{ old('fname', $staff->fname) }}" required=""
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
                                                <input type="text" name="lname" id="lastname" class="form-control @error('lname') is-invalid @enderror"
                                                    value="{{ old('lname', $staff->lname) }}" required=""
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
                                                <input type="text" required="" name="phone" id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $staff->phone) }}"
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
                                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email', $staff->email) }}" placeholder="Enter Email id" required="">
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="dob" class="form-label">Date of Birth <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="dob" id="dob" class="form-control @error('dob') is-invalid @enderror"
                                                    value="{{ old('dob', $staff->dob) }}" placeholder="Enter Date of Birth"
                                                    required="">
                                                @error('dob')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="gender" class="form-label">Gender <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                                    <option disabled value="">-- Select --</option>
                                                    <option value="Male"
                                                        {{ old('gender', $staff->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female"
                                                        {{ old('gender', $staff->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Other"
                                                        {{ old('gender', $staff->gender) == 'Other' ? 'selected' : '' }}>Other</option>
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
                                                <select class="form-control @error('marital') is-invalid @enderror" name="marital" id="marital">
                                                    <option disabled value="">-- Select --</option>
                                                    <option value="Yes"
                                                        {{ old('marital', $staff->marital) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No"
                                                        {{ old('marital', $staff->marital) == 'No' ? 'selected' : '' }}>No</option>
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
                                                <label for="current_address" class="form-label">Current Address <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="current_address" id="current_address" class="form-control @error('current_address') is-invalid @enderror"
                                                    placeholder="Enter Current Address">{{ old('current_address', $staff->current_address) }}</textarea>
                                                @error('current_address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="permanent-address" class="form-label">Permanent Address
                                                </label>
                                                <textarea name="permanent_address" id="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror"
                                                    placeholder="Enter Permanent Address">{{ old('permanent_address', $staff->permanent_address) }}</textarea>
                                                @error('permanent_address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="city" class="form-label">City<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required="" name="city" id="city"
                                                    class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $staff->city) }}"
                                                    placeholder="Enter City">
                                                @error('city')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="state" class="form-label">State <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror"
                                                    value="{{ old('state', $staff->state) }}" placeholder="Enter State"
                                                    required="">
                                                @error('state')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="country" class="form-label">Country <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror"
                                                    value="{{ old('country', $staff->country) }}" required=""
                                                    placeholder="Enter Country">
                                                @error('country')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="pincode" class="form-label">Pincode<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="pincode" id="pincode" class="form-control @error('pincode') is-invalid @enderror"
                                                    value="{{ old('pincode', $staff->pincode) }}" required=""
                                                    placeholder="Enter Pincode">
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
@endsection