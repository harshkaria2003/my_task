<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment Confirmation</title>
</head>
<body>
    <h2>Hello {{ $student->name }},</h2>

    <p>Thank you for enrolling in <strong>{{ $course->title }}</strong>.</p>

    <p>
        @if($payment_completed)
            Your payment has been successfully received.<br>
            Transaction ID: {{ $transaction_id }}<br>
            Amount Paid: ${{ number_format($amount, 2) }}
        @else
            Your enrollment is pending payment. Please complete your payment to access the course.
        @endif
    </p>

    <p>Happy learning!</p>
    <p>Regards,<br/>Course App Team</p>
</body>
</html>
