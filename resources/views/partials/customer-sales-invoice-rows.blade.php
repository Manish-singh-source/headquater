    @foreach ($invoices as $invoice)
        @php $salesOrder = $invoice->salesOrder; @endphp
                                        @php
                                            // Calculate totals from invoice details and salesOrderProduct
                                            $totalBoxCount = 0;
                                            $totalWeight = 0;
                                            $taxableValue = 0;
                                            $gstAmount = 0;
                                            $cgstAmount = 0;
                                            $sgstAmount = 0;
                                            $igstAmount = 0;
                                            $cessAmount = 0;
                                            $firstGstRate = 0;

                                            foreach ($invoice->details as $detail) {
                                                // Box count - use invoice_details or fallback to salesOrderProduct
                                                $totalBoxCount +=
                                                    $detail->box_count ?? ($detail->salesOrderProduct?->box_count ?? 0);

                                                // Weight - use invoice_details or fallback to salesOrderProduct
                                                $totalWeight +=
                                                    $detail->weight ?? ($detail->salesOrderProduct?->weight ?? 0);

                                                // Taxable value is the 'amount' field
                                                $taxableValue += $detail->amount ?? 0;

                                                // GST amount calculation: (amount * tax) / 100
                                                $detailGstAmount = ($detail->amount * $detail->tax) / 100;
                                                $gstAmount += $detailGstAmount;
                                                $cessAmount += (float) ($detail->cess ?? 0);

                                                // Store first GST rate for display
                                                if ($firstGstRate == 0 && $detail->tax > 0) {
                                                    $firstGstRate = $detail->tax;
                                                }

                                                // Calculate CGST/SGST/IGST based on customer state
                                                // If same state: CGST + SGST, else: IGST
                                                $customerState =
                                                    $invoice->customer?->shipping_state ??
                                                    $invoice->customer?->billing_state;
                                                $warehouseState = $invoice->warehouse?->state ?? 'Maharashtra'; // Default warehouse state

                                                if (
                                                    $customerState &&
                                                    $warehouseState &&
                                                    strtolower($customerState) === strtolower($warehouseState)
                                                ) {
                                                    // Intra-state: CGST + SGST (split GST equally)
                                                    $cgstAmount += $detailGstAmount / 2;
                                                    $sgstAmount += $detailGstAmount / 2;
                                                } else {
                                                    // Inter-state: IGST
                                                    // these two are optional
                                                    $cgstAmount += $detailGstAmount / 2;
                                                    $sgstAmount += $detailGstAmount / 2;
                                                    $igstAmount += $detailGstAmount;
                                                }
                                            }

                                            // If line-level cess is unavailable, derive from invoice-level tax
                                            if ($cessAmount <= 0) {
                                                $invoiceTaxAmount = (float) ($invoice->tax_amount ?? 0);
                                                if ($invoiceTaxAmount > $gstAmount) {
                                                    $cessAmount = $invoiceTaxAmount - $gstAmount;
                                                }
                                            }

                                            $invoicePoNumber = $invoice->po_number;
                                            if (empty($invoicePoNumber)) {
                                                foreach ($invoice->details as $detail) {
                                                    $invoicePoNumber =
                                                        $detail->po_number ??
                                                        $detail->tempOrder?->po_number ??
                                                        $detail->salesOrderProduct?->tempOrder?->po_number;

                                                    if (!empty($invoicePoNumber)) {
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $salesOrder->order_number ?? 'N/A' }}</td>
                                            <td>{{ $salesOrder->customerGroup->name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->client_name ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->gstin ?? 'N/A' }}</td>
                                            <td>{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                            <td>{{ $invoice->created_at?->format('d-m-Y') ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->contact_no ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->email ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->shipping_city ?? 'N/A' }}</td>
                                            <td>{{ $invoice->customer->shipping_state ?? 'N/A' }}</td>
                                            <td>{{ $invoicePoNumber ?? 'N/A' }}</td>
                                            <td>{{ $invoice->details->first()?->tempOrder?->po_date ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->appointment?->appointment_date?->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->appointment?->appointment_date?->addMonth()->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->appointment?->grn_date?->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $invoice->appointment?->grn ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->appointment?->pod ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->dns?->dn_number ?? 'N/A' }}</td>
                                            <td>{{ $invoice->dns?->dn_amount ? $invoice->dns->dn_amount : 0 }}</td>
                                            <td>{{ $invoice->dns?->dn_receipt ? 'Yes' : 'No' }}</td>
                                            <td>{{ $invoice->dns?->dn_reason ?: 'N/A' }}</td>
                                            {{-- <td>{{ $invoice->lr ? 'Yes' : 'No' }}</td> --}}
                                            <td>{{ $invoice->currency ?? 'INR' }}</td>
                                            <td>{{ $invoice->details->first()->hsn ?? $invoice->details->first()?->product?->hsn ??'N/A' }}</td>
                                            <td>{{ $invoice->details->sum('quantity') ?? 0 }}</td>
                                            <td>{{ $invoice->details->sum('quantity') ?? 0 }}</td>
                                            <td>{{ number_format($totalBoxCount, 0) }}</td>
                                            <td>{{ number_format($totalWeight, 2) }}</td>
                                            <td>₹{{ number_format($taxableValue, 2) }}</td>
                                            <td>{{ $firstGstRate }}%</td>
                                            <td>₹{{ number_format($gstAmount, 2) }}</td>
                                            <td>₹{{ number_format($gstAmount + $taxableValue ?? 0, 2) }}</td>
                                            <td>{{ ucfirst($invoice->payment_status ?? 'N/A') }}</td>
                                            <td>₹{{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                                            <td>₹{{ number_format($invoice->balance_due ?? 0, 2) }}</td>
                                            <td>{{ $invoice->payments?->first()?->created_at?->format('d-m-Y') ?? 'N/A' }}
                                            </td>
                                            <td>{{ ucwords(str_replace('_', ' ', $invoice->payments->first()->payment_method ?? 'N/A')) }}</td>
                                            <td>₹{{ number_format($cgstAmount, 2) }}</td>
                                            <td>₹{{ number_format($sgstAmount, 2) }}</td>
                                            <td>₹{{ number_format($igstAmount, 2) }}</td>
                                            <td>{{ number_format($cessAmount, 2) }}</td>
                                        </tr>
    @endforeach
