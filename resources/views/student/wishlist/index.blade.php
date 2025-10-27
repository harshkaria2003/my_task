@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 fw-bold">My Wishlist</h2>


<div class="mt-4 d-flex justify-content-start">
    <a href="{{ route('student.courses.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left-circle me-2"></i> Back to All Courses
    </a>
</div>


    @if($courses->count())
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($courses as $course)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4">
                        <img 
                            src="{{ $course->image ? asset('storage/courses/' . $course->image) : 'https://via.placeholder.com/400x200?text=No+Image' }}" 
                            class="card-img-top rounded-top-4" 
                            alt="{{ $course->title }}"
                            style="height: 150px; width: 350px; object-fit:cover;"
                        >

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="{{ $course->title }}">{{ $course->title }}</h5>
                            <p class="card-text text-muted flex-grow-1" style="min-height: 180px;">
                                {{ \Illuminate\Support\Str::limit($course->description, 100) }}
                            </p>
                            <p class="mb-3 fw-semibold text-success fs-5">
                                ${{ number_format($course->price, 2) }}
                            </p>
                            
                            @if(in_array($course->id, $enrolledCourseIds))
                         <span class="badge bg-success mb-2">Already Enrolled</span>
                              @endif   


                            <button class="btn btn-light wishlist-btn mb-3" data-id="{{ $course->id }}">
                                <i class="bi bi-heart-fill text-danger"></i>
                            </button>

                            <a href="{{ route('student.courses.show', $course) }}" 
                               class="btn btn-primary mt-auto d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $courses->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center rounded-3 py-4 fs-5">
            Your wishlist is empty.
        </div>
    @endif
</div>




<script>
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const courseId = this.getAttribute('data-id');
            const icon = this.querySelector('i');

            fetch(`/student/wishlist/${courseId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'removed') {
               
                    this.closest('.col').remove();
                }
            });
        });
    });
</script>

@endsection
