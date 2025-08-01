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
                                                Search Received Vendor Order
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('received-products.view') }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12 col-lg-2">
                                                <label for="purchase_order_id" class="form-label">Order id
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control purchaseOrder" name="purchase_order_id"
                                                    id="purchase_order_id">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($purchaseOrders as $order)
                                                        <option
                                                            {{ request('purchase_order_id') == $order->id ? 'selected' : '' }}
                                                            value="{{ $order->id }}">{{ 'ORDER-' . $order->id }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <label for="vendor_code" class="form-label">Vendor Name
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="vendor_code" id="vendor_code">
                                                    <option selected disabled value="">-- Select --
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <button type="submit"
                                                    class="btn btn-success w-sm waves ripple-light text-center  mt-md-4">
                                                    Submit
                                                </button>
                                            </div>
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
    <!--end main wrapper-->
@endsection


@section('script')
    <script>
        $(document).on('change', '.purchaseOrder', function() {
            var purchaseOrderId = $(this).val();

            console.log(purchaseOrderId);
            $.ajax({
                url: '{{ route('get.vendors') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: purchaseOrderId,
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data)
                        let itemsHtml = `<option selected disabled> -- Selected -- </option>`;
                        
                        itemsHtml += response.data.map(function(item) {
                            return `<option value="${item.vendor_code}">${item.vendor_code}</option>`; // change 'name' to the actual property
                        }).join('');
                        $('#vendor_code').html(itemsHtml);
                    } else {
                        $('#vendor_code').html('<option>No items found.</option>');
                    }
                },
                error: function() {
                    $('#vendor_code').html('<option>Error loading data.</option>');
                }
            });
        });
    </script>
@endsection
