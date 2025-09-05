@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Delivery Details</h5>
                </div>
                <div class="col-6 d-flex justify-content-end text-end my-2 ">
                    <div>
                        <form id="statusForm" action="{{ route('change.sales.order.status') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_id" value="{{ $salesOrder->id }}">
                            <select class="form-select border-2 border-primary" id="changeStatus"
                                aria-label="Default select example" name="status">
                                <option value="" selected disabled>Change Status</option>
                                <option value="completed" @if ($salesOrder->status == 'completed') selected @endif>
                                    Completed</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex  flex-sm-row flex-col">
                        <ul class="col-12 list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Order Id</b></span>

                                <span>{{ $salesOrder->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Customer Group Name</b></span>
                                <span>{{ $salesOrder->customerGroup->name ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Phone No</b></span>
                                <span> {{ $customerInfo->contact_no ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Email</b></span>
                                <span> {{ $customerInfo->email ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Ordered Date</b></span>
                                <span> NA</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Delivery Date</b></span>
                                <span> NA</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b>Billing Address</b></span>
                                <span> {{ $customerInfo->billing_address ?? 'NA' }} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center  mb-2 pe-3">
                                <span><b> Shipping Address</b></span>
                                <span> {{ $customerInfo->shipping_address ?? 'NA' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                <span><b>Invoices PDF</b></span>
                                <span>
                                    <a aria-label="anchor" href="{{ route('invoice.downloadPdf', $invoice->id) }}"
                                        class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip"
                                        data-bs-original-title="View">
                                        Download
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="div d-flex my-2">
                                <div class="col">
                                    <h6 class="mb-3">PO Table</h6>
                                </div>
                            </div>
                            <div class="product-table" id="poTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table id="example" class="table align-middle">
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
                                                {{-- <th>PI&nbsp;Qty</th> --}}
                                                <th>Purchase&nbsp;Order&nbsp;No</th>
                                                <th>Total&nbsp;Dispatch&nbsp;Qty</th>
                                                <th>Final&nbsp;Dispatch&nbsp;Qty</th>
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
                                                    {{-- <td>{{ $order->warehouseStock->block_quantity ?? '0' }}</td> --}}
                                                    {{-- <td>{{ $order->ordered_quantity }}</td> --}}
                                                    <td>{{ $order->warehouseStock->quantity ?? '0' }}</td>
                                                    <td>{{ $order->tempOrder->po_number }}</td>
                                                    @if ($order->warehouseStock?->quantity)
                                                        <td>
                                                            @if ($order->vendorPIProduct?->order?->status != 'completed')
                                                                @if ($order->vendorPIProduct?->available_quantity >= $order->vendorPIProduct?->quantity_received)
                                                                    @if (
                                                                        $order->vendorPIProduct?->quantity_received + $order->warehouseStockLog?->block_quantity >=
                                                                            $order->ordered_quantity)
                                                                        <span
                                                                            class="badge text-success bg-success-subtle">{{ $order->ordered_quantity }}</span>
                                                                    @else
                                                                        <span
                                                                            class="badge text-danger bg-danger-subtle">{{ $order->vendorPIProduct?->quantity_received + $order->warehouseStockLog?->block_quantity }}</span>
                                                                    @endif
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
                                                    @if ($order->warehouseStock?->quantity)
                                                        <td>
                                                            @if ($order->vendorPIProduct?->order?->status != 'completed')
                                                                @if ($order->vendorPIProduct?->available_quantity >= $order->vendorPIProduct?->quantity_received)
                                                                    @if (
                                                                        $order->vendorPIProduct?->quantity_received + $order->warehouseStockLog?->block_quantity >=
                                                                            $order->ordered_quantity)
                                                                        <span
                                                                            class="badge text-success bg-success-subtle">{{ $order->ordered_quantity }}</span>
                                                                    @else
                                                                        <span
                                                                            class="badge text-danger bg-danger-subtle">{{ $order->vendorPIProduct?->quantity_received + $order->warehouseStockLog?->block_quantity }}</span>
                                                                    @endif
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
@endsection
