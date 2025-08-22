<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice</title>
    <style>
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
    </style>
</head>

<body>

    <table class="no-border">
        <tr>
            <td width="20%" rowspan="4" style="text-align:center;">Company Logo</td>
            <td class="header" colspan="2">INOVIZIDEAS PVT. LTD.</td>
            <td rowspan="4" style="text-align:center;">Original for Recipient</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                BLDG.3 GALA.110 ARIHANT COMPLEX, KOPER BHIWANDI, THANE 421302.,<br>
                Mumbai, Maharashtra (MH-27) 421302, IN
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">Tel: +91 9004858507</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">GSTIN: 27AAGCI3319H1ZM</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4" class="title">Tax Invoice</td>
        </tr>
        <tr>
            <td>Invoice No:</td>
            <td>{{ $invoice->invoice_number }}</td>
            <td>Invoice date:</td>
            <td>{{ $invoice->invoice_date }}</td>
        </tr>
        <tr>
            <td>Reverse Charge (Y/N):</td>
            <td>N</td>
            <td>State: {{ $invoice->state }}</td>
            <td>Code: {{ $invoice->code }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4" class="section-title">Bill to Party</td>
        </tr>
        <tr>
            <td>Name:</td>
            <td colspan="3">{{ $invoice->customer->client_name }}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td colspan="3">{{ $invoice->customer->address->billing_address }}</td>
        </tr>
        <tr>
            <td>GSTIN:</td>
            <td>{{ $invoice->customer->gstin }}</td>
            <td>State:</td>
            <td>{{ $invoice->customer->address->shipping_state }}</td>
        </tr>
    </table>

    <table>
        <tr class="section-title">
            <th>S. No.</th>
            <th>ASIN</th>
            <th>PRODUCT CODE</th>
            <th>Description</th>
            <th>HSN Code</th>
            <th>Qty</th>
            <th>BOX</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>IGST Rate</th>
            <th>IGST Amount</th>
            <th>Total</th>
        </tr>
        @php
            $totalAmountSum = 0;
            $totalIgstSum = 0;
        @endphp
        @foreach ($invoiceDetails as $index => $detail)
            <tr>
                {{ $igstAmount = ($detail->tax / 100) * $detail->amount }}
                {{ $totalAmount = $igstAmount + $detail->amount }}
                {{ $totalAmountSum = $totalAmount + $totalAmountSum }}
                {{ $totalIgstSum = $igstAmount + $totalIgstSum }}

                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->product->ean_code }}</td>
                <td>{{ $detail->product->sku }}</td>
                <td>{{ $detail->product->brand_title }}</td>
                <td>{{ $detail->product->tempOrder?->hsn }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->box }}</td>
                <td>{{ $detail->unit_price }}</td>
                <td>{{ $detail->amount }}</td>
                <td>{{ $detail->tax }}</td>
                <td>{{ $igstAmount }}</td>
                <td>{{ $totalAmount }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" class="section-title">Total</td>
            <td>{{ $invoiceDetails->sum('quantity') }}</td>
            <td>{{ $invoiceDetails->sum('box') }}</td>
            <td>{{ $invoiceDetails->sum('unit_price') }}</td>
            <td>{{ $invoiceDetails->sum('amount') }}</td>
            <td>{{ $invoiceDetails->sum('igst_rate') }}</td>
            <td>{{ $totalIgstSum }}</td>
            <td>{{ $totalAmountSum }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td>Total Invoice amount in words:</td>
            <td colspan="3">{{ ucfirst(numberToWords(floor($totalAmountSum))) }} Rupees Only</td>
            {{-- <td colspan="3">{{ $totalAmountSum }}</td> --}}
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">Bank Details</td>
        </tr>
        <tr>
            <td>Bank A/C:</td>
            <td></td>
        </tr>
        <tr>
            <td>Bank IFSC:</td>
            <td></td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="section-title">Terms & Conditions</td>
        </tr>
        <tr>
            <td colspan="2">
                TOTAL SETS - QTY 160<br>
                TOTAL BOX COUNT - 15<br>
                WEIGHT - KG 183
            </td>
        </tr>
    </table>

</body>

</html>
