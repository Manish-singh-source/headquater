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
            <div class="modal fade" id="skuMappingUpload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="skuMappingUploadLabel" aria-hidden="true">
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
            <div class="modal fade" id="skuMappingBulkAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="skuMappingBulkAddLabel" aria-hidden="true">
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
                                <a href="{{ asset('uploads/excel-formats/sku-mapping.xlsx') }}"
                                    class="btn btn-outline-success" download="sku-mapping.xlsx">
                                    Export Format
                                </a>
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
                            <table id="skuMappingTable" class="table align-middle">
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
                                        <th>MRP</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
            $('#skuMappingTable').DataTable({
                processing: true,
                serverSide: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, -1],
                }],
                searchDelay: 350,
                lengthChange: true,
                pageLength: 10,
                ajax: function(requestData, callback) {
                    $.ajax({
                        url: window.location.pathname,
                        type: 'GET',
                        data: {
                            draw: requestData.draw,
                            start: requestData.start,
                            length: requestData.length,
                            order: requestData.order,
                            search: {
                                value: requestData.search.value
                            },
                        },
                        dataType: 'json',
                    }).done(function(response) {
                        if (response.recordsFiltered === 0) {
                            callback({
                                draw: response.draw,
                                recordsTotal: response.recordsTotal,
                                recordsFiltered: response.recordsFiltered,
                                data: [],
                            });
                            return;
                        }
                        const table = document.createElement('table');
                        table.innerHTML = '<tbody>' + response.html + '</tbody>';
                        const rows = Array.from(table.tBodies[0].rows, function(row) {
                            return Array.from(row.cells, function(cell) {
                                return cell.innerHTML;
                            });
                        });
                        callback({
                            draw: response.draw,
                            recordsTotal: response.recordsTotal,
                            recordsFiltered: response.recordsFiltered,
                            data: rows,
                        });
                    }).fail(function() {
                        callback({
                            draw: requestData.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: [],
                        });
                    });
                },
                language: {
                    emptyTable: 'None SKU Mapping Found',
                },
            });
        });
    </script>
@endsection
