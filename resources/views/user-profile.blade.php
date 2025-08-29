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
                            <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="card w-100 d-flex  flex-sm-row flex-col">
                <div class="col card-body d-flex">
                    <div class="position-relative justify-content-center">
                        <img src="{{ Auth::user()->profile_image ?? Avatar::create(Auth::user()->fname)->toBase64() }}"
                            id="profilePreview" class="img-fluid rounded" alt="">
                        <div class="text-center my-2 pt-2">
                            <h4 class="mb-1">{{ ucfirst($user->user_name) }}</h4>
                            <p class="mb-0">{{ ucfirst($user->fname) }} {{ ucfirst($user->lname) }}</p>
                        </div>
                    </div>
                </div>
                <ul class="col-10 list-group list-group-flush">
                    <li class="list-group-item">
                        <b>Phone No</b>
                        <br>
                        {{ $user->phone ?? 'NA' }}
                    </li>
                    <li class="list-group-item">
                        <b>Email</b>
                        <br>
                        {{ $user->email ?? 'NA' }}
                    </li>
                    <li class="list-group-item border-top">
                        <b>Address</b>
                        <br>
                        {{ $user->current_address ?? 'NA' }}
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-12 col-xl-8">
                    <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="">
                                    <h5 class="mb-0 fw-bold">Edit Profile</h5>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <span class="material-icons-outlined fs-5">more_vert</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                        <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                        <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                    </ul>
                                </div>
                            </div>
                            <form action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data"
                                method="POST" class="row g-4">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    <label for="input1" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('fname') is-invalid @enderror"
                                        name="fname" id="fname" placeholder="First Name" value="{{ $user->fname }}">
                                    @error('fname')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('lname') is-invalid @enderror"
                                        name="lname" id="input2" placeholder="Last Name" value="{{ $user->lname }}">
                                    @error('lname')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" id="input3" placeholder="Phone" value="{{ $user->phone }}">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" id="input4" placeholder="Email" value="{{ $user->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country</label>
                                    <select id="country" class="form-select" name="country">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="state" class="form-label">State</label>
                                    <select id="state" class="form-select" name="state">
                                        <option value="">Select State</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="city" class="form-label">City</label>
                                    <select id="city" class="form-select" name="city">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="input10" class="form-label">Zip</label>
                                    <input type="text" class="form-control @error('zip') is-invalid @enderror"
                                        id="input10" placeholder="Zip" name="zip" value="{{ $user->pincode }}">
                                    @error('zip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="input10" class="form-label">Profile Image</label>
                                    <input type="file"
                                        class="form-control @error('profile_image') is-invalid @enderror"
                                        id="inputProfileImage" name="profile_image" placeholder="Profile"
                                        accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="input11" class="form-label">Address</label>
                                    <textarea class="form-control @error('current_address') is-invalid @enderror" name="current_address" id="input11"
                                        placeholder="Address ..." rows="4" cols="4">{{ $user->current_address }}</textarea>
                                    @error('current_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-success px-4">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="card rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="">
                                    <h5 class="mb-0 fw-bold">Change Password</h5>
                                </div>

                            </div>
                            <div class="card-body mb-0">
                                <div class="form-group mb-3 row">
                                    <label class="form-label">Old Password</label>
                                    <div class="col-lg-12 col-xl-12">
                                        <input class="form-control" type="password" placeholder="Old Password">
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <label class="form-label">New Password</label>
                                    <div class="col-lg-12 col-xl-12">
                                        <input class="form-control" type="password" placeholder="New Password">
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="col-lg-12 col-xl-12">
                                        <input class="form-control" type="password" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-12 col-xl-12">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                        <button type="button" class="btn btn-danger">Cancel</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection


@section('script')
    <script>
        document.getElementById('inputProfileImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
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
                                    // how to add data-id attribute
                                    'data-id': country.id,
                                    value: country.name,
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

            getLocationData("/countries", '#country', "Country");

            $("#country").on("change", function() {
                let countryId = $(this).find('option:selected').data('id');
                // let countryId = $(this).val();
                // console.log(countryId);
                getLocationData("/states", "#state", "State", {
                    countryId: countryId
                });
            });

            $("#state").on("change", function() {
                let stateId = $(this).find('option:selected').data('id');
                // let stateId = $(this).val();
                // console.log(stateId);
                getLocationData("/cities", "#city", "City", {
                    stateId: stateId
                });
            });
        });
    </script>
@endsection
