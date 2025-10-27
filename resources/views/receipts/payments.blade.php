@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Payment Receipt</h2>

    <div class="card p-4 shadow-sm">
        <p><strong>Course:</strong> {{ $enrollment->course->title }}</p>
        <p><strong>Student:</strong> {{ $enrollment->student->name }}</p>
        <p><strong>Amount Paid:</strong> ${{ number_format($payment->amount, 2) }}</p>
        <p><strong>Payment Status:</strong> {{ ucfirst($payment->status) }}</p>
        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
        <p><strong>Paid At:</strong> {{ $payment->paid_at->format('d M Y, h:i A') }}</p>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
       
        <a href="{{ route('student.courses.index') }}" class="btn btn-secondary">
            Back to Courses
        </a>

       
 <a href="{{ secure_url(route('student.payment.receipt.pdf', $enrollment->id, false)) }}" class="btn btn-primary">
    Download PDF
</a>



    </div>
</div>
@endsection
