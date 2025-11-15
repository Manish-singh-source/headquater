<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Sales Summary Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            background: #f8f9fa;
        }
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #2c3e50;
        }
        .summary-card p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
        }
        .filters h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .filters p {
            margin: 2px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 3px;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Customer Sales Summary Report</h1>
        <p>Generated on: {{ $generated_at }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $summary['total_customers'] }}</h3>
            <p>Total Customers</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_revenue'], 2) }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_pending_payments'], 2) }}</h3>
            <p>Pending Payments</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['top_customer'] }}</h3>
            <p>Top Customer</p>
        </div>
    </div>

    <!-- Applied Filters -->
    @if($filters['from_date'] || $filters['to_date'] || $filters['customer_id'] || $filters['region'] || $filters['payment_status'] || $filters['customer_type'])
    <div class="filters">
        <h4>Applied Filters:</h4>
        @if($filters['from_date']) <p><strong>From Date:</strong> {{ $filters['from_date'] }}</p> @endif
        @if($filters['to_date']) <p><strong>To Date:</strong> {{ $filters['to_date'] }}</p> @endif
        @if($filters['customer_id']) <p><strong>Customer:</strong> {{ $filters['customer_id'] }}</p> @endif
        @if($filters['region']) <p><strong>Region:</strong> {{ $filters['region'] }}</p> @endif
        @if($filters['payment_status']) <p><strong>Payment Status:</strong> {{ ucfirst($filters['payment_status']) }}</p> @endif
        @if($filters['customer_type']) <p><strong>Customer Type:</strong> {{ $filters['customer_type'] }}</p> @endif
    </div>
    @endif

    <!-- Customer Sales Table -->
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Customer Group</th>
                <th class="text-right">Total Sales Amount</th>
                <th class="text-center">Total Invoices</th>
                <th class="text-center">Total Products Sold</th>
                <th class="text-center">Payment Status (P/U/T)</th>
                <th class="text-right">Outstanding Balance</th>
                <th>Date Range</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customerAggregates as $aggregate)
                <tr>
                    <td>
                        <strong>{{ $aggregate['customer']->client_name ?? 'N/A' }}</strong><br>
                        <small>{{ $aggregate['customer']->email ?? '' }}</small>
                    </td>
                    <td>{{ $aggregate['customer']->groupInfo->customerGroup->name ?? 'N/A' }}</td>
                    <td class="text-right">
                        <strong>₹{{ number_format($aggregate['total_sales_amount'], 2) }}</strong>
                    </td>
                    <td class="text-center">{{ $aggregate['total_invoices'] }}</td>
                    <td class="text-center">{{ number_format($aggregate['total_products_sold']) }}</td>
                    <td class="text-center">
                        @if($aggregate['paid_invoices'] > 0)
                            <span class="badge badge-success">{{ $aggregate['paid_invoices'] }}P</span>
                        @endif
                        @if($aggregate['unpaid_invoices'] > 0)
                            <span class="badge badge-danger">{{ $aggregate['unpaid_invoices'] }}U</span>
                        @endif
                        @if($aggregate['partial_invoices'] > 0)
                            <span class="badge badge-warning">{{ $aggregate['partial_invoices'] }}T</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <span style="color: {{ $aggregate['outstanding_balance'] > 0 ? '#dc3545' : '#28a745' }};">
                            ₹{{ number_format($aggregate['outstanding_balance'], 2) }}
                        </span>
                    </td>
                    <td>
                        @if($aggregate['date_range_start'] && $aggregate['date_range_end'])
                            {{ $aggregate['date_range_start']->format('d-m-Y') }}<br>to<br>{{ $aggregate['date_range_end']->format('d-m-Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No customer sales records found</td>
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