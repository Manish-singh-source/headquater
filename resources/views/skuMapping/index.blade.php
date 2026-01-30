@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">SKU Mapping</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('download.sku.mapping.excel') }}" class="btn btn-outline-primary">
                        <i class="bi bi-download me-2"></i>Download Excel
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#skuMappingUpload">
                        <i class="bi bi-upload me-2"></i>Upload Excel
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#skuMappingBulkAdd">
                        <i class="bi bi-plus-lg me-2"></i>Add SKU Mapping (Excel)
                    </button>
                </div>
            </div>
            <div class="modal fade" id="skuMappingUpload" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="skuMappingUploadLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('upload.sku.mapping.excel') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="skuMappingUploadLabel">Upload SKU Mapping Excel</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12 mb-3">
                                    <label for="sku_mapping_excel" class="form-label">Upload Excel (CSV/XLSX)
                                        <span class="text-danger">*</span></label>
                                    <input type="file" name="sku_mapping_excel" id="sku_mapping_excel"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="skuMappingBulkAdd" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="skuMappingBulkAddLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('add.sku.mapping') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="skuMappingBulkAddLabel">Add SKU Mapping (Excel)</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12 mb-3">
                                    <label for="sku_mapping_excel_add" class="form-label">Upload Excel (CSV/XLSX)
                                        <span class="text-danger">*</span></label>
                                    <input type="file" name="sku_mapping_excel_add" id="sku_mapping_excel_add"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
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
                                        <th>SKU</th>
                                        <th>Portal Code</th>
                                        <th>Item Code</th>
                                        <th>Basic Rate</th>
                                        <th>Net Landing Rate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productMapping as $product)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $product->id }}">
                                            </td>
                                            <td>
                                                {{ $product->sku }}
                                            </td>
                                            <td>
                                                {{ $product->portal_code }}
                                            </td>
                                            <td>
                                                {{ $product->item_code }}
                                            </td>
                                            <td>
                                                {{ $product->basic_rate }}
                                            </td>
                                            <td>
                                                {{ $product->net_landing_rate }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor" href="{{ route('sku.mapping.edit', $product->id) }}"
                                                        class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="Edit">
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
                                                    <form action="{{ route('sku.mapping.destroy', $product->id) }}"
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
                                            <td class="text-center" colspan="7">None SKU Mapping Found</td>
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
@endsection
