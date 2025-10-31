@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">

                <img 
                    src="{{ $course->image ? asset('storage/courses/' . $course->image) : 'https://via.placeholder.com/900x300?text=No+Image' }}" 
                    class="card-img-top" 
                    alt="{{ $course->title }}"
                    style="height:300px; width:100%; object-fit:cover;"
                >

                <div class="card-body">
                    <h2 class="card-title mb-3">{{ $course->title }}</h2>

                    <p class="mb-2">
                        <strong><i class="bi bi-person-fill me-1"></i> Instructor:</strong>
                        {{ $course->instructor->name }}
                    </p>

                    <p class="text-muted mb-3">
                        {{ $course->description }}
                    </p>

                    <h4 class="text-primary mb-3">
                        <i class="bi bi-currency-dollar me-1"></i> {{ number_format($course->price, 2) }}
                    </h4>

                    <hr>

                    @if($enrolled)
                        <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            You are already enrolled in this course.
                        </div>

                        <a href="{{ route('student.enrollments') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> View My Enrollments
                        </a>
                    @else
                        <form action="{{ route('student.courses.enroll', $course) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-cart-plus-fill me-1"></i> Enroll Now
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('student.courses.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to All Courses
        </a>
    </div>
</div>
@endsection
