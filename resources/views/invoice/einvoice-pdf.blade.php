<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>E-Invoice</title>

    <style>
        @page {
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 5px;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

        td,
        th {
            border: 1px solid #daa520;
            padding: 3px;
            vertical-align: top;
            font-size: 11px;
            word-break: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        .invoice-copy {
            table-layout: fixed;
            page-break-after: always;
        }

        .invoice-copy.last-copy {
            page-break-after: auto;
        }

        .invoice-table {
            table-layout: fixed;
        }

        .no-border td {
            border: none;
        }

        .header {
            font-size: 18px;
        }

        .header,
        .footer {
            text-align: center;
            font-weight: bold;
        }

        .title {
            background-color: #ffffcc;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
        }

        .section-title {
            background-color: #ffffcc;
            font-weight: bold;
        }

        .sno {
            width: 3%;
        }

        .item-desc {
            width: 22%;
        }

        .hsn {
            width: 7%;
        }

        .qty {
            width: 4%;
        }

        .box {
            width: 4%;
        }

        .rate {
            width: 7%;
        }

        .amt {
            width: 7%;
        }

        .igstr {
            width: 5%;
        }

        .igsta {
            width: 7%;
        }

        .total {
            width: 8%;
        }

        .right-align {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .invoice-copy td {
            background-color: #e8f07f0e;
        }

        .invoice-copy th {
            background-color: #ffffcc;
        }

        .header-cell,
        .summary-cell {
            border: none;
            padding: 0;
            background-color: transparent;
        }

        .company-header td {
            background-color: #ffffff;
        }

        .einvoice-info {
            border: 2px solid #daa520;
            margin: 10px 0;
        }

        .top-right-note {
            width: 100%;
            text-align: right;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    @php
        $copyLabels = ['Original Copy', 'Duplicate Copy', 'Triplicate Copy'];
        $taxColumnCount = $igstStatus ? 4 : 2;
        $itemColumnCount = $invoiceItemType === 'service' ? 12 + $taxColumnCount : 8 + $taxColumnCount;
    @endphp

    @foreach ($copyLabels as $copyLabel)
        @php
            $totalAmountSum = 0;
            $totalIgstSum = 0;
            $totalBoxCount = 0;
            $totalWeight = 0;
        @endphp

        <table class="invoice-copy {{ $loop->last ? 'last-copy' : '' }}">
            <thead>
                <tr>
                    <td colspan="{{ $itemColumnCount }}" class="header-cell">
                        <div class="top-right-note">{{ $copyLabel }}</div>

                        <table class="no-border company-header">
                            <tr>
                                <td width="20%" rowspan="4" style="text-align:left;">
                                    <img src="{{ $image }}" alt="Logo" style="height: 100px; width: auto;">
                                </td>
                                <td class="header" colspan="2">INOVIZIDEAS PVT. LTD.</td>
                                <td width="20%" rowspan="4" style="text-align:right; vertical-align: center;">
                                    @if ($qrCodeImage)
                                        <img src="{{ $qrCodeImage }}" alt="E-Invoice QR Code" style="height: 90px; width: auto;">
                                    @else
                                        <img src="{{ $image1 }}" alt="E-Invoice" style="height: 90px; width: auto;">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center;">
                                    BLDG.3 GALA.110 ARIHANT COMPLEX, KOPER BHIWANDI, THANE 421302.,
                                    Mumbai, Maharashtra (MH-27) 421302, IN
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">Tel: +91 9004858507</td>
                                <td style="text-align:center;">Email: accounts@inovizideas.com</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">Contact Name: Parag Patel</td>
                                <td style="text-align:center;">GSTIN: 27AAGCI3319H1ZM</td>
                            </tr>
                        </table>

                        @if ($eInvoice->irn)
                            <table class="einvoice-info">
                                <tr>
                                    <td colspan="4" class="title">E-Invoice</td>
                                </tr>
                                <tr class="invoice-table">
                                    <td><b>IRN:</b></td>
                                    <td colspan="3">{{ $eInvoice->irn }}</td>
                                </tr>
                                <tr class="invoice-table">
                                    <td><b>Ack No:</b></td>
                                    <td>{{ $eInvoice->ack_no }}</td>
                                    <td><b>Ack Date:</b></td>
                                    <td>{{ $eInvoice->ack_dt ? date('d-m-Y H:i:s', strtotime($eInvoice->ack_dt)) : '' }}</td>
                                </tr>
                            </table>
                        @endif

                        <table>
                            <tr>
                                <td colspan="4" class="title">Tax&nbsp;Invoice</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Invoice&nbsp;No:</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>Invoice&nbsp;date:</td>
                                <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>PO No: </td>
                                <td>{{ $invoice->po_number }}</td>
                                <td>PO Date: </td>
                                @if ($invoice->po_date)
                                    <td>{{ $invoice->po_date ? $invoice->po_date->format('d-m-Y') : '' }}</td>
                                @elseif($invoiceDetails[0]->tempOrder?->po_date)
                                    <td>{{ $invoiceDetails[0]->tempOrder?->po_date }}</td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        </table>

                        <table>
                            <tr>
                                <td colspan="3" class="section-title">Bill&nbsp;To</td>
                                <td colspan="3" class="section-title">Ship&nbsp;To</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Name:</td>
                                <td colspan="2">{{ $invoice->customer->client_name }}</td>
                                <td>Name:</td>
                                <td colspan="2">{{ $invoice->customer->client_name }}</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Address:</td>
                                <td colspan="2">{{ $invoice->customer->billing_address }}</td>
                                <td>Address:</td>
                                <td colspan="2">{{ $invoice->customer->shipping_address }}</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>State:</td>
                                <td>{{ $invoice->customer->billing_state }}</td>
                                <td>Pincode: {{ $invoice->customer->billing_zip }}</td>
                                <td>State:</td>
                                <td>{{ $invoice->customer->shipping_state }}</td>
                                <td>Pincode: {{ $invoice->customer->shipping_zip }}</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>GSTIN: </td>
                                <td>{{ $invoice->customer->gstin }}</td>
                                <td>PAN: {{ $invoice->customer->pan }}</td>
                                <td>GSTIN: </td>
                                <td>{{ $invoice->customer->gstin }}</td>
                                <td>PAN: {{ $invoice->customer->pan }}</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Contact Name: </td>
                                <td colspan="2">{{ $invoice->customer->contact_name }}</td>
                                <td>Contact No.: </td>
                                <td colspan="2">{{ $invoice->customer->contact_no }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="section-title">
                    <th class="sno">S No.</th>
                    @if ($invoiceItemType === 'service')
                        <th class="item-desc">Service Title</th>
                        <th class="hsn">Category</th>
                        <th class="hsn">Description</th>
                        <th class="hsn">Campaign Name</th>
                        <th class="qty">Qty</th>
                        <th class="box">Unit Type</th>
                        <th class="box">BOX</th>
                        <th class="box">Weight (KG)</th>
                    @else
                        <th class="item-desc">Item Description</th>
                        <th class="hsn">HSN <br>Code</th>
                        <th class="qty">Qty</th>
                        <th class="box">BOX</th>
                    @endif
                    <th class="rate">Rate</th>
                    <th class="amt">Amount</th>
                    @if ($igstStatus)
                        <th class="igstr">CGST <br> Rate</th>
                        <th class="igsta">CGST <br> Amount</th>
                        <th class="igstr">SGST <br> Rate</th>
                        <th class="igsta">SGST <br> Amount</th>
                    @else
                        <th class="igstr">IGST <br> Rate</th>
                        <th class="igsta">IGST <br> Amount</th>
                    @endif
                    <th class="total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceDetails as $index => $detail)
                    @php
                        $igstAmount = (ceil($detail->tax) / 100) * $detail->amount;
                        $totalAmount = $igstAmount + $detail->amount;
                        $totalAmountSum = $totalAmount + $totalAmountSum;
                        $totalIgstSum = $igstAmount + $totalIgstSum;
                        $totalBoxCount += $detail->box_count
                            ? ceil($detail->box_count)
                            : $detail->salesOrderProduct?->box_count;
                        $totalWeight += $detail->weight ? $detail->weight : $detail->salesOrderProduct?->weight;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>

                        @if ($invoiceItemType === 'service')
                            <td>{{ $detail->service_title }}</td>
                            <td>{{ $detail->service_category }}</td>
                            <td>{{ $detail->service_description }}</td>
                            <td>{{ $detail->campaign_name }}</td>
                            <td class="right-align">{{ $detail->quantity }}</td>
                            <td>{{ $detail->unit_type }}</td>
                            <td class="right-align">{{ ceil($detail->box_count) ?? 0 }}</td>
                            <td class="right-align">{{ $detail->weight ?? 0 }}</td>
                        @else
                            <td>
                                <strong style="color: #000000;">{{ $detail->tempOrder?->item_code ?? $detail->item_code }}</strong>
                                <br>
                                {{ $detail->product?->sku }}
                                <br>
                                {{ $detail->tempOrder?->description ?? $detail->product?->brand_title }}
                            </td>
                            <td class="right-align">{{ $detail->hsn ?? $detail->tempOrder?->hsn }}</td>
                            <td class="right-align">{{ $detail->quantity }}</td>
                            <td class="right-align">{{ $detail->box_count ? intval($detail->box_count) : intval($detail->salesOrderProduct?->box_count) }}</td>
                        @endif

                        <td class="right-align">{{ number_format($detail->unit_price, 2) }}</td>
                        <td class="right-align">{{ number_format($detail->amount, 2) }}</td>
                        @if ($igstStatus)
                            <td class="right-align">{{ ceil($detail->tax) / 2 }}%</td>
                            <td class="right-align">{{ number_format($igstAmount / 2, 2) }}</td>
                            <td class="right-align">{{ ceil($detail->tax) / 2 }}%</td>
                            <td class="right-align">{{ number_format($igstAmount / 2, 2) }}</td>
                        @else
                            <td class="right-align">{{ ceil($detail->tax) }}%</td>
                            <td class="right-align">{{ number_format($igstAmount, 2) }}</td>
                        @endif
                        <td class="right-align">{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    @if ($invoiceItemType === 'service')
                        <td colspan="5" class="section-title">Total</td>
                        <td class="right-align">{{ $invoiceDetails->sum('quantity') }}</td>
                        <td></td>
                        <td class="right-align">{{ $totalBoxCount ?? ($TotalBoxCount ?? 0) }}</td>
                        <td class="right-align">{{ $totalWeight ?? ($TotalWeight ?? 0) }}</td>
                    @else
                        <td colspan="3" class="section-title">Total</td>
                        <td class="right-align">{{ $invoiceDetails->sum('quantity') }}</td>
                        <td class="right-align">{{ $totalBoxCount ?? ($TotalBoxCount ?? 0) }}</td>
                    @endif
                    <td class="right-align">{{ number_format($invoiceDetails->sum('unit_price'), 2) }}</td>
                    <td class="right-align">{{ number_format($invoiceDetails->sum('amount'), 2) }}</td>
                    @if ($igstStatus)
                        <td class="right-align">{{ $invoiceDetails->sum('igst_rate') / 2 }}</td>
                        <td class="right-align">{{ number_format($totalIgstSum / 2, 2) }}</td>
                        <td class="right-align">{{ $invoiceDetails->sum('igst_rate') / 2 }}</td>
                        <td class="right-align">{{ number_format($totalIgstSum / 2, 2) }}</td>
                    @else
                        <td class="right-align">{{ $invoiceDetails->sum('igst_rate') }}</td>
                        <td class="right-align">{{ number_format($totalIgstSum, 2) }}</td>
                    @endif
                    <td class="right-align">{{ number_format($totalAmountSum, 2) }}</td>
                </tr>

                <tr>
                    <td colspan="{{ $itemColumnCount }}" class="summary-cell">
                        <table>
                            <tr class="invoice-table">
                                <td>Total&nbsp;Invoice&nbsp;amount&nbsp;in&nbsp;words:</td>
                                <td colspan="3" class="right-align">
                                    {{ ucfirst(numberToWords(floor($totalAmountSum))) }} Rupees Only
                                </td>
                            </tr>
                        </table>

                        <table>
                            <tr>
                                <td width="70%" class="section-title">Bank Details</td>
                                <td width="30%" class="section-title text-center">Sign/Stamp</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Account Holder Name: INOVIZ IDEAS PRIVATE LIMITED</td>
                                <td rowspan="6" class="text-center" style="height:50px; vertical-align:bottom;">
                                    <div class="d-flex flex-col justify-content-center align-items-center">
                                        <img src="{{ $sign64Image }}" alt="Authorised Signature" width="100" height="100">
                                    </div>
                                </td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Bank Name: YES BANK</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Bank A/C: 034663700001092</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Bank IFSC: YESB0000346</td>
                            </tr>
                            <tr class="invoice-table">
                                <td>Branch Name: HINDUSTAN NAKA KANDIVALI WEST MUMBAI</td>
                            </tr>
                            <tr>
                                <td class="section-title">Terms & Conditions:</td>
                            </tr>
                            <tr class="invoice-table">
                                <td width="70%">
                                    TOTAL&nbsp;SETS&nbsp;-&nbsp;QTY {{ $invoiceDetails->sum('quantity') }}<br>
                                    TOTAL&nbsp;BOX&nbsp;COUNT&nbsp;- {{ $totalBoxCount ?? ($TotalBoxCount ?? 0) }}<br>
                                    WEIGHT&nbsp;-&nbsp;KG {{ $totalWeight ?? ($TotalWeight ?? 0) }}
                                </td>
                                <td width="30%" class="text-center" style="height:50px; vertical-align:bottom">
                                    <div>(Authorised Signature)</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>

</html>
