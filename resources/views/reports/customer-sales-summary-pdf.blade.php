<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Sales Summary Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; line-height: 1.35; color: #333; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 22px; color: #2c3e50; }
        .header p { margin: 5px 0; font-size: 13px; }
        .summary-cards { display: table; width: 100%; margin-bottom: 24px; }
        .summary-card { display: table-cell; width: 25%; padding: 12px; border: 1px solid #ddd; text-align: center; background: #f8f9fa; }
        .summary-card h3 { margin: 0 0 5px 0; font-size: 16px; color: #2c3e50; }
        .summary-card p { margin: 0; font-size: 12px; color: #666; }
        .filters { margin-bottom: 18px; padding: 10px; background: #f8f9fa; border: 1px solid #ddd; }
        .filters h4 { margin: 0 0 10px 0; font-size: 13px; }
        .filters p { margin: 2px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f8f9fa; font-weight: bold; font-size: 9px; }
        td { font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 24px; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Customer Sales Summary Report</h1>
        <p>Generated on: {{ $generated_at }}</p>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $summary['total_customers'] }}</h3>
            <p>Total Customers</p>
        </div>
        <div class="summary-card">
            <h3>Rs. {{ number_format($summary['total_revenue'], 2) }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="summary-card">
            <h3>Rs. {{ number_format($summary['total_pending_payments'], 2) }}</h3>
            <p>Pending Payments</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['top_customer'] ?? 'N/A' }}</h3>
            <p>Top Customer</p>
        </div>
    </div>

    @if($filters['from_date'] || $filters['to_date'] || $filters['customer_id'] || $filters['region'] || $filters['payment_status'] || $filters['customer_type'])
        <div class="filters">
            <h4>Applied Filters:</h4>
            @if($filters['from_date']) <p><strong>From Invoice Date:</strong> {{ $filters['from_date'] }}</p> @endif
            @if($filters['to_date']) <p><strong>To Invoice Date:</strong> {{ $filters['to_date'] }}</p> @endif
            @if($filters['customer_id']) <p><strong>Customer:</strong> {{ is_array($filters['customer_id']) ? implode(', ', $filters['customer_id']) : $filters['customer_id'] }}</p> @endif
            @if($filters['region']) <p><strong>Region:</strong> {{ is_array($filters['region']) ? implode(', ', $filters['region']) : $filters['region'] }}</p> @endif
            @if($filters['payment_status']) <p><strong>Payment Status:</strong> {{ is_array($filters['payment_status']) ? implode(', ', $filters['payment_status']) : ucfirst($filters['payment_status']) }}</p> @endif
            @if($filters['customer_type']) <p><strong>Customer Type:</strong> {{ is_array($filters['customer_type']) ? implode(', ', $filters['customer_type']) : $filters['customer_type'] }}</p> @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Sales Order No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
                <th>Customer Group</th>
                <th>PO No</th>
                <th>Appointment Date</th>
                <th class="text-right">Taxable Amount</th>
                <th class="text-right">Total</th>
                <th>Status</th>
                <th class="text-right">Amount Paid</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice['sales_order_no'] ?? 'N/A' }}</td>
                    <td>{{ $invoice['invoice_no'] ?? 'N/A' }}</td>
                    <td>{{ $invoice['invoice_date'] ?? 'N/A' }}</td>
                    <td>{{ $invoice['customer_name'] ?? 'N/A' }}</td>
                    <td>{{ $invoice['customer_group_name'] ?? 'N/A' }}</td>
                    <td>{{ $invoice['po_no'] ?? 'N/A' }}</td>
                    <td>{{ $invoice['appointment_date'] ?? 'N/A' }}</td>
                    <td class="text-right">Rs. {{ number_format($invoice['amount'] ?? 0, 2) }}</td>
                    <td class="text-right"><strong>Rs. {{ number_format($invoice['total'] ?? 0, 2) }}</strong></td>
                    <td>{{ $invoice['status'] ?? 'N/A' }}</td>
                    <td class="text-right">Rs. {{ number_format($invoice['amount_paid'] ?? 0, 2) }}</td>
                    <td class="text-right">Rs. {{ number_format($invoice['balance'] ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No customer sales records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Report generated by {{ auth()->user()->name ?? 'System' }} on {{ now()->format('d-m-Y H:i:s') }}</p>
        <p>This is a computer-generated report and does not require signature.</p>
    </div>
</body>
</html>
