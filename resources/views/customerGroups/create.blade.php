@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <!-- Breadcrumb -->
            
            <div class="page-header mb-3">
                <div class="d-flex align-items-center justify-content-between">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('index') }}" class="d-inline-flex align-items-center" aria-label="Dashboard">
                                    <div class="parent-icon"><i class="bi bi-house-fill"></i></div>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('customer.groups.index') }}">Customer Groups</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Create Customer Group</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('layouts.errors')

            <!-- Create Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Create Customer Group</h5>
                                <a href="{{ route('customer.groups.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('customer.groups.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Customer Group Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" id="name" placeholder="Enter Group Name"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="csv_file" class="form-label">Customers Data (Excel/CSV) <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="csv_file" id="csv_file"
                                                class="form-control @error('csv_file') is-invalid @enderror" required>
                                            @error('csv_file')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="text-muted">Upload Excel or CSV file with customer data</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Submit
                                        </button>
                                        <a href="{{ route('customer.groups.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Cancel
                                        </a>
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
