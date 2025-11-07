<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice</title>
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .no-border td {
            border: none;
        }

        .header,
        .footer {
            text-align: center;
            font-weight: bold;
        }

        .title {
            background-color: #d0e4f5;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
        }

        .section-title {
            background-color: #d0e4f5;
            font-weight: bold;
        }
    </style> --}}

    <style>
        @page {
            margin: 5mm;
            /* Adjust this value - smaller = less margin */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 5px;
            /* Minimal padding for content */
            box-sizing: border-box;
        }

        /* body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        } */

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

        /* .invoice-table {
            border-collapse: collapse;
            width: 80%;
            margin: 0 auto;
        } */

        td,
        th {
            border: 1px solid #000;
            padding: 3px;
            /* Reduce padding */
            vertical-align: top;
            font-size: 11px;
            /* Small font for tables */
            word-break: break-word;
            /* Wrap content inside cell */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .no-border td {
            border: none;
        }

        .header,
        .footer {
            text-align: center;
            font-weight: bold;
        }

        .title {
            background-color: #d0e4f5;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
        }

        .section-title {
            background-color: #d0e4f5;
            font-weight: bold;
        }

        /* Set widths for each column for best fit */
        .invoice-table th,
        .invoice-table td {
            /* Adjust these widths as needed to prevent overflow */
        }

        .sno {
            width: 4%;
        }

        .item-desc {
            width: 32%;
            /* Combined Product Code + ASIN + Description */
        }

        .hsn {
            width: 8%;
        }

        .qty {
            width: 4%;
        }

        .box {
            width: 4%;
        }

        .rate {
            width: 6%;
        }

        .amt {
            width: 9%;
        }

        .igstr {
            width: 6%;
        }

        .igsta {
            width: 8%;
        }

        .total {
            width: 9%;
        }

        .right-align {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <table class="no-border">
        <tr>
            <td width="20%" rowspan="4" style="text-align:left;">Company Logo</td>
            <td class="header" colspan="2">INOVIZIDEAS PVT. LTD.</td>
            <td rowspan="4" style="text-align:right;">Original for Recipient</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                BLDG.3 GALA.110 ARIHANT COMPLEX, KOPER BHIWANDI, THANE 421302.,
                Mumbai, Maharashtra (MH-27) 421302, IN
            </td>
        </tr>
        <tr>
            <td style="text-align:center; ">Tel: +91 9004858507</td>
            <td style="text-align:center; ">Email: accounts@inovizideas.com</td>
        </tr>
        <tr>
            <td style="text-align:center; ">Contact Name: Parag Patel</td>
            <td style="text-align:center; ">GSTIN: 27AAGCI3319H1ZM</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4" class="title">Tax&nbsp;Invoice</td>
        </tr>
        <tr>
            <td>Invoice&nbsp;No:</td>
            <td>{{ $invoice->invoice_number }}</td>
            <td>Invoice&nbsp;date:</td>
            <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
        </tr>
        <tr>
            {{-- <td>Reverse&nbsp;Charge&nbsp;(Y/N):</td> --}}
            {{-- <td>N</td> --}}
            {{-- <td>State: {{ $invoice->customer->shipping_state }}</td> --}}
            {{-- <td>Code: {{ $invoice->customer->shipping_zip }}</td> --}}
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
        <tr>
            <td>Name:</td>
            <td colspan="2">{{ $invoice->customer->client_name }}</td>
            <td>Name:</td>
            <td colspan="2">{{ $invoice->customer->client_name }}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td colspan="2">{{ $invoice->customer->billing_address }}</td>
            <td>Address:</td>
            <td colspan="2">{{ $invoice->customer->shipping_address }}</td>
        </tr>
        <tr>
            <td>State:</td>
            <td colspan="2">{{ $invoice->customer->billing_state }}</td>
            {{-- <td>GSTIN: {{ $invoice->customer->gstin }}</td> --}}

            <td>State:</td>
            <td colspan="2">{{ $invoice->customer->shipping_state }}</td>
            {{-- <td colspan="2">GSTIN: {{ $invoice->customer->gstin }}</td> --}}
        </tr>
        <tr>
            <td>GSTIN: </td>
            <td>{{ $invoice->customer->gstin }}</td>
            <td>PAN: {{ $invoice->customer->pan }}</td>
            <td>GSTIN: </td>
            <td>{{ $invoice->customer->gstin }}</td>
            <td>PAN: {{ $invoice->customer->pan }}</td>
        </tr>
        <tr>
            <td>Contact Name: </td>
            <td colspan="2">{{ $invoice->customer->contact_name }}</td>
            <td>Contact No.: </td>
            <td colspan="2">{{ $invoice->customer->contact_no }}</td>
        </tr>
    </table>

    <table class="invoice-table">
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
                <th class="hsn">HSN </br>Code</th>
                <th class="qty">Qty</th>
                <th class="box">BOX</th>
            @endif
            <th class="rate">Rate</th>
            <th class="amt">Amount</th>
            @if ($igstStatus)
                <th class="igstr">CGST </br> Rate</th>
                <th class="igsta">CGST </br> Amount</th>
                <th class="igstr">SGST </br> Rate</th>
                <th class="igsta">SGST </br> Amount</th>
            @else
                <th class="igstr">IGST </br> Rate</th>
                <th class="igsta">IGST </br> Amount</th>
            @endif
            <th class="total">Total</th>
        </tr>
        @php
            $totalAmountSum = 0;
            $totalIgstSum = 0;
            $totalBoxCount = 0;
            $totalWeight = 0;
        @endphp
        @foreach ($invoiceDetails as $index => $detail)
            <tr>
                {{ $igstAmount = ($detail->tax / 100) * $detail->amount }}
                {{ $totalAmount = $igstAmount + $detail->amount }}
                {{ $totalAmountSum = $totalAmount + $totalAmountSum }}
                {{ $totalIgstSum = $igstAmount + $totalIgstSum }}
                {{ $totalBoxCount += $detail->box_count ? $detail->box_count : $detail->salesOrderProduct?->box_count }}
                {{ $totalWeight += $detail->weight ? $detail->weight : $detail->salesOrderProduct?->weight }}

                <td class="text-center">{{ $index + 1 }}</td>

                @if ($invoiceItemType === 'service')
                    <td>{{ $detail->service_title }}</td>
                    <td>{{ $detail->service_category }}</td>
                    <td>{{ $detail->service_description }}</td>
                    <td>{{ $detail->campaign_name }}</td>
                    <td class="right-align">{{ $detail->quantity }}</td>
                    <td>{{ $detail->unit_type }}</td>
                    <td class="right-align">{{ $detail->box_count ?? 0 }}</td>
                    <td class="right-align">{{ $detail->weight ?? 0 }}</td>
                @else
                    <td>
                        <strong style="color: #000000;"> {{ $detail->product?->ean_code }} </strong>
                        <br>
                        {{ $detail->product?->sku }}
                        <br>
                        {{ $detail->product?->brand_title }}
                    </td>
                    <td class="right-align">{{ $detail->hsn ?? $detail->tempOrder?->hsn }}</td>
                    <td class="right-align">{{ $detail->quantity }}</td>
                    <td class="right-align">{{ $detail->box_count ?? $detail->salesOrderProduct?->box_count }}</td>
                @endif

                <td class="right-align">{{ $detail->unit_price }}</td>
                <td class="right-align">{{ $detail->amount }}</td>
                @if ($igstStatus)
                    <td class="right-align">{{ floor($detail->tax / 2) }}%</td>
                    <td class="right-align">{{ $igstAmount / 2 }}</td>
                    <td class="right-align">{{ floor($detail->tax / 2) }}%</td>
                    <td class="right-align">{{ $igstAmount / 2 }}</td>
                @else
                    <td class="right-align">{{ floor($detail->tax) }}%</td>
                    <td class="right-align">{{ $igstAmount }}</td>
                @endif
                <td class="right-align">{{ $totalAmount }}</td>
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
                <td class="right-align">{{ $TotalBoxCount }}</td>
            @endif
            <td class="right-align">{{ $invoiceDetails->sum('unit_price') }}</td>
            <td class="right-align">{{ $invoiceDetails->sum('amount') }}</td>
            @if ($igstStatus)
                <td class="right-align">{{ $invoiceDetails->sum('igst_rate') / 2 }}</td>
                <td class="right-align">{{ $totalIgstSum / 2 }}</td>
                <td class="right-align">{{ $invoiceDetails->sum('igst_rate') / 2 }}</td>
                <td class="right-align">{{ $totalIgstSum / 2 }}</td>
            @else
                <td class="right-align">{{ $invoiceDetails->sum('igst_rate') }}</td>
                <td class="right-align">{{ $totalIgstSum }}</td>
            @endif
            <td class="right-align">{{ $totalAmountSum }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td>Total&nbsp;Invoice&nbsp;amount&nbsp;in&nbsp;words:</td>
            {{-- <td colspan="3">{{ ucfirst(numberToWords(floor($totalAmountSum))) }} Rupees Only</td> --}}
            <td colspan="3" class="right-align">{{ $totalAmountSum }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="70%" class="section-title">Bank Details</td>
            <td width="30%" class="section-title text-center">Sign/Stamp</td>
        </tr>
        <tr>
            <td>Bank A/C:</td>
            <td rowspan="4" class="text-center" style="height:50px; vertical-align:bottom;">
                (Authorised Signature)
            </td>
        </tr>
        <tr>
            <td>Bank IFSC:</td>
        </tr>
        <tr>
            <td class="section-title">Terms & Conditions:</td>
        </tr>
        <tr>
            <td colspan="2">
                TOTAL&nbsp;SETS&nbsp;-&nbsp;QTY {{ $invoiceDetails->sum('quantity') }}<br>
                TOTAL&nbsp;BOX&nbsp;COUNT&nbsp;- {{ $totalBoxCount ?? ($TotalBoxCount ?? 0) }}<br>
                WEIGHT&nbsp;-&nbsp;KG {{ $totalWeight ?? ($TotalWeight ?? 0) }}
            </td>

        </tr>
    </table>


</body>

</html>
