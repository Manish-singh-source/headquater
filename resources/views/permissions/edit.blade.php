@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <form action="{{ route('permission.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">Edit Permission</h5>
                            </div>

                            <div class="card-body">
                                <div>
                                    <label for="permission_name" class="form-label">
                                        Permission Name <span class="text-danger">*</span>
                                    </label>
                                    <input placeholder="Enter Role Name" name="name" id="permission_name" required=""
                                        class="form-control" type="text" value="{{ $permission->name }}">
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success waves ripple-light" id="add-btn">
                                        Update
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