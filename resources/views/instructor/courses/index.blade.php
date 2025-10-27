@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex flex-column flex-md-row gap-2 align-items-start align-items-md-center">
        <h2 class="mb-0 fw-bold">My Courses</h2>
        
    </div>
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle fs-5"></i> Create New Course
    </a>
</div>


    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($courses->count())
        <div class="card shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive rounded-bottom-4">
                    <table class="table table-hover table-striped mb-0">
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
                                    <td class="fw-semibold">{{ $course->title }}</td>
                                    <td class="text-end">${{ number_format($course->price, 2) }}</td>
                                    <td class="text-center">{{ $course->enrollments()->count() }}</td>
                                    <td class="text-center d-flex justify-content-center gap-2">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 px-3">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        {{-- View Enrollments Button --}}
                                        <a href="{{ route('instructor.courses.enrolled_students', $course) }}" class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1 px-3">
                                            <i class="bi bi-people-fill"></i> Enrollments
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 px-3">
                                                <i class="bi bi-trash-fill"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-center rounded-bottom-4">
                {{ $courses->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center rounded-3 py-4 fs-5">
            You have not created any courses yet.
        </div>
    @endif


   
</div>


 <a href="{{ route('instructor.dashboard') }}" 
   class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1 px-3">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
@endsection
