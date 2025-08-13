@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            'blocked' => 'Blocked',
            'completed' => 'Completed',
            'ready_to_ship' => 'Ready To Ship',
            'ready_to_package' => 'Ready To Package',
        ];
    @endphp

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div my-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>

                                    <span>#
                                        <span id="orderId">{{ $salesOrder->id }}</span>
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
                                    <span><b>PO Quantity Status</b></span>
                                    <span>
                                        <b>
                                            @php
                                                $sum = 0;
                                                $sum2 = 0;
                                                foreach ($salesOrder->orderedProducts as $order) {
                                                    $sum = $sum + (int) $order->ordered_quantity;
                                                }

                                                foreach ($salesOrder->orderedProducts as $order) {
                                                    if ($order->product?->sets_ctn) {
                                                        if ($order->vendorPIProduct?->order?->status != 'completed') {
                                                            if (
                                                                $order->vendorPIProduct?->available_quantity +
                                                                    $order->warehouseStockLog?->block_quantity >=
                                                                $order->ordered_quantity
                                                            ) {
                                                                $sum2 = $sum2 + $order->ordered_quantity;
                                                            } else {
                                                                $sum2 =
                                                                    $sum2 +
                                                                    $order->vendorPIProduct?->available_quantity +
                                                                    $order->warehouseStockLog?->block_quantity;
                                                            }
                                                        } else {
                                                            if (
                                                                $order->warehouseStockLog?->block_quantity >=
                                                                $order->ordered_quantity
                                                            ) {
                                                                $sum2 = $sum2 + $order->ordered_quantity;
                                                            } else {
                                                                $sum2 =
                                                                    $sum2 + $order->warehouseStockLog?->block_quantity;
                                                            }
                                                        }
                                                    } else {
                                                        $sum2 = $sum2 + 0;
                                                    }
                                                }

                                                $total = $sum - $sum2;
                                            @endphp
                                            @if ($total > 0)
                                                <span class="badge text-danger bg-danger-subtle">
                                                    {{ 'Quantity Needs To Fulfill: ' . $total }}
                                                </span>
                                            @else
                                                <span class="badge text-success bg-success-subtle"> Quantity Fulfilled
                                                </span>
                                            @endif
                                        </b>
                                    </span>
                                </li>
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
                            <button class="btn border-2 border-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop1" class="btn border-2 border-primary">
                                Update PO
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('order.update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Order</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="col-12 mb-3">
                                                    <input type="hidden" name="sales_order_id" value="{{ $salesOrder->id }}">
                                                    <label for="products_excel" class="form-label">Products List (CSV/ELSX)
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

                            <button class="btn btn-icon btn-sm border-2 border-primary me-1" id="exportData">
                                <i class="fa fa-file-excel-o"></i> Export to Excel
                            </button>
                            <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                                <form id="statusForm" action="{{ route('change.order.status') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                                    <select class="form-select border-2 border-primary" id="changeStatus"
                                        aria-label="Default select example" name="status">
                                        <option value="" selected disabled>Change Status</option>
                                        <option value="pending" @if ($salesOrder->status == 'pending') selected @endif>Pending
                                        </option>
                                        <option value="blocked" @if ($salesOrder->status == 'blocked') selected @endif>Blocked
                                        </option>
                                        <option value="ready_to_package" @if ($salesOrder->status == 'ready_to_package') selected @endif>
                                            Ready To Package</option>
                                        <option value="ready_to_ship" @if ($salesOrder->status == 'ready_to_ship') selected @endif>
                                            Ready To Ship</option>
                                        <option value="completed" @if ($salesOrder->status == 'completed') selected @endif>
                                            Completed</option>
                                    </select>
                                </form>
                            </ul>
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
                    <div class="product-table" id="poTable">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Customer&nbsp;Name</th>
                                        <th>Facility&nbsp;Name</th>
                                        <th>HSN</th>
                                        <th>GST</th>
                                        <th>Item&nbsp;Code</th>
                                        <th>SKU&nbsp;Code</th>
                                        <th>Title</th>
                                        <th>Basic&nbsp;Rate</th>
                                        <th>Net&nbsp;Landing&nbsp;Rate</th>
                                        <th>PO&nbsp;MRP</th>
                                        <th>Product&nbsp;MRP</th>
                                        <th>Rate&nbsp;Confirmation</th>
                                        <th>Qty&nbsp;Requirement</th>
                                        <th>Qty&nbsp;Fullfilled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statuses = [
                                            'pending' => 'Pending',
                                            'blocked' => 'Blocked',
                                            'completed' => 'Completed',
                                            'ready_to_ship' => 'Ready To Ship',
                                        ];
                                    @endphp
                                    @forelse($salesOrder->orderedProducts as $order)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox"
                                                    name="ids[]" value="{{ $order->id }}">
                                            </td>
                                            <td>{{ $order->tempOrder->customer_name }}</td>
                                            <td>{{ $order->tempOrder->facility_name }}</td>
                                            <td>{{ $order->tempOrder->hsn }}</td>
                                            <td>{{ $order->tempOrder->gst }}</td>
                                            <td>{{ $order->tempOrder->item_code }}</td>
                                            <td>{{ $order->tempOrder->sku }}</td>
                                            <td>{{ $order->tempOrder->description }}</td>
                                            <td>{{ $order->tempOrder->basic_rate }}</td>
                                            <td>{{ $order->tempOrder->net_landing_rate }}</td>
                                            <td>{{ $order->tempOrder->mrp }}</td>
                                            <td>{{ $order->tempOrder->product_mrp }}</td>
                                            @if ($order->tempOrder->mrp == $order->tempOrder->product_mrp)
                                                <td> <span class="badge text-success bg-success-subtle">Yes</span></td>
                                            @else
                                                <td><span class="badge text-danger bg-danger-subtle">No</span></td>
                                            @endif
                                            <td>{{ $order->ordered_quantity }}</td>
                                            @if ($order->warehouseStock?->quantity)
                                                <td>
                                                    @if ($order->vendorPIProduct?->order?->status != 'completed')
                                                        @if (
                                                            $order->vendorPIProduct?->available_quantity + $order->warehouseStockLog?->block_quantity >=
                                                                $order->ordered_quantity)
                                                            <span
                                                                class="badge text-success bg-success-subtle">{{ $order->ordered_quantity }}</span>
                                                        @else
                                                            <span
                                                                class="badge text-danger bg-danger-subtle">{{ $order->vendorPIProduct?->available_quantity + $order->warehouseStockLog?->block_quantity }}</span>
                                                        @endif
                                                    @else
                                                        @if ($order->warehouseStockLog?->block_quantity >= $order->ordered_quantity)
                                                            <span
                                                                class="badge text-success bg-success-subtle">{{ $order->ordered_quantity }}</span>
                                                        @else
                                                            <span
                                                                class="badge text-danger bg-danger-subtle">{{ $order->warehouseStockLog?->block_quantity }}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                            @else
                                                <td>
                                                    <span class="badge text-danger bg-danger-subtle">0</span>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No Records Found</td>
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


@section('script')
    <script>
        document.getElementById('changeStatus').addEventListener('change', function() {
            if (confirm('Are you sure you want to change status for order?')) {
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
        });
    </script>
    
    <script>
        $(document).on('click', '#exportData', function() {
            var purchaseOrderId = $("#orderId").text().trim();

            // Construct download URL with parameters
            var downloadUrl = '{{ route('products.download.po.excel') }}' +
                '?salesOrderId=' + encodeURIComponent(purchaseOrderId);

            // Trigger browser download
            window.location.href = downloadUrl;
        });
    </script>
@endsection
