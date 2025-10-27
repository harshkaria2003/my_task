@extends('layouts.app')

@section('title', 'Enrolled Students')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h5 class="mb-0 fw-semibold">
                Students Enrolled in <span class="fst-italic">"{{ $course->title }}"</span>
            </h5>
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1 px-3">
                <i class="bi bi-arrow-left fs-5"></i> Back to Courses
            </a>
        </div>

        <div class="card-body">
            @if($enrollments->count())
                <div class="table-responsive rounded-3">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light text-uppercase small">
                            <tr>
                                <th scope="col">Student Name</th>
                                <th scope="col">Email</th>
                                <th scope="col" class="text-center">Payment Completed</th>
                                <th scope="col">Payment Gateway</th>
                                <th scope="col">Transaction ID</th>
                                <th scope="col" class="text-end">Amount</th>
                                <th scope="col" class="text-center">Payment Status</th>
                                <th scope="col">Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr>
                                <td class="fw-semibold">{{ $enrollment->student_name }}</td>
                                <td class="text-muted">{{ $enrollment->student_email }}</td>
                                <td class="text-center">
                                    @if($enrollment->payment_completed)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->payment_gateway ?? '-' }}</td>
                                <td>{{ $enrollment->transaction_id ?? '-' }}</td>
                                <td class="text-end">${{ number_format($enrollment->amount ?? 0, 2) }}</td>
                                <td class="text-center">
                                    @php
                                        $status = strtolower($enrollment->payment_status);
                                        $badgeColor = match($status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ ucfirst($status) ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $enrollment->payment_date 
                                        ? \Carbon\Carbon::parse($enrollment->payment_date)->format('Y-m-d H:i') 
                                        : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    {{ $enrollments->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info text-center mb-0 fs-5 py-4">
                    No students have enrolled in this course yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
