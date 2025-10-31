@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="container-fluid py-4 px-3 px-md-5">

    {{-- ===== Header Section ===== --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold text-primary mb-0">My Courses</h2>
        <a href="{{ route('instructor.courses.create') }}" 
           class="btn btn-success d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle fs-5"></i> Create New Course
        </a>
    </div>

    {{-- ===== Flash Messages ===== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ===== Courses Table ===== --}}
    @if($courses->count())
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive rounded-bottom-4">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark text-uppercase small">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col" class="text-end">Price</th>
                                <th scope="col" class="text-center">Enrollments</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    {{-- Course Title --}}
                                    <td class="fw-semibold">
                                        <div class="text-truncate" style="max-width: 220px;">
                                            {{ $course->title }}
                                        </div>
                                    </td>

                                    {{-- Price --}}
                                    <td class="text-end text-success fw-semibold">
                                        ${{ number_format($course->price, 2) }}
                                    </td>

                                    {{-- Enrollments Count --}}
                                    <td class="text-center fw-semibold">
                                        {{ $course->enrollments()->count() }}
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td class="text-center">
                                        <div class="d-flex flex-wrap justify-content-center gap-2">
                                            
                                            {{-- Edit --}}
                                            <a href="{{ route('instructor.courses.edit', $course) }}" 
                                               class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>

                                            {{-- Enrollments --}}
                                            <a href="{{ route('instructor.courses.enrolled_students', $course) }}" 
                                               class="btn btn-sm btn-info text-white d-flex align-items-center gap-1 px-3">
                                                <i class="bi bi-people-fill"></i> Enrollments
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('instructor.courses.destroy', $course) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger d-flex align-items-center gap-1 px-3">
                                                    <i class="bi bi-trash-fill"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-light d-flex justify-content-center rounded-bottom-4 py-3">
                {{ $courses->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="alert alert-info text-center rounded-3 py-5 shadow-sm fs-5">
            <i class="bi bi-info-circle-fill me-2"></i>
            You have not created any courses yet.
        </div>
    @endif

    {{-- ===== Back to Dashboard ===== --}}
    <div class="text-center mt-4">
        <a hre
