@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            'blocked' => 'Blocked',
            'shipped' => 'Shipped',
            'completed' => 'Complete',
            'ready_to_ship' => 'Ready To Ship',
            'ready_to_package' => 'Ready To Package',
            'packaging' => 'Packaging',
            'packaged' => 'Packaged',
            'cancelled' => 'Cancelled',
            'approval_pending' => 'Ready to Ship Approval Pending',
        ];
    @endphp

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Customer PO List</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">

                                <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                    <form id="statusForm" action="{{ route('change.sales.order.status') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                                        <select class="form-select border-2 border-primary" id="changeStatus"
                                            aria-label="Default select example" name="status">
                                            <option value="" selected disabled>Change Status</option>
                                            <option value="pending" @if ($salesOrder->status == 'pending') selected @endif
                                                @if (in_array($salesOrder->status, ['blocked', 'ready_to_package', 'ready_to_ship', 'shipped', 'completed'])) disabled @endif>Pending</option>
                                            <option value="blocked" @if ($salesOrder->status == 'blocked') selected @endif
                                                @if (in_array($salesOrder->status, ['ready_to_package', 'ready_to_ship', 'shipped', 'completed'])) disabled @endif>Blocked</option>
                                            <option value="ready_to_package"
                                                @if ($salesOrder->status == 'ready_to_package') selected @endif
                                                @if (in_array($salesOrder->status, ['ready_to_ship', 'shipped', 'completed'])) disabled @endif>Ready To Package</option>
                                            <option value="ready_to_ship" @if ($salesOrder->status == 'ready_to_ship') selected @endif
                                                @if (in_array($salesOrder->status, ['shipped', 'completed'])) disabled @endif>Ready To Ship</option>
                                            <option value="shipped" @if ($salesOrder->status == 'shipped') selected @endif
                                                @if (in_array($salesOrder->status, ['completed'])) disabled @endif>Shipped</option>
                                            <option value="completed" @if ($salesOrder->status == 'completed') selected @endif>
                                                Completed</option>
                                        </select>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>

                                    <span>
                                        <span>{{ $salesOrder->order_number }}</span>
                                        <span id="orderId" class="d-none">{{ $salesOrder->id }}</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Customer Group Name</b></span>
                                    <span> <b>{{ $salesOrder->customerGroup->name }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Status</b></span>
                                    <span> <b>{{ $statuses[$salesOrder->status] ?? 'On Hold' }}</b></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Total PO Quantity</b></span>
                                    <span> <b>{{ $salesOrder->ordered_products_sum_ordered_quantity }}</b></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Total Purchase Order Quantity</b></span>
                                    <span> <b>{{ $salesOrder->ordered_products_sum_purchase_ordered_quantity }}</b></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>PO Quantity Status</b></span>
                                    <span>
                                        <b>
                                            @if ($remainingQuantity > 0)
                                                <span class="badge text-danger bg-danger-subtle">Quantity Needs To Fulfill:
                                                    <span id="quantityNeedsToFullfill">
                                                        {{ $remainingQuantity }}
                                                    </span>
                                                </span>
                                            @else
                                                <span class="badge text-success bg-success-subtle"> Quantity
                                                    Fulfilled</span>
                                                <span id="quantityNeedsToFullfill" hidden>0</span>
                                            @endif
                                        </b>
                                    </span>
                                </li>
                                @if ($salesOrder->not_found_temp_order_by_product_count > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Products SKU Not
                                                Found
                                                <span
                                                    class="badge text-danger bg-danger-subtle">({{ $salesOrder->not_found_temp_order_by_product_count }})</span></b></span>
                                        <span> <a
                                                href="{{ route('download.not.found.sku.excel', $salesOrder->id) }}">Download</a></span>
                                    </li>
                                @endif
                                @if ($salesOrder->not_found_temp_order_by_customer_count > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Customer Not Found
                                                <span
                                                    class="badge text-danger bg-danger-subtle">({{ $salesOrder->not_found_temp_order_by_customer_count }})</span></b></span>
                                        <span> <a
                                                href="{{ route('download.not.found.customer.excel', $salesOrder->id) }}">Download</a></span>
                                    </li>
                                @endif
                                @if ($salesOrder->not_found_temp_order_by_vendor_count > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                        <span><b>Vendor Not Found
                                                <span
                                                    class="badge text-danger bg-danger-subtle">({{ $salesOrder->not_found_temp_order_by_vendor_count }})</span></b></span>
                                        <span> <a
                                                href="{{ route('download.not.found.vendor.excel', $salesOrder->id) }}">Download</a></span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">Customer PO Table</h6>
                        </div>

                        <!-- Tabs Navigation -->
                        <div class="div d-flex justify-content-end my-3 gap-2">

                            <div>
                                <select class="form-select border-2 border-primary" id="selectQuantityFulfilledFilter"
                                    aria-label="Default select example" name="selectQuantityFulfilledFilter">
                                    <option value="all" selected>Quantity Fulfilled</option>
                                    <option value="0">Not Fulfilled (0)</option>
                                    <option value="greater_than_0">Fulfilled (Greater Than 0)</option>
                                </select>
                            </div>
                            {{-- filter for quantity fulfilled --}}

                            <div>
                                <select class="form-select border-2 border-primary" id="selectFinalQuantityFulfilledFilter"
                                    aria-label="Default select example" name="selectFinalQuantityFulfilledFilter">
                                    <option value="all" selected>Final Quantity Fulfilled</option>
                                    <option value="0">Not Fulfilled (0)</option>
                                    <option value="greater_than_0">Fulfilled (Greater Than 0)</option>
                                </select>
                            </div>


                            <div>
                                <select class="form-select border-2 border-primary" id="selectProductStatusFilter"
                                    aria-label="Default select example" name="selectProductStatusFilter">
                                    <option value="" selected>Select Product Status</option>
                                    <option value="Pending">Pending</option>
                                    {{-- <option value="Blocked">Blocked</option> --}}
                                    <option value="Ready To Package">Ready To Package</option>
                                    <option value="Packaging">Packaging</option>
                                    <option value="Packaged">Packaged</option>
                                    {{-- <option value="Cancelled">Cancelled</option> --}}
                                    {{-- <option value="Approval Pending">Approval Pending</option> --}}
                                    <option value="Ready to Ship Approval Pending">Ready to Ship Approval Pending</option>
                                    <option value="Ready To Ship">Ready To Ship</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="Complete">Complete</option>
                                </select>
                            </div>

                            <div>
                                <select class="form-select border-2 border-primary" id="selectBrand"
                                    aria-label="Default select example" name="status">
                                    <option value="" selected>Select Brand</option>
                                    @foreach ($uniqueBrands as $brand)
                                        <option value="{{ $brand }}">{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select class="form-select border-2 border-primary" id="selectPONumber"
                                    aria-label="Default select example" name="status">
                                    <option value="" selected>Select PO Number</option>
                                    @foreach ($uniquePONumbers as $poNumber)
                                        <option value="{{ $poNumber }}">{{ $poNumber }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="ms-auto">
                                <div class="btn-group">
                                    <button class="btn border-2 border-primary  split-bg-primary dropdown-toggle"
                                        data-bs-toggle="dropdown" type="button">Action</button>

                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                        <button type="button" id="delete-selected"
                                            class="dropdown-item cursor-pointer">Delete All</button>
                                        <button type="button" id="sendToPackaging"
                                            class="dropdown-item cursor-pointer">Send To Packaging</button>
                                        @if (in_array($salesOrder->status, ['ready_to_ship', 'shipped', 'completed']))
                                            <button type="button" class="dropdown-item cursor-pointer"
                                                id="generateInvoice">
                                                <i class="fa fa-file-excel-o"></i> Generate Invoice
                                            </button>
                                        @endif
                                        {{-- @if (in_array($salesOrder->status, ['pending', 'blocked'])) --}}
                                        <button type="button" class="dropdown-item cursor-pointer" id="exportData">
                                            <i class="fa fa-file-excel-o"></i> Export(Excel)
                                        </button>
                                        <button class="dropdown-item cursor-pointer" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop1">
                                            Update PO
                                        </button>
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-table" id="poTable">

                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active status-filter-tab" id="all-tab" data-bs-toggle="tab"
                                    data-order="all" data-bs-target="#all" type="button" role="tab"
                                    aria-controls="all" aria-selected="true">All</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-filter-tab" id="active-tab" data-bs-toggle="tab"
                                    data-order="Completed" data-bs-target="#active" type="button" role="tab"
                                    aria-controls="active" aria-selected="false">Completed</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-filter-tab" id="inactive-tab" data-bs-toggle="tab"
                                    data-order="Pending" data-bs-target="#inactive" type="button" role="tab"
                                    aria-controls="inactive" aria-selected="false">Pending</button>
                            </li>
                        </ul>


                        <div class="table-responsive white-space-nowrap">
                            <table id="po_table" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Customer&nbsp;Name</th>
                                        <th>Facility&nbsp;Name</th>
                                        <th>Facility&nbsp;Location</th>
                                        <th>HSN</th>
                                        <th>GST</th>
                                        <th>Item&nbsp;Code</th>
                                        <th>SKU&nbsp;Code</th>
                                        <th>Brand</th>
                                        <th>Title</th>
                                        <th>Basic&nbsp;Rate</th>
                                        <th>Product&nbsp;Basic&nbsp;Rate</th>
                                        <th>Basic&nbsp;Rate&nbsp;Confirmation</th>
                                        <th>Net&nbsp;Landing&nbsp;Rate</th>
                                        <th>Product&nbsp;Net&nbsp;Landing&nbsp;Rate</th>
                                        <th>Net&nbsp;Landing&nbsp;Rate&nbsp;Confirmation</th>
                                        <th>PO&nbsp;MRP</th>
                                        <th>Product&nbsp;MRP</th>
                                        <th>MRP&nbsp;Confirmation</th>
                                        <th>PO&nbsp;Number</th>
                                        <th>PO&nbsp;Quantity</th>
                                        <th>Purchase&nbsp;Order&nbsp;Quantity</th>
                                        <th>Vendor&nbsp;PI&nbsp;Fulfillment&nbsp;Quantity</th>
                                        <th>Vendor&nbsp;PI&nbsp;Received&nbsp;Quantity</th>
                                        <th>Block&nbsp;Quantity</th>
                                        <th>Qty&nbsp;Fullfilled</th>
                                        <th>Final&nbsp;Quantity&nbsp;Fulfilled</th>
                                        <th>Warehouse&nbsp;Allocation</th>
                                        <th>Invoice&nbsp;Status</th>
                                        <th>Product&nbsp;Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statuses = [
                                            'pending' => 'Pending',
                                            'blocked' => 'Blocked',
                                            'shipped' => 'Shipped',
                                            'completed' => 'Complete',
                                            'ready_to_ship' => 'Ready To Ship',
                                            'ready_to_package' => 'Ready To Package',
                                            'packaging' => 'Packaging',
                                            'packaged' => 'Packaged',
                                            'cancelled' => 'Cancelled',
                                            'approval_pending' => 'Ready to Ship Approval Pending',
                                        ];
                                    @endphp
                                    @forelse($salesOrder->orderedProducts as $order)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $order->id }}">
                                            </td>
                                            <td>{{ $order->tempOrder?->customer_name }}</td>
                                            <td>{{ $order->tempOrder?->facility_name }}</td>
                                            <td>{{ $order->tempOrder?->facility_location }}</td>
                                            <td>{{ $order->tempOrder?->hsn }}</td>
                                            <td>{{ $order->tempOrder?->gst }}</td>
                                            <td>{{ $order->tempOrder?->item_code }}</td>
                                            <td>{{ $order->tempOrder?->sku }}</td>
                                            <td>{{ $order->product?->brand ?? '' }}</td>
                                            <td>{{ $order->tempOrder?->description }}</td>
                                            <td>{{ $order->tempOrder?->basic_rate }}</td>
                                            <td>{{ $order->tempOrder?->product_basic_rate }}</td>
                                            <td>{{ $order->tempOrder?->rate_confirmation }}</td>
                                            <td>{{ $order->tempOrder?->net_landing_rate }}</td>
                                            <td>{{ $order->tempOrder?->product_net_landing_rate }}</td>
                                            <td>{{ $order->tempOrder?->net_landing_rate_confirmation }}</td>
                                            <td>{{ $order->tempOrder?->mrp }}</td>
                                            <td>{{ $order->tempOrder?->product_mrp }}</td>
                                            <td>{{ $order->tempOrder?->mrp_confirmation }}</td>
                                            <td>{{ $order->tempOrder?->po_number }}</td>
                                            <td>{{ $order->ordered_quantity }}</td>
                                            <td>{{ $order->tempOrder?->purchase_order_quantity }}</td>
                                            <td>{{ $order->tempOrder?->vendor_pi_fulfillment_quantity }}</td>
                                            <td>{{ $order->tempOrder?->vendor_pi_received_quantity }}</td>
                                            <td>{{ $order->tempOrder?->block }}</td>
                                            <td>
                                                @if ($order->dispatched_quantity == $order->ordered_quantity)
                                                    <span
                                                        class="badge text-success bg-success-subtle">{{ $order->dispatched_quantity ?? 0 }}</span>
                                                @else
                                                    <span
                                                        class="badge text-danger bg-danger-subtle">{{ $order->dispatched_quantity ?? 0 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->final_dispatched_quantity == $order->ordered_quantity)
                                                    <span
                                                        class="badge text-success bg-success-subtle">{{ $order->final_dispatched_quantity ?? 0 }}</span>
                                                @else
                                                    <span
                                                        class="badge text-danger bg-danger-subtle">{{ $order->final_dispatched_quantity ?? 0 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    // Check if product has warehouse allocations (auto-allocation)
                                                    $hasAllocations =
                                                        $order->warehouseAllocations &&
                                                        $order->warehouseAllocations->count() > 0;
                                                @endphp

                                                @if ($hasAllocations)
                                                    {{-- Auto-allocation: Show warehouse-wise breakdown --}}
                                                    @if ($order->warehouseAllocations->count() > 0)
                                                        @foreach ($order->warehouseAllocations->sortBy('sequence') as $allocation)
                                                            <div class="mb-1">
                                                                <strong>{{ $allocation->warehouse->name ?? 'N/A' }}</strong>:
                                                                {{ $allocation->allocated_quantity }}
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                @else
                                                    {{-- Single warehouse allocation or legacy data --}}
                                                    @if ($order->warehouseStock)
                                                        <div>
                                                            <strong>{{ $order->warehouseStock->warehouse->name ?? 'N/A' }}</strong>:
                                                            {{ $order->tempOrder->block ?? 0 }}
                                                        </div>
                                                    @elseif($order->tempOrder && $order->tempOrder->block > 0)
                                                        {{-- Fallback: Try to find warehouse from warehouse stock --}}
                                                        @php
                                                            $fallbackWarehouseName = 'N/A';
                                                            $fallbackQuantity = $order->tempOrder->block ?? 0;

                                                            // First, try to get from warehouse stock for this SKU with block quantity
                                                            $warehouseStock = \App\Models\WarehouseStock::where(
                                                                'sku',
                                                                $order->sku,
                                                            )
                                                                ->where('block_quantity', '>', 0)
                                                                ->first();

                                                            if ($warehouseStock) {
                                                                $fallbackWarehouseName =
                                                                    $warehouseStock->warehouse->name ?? 'N/A';
                                                            } else {
                                                                // If no warehouse stock found, try to get from sales order warehouse
                                                                if ($salesOrder->warehouse) {
                                                                    $fallbackWarehouseName =
                                                                        $salesOrder->warehouse->name;
                                                                }
                                                            }
                                                        @endphp

                                                        <div>
                                                            <strong>{{ $fallbackWarehouseName }}</strong>:
                                                            {{ $fallbackQuantity }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ ucfirst($order->invoice_status) }}</td>
                                            <td>
                                                @if ($order->warehouseAllocations->count() > 0)
                                                    @foreach ($order->warehouseAllocations->sortBy('sequence') as $allocation)
                                                        <div class="mb-1">
                                                            <strong>{{ $allocation->warehouse->name ?? 'N/A' }}</strong>:
                                                            @php
                                                                if ($allocation->product_status == 'completed') {
                                                                    $allocation->product_status =
                                                                        $allocation->shipping_status;
                                                                }
                                                            @endphp
                                                            {{ $statuses[$allocation->product_status] ?? 'Unknown' }}
                                                        </div>
                                                    @endforeach
                                                @else
                                                    @php
                                                        if (
                                                            isset($allocation->product_status) &&
                                                            $allocation->product_status == 'completed'
                                                        ) {
                                                            $allocation->product_status = $allocation->shipping_status;
                                                        }
                                                    @endphp
                                                    {{ $statuses[$order->product_status] ?? 'Unknown' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="25" class="text-center">No Records Found</td>
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





    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sales.order.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Order</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="col-12 mb-3">
                            <input type="hidden" name="sales_order_id" value="{{ $salesOrder->id }}">
                            <label for="products_excel" class="form-label">Products List (CSV/ELSX)
                                <span class="text-danger">*</span></label>
                            <input type="file" name="products_excel" id="products_excel" class="form-control"
                                value="" required="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="holdOrder" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        document.getElementById('changeStatus').addEventListener('change', function() {
            if (confirm('Are you sure you want to change status for order?')) {
                var quantityNeedsToFullfill = document.getElementById('quantityNeedsToFullfill').innerHTML;
                if (quantityNeedsToFullfill > 0) {
                    alert('Please fulfill the quantity before changing the status.');
                    location.reload();
                }

                document.getElementById('statusForm').submit();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            // Delete Selected functionality
            const deleteSelectedBtn = document.getElementById('delete-selected');
            if (deleteSelectedBtn) {
                deleteSelectedBtn.addEventListener('click', function() {
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
                        form.action = '{{ route('delete.selected.order') }}';
                        form.innerHTML = `
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="ids" value="${selected.join(',')}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

        });
    </script>


    <script>
        $(document).ready(function() {
            var brandSelection = $('#po_table').DataTable({
                "columnDefs": [{
                    "orderable": false,
                }],
                lengthChange: true,
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            function escapeRegex(value) {
                return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            $('#selectBrand').on('change', function() {
                var selected = $(this).val().trim();

                // Use regex for exact match
                brandSelection.column(8).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            $('#selectPONumber').on('change', function() {
                var selected = $(this).val().trim();

                // Use regex for exact match
                brandSelection.column(-8).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            $('.status-filter-tab').on('click', function() {
                var selected = $(this).data('order').trim();
                console.log(selected);

                if (selected === 'all') {
                    selected = '';
                }
                // Use regex for exact match
                brandSelection.column(-2).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            $('#selectProductStatusFilter').on('change', function() {
                var selected = $(this).val().trim();
                if (!selected) {
                    brandSelection.column(-1).search('').draw();
                    return;
                }

                // Match exact status and allow optional "Warehouse: " prefix
                var pattern = '(?:^\\s*|:\\s*)' + escapeRegex(selected) + '(?:\\s|$)';
                brandSelection.column(-1).search(pattern, true, false).draw();
            });

            $('#selectQuantityFulfilledFilter').on('change', function() {
                var selected = $(this).val().trim();

                if (selected === 'all') {
                    selected = '';
                } else if (selected === 'greater_than_0') {
                    // Use regex to match numbers greater than 0
                    selected = '^(?!0$)\\d+$';
                    brandSelection.column(25).search(selected, true, false).draw();
                    return;
                }
                // Use regex for exact match
                brandSelection.column(25).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            $('#selectFinalQuantityFulfilledFilter').on('change', function() {
                var selected = $(this).val().trim();

                if (selected === 'all') {
                    selected = '';
                } else if (selected === 'greater_than_0') {
                    // Use regex to match numbers greater than 0
                    selected = '^(?!0$)\\d+$';
                    brandSelection.column(26).search(selected, true, false).draw();
                    return;
                }
                // Use regex for exact match
                brandSelection.column(26).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            $(document).on('click', '#exportData', function() {
                var purchaseOrderId = $("#orderId").text().trim();

                let selectQuantityFulfilledFilter = $('#selectQuantityFulfilledFilter').val();
                let selectFinalQuantityFulfilledFilter = $('#selectFinalQuantityFulfilledFilter').val();

                let selected = [];
                $('.row-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                let brand = $('#selectBrand').val();
                let poNumber = $('#selectPONumber').val();
                let shippingStatus = $('#selectProductStatusFilter').val();

                // If no checkboxes selected, select all visible rows only
                if (selected.length === 0) {
                    brandSelection.rows({
                        search: 'applied'
                    }).nodes().to$().find('.row-checkbox').prop('checked', true);
                    brandSelection.rows({
                        search: 'applied'
                    }).nodes().to$().find('.row-checkbox:checked').each(function() {
                        selected.push($(this).val());
                    });
                }

                // Create form for file download
                let form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('products.download.po.excel') }}'
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'order_id',
                    'value': purchaseOrderId
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'brand',
                    'value': brand
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'po_number',
                    'value': poNumber
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'shipping_status',
                    'value': shippingStatus
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'quantity_fulfilled_filter',
                    'value': selectQuantityFulfilledFilter
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'final_quantity_fulfilled_filter',
                    'value': selectFinalQuantityFulfilledFilter
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'ids',
                    'value': selected.join(',')
                }));

                $('body').append(form);
                form.submit();
                form.remove();
            });

            // Send to Packaging button click event
            $(document).on('click', '#sendToPackaging', function() {
                var purchaseOrderId = $("#orderId").text().trim();

                let selectQuantityFulfilledFilter = $('#selectQuantityFulfilledFilter').val();
                let selectFinalQuantityFulfilledFilter = $('#selectFinalQuantityFulfilledFilter').val();

                let selected = [];
                $('.row-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                // If no checkboxes selected, select all visible rows only
                if (selected.length === 0) {
                    brandSelection.rows({
                        search: 'applied'
                    }).nodes().to$().find('.row-checkbox').prop('checked', true);
                    brandSelection.rows({
                        search: 'applied'
                    }).nodes().to$().find('.row-checkbox:checked').each(function() {
                        selected.push($(this).val());
                    });
                }

                console.log(selected.join(','));

                if (confirm('Are you sure you want to send selected/all records to Packaging?')) {
                    $.ajax({
                        url: '{{ route('send.to.packaging') }}', // Your Laravel route
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'POST',
                            order_id: purchaseOrderId,
                            ids: selected.join(','),
                            quantity_fulfilled_filter: selectQuantityFulfilledFilter,
                            final_quantity_fulfilled_filter: selectFinalQuantityFulfilledFilter
                        },
                        success: function(response) {
                            // Handle success (e.g., show a message or update UI)
                            alert('Selected records sent to Packaging successfully!');
                            console.log(response);
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            alert('An error occurred while sending to Packaging.');
                            console.error(error);
                        }
                    });
                }
            });

            $(document).on('click', '#generateInvoice', function(e) {
                e.preventDefault();

                let selected = [];
                $('.row-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                let brand = $('#selectBrand').val();
                let poNumber = $('#selectPONumber').val();
                let shippingStatus = $('#selectProductStatusFilter').val();

                // If no checkboxes selected, select all visible rows only
                if (selected.length === 0) {
                    brandSelection.rows({
                        search: 'applied'
                    }).nodes().to$().find('.row-checkbox').prop('checked', true);
                    brandSelection.rows({
                        search: 'applied'
                    }).nodes().to$().find('.row-checkbox:checked').each(function() {
                        selected.push($(this).val());
                    });
                }

                console.log(selected.join(','));

                if (confirm('Are you sure you want to Create Invoice for selected/all records?')) {
                    $.ajax({
                        url: '{{ route('generate.invoice') }}', // Your Laravel route
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'POST',
                            order_id: '{{ $salesOrder->id }}',
                            brand: brand,
                            po_number: poNumber,
                            shipping_status: shippingStatus,
                            ids: selected.join(',')
                        },
                        success: function(response) {
                            // Handle success (e.g., show a message or update UI)
                            alert('Invoice generated successfully!');
                            console.log(response);
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            alert('An error occurred while generating the invoice.');
                            console.error(error);
                            // location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection
