@extends('layouts.app')

@section('title', 'My Enrollments')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 fw-bold">My Enrolled Courses</h2>

   





    @if($enrollments->count())
        <div class="card shadow-sm border-0 rounded-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-uppercase">Course</th>
                            <th scope="col" class="text-uppercase">Instructor</th>
                            <th scope="col" class="text-uppercase">Price</th>
                            <th scope="col" class="text-uppercase">Payment Status</th>
                            <th scope="col" class="text-center text-uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td class="fw-semibold">{{ $enrollment->course->title }}</td>
                                <td>{{ $enrollment->course->instructor->name }}</td>
                                <td class="text-success fw-semibold">${{ number_format($enrollment->course->price, 2) }}</td>
                                <td>
                                    @if($enrollment->payment_completed)
                                        <span class="badge bg-success d-flex align-items-center gap-1">
                                            <i class="bi bi-check-circle"></i> Paid
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-clock-history"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!$enrollment->payment_completed)
                                        <a href="{{ route('student.checkout', $enrollment->id) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                                            <i class="bi bi-credit-card-fill"></i> Pay Now
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-center">
                {{ $enrollments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center fs-5">
            You have no enrolled courses.
        </div>
    @endif
     @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>

    {{-- Auto-redirect after 3 seconds --}}
    <script>
        setTimeout(() => {
            window.location.href = "{{ route('student.courses.index') }}";
        }, 5000);
    </script>
@endif

<a href="{{ route('student.courses.index') }}" class="btn btn-primary mt-3">
    Go to Main Courses Page
</a>
</div>
@endsection
