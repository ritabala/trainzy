<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .header-left h1 {
            font-size: 24px;
            margin: 0;
            color: #1f2937;
        }
        .header-right {
            text-align: right;
        }
        .invoice-number {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-paid { background-color: #dcfce7; color: #166534; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-unpaid { background-color: #fee2e2; color: #991b1b; }
        .status-overdue { background-color: #fee2e2; color: #991b1b; }
        .status-partially-paid { background-color: #fdf6b2; color: #723b13; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-box {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }
        .info-box h2 {
            font-size: 12px;
            text-transform: capitalize;
            color: #6b7280;
            margin: 0 0 8px 0;
        }
        .info-box h3 {
            font-size: 16px;
            margin: 0 0 4px 0;
            color: #1f2937;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #4b5563;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
        }
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .summary-section {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .summary-section h3 {
            font-size: 16px;
            color: #1f2937;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .summary-row.discount {
            color: #dc2626;
        }
        .summary-row.total {
            font-weight: 600;
            font-size: 16px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .notes-section {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .notes-section h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #6b7280;
            margin: 0 0 10px 0;
        }
        .notes-section p {
            margin: 0;
            font-size: 14px;
            color: #4b5563;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>INVOICE</h1>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
        </div>
        <div class="header-right">
            <span class="status status-{{ strtolower(str_replace(' ', '-', $invoice->status)) }}">
                {{ strtoupper($invoice->status) }}
            </span>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h2>Billed From</h2>
            <h3>{{ $invoice->user->name }}</h3>
            <p>{{ $invoice->user->address ?? 'N/A' }}</p>
            <div style="margin-top: 15px;">
                <h2>Issued On</h2>
                <p>{{ $invoice->invoice_date->format('d M, Y') }}</p>
            </div>
        </div>
        <div class="info-box" style="text-align: right;">
            <h2>Billed To</h2>
            <h3>{{ $invoice->user->name }}</h3>
            <p>{{ $invoice->user->address ?? 'N/A' }}</p>
            <div style="margin-top: 15px;">
                <h2>Due On</h2>
                <p>{{ $invoice->due_date->format('d M, Y') }}</p>
            </div>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->details as $detail)
            <tr>
                <td>{{ $detail->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ currency_format($detail->unit_price) }}</td>
                <td>{{ currency_format($detail->amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <h3>Invoice Summary</h3>
        <div class="summary-row">
            <span>Subtotal</span>
            <span>{{ currency_format($invoice->sub_total) }}</span>
        </div>
        @if($invoice->discount_amount > 0)
        <div class="summary-row discount">
            <span>Discount {{ $invoice->discount_type === '%' ? "({$invoice->discount_value}%)" : '' }}</span>
            <span>-{{ currency_format($invoice->discount_amount) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span>Total Tax</span>
            <span>{{ currency_format(collect($taxSummary)->sum('amount')) }}</span>
        </div>
        <div class="summary-row total">
            <span>Total Amount</span>
            <span>{{ currency_format($invoice->total_amount) }}</span>
        </div>
    </div>

    @if($invoice->notes)
    <div class="notes-section">
        <h3>Notes</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html> 