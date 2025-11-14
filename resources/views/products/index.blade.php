@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">

                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <button class="btn border-2 border-primary" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop1" class="btn border-2 border-primary">
                                    Update Products
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('products.update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Products
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="col-12 mb-3">
                                                        <label for="products_excel" class="form-label">Products List
                                                            (CSV/ELSX)
                                                            <span class="text-danger">*</span></label>
                                                        <input type="file" name="products_excel" id="products_excel"
                                                            class="form-control" value="" required="">
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
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="productTable" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Warehouse</th>
                                        <th>SKU&nbsp;Code</th>
                                        <th>EAN&nbsp;Code</th>
                                        <th>Brand</th>
                                        <th>Brand&nbsp;Title</th>
                                        <th>MRP</th>
                                        <th>Category</th>
                                        <th>PCS/Set </th>
                                        <th>Sets/CTN</th>
                                        <th>Basic&nbsp;Rate</th>
                                        <th>Net&nbsp;Landing&nbsp;Rate</th>
                                        <th>Case&nbsp;Pack&nbsp;Quantity</th>
                                        <th>Vendor&nbsp;Code</th>
                                        <th>Vendor&nbsp;Name</th>
                                        <th>Vendor&nbsp;Purchase&nbsp;Rate</th>
                                        <th>GST</th>
                                        <th>HSN</th>
                                        <th>Vendor&nbsp;Net&nbsp;Landing</th>
                                        <th>po&nbsp;status</th>
                                        <th>Original&nbsp;Quantity</th>
                                        <th>Available&nbsp;Quantity</th>
                                        <th>Blocked&nbsp;Qty</th>
                                        <th>Allocated&nbsp;Qty</th>
                                        <th>PO&nbsp;Required</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $product->id }}">
                                            </td>
                                            <td>{{ $product->warehouse->name ?? 'NA' }}</td>
                                            <td>{{ $product->product->sku ?? 'NA' }}</td>
                                            <td>{{ $product->product->ean_code ?? 'NA' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->product->brand ?? 'NA' }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->product->brand_title ?? 'NA' }}</td>
                                            <td>{{ $product->product->mrp ?? 'NA' }}</td>
                                            <td>{{ $product->product->category ?? 'NA' }}</td>
                                            <td>{{ $product->product->pcs_set ?? 'NA' }}</td>
                                            <td>{{ $product->product->sets_ctn ?? 'NA' }}</td>
                                            <td>{{ $product->product->basic_rate ?? 'NA' }}</td>
                                            <td>{{ $product->product->net_landing_rate ?? 'NA' }}</td>
                                            <td>{{ $product->product->case_pack_quantity ?? 'NA' }}</td>
                                            <td>{{ $product->product->vendor_code ?? 'NA' }}</td>
                                            <td>{{ $product->product->vendor_name ?? 'NA' }}</td>
                                            <td>{{ $product->product->vendor_purchase_rate ?? 'NA' }}</td>
                                            <td>{{ $product->product->gst ?? 'NA' }}</td>
                                            <td>{{ $product->product->hsn ?? 'NA' }}</td>
                                            <td>{{ $product->product->vendor_net_landing ?? 'NA' }}</td>
                                            <td>{{ $product->product->status === '1' ? 'Active' : 'Inactive' }}</td>
                                            <td>{{ $product->original_quantity ?? 'NA' }}</td>
                                            <td>{{ $product->available_quantity ?? 'NA' }}</td>
                                            <td>
                                                @if ($product->block_quantity)
                                                    <span class="badge text-danger bg-danger-subtle">
                                                        {{ $product->block_quantity }}</span>
                                                @else
                                                    <span>0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->allocated_quantity ?? 0)
                                                    <span class="badge text-warning bg-warning-subtle">
                                                        {{ $product->allocated_quantity }}</span>
                                                @else
                                                    <span>0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->po_required ?? 0)
                                                    <span class="badge text-info bg-info-subtle">
                                                        {{ $product->po_required }}</span>
                                                @else
                                                    <span>0</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->product->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor" data-id="{{ $product->product->id }}"
                                                        href="javascript:void(0);"
                                                        class="btn btn-icon btn-sm bg-warning-subtle me-1 editProductBtn">
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

                                                    <!-- per-row modal removed to avoid duplicate IDs; single modal is placed once after the table -->

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
                                    @empty
                                        <tr>
                                            <td colspan="27" class="text-center">No products found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->

    <!-- Single Edit Product Modal -->
    <!-- Single Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editProductForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Update Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">SKU Code</label>
                                <input type="text" name="sku" id="sku" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ean_code" class="form-label">EAN Code</label>
                                <input type="text" name="ean_code" id="ean_code" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" name="brand" id="brand" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand_title" class="form-label">Brand Title</label>
                                <input type="text" name="brand_title" id="brand_title" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mrp" class="form-label">MRP</label>
                                <input type="number" step="0.01" name="mrp" id="mrp"
                                    class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" name="category" id="category" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pcs_set" class="form-label">PCS/Set</label>
                                <input type="number" name="pcs_set" id="pcs_set" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sets_ctn" class="form-label">Sets/CTN</label>
                                <input type="number" name="sets_ctn" id="sets_ctn" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="basic_rate" class="form-label">Basic Rate</label>
                                <input type="number" step="0.01" name="basic_rate" id="basic_rate"
                                    class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hsn" class="form-label">HSN</label>
                                <input type="text" name="hsn" id="hsn" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="original_quantity" class="form-label">Original Quantity</label>
                                <input type="number" name="original_quantity" id="original_quantity"
                                    class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="available_quantity" class="form-label">Available Quantity</label>
                                <input type="number" name="available_quantity" id="available_quantity"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if not already initialized
            var table = $('#productTable').DataTable ? $('#productTable').DataTable() : null;

            // Select All functionality (across all pages)
            const selectAll = document.getElementById('select-all');
            selectAll.addEventListener('change', function() {
                if (table) {
                    // Select/deselect all checkboxes in all pages
                    table.rows().every(function() {
                        var node = this.node();
                        var cb = node.querySelector('.row-checkbox');
                        if (cb) cb.checked = selectAll.checked;
                    });
                } else {
                    // Fallback if DataTable not initialized
                    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = selectAll
                    .checked);
                }
            });

            // Delete Selected functionality (across all pages)
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [];
                if (table) {
                    // Get all checked checkboxes across all pages
                    table.rows().every(function() {
                        var node = this.node();
                        var cb = node.querySelector('.row-checkbox');
                        if (cb && cb.checked) {
                            selected.push(cb.value);
                        }
                    });
                } else {
                    // Fallback if DataTable not initialized
                    document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                        selected.push(cb.value);
                    });
                }
                if (selected.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected records?')) {
                    // Create a form and submit with ids[] inputs (array)
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('delete.selected.product') }}';

                    // Add CSRF token and method override
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    `;

                    // Append individual ids[] inputs for each selected id
                    selected.forEach(function(id) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

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
                success: function(response) {
                    console.log(response);

                    // Extract product data from response
                    const product = response.data || response;

                    $('#id').val(product.id);
                    $('#sku').val(product.sku);
                    $('#ean_code').val(product.ean_code);
                    $('#brand').val(product.brand);
                    $('#brand_title').val(product.brand_title);
                    $('#mrp').val(product.mrp);
                    $('#category').val(product.category);
                    $('#pcs_set').val(product.pcs_set);
                    $('#sets_ctn').val(product.sets_ctn);
                    $('#basic_rate').val(product.basic_rate);
                    $('#hsn').val(product.hsn);

                    // Handle warehouse stock data
                    const ws = product.warehouse_stock || product.warehouseStock || null;
                    if (ws) {
                        $('#original_quantity').val(ws.original_quantity ?? ws.originalQuantity ?? 0);
                        $('#available_quantity').val(ws.available_quantity ?? ws.availableQuantity ??
                        0);
                    } else {
                        $('#original_quantity').val(0);
                        $('#available_quantity').val(0);
                    }

                    $('#editProductModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error loading product data:', xhr);
                    alert('Failed to load product data. Please try again.');
                }
            });
        });

        // Handle form submit
        $('#editProductForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: '{{ route('product.update') }}',
                method: 'POST',
                data: form.serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#editProductModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Update failed');
                    }
                },
                error: function(xhr) {
                    var message = 'Update failed';
                    if (xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON
                        .message;
                    alert(message);
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
    <script>
        // If page opened with ?brand=..., filter the products table client-side
        (function() {
            function getQueryParam(name) {
                const params = new URLSearchParams(window.location.search);
                return params.get(name);
            }

            const brandParam = getQueryParam('brand');
            if (!brandParam) return;

            // Normalize for comparison
            const wanted = brandParam.trim().toLowerCase();

            // Iterate rows and hide those whose .product-title text does not match
            document.querySelectorAll('#example tbody tr').forEach(function(row) {
                const brandAnchor = row.querySelector('.product-title');
                const text = brandAnchor ? brandAnchor.textContent.trim().toLowerCase() : '';
                if (text !== wanted) {
                    row.style.display = 'none';
                } else {
                    row.style.display = ''; // keep visible
                }
            });

            // Optional: show a small note to user about active filter
            const breadcrumb = document.querySelector('.page-breadcrumb');
            if (breadcrumb) {
                const info = document.createElement('div');
                info.className = 'alert alert-info mt-2';
                info.textContent = 'Filtered by brand: ' + brandParam;
                breadcrumb.parentNode.insertBefore(info, breadcrumb.nextSibling);
            }
        })();
    </script>
@endsection
