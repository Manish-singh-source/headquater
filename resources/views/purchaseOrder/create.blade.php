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
                            <li class="breadcrumb-item active" aria-current="page">Enter Vendor Details</li>
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
                            <form class="row g-3" action="{{ route('store.purchase.order') }}" method="POST" enctype="multipart/form-data">
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
                                    value: country.id,
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

            getLocationData("/countries", '#shipping_country', "Country");

            $("#shipping_country").on("change", function() {
                let countryId = $(this).val();
                console.log(countryId);
                getLocationData("/states", "#shipping_state", "State", {
                    countryId: countryId
                });
            });

            $("#shipping_state").on("change", function() {
                let stateId = $(this).val();
                console.log(stateId);
                getLocationData("/cities", "#shipping_city", "City", {
                    stateId: stateId
                });
            });

            getLocationData("/countries", '#billing_country', "Country");

            $("#billing_country").on("change", function() {
                let countryId = $(this).val();
                console.log(countryId);
                getLocationData("/states", "#billing_state", "State", {
                    countryId: countryId
                });
            });

            $("#billing_state").on("change", function() {
                let stateId = $(this).val();
                console.log(stateId);
                getLocationData("/cities", "#billing_city", "City", {
                    stateId: stateId
                });
            });

        });
    </script>
@endsection
