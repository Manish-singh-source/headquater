<table id="shortage-exceed-table" class="table align-middle">
    <thead class="table-light">
        <tr>
            <th>Purchase&nbsp;Order&nbsp;Id</th>
            <th>Brand&nbsp;Title</th>
            <th>SKU&nbsp;Code</th>
            <th>MRP</th>
            <th>GST</th>
            <th>HSN</th>
            <th>Quantity&nbsp;Requirement</th>
            <th>Available&nbsp;Quantity</th>
            <th>Purchase&nbsp;Rate</th>
            <th>Received&nbsp;Quantity</th>
            <th>Issue</th>
            <th>Issue&nbsp;Items</th>
            <th>Issue&nbsp;Reason</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vendorOrders as $order)
            <tr>
                <td><a
                        href="{{ route('purchase.order.view', $order->vendorPIProduct->purchase_order_id ?? 0) }}">{{ $order->vendorPIProduct->purchase_order_id ?? 0 }}</a>
                </td>
                <td>{{ $order->vendorPIProduct->product->brand_title ?? 'NA' }}</td>
                <td>{{ $order->vendorPIProduct->vendor_sku_code }}</td>
                <td>{{ $order->vendorPIProduct->mrp }}</td>
                <td>{{ $order->vendorPIProduct->gst }}</td>
                <td>{{ $order->vendorPIProduct->hsn }}</td>
                <td>{{ $order->vendorPIProduct->quantity_requirement }}</td>
                <td>{{ $order->vendorPIProduct->available_quantity }}</td>
                <td>{{ $order->vendorPIProduct->purchase_rate }}</td>
                <td>{{ $order->vendorPIProduct->quantity_received + $order->return_quantity }}</td>
                <td>{{ ucfirst($order->return_reason) }}</td>
                <td>{{ $order->return_quantity }}</td>
                <td>{{ $order->return_description }}</td>
                <td>
                    @php
                        $statusBadges = [
                            'pending' => 'bg-secondary',
                            'accepted' => 'bg-success',
                            'returned' => 'bg-info',
                        ];
                    @endphp
                    <span class="badge {{ $statusBadges[$order->return_status] }}">
                        {{ ucfirst($order->return_status) }}
                    </span>
                </td>
                @if ($order->return_status == 'pending')
                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a aria-label="return"
                            href="{{ route('return.vendor.products', $order->id) }}?status={{ $status ?? 'all' }}"
                            class="btn btn-icon btn-sm bg-warning-subtle me-1"
                            data-bs-toggle="tooltip" data-bs-original-title="Return">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-corner-up-left text-warning">
                                <polyline points="9 14 4 9 9 4"></polyline>
                                <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                            </svg>
                        </a>

                        <a aria-label="accept"
                            href="{{ route('accept.vendor.products', $order->id) }}?status={{ $status ?? 'all' }}"
                            class="btn btn-icon btn-sm bg-success-subtle me-1"
                            data-bs-toggle="tooltip" data-bs-original-title="Accept">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-check text-success">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </a>
                    </div>
                </td>
                @else
                <td>
                    --
                </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="15" class="text-center text-muted py-4">No records found</td>
            </tr>
        @endforelse
    </tbody>
</table>

