<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        /* Base styles */
        body {
            font-family: verdana, arial, helvetica, sans-serif;
            color: #1f2937;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Header styles */
        .header {
            width: 100%;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .header table {
            width: 100%;
        }
        .header td {
            padding: 0;
            border: none;
        }
        .invoice-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1f2937;
        }
        .invoice-number {
            color: #6b7280;
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* Status styles */
        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-block;
        }
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .status-partially-paid {
            background-color: #fdf6b2;
            color: #723b13;
            border: 1px solid #fdf6b2;
        }
        .status-unpaid {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status-completed {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Info section styles */
        .info-section {
            width: 100%;
            margin-bottom: 1rem;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 0.5rem 0;
            border: none;
            vertical-align: top;
        }
        .info-section td:first-child {
            border-right: 1px solid #e5e7eb;
            padding-right: 2rem;
        }
        .info-section td:last-child {
            padding-left: 2rem;
        }
        .label {
            font-size: 0.7rem;
            /* text-transform: uppercase; */
            color: #6b7280;
            margin-bottom: 0.25rem;
            letter-spacing: 0.05em;
        }
        .value {
            font-size: 0.8rem;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        .value:last-child {
            margin-bottom: 0;
        }
        .value-bold {
            font-weight: 600;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th {
            background-color: #f9fafb;
            text-align: left;
            padding: 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.05em;
            font-weight: 500;
        }
        td {
            padding: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.8rem;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* Column widths */
        .col-5 {
            width: 5%;
        }
        .col-45 {
            width: 45%;
        }
        .col-15 {
            width: 15%;
        }
        .col-20 {
            width: 20%;
        }

        /* Summary styles */
        .summary {
            width: 50%;
            margin-left: auto;
            background-color: white;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.25rem;
        }
        .summary-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            padding: 0.5rem 0;
            border: none;
            font-size: 0.8rem;
            color: #4b5563;
        }
        .summary td:first-child {
            text-align: left;
            color: #6b7280;
        }
        .summary td:last-child {
            text-align: right;
            font-weight: 500;
        }
        .summary-row-indent td:first-child {
            padding-left: 1rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .summary-row-indent td:last-child {
            color: #059669;
        }
        .summary-total td {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e5e7eb;
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9rem;
        }
        .summary-discount {
            color: #dc2626;
        }
        .summary-discount-row td {
            color: #dc2626;
        }

        /* Notes styles */
        .notes {
            background-color: #f9fafb;
            padding: 0.75rem;
            border-radius: 0.25rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .notes-title {
            font-size: 1rem;
            text-transform: capitalize;
            color: #6b7280;
            margin-bottom: 0.25rem;
            letter-spacing: 0.05em;
        }
        .notes-content {
            font-size: 0.8rem;
            color: #374151;
        }
        .payment {
            font-size: 0.8rem;
            color: #374151;
            padding-top: 1.5rem;
        }
        .payment-title {
            font-size: 1rem;
            text-transform: capitalize;
            color: #1f2937;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }
        .payment-status {
            background-color: transparent;
            border: none;
            font-weight: 600;
            letter-spacing: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Invoice Header -->
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="invoice-title">INVOICE</div>
                        <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                    </td>
                    <td style="text-align: right;">
                        <span class="status status-{{ strtolower(str_replace(' ', '-', $invoice->status)) }}">
                            {{ ucfirst(Str::replace('_', ' ', $invoice->status)) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- From/To Section -->
        <div class="info-section">
            <table>
                <tr>
                    <td width="50%">
                        <div class="label">Billed From</div>
                        <div class="value value-bold">{{ gym()->name }}</div>
                        <div class="value">{{ gym()->address ?? 'N/A' }}</div>
                        <div class="label">Issued On</div>
                        <div class="value">{{ $invoice->invoice_date->format('d M, Y') }}</div>
                    </td>
                    <td width="50%" style="text-align: right;">
                        <div class="label">Billed To</div>
                        <div class="value value-bold">{{ $invoice->user->name }}</div>
                        <div class="value">{{ $invoice->user->address }}</div>
                        <div class="label">Due On</div>
                        <div class="value">{{ $invoice->due_date->format('d M, Y') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Invoice Items Table -->
        <table>
            <thead>
                <tr>
                    <th class="col-5">#</th>
                    <th class="col-45">Product</th>
                    <th class="col-15 text-center">Quantity</th>
                    <th class="col-15 text-right">Unit Cost</th>
                    <th class="col-20 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->name }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">{{ number_format($detail->unit_price, 2) }} {{ currency()->code }}</td>
                    <td class="text-right">{{ number_format($detail->amount, 2) }}  {{ currency()->code }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Invoice Summary -->
        <div class="summary">
            <div class="summary-title">Invoice Summary</div>
            
            <table>
                <!-- Subtotal -->
                <tr>
                    <td>Subtotal</td>
                        <td>{{ number_format($invoice->sub_total, 2) }} {{ currency()->code }}</td>
                </tr>

                <!-- Tax Summary -->
                <tr>
                    <td>Tax Summary</td>
                    <td>{{ number_format(collect($taxSummary)->sum('amount'), 2) }} {{ currency()->code }}</td>
                </tr>
                @foreach($taxSummary as $tax)
                <tr class="summary-row-indent">
                    <td>{{ $tax['name'] }} ({{ $tax['rate'] }}%)</td>
                    <td>{{ number_format($tax['amount'], 2) }} {{ currency()->code }}</td>
                </tr>
                @endforeach

                <!-- Discount -->
                @if($invoice->discount_amount > 0)
                <tr class="summary-discount-row">
                    <td>Discount {{ $invoice->discount_type === '%' ? "({$invoice->discount_value}%)" : '' }}</td>
                    <td>-{{ number_format($invoice->discount_amount, 2) }} {{ currency()->code }}</td>
                </tr>
                @endif

                <!-- Total -->
                <tr class="summary-total">
                    <td>Total Amount</td>
                    <td>{{ number_format($invoice->total_amount, 2) }} {{ currency()->code }}</td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $invoice->notes }}</div>
        </div>
        @endif

        <!-- Payments Section -->
        @if($invoice->payments->count() > 0)
        <div class="payment">
            <div class="payment-title">Linked Payments</div>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ number_format($payment->amount_paid, 2) }} {{ currency()->code }}</td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ ucfirst(Str::replace('_', ' ', $payment->payment_mode)) }}</td>
                        <td>
                            <span class="payment-status status-{{ strtolower(str_replace(' ', '-', $payment->status)) }}">
                                {{ ucfirst(Str::replace('_', ' ', $payment->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</body>
</html> 