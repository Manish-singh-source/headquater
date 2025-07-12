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
                        <a href="{{ route('add-product') }}" class="btn btn-primary px-4"><i
                                class="bi bi-plus-lg me-2"></i>Add Product</a>
                                 <div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-outline-primary">Action</button>
							<button type="button" class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
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
                                        <th>Product&nbsp;Name</th>
                                        <th>SKU</th>
                                        <th>item&nbsp;id</th>
                                        <th>vendor&nbsp;name</th>
                                        <th>vendor&nbsp;legal&nbsp;name </th>
                                        <th>manufacturer&nbsp;name</th>
                                        <th>facility&nbsp;name</th>
                                        <th>units</th>
                                        <th>units&nbsp;ordered</th>
                                        <th>landing&nbsp;rate</th>
                                        <th>cost&nbsp;price</th>
                                        <th>total&nbsp;amount</th>
                                        <th>mrp</th>
                                        <th>po&nbsp;status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                           <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $product->id }}">
                                            </td>
                                            <td>{{ $product->warehouseStock?->warehouse->name ?? 'NA' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">

                                                    <div class="product-info">
                                                        <a href="javascript:;"
                                                            class="product-title">{{ $product->warehouseStock->product->name }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->warehouseStock->product->sku }}</td>
                                            <td>{{ $product->warehouseStock->product->item_code }}</td>
                                            <td>{{ $product->warehouseStock->product->vendor_name }}</td>
                                            <td>{{ $product->warehouseStock->product->entity_vendor_legal_name }}</td>
                                            <td>{{ $product->warehouseStock->product->manufacturer_name }}</td>
                                            <td>{{ $product->warehouseStock->product->facility_name }}</td>
                                            <td>{{ $product->warehouseStock->product->description }}</td>
                                            <td>{{ $product->warehouseStock->product->units_ordered }}</td>
                                            <td>{{ $product->warehouseStock->product->net_landing_rate }}</td>
                                            <td>{{ $product->warehouseStock->product->po_qty }}</td>
                                            <td>{{ $product->warehouseStock->product->total_amount }}</td>
                                            <td>{{ $product->warehouseStock->product->mrp }}</td>
                                            <td>{{ $product->warehouseStock->product->po_status }}</td>
                                            <td>{{ $product->warehouseStock->product->created_at }}</td>
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
document.addEventListener('DOMContentLoaded', function () {
    // Select All functionality
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
    });

    // Delete Selected functionality
    document.getElementById('delete-selected').addEventListener('click', function () {
        let selected = [];
        document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
            selected.push(cb.value);
        });
        if(selected.length === 0) {
            alert('Please select at least one record.');
            return;
        }
        if(confirm('Are you sure you want to delete selected records?')) {
            // Create a form and submit
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("delete.selected.product") }}';
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
</script>
@endsection