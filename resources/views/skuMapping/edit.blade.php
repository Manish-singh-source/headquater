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
                            <h5 class="mb-4">SKU Mapping details</h5>
                            <form class="row g-3" action="{{ route('sku.mapping.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="sku_id" value="{{ $productMapping->id }}">

                                <div class="col-md-6">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                        value="{{ old('sku', $productMapping->sku) }}" id="sku" name="sku"
                                        placeholder="Enter Product SKU" readonly>
                                    @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="portal_code" class="form-label">Portal Code</label>
                                    <input type="text" class="form-control @error('portal_code') is-invalid @enderror"
                                        value="{{ old('portal_code', $productMapping->portal_code) }}" id="portal_code" name="portal_code"
                                        placeholder="Enter Product SKU">
                                    @error('portal_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="item_code" class="form-label">Item Code</label>
                                    <input type="text" class="form-control  @error('item_code') is-invalid @enderror"
                                        value="{{ old('item_code', $productMapping->item_code) }}" id="item_code" placeholder="Enter Customer SKU"
                                        name="item_code">
                                    @error('item_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="basic_rate" class="form-label">Basic Rate</label>
                                    <input type="basic_rate" class="form-control  @error('basic_rate') is-invalid @enderror"
                                        value="{{ old('basic_rate', $productMapping->basic_rate) }}" id="basic_rate" placeholder="Enter Vendor SKU" name="basic_rate">
                                    @error('basic_rate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="net_landing_rate" class="form-label">Net Landing Rate</label>
                                    <input type="net_landing_rate" class="form-control  @error('net_landing_rate') is-invalid @enderror"
                                        value="{{ old('net_landing_rate', $productMapping->net_landing_rate) }}" id="net_landing_rate" placeholder="Enter Vendor SKU" name="net_landing_rate">
                                    @error('net_landing_rate')
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
