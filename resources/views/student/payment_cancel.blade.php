@extends('layouts.app')

@section('title', 'Payment Cancelled')

@section('content')
<div class="container mt-5 text-center">
    <h1>Payment Cancelled</h1>
    <p>Your payment was cancelled. You can try again later.</p>
    <a href="{{ route('student.enrollments') }}" class="btn btn-primary">Go to My Enrollments</a>
</div>
@endsection
