@extends('layouts.master')

@section('styles')
    <style>
        #hideTable {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
@endsection

@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="div d-flex my-2">
                <div class="col">
                    <h5 class="mb-3">Packaging List: <span id="salesOrderId">{{ $salesOrder->id }}</span></h5>
                </div>
            </div>

            {{-- Debug Info - Only show in debug mode
            @if(config('app.debug'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Debug Info:</strong><br>
                Is Admin: {{ isset($isAdmin) ? ($isAdmin ? 'Yes' : 'No') : 'NOT SET' }}<br>
                User Warehouse ID: {{ $userWarehouseId ?? 'NOT SET' }}<br>
                Total Products: {{ $salesOrder->orderedProducts->count() }}<br>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif --}}
            {{-- Pending Approvals - Individual Warehouse Cards for Admin --}}
            @if($isAdmin ?? false)
                @php
                    // Group pending allocations by warehouse
                    $pendingWarehouseData = [];
                    foreach ($displayProducts as $displayProduct) {
                        $allocationId = $displayProduct['allocation_id'] ?? null;
                        if ($allocationId) {
                            $order = $displayProduct['order'];
                            $allocation = $order->warehouseAllocations->firstWhere('id', $allocationId);
                            if ($allocation && $allocation->approval_status === 'pending' && $allocation->final_dispatched_quantity > 0) {
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

                @if(count($pendingWarehouseData) > 0)
                    <div class="mb-3">
                        <h6 class="mb-2"><i class="bx bx-info-circle me-1"></i> Pending Warehouse Approvals</h6>
                        <div class="row g-2">
                            @foreach($pendingWarehouseData as $warehouseId => $warehouseData)
                                <div class="col-md-6">
                                    <div class="alert alert-warning mb-0" role="alert">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><i class="bx bx-store me-1"></i> {{ $warehouseData['name'] }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $warehouseData['product_count'] }} product(s) ready for approval</small>
                                            </div>
                                            <div>
                                                <form action="{{ route('change.packaging.status.ready.to.ship') }}" method="POST" class="d-inline"
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

                        @if(count($pendingWarehouseData) > 1)
                            <div class="mt-2 text-end">
                                <form action="{{ route('change.packaging.status.ready.to.ship') }}" method="POST" class="d-inline"
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
                                                    <input type="hidden" name="salesOrderId" value="{{ $salesOrder->id }}">
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
                                    @forelse($displayProducts as $displayProduct)
                                        @php
                                            $order = $displayProduct['order'];
                                        @endphp
                                        <tr>
                                            <td>{{ $order->customer->contact_name }}</td>
                                            {{-- <td>{{ $order->tempOrder->po_number }}</td> --}}
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
                                            <td>{{ $order->tempOrder->purchase_order_quantity }}</td>
                                            <td>
                                                @php
                                                    // Check if product has warehouse allocations (auto-allocation)
                                                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;
                                                    $warehouseName = 'N/A';

                                                    if ($hasAllocations) {
                                                        if ($isAdmin ?? false) {
                                                            $warehouseName = 'All';
                                                        } else {
                                                            // Warehouse user: Show their warehouse name
                                                            $userAllocation = $order->warehouseAllocations->where('warehouse_id', $userWarehouseId ?? 0)->first();
                                                            if ($userAllocation) {
                                                                $warehouseName = $userAllocation->warehouse->name ?? 'N/A';
                                                            } else {
                                                                // Fallback: Check if any warehouse stock exists for this SKU in user's warehouse
                                                                $warehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                                                                    ->where('warehouse_id', $userWarehouseId ?? 0)
                                                                    ->first();
                                                                if ($warehouseStock) {
                                                                    $warehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        // No allocations found - check warehouse stock for blocked quantity
                                                        $warehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                                                            ->where('block_quantity', '>', 0)
                                                            ->first();

                                                        if ($warehouseStock) {
                                                            if ($isAdmin ?? false) {
                                                                $warehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                                                            } else {
                                                                // Warehouse user: Only show if it's their warehouse
                                                                if ($warehouseStock->warehouse_id == ($userWarehouseId ?? 0)) {
                                                                    $warehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                                                                }
                                                            }
                                                        } elseif ($order->warehouseStock) {
                                                            $warehouseName = $order->warehouseStock->warehouse->name ?? 'N/A';
                                                        } elseif ($order->tempOrder && $order->tempOrder->block > 0 && ($isAdmin ?? false)) {
                                                            $warehouseName = 'All';
                                                        }
                                                    }
                                                @endphp
                                                {{ $warehouseName }}
                                            </td>
                                            <td>
                                                @php
                                                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;
                                                @endphp

                                                @if($hasAllocations)
                                                    {{-- Auto-allocation: Show warehouse-wise breakdown --}}
                                                    @if($isAdmin ?? false)
                                                        {{-- Admin sees all warehouses --}}
                                                        @if($order->warehouseAllocations->count() > 0)
                                                            @foreach($order->warehouseAllocations->sortBy('sequence') as $allocation)
                                                                <div class="mb-1">
                                                                    <strong>{{ $allocation->warehouse->name ?? 'N/A' }}</strong>: {{ $allocation->allocated_quantity }}
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No allocations</span>
                                                        @endif
                                                    @else
                                                        {{-- Warehouse user sees only their warehouse --}}
                                                        @php
                                                            $userAllocations = $order->warehouseAllocations->where('warehouse_id', $userWarehouseId ?? 0);
                                                        @endphp
                                                        @if($userAllocations->count() > 0)
                                                            @foreach($userAllocations as $allocation)
                                                                <div class="mb-1">
                                                                    <strong>{{ $allocation->warehouse->name ?? 'N/A' }}</strong>: {{ $allocation->allocated_quantity }}
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{-- Fallback: Check warehouse stock for this SKU in user's warehouse --}}
                                                            @php
                                                                $warehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                                                                    ->where('warehouse_id', $userWarehouseId ?? 0)
                                                                    ->where('block_quantity', '>', 0)
                                                                    ->first();
                                                            @endphp
                                                            @if($warehouseStock && $order->tempOrder)
                                                                <div class="mb-1">
                                                                    <strong>{{ $warehouseStock->warehouse->name ?? 'N/A' }}</strong>: {{ $order->tempOrder->block ?? 0 }}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    {{-- Single warehouse allocation --}}
                                                    @if($order->warehouseStock)
                                                        <div>
                                                            <strong>{{ $order->warehouseStock->warehouse->name ?? 'N/A' }}</strong>: {{ $order->tempOrder->block ?? 0 }}
                                                        </div>
                                                    @elseif($order->tempOrder && $order->tempOrder->block > 0)
                                                        {{-- Fallback: Try to find warehouse from warehouse stock --}}
                                                        @php
                                                            $fallbackWarehouseName = 'N/A';
                                                            $fallbackQuantity = $order->tempOrder->block ?? 0;

                                                            // First, try to get from warehouse stock for this SKU
                                                            $warehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                                                                ->where('block_quantity', '>', 0)
                                                                ->first();

                                                            if ($warehouseStock) {
                                                                $fallbackWarehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                                                            } elseif ($isAdmin ?? false) {
                                                                // For admin, if no warehouse stock found but we have blocked quantity,
                                                                // it might be from auto-allocation that didn't create allocations yet
                                                                $fallbackWarehouseName = 'Multiple Warehouses';
                                                            } elseif ($userWarehouseId) {
                                                                // For warehouse user, check their specific warehouse
                                                                $userWarehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                                                                    ->where('warehouse_id', $userWarehouseId)
                                                                    ->where('block_quantity', '>', 0)
                                                                    ->first();
                                                                if ($userWarehouseStock) {
                                                                    $fallbackWarehouseName = $userWarehouseStock->warehouse->name ?? 'N/A';
                                                                }
                                                            }
                                                        @endphp

                                                        @if($isAdmin ?? false)
                                                            <div>
                                                                <strong>{{ $fallbackWarehouseName }}</strong>: {{ $fallbackQuantity }}
                                                            </div>
                                                        @else
                                                            {{-- Warehouse user: Show only if it's their warehouse --}}
                                                            @if($fallbackWarehouseName !== 'N/A' && $fallbackWarehouseName !== 'Multiple Warehouses')
                                                                <div>
                                                                    <strong>{{ $fallbackWarehouseName }}</strong>: {{ $fallbackQuantity }}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                @endif
                                            </td>
                                            {{-- <td>{{ $order->tempOrder?->vendor_pi_fulfillment_quantity }}</td> --}}
                                            <td>{{ $order->tempOrder->po_number }}</td>
                                            <td>
                                                @php
                                                    // Calculate Total Dispatch Qty based on user role
                                                    $totalDispatchQty = 0;
                                                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                                                    if ($hasAllocations) {
                                                        // Auto-allocation case
                                                        if ($isAdmin ?? false) {
                                                            // Admin: Show total from all warehouses
                                                            $totalDispatchQty = $order->warehouseAllocations->sum('allocated_quantity');
                                                        } else {
                                                            // Warehouse user: Show only their warehouse's quantity
                                                            $totalDispatchQty = $order->warehouseAllocations
                                                                ->where('warehouse_id', $userWarehouseId ?? 0)
                                                                ->sum('allocated_quantity');
                                                        }
                                                    } else {
                                                        // Single warehouse case or fallback
                                                        if ($isAdmin ?? false) {
                                                            // Admin: Show full blocked quantity from tempOrder
                                                            $totalDispatchQty = $order->tempOrder->block ?? 0;
                                                        } else {
                                                            // Warehouse user: Show only if it's their warehouse
                                                            if ($order->warehouseStock && $order->warehouseStock->warehouse_id == ($userWarehouseId ?? 0)) {
                                                                $totalDispatchQty = $order->tempOrder->block ?? 0;
                                                            } else {
                                                                $totalDispatchQty = 0;
                                                            }
                                                        }
                                                    }

                                                    // Fallback: If still 0, use tempOrder->block (order-specific blocked quantity)
                                                    if ($totalDispatchQty == 0 && isset($order->tempOrder->block)) {
                                                        $totalDispatchQty = $order->tempOrder->block;
                                                    }
                                                @endphp
                                                {{ $totalDispatchQty }}
                                            </td>
                                            <td>
                                                @php
                                                    // Calculate Final Dispatch Qty from warehouse allocations if available
                                                    $finalDispatchQty = 0;
                                                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                                                    if ($hasAllocations) {
                                                        if ($isAdmin ?? false) {
                                                            // Admin: Sum all warehouses' final dispatch quantities
                                                            $finalDispatchQty = $order->warehouseAllocations->sum('final_dispatched_quantity') ?: 0;
                                                        } else {
                                                            // Warehouse user: Only their warehouse's final dispatch quantity
                                                            $finalDispatchQty = $order->warehouseAllocations
                                                                ->where('warehouse_id', $userWarehouseId ?? 0)
                                                                ->sum('final_dispatched_quantity') ?: 0;
                                                        }
                                                    } else {
                                                        // Single warehouse or fallback to sales_order_products table
                                                        $finalDispatchQty = $order->final_dispatched_quantity ?? 0;
                                                    }
                                                @endphp
                                                {{ $finalDispatchQty }}
                                            </td>
                                            <td>{{ $order->box_count ?? 0 }}</td>
                                            <td>{{ $order->weight ?? 0 }}</td>
                                            <td>
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

                                                    // For multi-warehouse products, determine status based on warehouse allocation
                                                    $currentStatus = 'packaging'; // Default status
                                                    $allocationId = $displayProduct['allocation_id'] ?? null;

                                                    if ($allocationId) {
                                                        // Multi-warehouse product - check allocation status
                                                        $allocation = $order->warehouseAllocations->firstWhere('id', $allocationId);

                                                        if ($allocation) {
                                                            if ($allocation->approval_status === 'approved') {
                                                                $currentStatus = 'ready_to_ship';
                                                            } elseif ($allocation->final_dispatched_quantity > 0) {
                                                                $currentStatus = 'packaged';
                                                            } else {
                                                                $currentStatus = 'packaging';
                                                            }
                                                        }
                                                    } else {
                                                        // Single warehouse product - use product status
                                                        $currentStatus = $order->status ?? 'packaging';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusBadges[$currentStatus] ?? 'bg-secondary' }}">
                                                    {{ $statusLabels[$currentStatus] ?? 'Unknown' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="23" class="text-center">No records found. Please update or upload
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
                @if(!($isAdmin ?? false))
                    <div class="text-end">
                        <form action="{{ route('change.packaging.status.ready.to.ship') }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to mark products as Ready to Ship?')">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                            <button class="btn btn-success w-sm waves ripple-light" type="submit">
                                <i class="bx bx-package"></i> Ready to Ship
                            </button>
                        </form>
                    </div>
                @endif
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
