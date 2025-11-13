@extends('layouts.master')

@section('styles')
    <style>
        #hideTable {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
@endsection

@php
    $statusBadges = [
        'pending' => 'bg-secondary',
        'packaging' => 'bg-warning',
        'packaged' => 'bg-info',
        'ready_to_ship' => 'bg-success',
        'dispatched' => 'bg-primary',
        'shipped' => 'bg-dark',
        'completed' => 'bg-success',
    ];
    $statusLabels = [
        'pending' => 'Pending',
        'packaging' => 'Packaging',
        'packaged' => 'Packaged',
        'ready_to_ship' => 'Ready to Ship',
        'dispatched' => 'Dispatched',
        'shipped' => 'Shipped',
        'completed' => 'Completed',
    ];
@endphp

@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="div d-flex my-2">
                <div class="col">
                    <h5 class="mb-3">Packaging List: <span id="salesOrderId">{{ $salesOrder->id }}</span></h5>
                </div>
            </div>

            {{-- Pending Approvals - Individual Warehouse Cards for Admin --}}
            @if ($isAdmin ?? false)
                @php
                    $displayProducts = [];
                    $facilityNamesArray = [];
                    foreach ($salesOrder->orderedProducts as $order) {
                        foreach ($order->warehouseAllocations as $allocation) {
                            $displayProducts[] = [
                                'order' => $order,
                                'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                                'allocated_quantity' => $allocation->allocated_quantity,
                                'warehouse_allocation_display' =>
                                    $allocation->warehouse->name . ': ' . $allocation->allocated_quantity,
                                'allocation_id' => $allocation->id,
                            ];
                            $facilityNamesArray[] = $order->tempOrder->facility_name;
                        }
                    }

                    // Group pending allocations by warehouse
                    $pendingWarehouseData = [];
                    foreach ($salesOrder->orderedProducts as $order) {
                        $allocationId = $displayProduct['allocation_id'] ?? null;
                        if ($allocationId) {
                            $order = $displayProduct['order'];
                            $allocation = $order->warehouseAllocations->firstWhere('id', $allocationId);
                            if (
                                $allocation &&
                                $allocation->approval_status === 'pending' &&
                                $allocation->final_dispatched_quantity > 0
                            ) {
                                $warehouseId = $allocation->warehouse_id;
                                $warehouseName = $displayProduct['warehouse_name'];

                                if (!isset($pendingWarehouseData[$warehouseId])) {
                                    $pendingWarehouseData[$warehouseId] = [
                                        'name' => $warehouseName,
                                        'allocation_ids' => [],
                                        'product_count' => 0,
                                    ];
                                }

                                if (!in_array($allocationId, $pendingWarehouseData[$warehouseId]['allocation_ids'])) {
                                    $pendingWarehouseData[$warehouseId]['allocation_ids'][] = $allocationId;
                                    $pendingWarehouseData[$warehouseId]['product_count']++;
                                }
                            }
                        }
                    }
                @endphp

                @if (count($pendingWarehouseData) > 0)
                    <div class="mb-3">
                        <h6 class="mb-2"><i class="bx bx-info-circle me-1"></i> Pending Warehouse Approvals</h6>
                        <div class="row g-2">
                            @foreach ($pendingWarehouseData as $warehouseId => $warehouseData)
                                <div class="col-md-6">
                                    <div class="alert alert-warning mb-0" role="alert">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><i class="bx bx-store me-1"></i>
                                                    {{ $warehouseData['name'] }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $warehouseData['product_count'] }} product(s)
                                                    ready for approval</small>
                                            </div>
                                            <div>
                                                <form action="{{ route('change.packaging.status.ready.to.ship') }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to approve {{ $warehouseData['name'] }} allocations?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                                                    <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bx bx-check"></i> Approve
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (count($pendingWarehouseData) > 1)
                            <div class="mt-2 text-end">
                                <form action="{{ route('change.packaging.status.ready.to.ship') }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to approve ALL warehouse allocations?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-check-double"></i> Approve All Warehouses
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <div class="div d-flex justify-content-end my-3 gap-2">
                            <h6 class="mb-3">Customer PO Table</h6>
                        </div>
                        <!-- Tabs Navigation -->
                        <div class="d-flex justify-content-end my-3 gap-2">

                            <!-- Client Name Dropdown -->
                            {{-- <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                <select class="form-select border-2 border-primary" id="customerPOTable"
                                    aria-label="Select Client Name">
                                    <option value="" selected> -- Select Client Name --</option>
                                    @foreach ($facilityNames as $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </ul> --}}

                            <button class="btn btn-sm border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop1" class="btn btn-sm border-2 border-primary">
                                Update PO
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('update.packing.products') }}" method="POST"
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
                                                    <input type="hidden" name="salesOrderId"
                                                        value="{{ $salesOrder->id }}">
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

                            <button id="exportPackagingProducts" class="btn btn-sm border-2 border-primary">
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
                                        {{-- <th>PO&nbsp;Number</th> --}}
                                        <th>SKU&nbsp;Code</th>
                                        <th>Facility&nbsp;Name</th>
                                        <th>Facility&nbsp;Location</th>
                                        <th>PO&nbsp;Date</th>
                                        <th>PO&nbsp;Expiry&nbsp;Date</th>
                                        <th>HSN</th>
                                        <th>Item&nbsp;Code</th>
                                        <th>Description</th>
                                        <th>GST</th>
                                        <th>Basic&nbsp;Rate</th>
                                        <th>Net&nbsp;Landing&nbsp;Rate</th>
                                        <th>MRP</th>
                                        <th>PO&nbsp;Quantity</th>
                                        <th>Purchase&nbsp;Order&nbsp;Quantity</th>
                                        <th>Warehouse&nbsp;Name</th>
                                        <th>Warehouse&nbsp;Allocation</th>
                                        {{-- <th>PI&nbsp;Quantity</th> --}}
                                        <th>Purchase&nbsp;Order&nbsp;No</th>
                                        <th>Total&nbsp;Dispatch&nbsp;Qty</th>
                                        <th>Final&nbsp;Dispatch&nbsp;Qty</th>
                                        <th>Box&nbsp;Count</th>
                                        <th>Weight</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salesOrder->orderedProducts as $order)
                                        <tr>
                                            <td>{{ $order->customer->contact_name }}</td>
                                            <td>{{ $order->tempOrder->sku }}</td>
                                            <td>{{ $order->tempOrder->facility_name }}</td>
                                            <td>{{ $order->tempOrder->facility_location }}</td>
                                            <td>{{ $order->tempOrder->po_date }}</td>
                                            <td>{{ $order->tempOrder->po_expiry_date }}</td>
                                            <td>{{ $order->tempOrder->hsn }}</td>
                                            <td>{{ $order->tempOrder->item_code }}</td>
                                            <td>{{ $order->tempOrder->description }}</td>
                                            <td>{{ $order->tempOrder->gst }}</td>
                                            <td>{{ $order->tempOrder->basic_rate }}</td>
                                            <td>{{ $order->tempOrder->net_landing_rate }}</td>
                                            <td>{{ $order->tempOrder->mrp }}</td>
                                            <td>{{ $order->tempOrder->po_qty }}</td>
                                            <td>{{ $order->purchase_ordered_quantity }}</td>
                                            @if ($isSuperAdmin)
                                                <td>All</td>
                                            @else
                                                <td>{{ $user->warehouse->name }}</td>
                                            @endif
                                            <td>
                                                @if ($order->warehouseAllocations->count() >= 1)
                                                    @foreach ($order->warehouseAllocations as $allocation)
                                                        @if ($isSuperAdmin ?? false)
                                                            <div>
                                                                {{ $allocation->warehouse->name }}:
                                                                {{ $allocation->allocated_quantity }}
                                                            </div>
                                                        @else
                                                            @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                <div>
                                                                    {{ $user->warehouse->name }}:
                                                                    {{ $allocation->allocated_quantity ?? 0 }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $order->dispatched_quantity ?? 0 }}
                                                @endif
                                            </td>
                                            <td>{{ $order->tempOrder->po_number }}</td>
                                            <td>
                                                @if ($order->warehouseAllocations->count() > 1)
                                                    @foreach ($order->warehouseAllocations as $allocation)
                                                        @if ($isSuperAdmin ?? false)
                                                            <div>
                                                                {{ $allocation->warehouse->name }}:
                                                                {{ $allocation->allocated_quantity ?? 0 }}
                                                            </div>
                                                        @else
                                                            @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                <div>
                                                                    {{ $allocation->allocated_quantity ?? 0 }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $order->dispatched_quantity ?? 0 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->warehouseAllocations->count() > 1)
                                                    @foreach ($order->warehouseAllocations as $allocation)
                                                        @if ($isSuperAdmin ?? false)
                                                            <div>
                                                                {{ $allocation->warehouse->name }}:
                                                                {{ $allocation->final_dispatched_quantity ?? 0 }}
                                                            </div>
                                                        @else
                                                            @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                <div>
                                                                    {{ $allocation->final_dispatched_quantity ?? 0 }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $order->final_dispatched_quantity ?? 0 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->warehouseAllocations->count() > 1)
                                                    @foreach ($order->warehouseAllocations as $allocation)
                                                        @if ($isSuperAdmin ?? false)
                                                            <div>
                                                                {{ $allocation->warehouse->name }}:
                                                                {{ $allocation->box_count ?? 0 }}
                                                            </div>
                                                        @else
                                                            @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                <div>
                                                                    {{ $allocation->box_count ?? 0 }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $order->box_count ?? 0 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->warehouseAllocations->count() > 1)
                                                    @foreach ($order->warehouseAllocations as $allocation)
                                                        @if ($isSuperAdmin ?? false)
                                                            <div>
                                                                {{ $allocation->warehouse->name }}:
                                                                {{ $allocation->weight ?? 0 }}
                                                            </div>
                                                        @else
                                                            @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                <div>
                                                                    {{ $allocation->weight ?? 0 }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $order->weight ?? 0 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->status == 'ready_to_ship')
                                                    @if ($isSuperAdmin ?? false)
                                                        <span
                                                            class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                            {{ $user->warehouse->name }}:
                                                            {{ $statusLabels[$order->status] ?? 'Ready to Ship' }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                            {{ $statusLabels[$order->status] ?? 'Ready to Ship' }}
                                                        </span>
                                                    @endif
                                                @else
                                                    @if ($order->warehouseAllocations->count() >= 1)
                                                        @foreach ($order->warehouseAllocations as $allocation)
                                                            @if ($isSuperAdmin ?? false)
                                                                @if ($allocation->final_dispatched_quantity > 0)
                                                                    <span
                                                                        class="badge {{ $statusBadges['packaged'] ?? 'bg-secondary' }}">
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $statusLabels['packaged'] ?? 'Unknown' }}
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="badge {{ $statusBadges['packaging'] ?? 'bg-secondary' }}">
                                                                        {{ $allocation->warehouse->name }}:
                                                                        {{ $statusLabels['packaging'] ?? 'Unknown' }}
                                                                    </span>
                                                                @endif
                                                            @else
                                                                @if ($user->warehouse_id == $allocation->warehouse_id)
                                                                    @if ($allocation->final_dispatched_quantity > 0)
                                                                        <span
                                                                            class="badge {{ $statusBadges['packaged'] ?? 'bg-secondary' }}">
                                                                            {{ $statusLabels['packaged'] ?? 'Unknown' }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge {{ $statusBadges['packaging'] ?? 'bg-secondary' }}">
                                                                            {{ $statusLabels['packaging'] ?? 'Unknown' }}
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span
                                                            class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                            {{-- {{ $order->warehouse->name }}: --}}
                                                            {{ $statusLabels[$order->status] ?? 'Unknown' }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="23" class="text-center">No records found. Please update or
                                                upload
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
                    <form action="{{ route('change.packaging.status.ready.to.ship') }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to mark your products as ready to ship?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                        <button class="btn btn-success w-sm waves ripple-light" type="submit">
                            @if ($isAdmin ?? false)
                                Mark All Ready to Ship
                            @else
                                Mark My Products Ready to Ship
                            @endif
                        </button>
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
                    customerPOTableList.column(2).search(selected ? '^' + selected + '$' : '', true, false)
                        .draw();
                });
            }

            $("#exportPackagingProducts").on("click", function() {
                var customerFacilityName = $("#customerPOTable").val().trim();
                var salesOrderId = $("#salesOrderId").text().trim();

                // if (customerFacilityName != "" && salesOrderId != "") {
                $.ajax({
                    url: '/download-packing-products-excel',
                    method: 'GET',
                    data: {
                        id: salesOrderId,
                        facility_name: customerFacilityName,
                    },
                    xhrFields: {
                        responseType: 'blob' // important for binary files
                    },
                    success: function(data, status, xhr) {
                        // Get filename from response header (optional)
                        var filename = xhr.getResponseHeader("Content-Disposition")
                            ?.split("filename=")[1] || "products.xlsx";

                        var url = window.URL.createObjectURL(data);
                        var a = document.createElement("a");
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        alert("Error");
                    }
                });
                // } else {
                //     alert("Please Select Client Name.");
                // }
            });

        });
    </script>
@endsection
