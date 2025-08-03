<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Codes Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .qr-item {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }
        .user-info {
            margin-top: 10px;
            font-size: 12px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>QR Codes Export</h1>
        <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
        <p>Date Range: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
    </div>

    <div class="qr-grid">
        @foreach($users as $user)
            <div class="qr-item">
                <img src="data:image/png;base64,{{ base64_encode($user->qr_code) }}" class="qr-code">
                <div class="user-info">
                    <p><strong>{{ $user->name }}</strong></p>
                    <p>{{ $user->email }}</p>
                    <p>Created: {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            @if($loop->iteration % 4 == 0 && !$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>
</html> 