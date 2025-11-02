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
                            <li class="breadcrumb-item active" aria-current="page"><b>Warehouse:</b>
                                {{ ucfirst($warehouse->name) }} </li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Warehouse Id</b></span>
                                <span>{{ $warehouse->id }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Warehouse Location</b></span>
                                <span>{{ $warehouse->cities->name }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Contact Name</b></span>
                                <span>{{ $warehouse->contact_person_name }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> {{ $warehouse->phone }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> {{ $warehouse->email }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <span><b>GST No</b></span>
                                <span> {{ $warehouse->gst_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Address</b></span>
                                <span> {{ $warehouse->address_line_1 }} {{ $warehouse->address_line_2 }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row g-3 justify-content-end">
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-icon border-2 border-primary me-1" id="exportData">
                            <i class="fa fa-file-excel-o"></i> Export to Excel
                        </button>
                        {{-- <a href="{{ route('products.create') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Add Product</a> --}}
                    </div>
                </div>
            </div>

            @isset($warehouse->warehouseStock)
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table id="example" class="table align-middle">
                                    <thead class="table-light">


                                        <tr>
                                            <th>
                                                <input class="form-check-input" type="checkbox">
                                            </th>
                                            <th>Brand</th>
                                            <th>Brand&nbsp;Title</th>
                                            <th>SKU</th>
                                            <th>EAN&nbsp;Code</th>
                                            <th>Category </th>
                                            <th>PCS/Set</th>
                                            <th>Sets/CTN</th>
                                            <th>Vendor</th>
                                            <th>Vendor&nbsp;Purchase&nbsp;Rate</th>
                                            <th>GST</th>
                                            <th>Vendor&nbsp;Net&nbsp;Landing</th>
                                            <th>MRP</th>
                                            <th>Status</th>
                                            <th>Original&nbsp;Quantity</th>
                                            <th>Available&nbsp;Quantity</th>
                                            <th>Hold&nbsp;Qty</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouse->warehouseStock as $stock)
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="product-info">
                                                            <a href="javascript:;"
                                                                class="product-title">{{ $stock->product->brand }}</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $stock->product->brand_title }}</td>
                                                <td>{{ $stock->product->sku }}</td>
                                                <td>{{ $stock->product->ean_code }}</td>
                                                <td>{{ $stock->product->category }}</td>
                                                <td>{{ $stock->product->pcs_set }}</td>
                                                <td>{{ $stock->product->sets_ctn }}</td>
                                                <td>{{ $stock->product->vendor_name }}</td>
                                                <td>{{ $stock->product->vendor_purchase_rate }}</td>
                                                <td>{{ $stock->product->gst }}</td>
                                                <td>{{ $stock->product->vendor_net_landing }}</td>
                                                <td>{{ $stock->product->mrp }}</td>
                                                <td>{{ $stock->product->status === '1' ? 'Active' : 'Inactive' }}</td>
                                                <td>{{ $stock->original_quantity }}</td>    
                                                <td>{{ $stock->available_quantity }}</td>
                                                <td>
                                                    @if ($stock->block_quantity)
                                                        <span class="badge text-danger bg-danger-subtle">
                                                            {{ $stock->block_quantity }}</span>
                                                    @else
                                                        <span>NA</span>
                                                    @endif
                                                </td>
                                                <td>{{ $stock->product?->created_at->format('d-M-Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="15" class="text-center">No Products Found</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset


        </div>
    </main>
    <!--end main wrapper-->
@endsection

@section('script')
    <script>
        $(document).on('click', '#exportData', function() {

            // Construct download URL with parameters
            var downloadUrl = '{{ route('download.product.sheet', $warehouse->id) }}';

            // Trigger browser download
            window.location.href = downloadUrl;
        });
    </script>
@endsection
