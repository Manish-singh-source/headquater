                                        @foreach ($reportRows as $row)
                                            @php
                                                $salesOrder = $row['salesOrder'];
                                                $product = $row['product'];
                                            @endphp
                                                @if (! $row['allocation'])
                                                    @php
                                                        $invoiceDetail = $product->invoiceDetails->first();
                                                        $invoice = $invoiceDetail?->invoice;
                                                    @endphp <tr>
                                                        <td>{{ $salesOrder->order_number ?? 'N/A' }}</td>
                                                        <td>{{ $salesOrder->created_at?->format('d-m-Y') ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $salesOrder->customerGroup->name ?? 'N/A' }}</td>
                                                        <td>N/A</td>
                                                        <td>{{ $product->customer?->client_name ?? 'N/A' }}</td>
                                                        <td>{{ $invoice?->invoice_number ?? 'N/A' }}</td>
                                                        @php
                                                            $invoiceDate = $invoice?->invoice_date ?? $invoice?->created_at;
                                                        @endphp
                                                        <td data-order="{{ $invoiceDate?->format('Y-m-d') ?? '' }}">
                                                            {{ $invoiceDate?->format('d-m-Y') ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $product->customer?->contact_no ?? 'N/A' }}</td>
                                                        <td>{{ $product->customer?->email ?? 'N/A' }}</td>
                                                        <td>{{ $product->customer?->shipping_city ?? 'N/A' }}</td>
                                                        <td>{{ $product->customer?->shipping_state ?? 'N/A' }}</td>
                                                        <td>{{ $product->tempOrder?->po_date ?? 'N/A' }}</td>
                                                        <td>{{ $product->tempOrder?->po_expiry_date ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $product->tempOrder?->po_number ?? 'N/A' }}</td>
                                                        <td>{{ $product->tempOrder?->sku ?? 'N/A' }}</td>
                                                        <td>{{ $product->product?->brand_title ?? 'N/A' }}</td>
                                                        <td>{{ $product->product?->brand ?? 'N/A' }}</td>
                                                        <td>{{ $product->product?->hsn ?? 'N/A' }}</td>
                                                        <td>{{ intval($product->tempOrder?->po_qty ?? ($product->ordered_quantity ?? 0)) }}
                                                        </td>
                                                        <td>0</td>
                                                        <td>N/A</td>
                                                        <td>0</td>
                                                        <td>N/A</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>{{ $product->tempOrder?->basic_rate ?? 0 }}</td>
                                                        <td>0</td>
                                                        <td>{{ $product->tempOrder?->gst ?? 0 }}</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>{{ $product->purchase_ordered_quantity ?? 0 }}</td>
                                                        <td>{{ $product->vendorPIProduct?->purchase_rate ?? 0 }}
                                                        </td>
                                                        <td>0</td>
                                                        <td>{{ $product->vendorPIProduct?->gst ?? 0 }}</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>N/A</td>
                                                        <td>N/A</td>
                                                    </tr>
                                                    @else
                                                        @php $allocation = $row['allocation']; @endphp
                                                            <tr>
                                                                <td>{{ $salesOrder->order_number ?? 'N/A' }}</td>
                                                                <td>{{ $salesOrder->created_at?->format('d-m-Y') ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $salesOrder->customerGroup->name ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $allocation->warehouse?->name ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->customer?->client_name ?? 'N/A' }}
                                                                </td>
                                                                <td> @php
                                                                    $invoiceNumber = 'N/A';
                                                                    $invoiceDetail = $product->invoiceDetails->first();
                                                                    $invoice = $invoiceDetail?->invoice;
                                                                    $invoiceNumber = $invoice->invoice_number ?? 'N/A';
                                                                @endphp {{ $invoiceNumber }} </td>
                                                                @php
                                                                    $invoiceDate = $invoice?->invoice_date ?? $invoice?->created_at;
                                                                @endphp
                                                                <td data-order="{{ $invoiceDate?->format('Y-m-d') ?? '' }}">
                                                                    {{ $invoiceDate?->format('d-m-Y') ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->customer?->contact_no ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->customer?->email ?? 'N/A' }}</td>
                                                                <td>{{ $product->customer?->shipping_city ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->customer?->shipping_state ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->tempOrder?->po_date ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->tempOrder?->po_expiry_date ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->tempOrder?->po_number ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $product->tempOrder?->sku ?? 'N/A' }}</td>
                                                                <td>{{ $product->product?->brand_title }}</td>
                                                                <td>{{ $product->product?->brand }}</td>
                                                                <td>{{ $product->product?->hsn }}</td>
                                                                <td>{{ intval($product->tempOrder?->po_qty ?? ($product->ordered_quantity ?? 0)) }}
                                                                </td>
                                                                <td>{{ $allocation->final_dispatched_quantity ?? 0 }}
                                                                </td>
                                                                <td> {{ $allocation->send_to_pkg_at ? \Carbon\Carbon::parse($allocation->send_to_pkg_at)->format('d-m-Y') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $allocation->final_final_dispatched_quantity ?? 0 }}
                                                                </td>
                                                                <td>
                                                                    @if ($allocation->send_to_pkg_at)
                                                                        @php
                                                                            $date = \Carbon\Carbon::parse(
                                                                                $allocation->send_to_pkg_at,
                                                                            );
                                                                            $daysAdded = 0;
                                                                            while ($daysAdded < 4) {
                                                                                $date->addDay();
                                                                                if (!$date->isSunday()) {
                                                                                    $daysAdded++;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        {{ $date->format('d-m-Y') }}
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </td>
                                                                <td>{{ $allocation->box_count ?? 0 }}</td>
                                                                <td>{{ $allocation->weight ?? 0 }}</td>
                                                                <td>{{ $product->tempOrder?->basic_rate ?? 0 }}
                                                                </td>
                                                                <td>{{ $allocation->final_final_dispatched_quantity * $product->tempOrder?->basic_rate ?? 0 }}
                                                                </td>
                                                                <td>{{ $product->tempOrder?->gst ?? 0 }}</td>
                                                                <td>{{ $allocation->final_final_dispatched_quantity * $product->tempOrder?->basic_rate * (($product->tempOrder?->gst ?? 0) / 100) ?? 0 }}
                                                                </td>
                                                                <td>{{ $allocation->final_final_dispatched_quantity * $product->tempOrder?->basic_rate * (1 + ($product->tempOrder?->gst ?? 0) / 100) ?? 0 }}
                                                                </td>
                                                                <td>{{ $product->purchase_ordered_quantity ?? 0 }}
                                                                </td>
                                                                <td>{{ $product->vendorPIProduct?->purchase_rate ?? 0 }}
                                                                </td>
                                                                <td> {{ $subtotal = $product->purchase_ordered_quantity * ($product->vendorPIProduct?->purchase_rate ?? 0) }}
                                                                </td>
                                                                <td>{{ $product->vendorPIProduct?->gst ?? 0 }}</td>
                                                                <td> {{ $gstAmount = $subtotal * (($product->vendorPIProduct?->gst ?? 0) / 100) }}
                                                                </td>
                                                                <td> {{ $subtotal + $gstAmount }} </td>
                                                                <td> {{ $allocation->product_status == 'completed' ? 'Shipped' : ucwords(str_replace('_', ' ', $allocation->product_status) ?? 'Pending') }}
                                                                </td>
                                                                <td> {{ ucwords(str_replace('_', ' ', $allocation?->invoice_status ?? 'N/A')) }}
                                                                </td>
                                                            </tr>
                                                    @endif
                                        @endforeach
