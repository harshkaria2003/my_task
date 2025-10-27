@component('mail::message')
# Hello {{ $student->name }},

Thank you for enrolling in **{{ $course->title }}**.

@if($payment_completed)
Your payment has been successfully received.

**Transaction ID:** {{ $transaction_id ?? 'N/A' }}  
**Amount Paid:** ${{ number_format($amount ?? 0, 2) }}
@else
Your enrollment is pending payment.  
Please complete your payment to access the course.
@endif

@component('mail::button', ['url' => config('app.url')])
Go to Platform
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
