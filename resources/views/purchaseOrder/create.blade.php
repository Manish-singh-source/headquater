@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="ps-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Create Purchase Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Create Purchase Order</h5>
                            {{-- Flash messages --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if (session('purchase_excel'))
                                <div class="alert alert-danger">{{ session('purchase_excel') }}</div>
                            @endif
                            @if (session('pi_excel'))
                                <div class="alert alert-danger">{{ session('pi_excel') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="row g-3" action="{{ route('store.purchase.order') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                @isset($purchaseId)
                                    <div class="col-md-4">
                                        <label for="input1" class="form-label">Purchase Order No</label>
                                        <input type="text" class="form-control @error('purchaseId') is-invalid @enderror"
                                            value="{{ old('purchaseId', $purchaseId) }}" id="input1"
                                            placeholder="Purchase Order No" name="purchaseId">
                                        @error('purchaseId')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                @endisset

                                <div class="col-12 col-lg-3">
                                    <label for="warehouseName" class="form-label">Warehouse Name
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control" name="warehouse_id" id="warehouseName">
                                        <option selected="" disabled="" value="">-- Select --
                                        </option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <label for="vendorName" class="form-label">Vendor Name
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control" name="vendor_id" id="vendorName">
                                        <option selected="" disabled="" value="">-- Select --
                                        </option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->vendor_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="input9" class="form-label">Purchase Order</label>
                                    <input type="file" name="purchase_excel" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
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


@section('script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
