@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card customer-inputs">
                                <div class="card-header border-bottom-dashed">
                                    <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                Add New Order
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('process.order') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12 col-lg-3">
                                                <label for="customerGroup" class="form-label">Select Customer Group
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="customer_group_id" id="customerGroup">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($customerGroup as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="warehouseName" class="form-label">Warehouse Name
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-control" name="warehouse_id" id="warehouseName">
                                                    <option selected="" disabled="" value="">-- Select --
                                                    </option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="document_image" class="form-label">Customer PO (CSV/XLSX) <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="csv_file" id="csv_file" class="form-control"
                                                    value="" required="" placeholder="Upload ID Document" multiple>
                                            </div>
                                            <div class="col-12 col-lg-1">
                                                <button class="btn btn-primary" id="upload-excel">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card customer-inputs">
                                <div class="card-header border-bottom-dashed">
                                    <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                Add Blocking Order
                                            </h5>
                                        </div>
                                        <div>
                                            <a class="px-2 py-1" data-bs-toggle="collapse" href="#collapseExample"
                                                role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <span class="material-icons-outlined">keyboard_arrow_down</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="collapse" id="collapseExample">
                                    <div class="card-body">
                                        <form action="{{ route('process.block.order') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="row g-3 align-items-end">
                                                <div class="col-12 col-lg-3">
                                                    <label for="customerGroup" class="form-label">Select Customer Group
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="customer_group_id" id="customerGroup">
                                                        <option selected="" disabled="" value="">-- Select --
                                                        </option>
                                                        @foreach ($customerGroup as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->group_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
                                                    <label for="document_image" class="form-label">Customer PO (CSV/XLSX)
                                                        <span class="text-danger">*</span></label>
                                                    <input type="file" name="csv_file" id="csv_file"
                                                        class="form-control" value="" required=""
                                                        placeholder="Upload ID Document" multiple>
                                                </div>
                                                <div class="col-12 col-lg-1">
                                                    <button class="btn btn-primary" id="upload-excel">Submit</button>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection



@section('script')
    <script>
        new PerfectScrollbar(".customer-notes")
    </script>


    <script>
        function exportTableToExcel(tableID, filename = 'table_data.xls') {
            const dataType = 'application/vnd.ms-excel';
            const tableSelect = document.getElementById(tableID);
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Create download link element
            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
@endsection
