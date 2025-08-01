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
                            <li class="breadcrumb-item active" aria-current="page">SKU Mapping Form</li>
                        </ol>
                    </nav>
                </div>

            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">SKU details</h5>
                            <form class="row g-3" action="{{ route('sku.mapping.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="sku_id" value="{{ $skuMapping->id }}">

                                <div class="col-md-6">
                                    <label for="product_sku" class="form-label">Product SKU</label>
                                    <input type="text" class="form-control @error('product_sku') is-invalid @enderror"
                                        value="{{ old('product_sku', $skuMapping->product_sku) }}" id="product_sku" name="product_sku"
                                        placeholder="Enter Product SKU">
                                    @error('product_sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_sku" class="form-label">Customer SKU</label>
                                    <input type="text" class="form-control  @error('customer_sku') is-invalid @enderror"
                                        value="{{ old('customer_sku', $skuMapping->customer_sku) }}" id="customer_sku" placeholder="Enter Customer SKU"
                                        name="customer_sku">
                                    @error('customer_sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="vendor_sku" class="form-label">Vendor SKU</label>
                                    <input type="vendor_sku" class="form-control  @error('vendor_sku') is-invalid @enderror"
                                        value="{{ old('vendor_sku', $skuMapping->vendor_sku) }}" id="vendor_sku" placeholder="Enter Vendor SKU" name="vendor_sku">
                                    @error('vendor_sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary px-4">Submit</button>
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
