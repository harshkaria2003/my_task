<!DOCTYPE html>
<html>
<head>
    <title>Payment Reminder</title>
</head>
<body>
    <h2>Hello {{ $student->name }},</h2>

    <p>This is a friendly reminder to complete your payment for the course <strong>{{ $course->title }}</strong>.</p>

    <p>
        please complete payment in 24 hours
    </p>

    <p>
        <a href="{{ $payment_link }}" style="padding: 10px 15px; background-color: #007bff; color: #fff; text-decoration: none;">
            Complete Payment Now
        </a>
    </p>

    <p>Thank you,<br/>Course App Team</p>
</body>
</html>
