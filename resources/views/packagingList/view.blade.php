@section('styles')
    <style>
        #hideTable {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
@endsection

@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="div d-flex my-2">
                <div class="col">
                    <h5 class="mb-3">Packaging List: {{ $salesOrder->id }}</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <div class="div d-flex justify-content-end my-3 gap-2">
                            <h6 class="mb-3">Customer PO Table</h6>
                        </div>
                        <!-- Tabs Navigation -->
                        <div class="d-flex justify-content-end my-3 gap-2">

                            <!-- Client Name Dropdown -->
                            <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                <select class="form-select border-2 border-primary" id="customerPOTable"
                                    aria-label="Select Client Name">
                                    <option value="" selected> -- Select Client Name --</option>
                                    @foreach ($facilityNames as $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </ul>

                            <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop1" class="btn btn-sm border-2 border-primary">
                                Update PO
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('purchase.order.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Customer PO
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="col-12 mb-3">
                                                    <input type="hidden" name="purchase_order_id" value="">
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <label for="pi_excel" class="form-label">Updated PO(CSV/ELSX) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="pi_excel" id="pi_excel"
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

                            <button id="customExcelBtn" class="btn btn-sm border-2 border-primary">
                                <i class="fa fa-file-excel-o"></i> Export to Excel
                            </button>

                        </div>
                    </div>

                    <div class="product-table" id="poTable">
                        <div class="table-responsive white-space-nowrap">
                            <table id="customerPOTableList" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Customer&nbsp;Name</th>
                                        <th>PO&nbsp;Number</th>
                                        <th>SKU&nbsp;Code</th>
                                        <th>Facility&nbsp;Name</th>
                                        <th>Facility&nbsp;Location</th>
                                        <th>PO&nbsp;Date</th>
                                        <th>PO&nbsp;Expiry&nbsp;Date</th>
                                        <th>HSN</th>
                                        <th>Item&nbsp;Code</th>
                                        <th>Description</th>
                                        <th>Basic&nbsp;Rate</th>
                                        <th>GST</th>
                                        <th>Net&nbsp;Landing&nbsp;Rate</th>
                                        <th>MRP</th>
                                        <th>PO&nbsp;Quantity</th>
                                        <th>Warehouse&nbsp;Stock</th>
                                        <th>PI&nbsp;Qty</th>
                                        <th>Purchase&nbsp;Order&nbsp;No</th>
                                        <th>Total&nbsp;Dispatch&nbsp;Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salesOrder->orderedProducts as $order)
                                        <tr>
                                            <td>{{ $order->customer->contact_name }}</td>
                                            <td>{{ $order->tempOrder->po_number }}</td>
                                            <td>{{ $order->tempOrder->sku }}</td>
                                            <td>{{ $order->tempOrder->facility_name }}</td>
                                            <td>{{ $order->tempOrder->facility_location }}</td>
                                            <td>{{ $order->tempOrder->po_date }}</td>
                                            <td>{{ $order->tempOrder->po_expiry_date }}</td>
                                            <td>{{ $order->tempOrder->hsn }}</td>
                                            <td>{{ $order->tempOrder->item_code }}</td>
                                            <td>{{ $order->tempOrder->description }}</td>
                                            <td>{{ $order->tempOrder->basic_rate }}</td>
                                            <td>{{ $order->tempOrder->gst }}</td>
                                            <td>{{ $order->tempOrder->net_landing_rate }}</td>
                                            <td>{{ $order->tempOrder->mrp }}</td>
                                            <td>{{ $order->tempOrder->po_qty }}</td>
                                            {{-- Need to check --}}
                                            <td>{{ $order->warehouseStockLog->block_quantity }}</td>
                                            <td>{{ $order->ordered_quantity }}</td>
                                            <td>{{ $order->tempOrder->po_number }}</td>
                                            <td>{{ $order->warehouseStockLog->block_quantity }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="19" class="text-center">No records found. Please update or upload
                                                a PO to see data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 my-2">
                {{-- <div class="text-end">
                    <a href="#" class="btn btn-success w-sm waves ripple-light">
                        Download Excel File
                    </a>
                </div>
                <div class="text-end">
                    <a href="{{ route('invoices-details') }}" class="btn btn-success w-sm waves ripple-light">
                        Generate Invoice
                    </a>
                </div> --}}
                <div class="text-end">
                    <form action="{{ route('change.order.status') }}" method="POST"
                        onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                        <input type="hidden" name="status" value="ready_to_ship">
                        <button class="btn btn-success w-sm waves ripple-light" type="submit">Ready to Ship</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize DataTable only if table exists
            if ($('#customerPOTableList').length) {
                var customerPOTableList = $('#customerPOTableList').DataTable({
                    "columnDefs": [{
                        "orderable": false,
                    }],
                    lengthChange: true,
                    buttons: [{
                        extend: 'excelHtml5',
                        className: 'd-none',
                    }]
                });

                // Filter by client name
                $('#customerPOTable').on('change', function() {
                    var selected = $(this).val().trim();
                    customerPOTableList.column(3).search(selected ? '^' + selected + '$' : '', true, false)
                        .draw();
                });
            }
        });
    </script>
@endsection
