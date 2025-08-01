<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - PDF Format</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background: white;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }
        
        .invoice-container {
            background: white;
            border: 2px solid #000;
            padding: 15mm;
            min-height: 250mm;
        }
        
        .header-section {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 11pt;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            background: #000;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .invoice-meta {
            display: table;
            width: 100%;
            margin: 20px 0;
            border: 1px solid #000;
        }
        
        .meta-row {
            display: table-row;
        }
        
        .meta-cell {
            display: table-cell;
            padding: 8px;
            border-right: 1px solid #000;
            vertical-align: top;
            width: 25%;
        }
        
        .meta-cell:last-child {
            border-right: none;
        }
        
        .meta-label {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
        }
        
        .meta-value {
            margin-top: 3px;
            font-size: 11pt;
        }
        
        .billing-section {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .billing-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .billing-column:last-child {
            padding-right: 0;
            padding-left: 20px;
            border-left: 1px solid #000;
        }
        
        .billing-header {
            background: #000;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11pt;
            margin-bottom: 10px;
        }
        
        .billing-content {
            padding: 0 10px;
            font-size: 11pt;
            line-height: 1.6;
        }
        
        .items-section {
            margin: 30px 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        
        .items-table th {
            background: #000;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        
        .items-table .description {
            width: 50%;
        }
        
        .items-table .qty,
        .items-table .rate,
        .items-table .amount {
            width: 16.66%;
            text-align: right;
        }
        
        .items-table .qty {
            text-align: center;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            border: 2px solid #000;
            margin-top: 20px;
        }
        
        .total-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }
        
        .total-row:last-child {
            border-bottom: none;
            background: #000;
            color: white;
            font-weight: bold;
        }
        
        .total-label,
        .total-value {
            display: table-cell;
            padding: 8px 12px;
            font-size: 11pt;
        }
        
        .total-label {
            text-align: left;
            border-right: 1px solid #000;
            width: 60%;
        }
        
        .total-value {
            text-align: right;
            width: 40%;
        }
        
        .footer-section {
            clear: both;
            margin-top: 40px;
            border-top: 2px solid #000;
            padding-top: 15px;
        }
        
        .payment-info {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .payment-header {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-size: 12pt;
        }
        
        .payment-details {
            font-size: 10pt;
            line-height: 1.6;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
            border: 1px solid #000;
        }
        
        .signature-box:first-child {
            border-right: none;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 20px auto 10px;
            height: 40px;
        }
        
        .signature-label {
            font-size: 10pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .invoice-container {
                border: none;
                padding: 0;
                min-height: auto;
            }
        }
        
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
                font-size: 10pt;
            }
            
            .billing-section {
                display: block;
            }
            
            .billing-column {
                display: block;
                width: 100%;
                margin-bottom: 20px;
                padding: 0;
                border: 1px solid #000;
            }
            
            .billing-column:last-child {
                border-left: 1px solid #000;
                padding-left: 0;
            }
            
            .totals-section {
                float: none;
                width: 100%;
            }
            
            .signature-section {
                display: block;
            }
            
            .signature-box {
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="company-info">
                <div class="company-name">ABC CORPORATION</div>
                <div class="company-details">
                    123 Business Street, Suite 100<br>
                    City, State 12345 | Phone: (555) 123-4567<br>
                    Email: info@abccorp.com | Tax ID: 12-3456789
                </div>
            </div>
            <div class="invoice-title">INVOICE</div>
        </div>
        
        <!-- Invoice Meta Information -->
        <div class="invoice-meta">
            <div class="meta-row">
                <div class="meta-cell">
                    <div class="meta-label">Invoice Number</div>
                    <div class="meta-value">INV-2024-0001</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Invoice Date</div>
                    <div class="meta-value">August 01, 2025</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Due Date</div>
                    <div class="meta-value">August 31, 2025</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Terms</div>
                    <div class="meta-value">Net 30 Days</div>
                </div>
            </div>
        </div>
        
        <!-- Billing Information -->
        <div class="billing-section">
            <div class="billing-column">
                <div class="billing-header">Bill To</div>
                <div class="billing-content">
                    <strong>CLIENT COMPANY NAME</strong><br>
                    Attn: John Smith<br>
                    456 Client Avenue<br>
                    Client City, State 67890<br>
                    Phone: (555) 987-6543<br>
                    Email: john.smith@clientco.com
                </div>
            </div>
            <div class="billing-column">
                <div class="billing-header">Ship To</div>
                <div class="billing-content">
                    <strong>SAME AS BILLING ADDRESS</strong><br>
                    456 Client Avenue<br>
                    Client City, State 67890<br>
                    <br>
                    <strong>P.O. Number:</strong> PO-2024-456<br>
                    <strong>Sales Rep:</strong> Jane Doe
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <div class="items-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="description">Description</th>
                        <th class="qty">Qty</th>
                        <th class="rate">Rate</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Professional Consulting Services - Project Phase 1<br>
                            <small>Analysis and planning for system implementation</small>
                        </td>
                        <td style="text-align: center;">40.00</td>
                        <td style="text-align: right;">$125.00</td>
                        <td style="text-align: right;">$5,000.00</td>
                    </tr>
                    <tr>
                        <td>Software Development Services<br>
                            <small>Custom application development and testing</small>
                        </td>
                        <td style="text-align: center;">80.00</td>
                        <td style="text-align: right;">$95.00</td>
                        <td style="text-align: right;">$7,600.00</td>
                    </tr>
                    <tr>
                        <td>Training and Documentation<br>
                            <small>User training sessions and system documentation</small>
                        </td>
                        <td style="text-align: center;">16.00</td>
                        <td style="text-align: right;">$85.00</td>
                        <td style="text-align: right;">$1,360.00</td>
                    </tr>
                    <tr>
                        <td>Hardware Setup and Configuration<br>
                            <small>Server setup and network configuration</small>
                        </td>
                        <td style="text-align: center;">24.00</td>
                        <td style="text-align: right;">$75.00</td>
                        <td style="text-align: right;">$1,800.00</td>
                    </tr>
                    <tr>
                        <td>Project Management and Coordination<br>
                            <small>Overall project oversight and client communication</small>
                        </td>
                        <td style="text-align: center;">32.00</td>
                        <td style="text-align: right;">$110.00</td>
                        <td style="text-align: right;">$3,520.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Totals Section -->
        <div class="totals-section">
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">$19,280.00</div>
            </div>
            <div class="total-row">
                <div class="total-label">Discount (5%):</div>
                <div class="total-value">-$964.00</div>
            </div>
            <div class="total-row">
                <div class="total-label">Tax (8.25%):</div>
                <div class="total-value">$1,501.07</div>
            </div>
            <div class="total-row">
                <div class="total-label">Shipping:</div>
                <div class="total-value">$0.00</div>
            </div>
            <div class="total-row">
                <div class="total-label">TOTAL AMOUNT DUE:</div>
                <div class="total-value">$19,817.07</div>
            </div>
        </div>
        
        <!-- Footer Section -->
        <div class="footer-section">
            <div class="payment-info">
                <div class="payment-header">Payment Instructions</div>
                <div class="payment-details">
                    <strong>Remit Payment To:</strong><br>
                    ABC Corporation<br>
                    123 Business Street, Suite 100<br>
                    City, State 12345<br><br>
                    
                    <strong>Wire Transfer Information:</strong><br>
                    Bank Name: First National Bank<br>
                    Account Number: 1234567890<br>
                    Routing Number: 021000021<br>
                    Swift Code: FNBKUS33<br><br>
                    
                    <strong>Terms:</strong> Payment is due within 30 days of invoice date. A 1.5% monthly service charge will be applied to past due accounts. Please reference invoice number with payment.
                </div>
            </div>
            
            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signature</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Date</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>