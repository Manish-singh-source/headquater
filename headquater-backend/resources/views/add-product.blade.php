@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <h5 class="card-title mb-0">
                                                Add New Products
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('store.products') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row g-3">
                                            <div class="col-12 col-lg-3">
                                                <label for="warehouse" class="form-label">Warehouse
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="warehouse_id" id="warehouse">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="products_excel" class="form-label">Products Sheet <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="products_excel" id="products_excel"
                                                    class="form-control" multiple>
                                            </div>
                                            <div class="col-12 col-lg-3 text-start">
                                                <button type=""
                                                    class="btn btn-success w-sm waves ripple-light text-center mt-md-4">
                                                    Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            

                            <div class="col-lg-12">
                                <div class="text-end mb-3">

                                    <a href="{{ route('products') }}" type=""
                                        class="btn btn-success w-sm waves ripple-light">
                                        Save
                                    </a>
                                </div>
                            </div>



                        </div>


                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection
