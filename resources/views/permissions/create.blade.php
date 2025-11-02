@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <form action="{{ route('permission.store') }}" method="POST">
                @csrf 
                @method('POST')

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">Create Permission</h5>
                            </div>
                            
                            <div class="card-body">
                                <div>
                                    <label for="permission_name" class="form-label">
                                        Permission Name <span class="text-danger">*</span>
                                    </label>
                                    <input placeholder="Enter Role Name" name="name" value="" id="permission_name"
                                        class="form-control @error('name') is-invalid @enderror" type="text">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success waves ripple-light" id="add-btn">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </main>
    <!--end main wrapper-->
@endsection