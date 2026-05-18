@extends('layouts.master')

@section('main-content')
    @php
        $fullName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
        $displayName = $fullName !== '' ? $fullName : ($user->user_name ?? 'User');
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();
        $statusClass = $user->status == '1' ? 'success' : 'danger';
        $statusText = $user->status == '1' ? 'Active' : 'Inactive';
        $passwordErrors = $errors->has('current_password') || $errors->has('password');
        $editErrors = $errors->any() && ! $passwordErrors;
    @endphp

    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card rounded-4">
                        <div class="card-body p-4 text-center">
                            <img src="{{ $user->profile_image ?? Avatar::create($user->fname ?? $displayName)->toBase64() }}"
                                class="rounded-circle p-1 border shadow-sm mb-3" width="120" height="120"
                                alt="Profile image">

                            <h4 class="mb-1">{{ ucwords($displayName) }}</h4>
                            <p class="text-muted mb-3">{{ $user->user_name ? '@' . $user->user_name : 'Staff Account' }}</p>

                            <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                                <span class="badge rounded-pill bg-{{ $statusClass }} px-3 py-2">{{ $statusText }}</span>
                                <span class="badge rounded-pill bg-primary px-3 py-2">
                                    {{ $roles->isNotEmpty() ? $roles->implode(', ') : 'Staff/Admin' }}
                                </span>
                            </div>

                            <ul class="list-group list-group-flush text-start mb-3">
                                <li class="list-group-item px-0 d-flex justify-content-between gap-3">
                                    <span class="text-muted">Email</span>
                                    <span class="fw-semibold text-end">{{ $user->email ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between gap-3">
                                    <span class="text-muted">Phone</span>
                                    <span class="fw-semibold text-end">{{ $user->phone ?? 'NA' }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between gap-3">
                                    <span class="text-muted">Warehouse</span>
                                    <span class="fw-semibold text-end">{{ $user->warehouse->name ?? 'NA' }}</span>
                                </li>
                            </ul>

                            <div class="d-grid gap-2">
                                <a href="{{ route('logout') }}" class="btn btn-outline-danger">
                                    <i class="bx bx-log-out me-1"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="card rounded-4">
                        <div class="card-header bg-light">
                            <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ ! $editErrors && ! $passwordErrors ? 'active' : '' }}"
                                        id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane"
                                        type="button" role="tab">Details</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $editErrors ? 'active' : '' }}" id="edit-profile-tab"
                                        data-bs-toggle="tab" data-bs-target="#edit-profile-pane" type="button"
                                        role="tab">Edit Data</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $passwordErrors ? 'active' : '' }}" id="change-password-tab"
                                        data-bs-toggle="tab" data-bs-target="#change-password-pane" type="button"
                                        role="tab">Change Password</button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content" id="profileTabsContent">
                                <div class="tab-pane fade {{ ! $editErrors && ! $passwordErrors ? 'show active' : '' }}"
                                    id="details-pane" role="tabpanel">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">First Name</label>
                                            <div class="form-control bg-light">{{ $user->fname ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Last Name</label>
                                            <div class="form-control bg-light">{{ $user->lname ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Email</label>
                                            <div class="form-control bg-light">{{ $user->email ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Phone</label>
                                            <div class="form-control bg-light">{{ $user->phone ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Role</label>
                                            <div class="form-control bg-light">
                                                {{ $roles->isNotEmpty() ? $roles->implode(', ') : 'NA' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Warehouse</label>
                                            <div class="form-control bg-light">{{ $user->warehouse->name ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Country</label>
                                            <div class="form-control bg-light">{{ $user->country ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">State</label>
                                            <div class="form-control bg-light">{{ $user->state ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">City</label>
                                            <div class="form-control bg-light">{{ $user->city ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Pincode</label>
                                            <div class="form-control bg-light">{{ $user->pincode ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Joined On</label>
                                            <div class="form-control bg-light">
                                                {{ $user->created_at ? $user->created_at->format('d M Y') : 'NA' }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Current Address</label>
                                            <div class="form-control bg-light">{{ $user->current_address ?? 'NA' }}</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Permanent Address</label>
                                            <div class="form-control bg-light">{{ $user->permanent_address ?? 'NA' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade {{ $editErrors ? 'show active' : '' }}" id="edit-profile-pane"
                                    role="tabpanel">
                                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                                        class="row g-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="col-md-6">
                                            <label for="fname" class="form-label">First Name</label>
                                            <input type="text" name="fname" id="fname"
                                                class="form-control @error('fname') is-invalid @enderror"
                                                value="{{ old('fname', $user->fname) }}" placeholder="First Name">
                                            @error('fname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="lname" class="form-label">Last Name</label>
                                            <input type="text" name="lname" id="lname"
                                                class="form-control @error('lname') is-invalid @enderror"
                                                value="{{ old('lname', $user->lname) }}" placeholder="Last Name">
                                            @error('lname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}" placeholder="Email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="phone" id="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', $user->phone) }}" placeholder="Phone">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" name="country" id="country"
                                                class="form-control @error('country') is-invalid @enderror"
                                                value="{{ old('country', $user->country) }}" placeholder="Country">
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" name="state" id="state"
                                                class="form-control @error('state') is-invalid @enderror"
                                                value="{{ old('state', $user->state) }}" placeholder="State">
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" name="city" id="city"
                                                class="form-control @error('city') is-invalid @enderror"
                                                value="{{ old('city', $user->city) }}" placeholder="City">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pincode" class="form-label">Pincode</label>
                                            <input type="text" name="pincode" id="pincode"
                                                class="form-control @error('pincode') is-invalid @enderror"
                                                value="{{ old('pincode', $user->pincode) }}" placeholder="Pincode">
                                            @error('pincode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="profile_image" class="form-label">Profile Image</label>
                                            <input type="file" name="profile_image" id="profile_image"
                                                class="form-control @error('profile_image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('profile_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="current_address" class="form-label">Current Address</label>
                                            <textarea name="current_address" id="current_address" rows="3"
                                                class="form-control @error('current_address') is-invalid @enderror" placeholder="Current Address">{{ old('current_address', $user->current_address) }}</textarea>
                                            @error('current_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="permanent_address" class="form-label">Permanent Address</label>
                                            <textarea name="permanent_address" id="permanent_address" rows="3"
                                                class="form-control @error('permanent_address') is-invalid @enderror" placeholder="Permanent Address">{{ old('permanent_address', $user->permanent_address) }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success px-4">
                                                <i class="bx bx-save me-1"></i>Update Profile
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade {{ $passwordErrors ? 'show active' : '' }}"
                                    id="change-password-pane" role="tabpanel">
                                    <form action="{{ route('profile.change-password') }}" method="POST" class="row g-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="col-12">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" name="current_password" id="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                placeholder="Current Password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="New Password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control" placeholder="Confirm Password">
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="bx bx-lock-alt me-1"></i>Change Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
