@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row g-3 justify-content-end">
                <div class="col-12 col-md-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn border-2 border-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop1"
                            class="btn border-2 border-primary">
                            Update Products
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('products.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Products</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="col-12 mb-3">
                                                <label for="products_excel" class="form-label">Products List (CSV/ELSX)
                                                    <span class="text-danger">*</span></label>
                                                <input type="file" name="products_excel" id="products_excel"
                                                    class="form-control" value="" required="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="holdOrder" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-icon border-2 border-primary me-1" id="exportData">
                            <i class="fa fa-file-excel-o"></i> Export to Excel
                        </button>

                        <a href="{{ route('products.create') }}" class="btn border-2 border-primary px-4"></i>Add
                            Product</a>
                        <div class="ms-auto">
                            <div class="btn-group">
                                <button type="button" class="btn border-2 border-primary">Action</button>
                                <button type="button"
                                    class="btn border-2 border-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                    data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                    <a class="dropdown-item cursor-pointer" id="delete-selected">Delete All</a>
                                </div>
                            </div>
                            {{-- <a href="{{ route('add-customer') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Add Customers</a> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Warehouse</th>
                                        <th>SKU Code</th>
                                        <th>EAN&nbsp;Code</th>
                                        <th>Brand</th>
                                        <th>Brand&nbsp;Title</th>
                                        <th>MRP</th>
                                        <th>Category</th>
                                        <th>PCS/Set </th>
                                        <th>Sets/CTN</th>
                                        <th>Vendor&nbsp;Name</th>
                                        <th>Vendor&nbsp;Purchase&nbsp;Rate</th>
                                        <th>GST</th>
                                        <th>Vendor&nbsp;Net&nbsp;Landing</th>
                                        <th>po&nbsp;status</th>
                                        <th>Quantity</th>
                                        <th>Hold&nbsp;Qty</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $product->id }}">
                                            </td>
                                            <td>{{ $product->warehouse->name ?? 'NA' }}</td>
                                            <td>{{ $product->product->sku }}</td>
                                            <td>{{ $product->product->ean_code }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->product->brand }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->product->brand_title }}</td>
                                            <td>{{ $product->product->mrp }}</td>
                                            <td>{{ $product->product->category }}</td>
                                            <td>{{ $product->product->pcs_set }}</td>
                                            <td>{{ $product->product->sets_ctn }}</td>
                                            <td>{{ $product->product->vendor_name }}</td>
                                            <td>{{ $product->product->vendor_purchase_rate }}</td>
                                            <td>{{ $product->product->gst }}</td>
                                            <td>{{ $product->product->vendor_net_landing }}</td>
                                            <td>{{ $product->product->status === '1' ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                @if ($product->quantity)
                                                    {{ $product->quantity }}
                                                @else
                                                    <span>0</span>
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
                                            <td>{{ $product->product->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor" data-id="{{ $product->product->id }}"
                                                        href="{{ route('product.edit', $product->product->id) }}"
                                                        class="btn btn-icon btn-sm bg-warning-subtle me-1 editProductBtn"
                                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-edit text-warning">
                                                            <path
                                                                d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                            </path>
                                                            <path
                                                                d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                            </path>
                                                        </svg>
                                                    </a>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop2"
                                                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                        aria-labelledby="staticBackdropLabel2" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form id="editProductForm">
                                                                    @csrf
                                                                    <input type="hidden" name="id" id="id">

                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="staticBackdropLabel2">Update Product
                                                                        </h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="col-12 mb-3">
                                                                            <label for="sku" class="form-label">SKU
                                                                                Code
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="sku"
                                                                                id="sku" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="ean_code" class="form-label">EAN
                                                                                Code
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="ean_code"
                                                                                id="ean_code" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="brand" class="form-label">Brand
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="brand"
                                                                                id="brand" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="brand_title"
                                                                                class="form-label">Brand Title
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="brand_title"
                                                                                id="brand_title" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="mrp" class="form-label">MRP
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="mrp"
                                                                                id="mrp" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="category"
                                                                                class="form-label">Category
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="category"
                                                                                id="category" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="pcs_set"
                                                                                class="form-label">PCS/SET
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="pcs_set"
                                                                                id="pcs_set" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label for="sets_ctn"
                                                                                class="form-label">SETS/CTN
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="sets_ctn"
                                                                                id="sets_ctn" class="form-control"
                                                                                value="" required="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" id="holdOrder"
                                                                            class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <form action="{{ route('product.delete', $product->product->id) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm bg-danger-subtle delete-row">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-trash-2 text-danger">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                                <line x1="10" y1="11" x2="10"
                                                                    y2="17"></line>
                                                                <line x1="14" y1="11" x2="14"
                                                                    y2="17"></line>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
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
    <!--end main wrapper-->
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            // Delete Selected functionality
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                if (selected.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected records?')) {
                    // Create a form and submit
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('delete.selected.product') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });


        $(document).on('click', '.editProductBtn', function() {
            let productId = $(this).data('id');

            $.ajax({
                url: '/products/' + productId + '/edit',
                method: 'GET',
                success: function(data) {
                    $('#id').val(data.id);
                    $('#sku').val(data.sku);
                    $('#ean_code').val(data.ean_code);
                    $('#brand').val(data.brand);
                    $('#brand_title').val(data.brand_title);
                    $('#mrp').val(data.mrp);
                    $('#category').val(data.category);
                    $('#pcs_set').val(data.pcs_set);
                    $('#sets_ctn').val(data.sets_ctn);
                    $('#editProductModal').modal('show');
                }
            });
        });

        // Handle form submit
        $('#editProductForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '/products/update',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    console.log(res);
                    if (res.success) {
                        $('#editProductModal').modal('hide');
                        location.reload();
                    }
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '#exportData', function() {

            // Construct download URL with parameters
            var downloadUrl = '{{ route('download.product.sheet') }}';

            // Trigger browser download
            window.location.href = downloadUrl;
        });
    </script>
@endsection
