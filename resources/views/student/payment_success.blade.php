@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg p-5 text-center rounded-4" style="max-width: 480px;">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;" aria-hidden="true"></i>
        </div>
        <h1 class="mb-3 fw-bold">Payment Successful!</h1>
        <p class="lead mb-4">Thank you for your payment. You are now enrolled in the course.</p>
        <a href="{{ route('student.enrollments') }}" class="btn btn-success btn-lg px-4" role="button" aria-label="Go to My Enrollments">
            <i class="bi bi-journal-check me-2"></i> Go to My Enrollments
        </a>
    </div>
</div>
@endsection
