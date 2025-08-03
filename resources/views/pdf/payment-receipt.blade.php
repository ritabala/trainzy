<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .amount-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 500;
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            float: right;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            border-bottom: 1px solid #e5e7eb;
        }
        .icon-cell {
            width: 36px;
            padding: 12px;
            vertical-align: middle;
            text-align: center;
        }
        .icon-bg {
            width: 36px;
            height: 36px;
            background-color: #f3f4f6;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }
        .icon-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #6b7280;
            font-size: 16px;
            font-weight: 500;
        }
        .content-cell {
            padding: 12px 0;
            vertical-align: middle;
        }
        .info-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <table class="main-table">
        <tr>
            <td>
                <table class="header-table">
                    <tr>
                        <td style="width: 70%;">
                            <div class="amount-label">Total Amount</div>
                            <div class="amount-value">${{ number_format($payment->amount_paid, 2) }}</div>
                        </td>
                        <td style="width: 30%; text-align: right; ">
                            <span class="status-badge">{{ ucfirst(Str::replace('_', ' ', $payment->status)) }}</span>
                        </td>
                    </tr>
                </table>

                <table class="info-table">
                    <tr>
                        <td class="content-cell">
                            <div class="info-label">Client</div>
                            <div class="info-value">{{ $payment->user->name ?? '--' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="content-cell">
                            <div class="info-label">Payment Date</div>
                            <div class="info-value">{{ $payment->payment_date->format('d M, Y H:i:s') }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td class="content-cell">
                            <div class="info-label">Invoice Number</div>
                            <div class="info-value">{{ $payment->invoice->invoice_number ?? '--' }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td class="content-cell">
                            <div class="info-label">Transaction ID</div>
                            <div class="info-value">{{ $payment->transaction_no ?? 'N/A' }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td class="content-cell">
                            <div class="info-label">Payment Mode</div>
                            <div class="info-value">{{ ucfirst(Str::replace('_', ' ', $payment->payment_mode)) }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td class="content-cell">
                            <div class="info-label">Remark</div>
                            <div class="info-value">{{ $payment->remark ?? '--' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
