<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            display: inline-block;
        }
        .receipt-details {
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
        }
        .receipt-details p {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Payment Receipt</h2>
    </div>

    <div class="receipt-details">
        <p><span class="label">Course:</span> {{ $enrollment->course->title }}</p>
        <p><span class="label">Student:</span> {{ $enrollment->student->name }}</p>
        <p><span class="label">Amount Paid:</span> ${{ number_format($payment->amount, 2) }}</p>
        <p><span class="label">Payment Status:</span> {{ ucfirst($payment->status) }}</p>
        <p><span class="label">Transaction ID:</span> {{ $payment->transaction_id }}</p>
        <p><span class="label">Paid At:</span> {{ $payment->paid_at->format('d M Y, h:i A') }}</p>
    </div>

    <div class="footer">
        Thank you for your payment.
    </div>

</body>
</html>
