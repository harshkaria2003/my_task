@extends('layouts.app')

@section('title', 'Enrolled Students')

@section('content')
<div class="container-fluid py-4 px-3 px-md-5">

    {{-- ====== Page Header ====== --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-primary mb-0">
            Students Enrolled in <span class="fst-italic text-dark">"{{ $course->title }}"</span>
        </h3>
        <a href="{{ route('instructor.courses.index') }}" 
           class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Courses
        </a>
    </div>

    {{-- ====== Enrollments Card ====== --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">

            @if($enrollments->count())
                <div class="table-responsive rounded-4">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark text-uppercase small">
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th class="text-center">Payment Completed</th>
                                <th>Gateway</th>
                                <th>Transaction ID</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    {{-- Student Name --}}
                                    <td class="fw-semibold text-truncate" style="max-width: 200px;">
                                        {{ $enrollment->student_name }}
                                    </td>

                                    {{-- Email --}}
                                    <td class="text-muted text-truncate" style="max-width: 250px;">
                                        {{ $enrollment->student_email }}
                                    </td>

                                    {{-- Payment Completed --}}
                                    <td class="text-center">
                                        @if($enrollment->payment_completed)
                                            <span class="badge bg-success px-3 py-2">Yes</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">No</span>
                                        @endif
                                    </td>

                                    {{-- Payment Gateway --}}
                                    <td>{{ $enrollment->payment_gateway ?? '-' }}</td>

                                    {{-- Transaction ID --}}
                                    <td class="text-muted text-truncate" style="max-width: 180px;">
                                        {{ $enrollment->transaction_id ?? '-' }}
                                    </td>

                                    {{-- Amount --}}
                                    <td class="text-end fw-semibold text-success">
                                        ${{ number_format($enrollment->amount ?? 0, 2) }}
                                    </td>

                                    {{-- Payment Status --}}
                                    <td class="text-center">
                                        @php
                                            $status = strtolower($enrollment->payment_status ?? 'N/A');
                                            $badgeColor = match($status) {
                                                'paid' => 'success',
                                                'pending' => 'warning',
                                                'failed' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }} px-3 py-2">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>

                                    {{-- Payment Date --}}
                                    <td class="text-nowrap">
                                        {{ $enrollment->payment_date 
                                            ? \Carbon\Carbon::parse($enrollment->payment_date)->format('Y-m-d H:i') 
                                            : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="card-footer bg-light border-0 d-flex justify-content-center py-3">
                    {{ $enrollments->links('pagination::bootstrap-5') }}
                </div>
            @else
                {{-- No Enrollments Message --}}
                <div class="alert alert-info text-center rounded-3 mb-0 fs-5 py-5 shadow-sm">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    No students have enrolled in this course yet.
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
