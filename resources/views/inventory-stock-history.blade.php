@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Inventory Stock Report</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Product Available</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $productsSum }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Hold Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">{{ $blockProductsSum }}</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Shortage Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">0</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Exceed Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">0</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4">
                <div class="card-body pb-1">
                    <form action="customer-report.html">
                        <div class="row align-items-end">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Choose Date</label>
                                            <div class="input-icon-start position-relative">
                                                <input type="date" class="form-control date-range bookingrange"
                                                    id="date-select" placeholder="dd/mm/yyyy">
                                                <span class="input-icon-left">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Vendor Name</label>
                                            <select id="vendor-select" class="form-select">
                                                <option value="" selected>-- Select --</option>
                                                @foreach ($purchaseOrdersVendors as $purchaseOrdersVendor)
                                                    <option value="{{ $purchaseOrdersVendor }}">{{ $purchaseOrdersVendor }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Product Status</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Available</option>
                                                <option>Hold</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Sort</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Asc</option>
                                                <option>Dce</option>
                                                <option>High To Low</option>
                                                <option>Low To High</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <a href="#" class="btn btn-danger w-100" type="">Generate Report</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="inventory-stock-history-table" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Brand</th>
                                        <th>Brand&nbsp;Title</th>
                                        <th>Category</th>
                                        <th>SKU</th>
                                        <th>PCS/Set</th>
                                        <th>Sets/CTN</th>
                                        <th>MRP</th>
                                        <th>po&nbsp;status</th>
                                        <th>Quantity</th>
                                        <th>Hold&nbsp;Qty</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $product->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->product->brand }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->product->brand_title }}</td>
                                            <td>{{ $product->product->category }}</td>
                                            <td>{{ $product->product->sku }}</td>
                                            <td>{{ $product->product->pcs_set }}</td>
                                            <td>{{ $product->product->sets_ctn }}</td>
                                            <td>{{ $product->product->mrp }}</td>
                                            <td>{{ $product->product->status === '1' ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if ($product->available_quantity)
                                                    {{ $product->available_quantity }}
                                                @else
                                                    <span>NA</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->block_quantity)
                                                    <span class="badge text-danger bg-danger-subtle">
                                                        {{ $product->block_quantity }}</span>
                                                @else
                                                    <span>NA</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->product->created_at->format('d-m-Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
            var vendorHistoryTable = $('#inventory-stock-history-table').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        //   "targets": [0, -1],
                    } // Disable sorting for the 4th column (index starts at 0)
                ],
                lengthChange: true,
                // buttons: ['excel', 'pdf', 'print']
                // buttons: ['excel']
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            $('#date-select').on('change', function() {
                var selected = $(this).val().trim();
                if (selected) {
                    var parts = selected.split('-');
                    var formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
                }
                vendorHistoryTable.column(-1).search(formatted ? '^' + formatted + '$' : '', true, false)
                    .draw();
            });

            $('#vendor-select').on('change', function() {
                var selected = $(this).val().trim();
                vendorHistoryTable.column(2).search(selected ? '^' + selected + '$' : '', true, false)
                    .draw();
            });

        });
    </script>
@endsection
