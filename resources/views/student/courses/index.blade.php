@extends('layouts.app')

@section('title', 'Browse Courses')

@section('content')
<div class="container my-5">

    <h2 class="mb-4 fw-bold">Courses</h2>
    




<form method="GET" action="{{ route('student.courses.index') }}" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control"
                placeholder="Search by course or instructor"
                value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </div>
</form>





<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">

    <ul class="navbar-nav ms-auto align-items-center"> 
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-1 text-danger fw-semibold" href="{{ route('student.wishlist.index') }}">
          <i class="bi bi-heart-fill fs-5"></i> Wishlist
        </a>
      </li>
    </ul>

  </div>
</nav>




    {{-- Menu Tabs --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request()->query('filter') !== 'my' ? 'active' : '' }}" 
               href="{{ route('student.courses.index') }}">
               All Courses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->query('filter') === 'my' ? 'active' : '' }}" 
               href="{{ route('student.courses.index', ['filter' => 'my']) }}">
               My Courses
            </a>
        </li>
    </ul>

    @if($courses->count())
        <div class="row row-cols-1 row-cols-md-3 g-4">
           @foreach($courses as $course)
    <div class="col">
        <div class="card h-100 shadow-sm border-0 rounded-4">

            <img 
                src="{{ $course->image ? asset('storage/courses/' . $course->image) : 'https://via.placeholder.com/400x200?text=No+Image' }}" 
                class="card-img-top rounded-top-4" 
                alt="{{ $course->title }}"
                style="height: 200px; width: 400px; object-fit:cover;"
            >

            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-truncate" title="{{ $course->title }}">{{ $course->title }}</h5>
                <p class="card-text text-muted flex-grow-1" style="min-height: 180px;">
                    {{ \Illuminate\Support\Str::limit($course->description, 100) }}
                </p>
                <p class="mb-3 fw-semibold text-success fs-5">
                    ${{ number_format($course->price, 2) }}
                </p>

            
              @auth
    <button class="btn btn-light wishlist-btn mb-3" data-id="{{ $course->id }}">
        <i class="bi bi-heart{{ auth()->user()->wishedCourses->contains($course->id) ? '-fill text-danger' : '' }}"></i>
    </button>
@endauth


                @if(in_array($course->id, $enrolledCourseIds))
                    <a href="{{ route('student.courses.show', $course) }}" 
                       class="btn btn-success mt-auto d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-circle"></i> Enrolled
                    </a>
                @else
                    <a href="{{ route('student.courses.show', $course) }}" 
                       class="btn btn-primary mt-auto d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-eye"></i> View Details
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach

        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $courses->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center rounded-3 py-4 fs-5">
            No courses available right now.
        </div>
    @endif

    <form method="GET" action="{{ route('student.courses.index') }}" class="mb-4">
    <div class="card card-body shadow-sm mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label for="min_price" class="form-label">Min Price: ₹<span id="minPriceValue">{{ request('min_price', 0) }}</span></label>
                <input type="range" class="form-range" min="0" max="500" step="10" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
            </div>

            <div class="col-md-4">
                <label for="max_price" class="form-label">Max Price: ₹<span id="maxPriceValue">{{ request('max_price', 500) }}</span></label>
                <input type="range" class="form-range" min="0" max="500" step="10" name="max_price" id="max_price" value="{{ request('max_price', 500) }}">
            </div>

            <div class="col-md-4 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-filter-circle"></i> Apply Filter
                </button>
                <a href="{{ route('student.courses.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </div>
</form>

</div>
<script>
    const minSlider = document.getElementById('min_price');
    const maxSlider = document.getElementById('max_price');

    const minDisplay = document.getElementById('minPriceValue');
    const maxDisplay = document.getElementById('maxPriceValue');

    if (minSlider && minDisplay) {
        minSlider.addEventListener('input', () => {
            minDisplay.textContent = minSlider.value;
        });
    }

    if (maxSlider && maxDisplay) {
        maxSlider.addEventListener('input', () => {
            maxDisplay.textContent = maxSlider.value;
        });
    }
</script> 
<script>
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const courseId = this.getAttribute('data-id');
            const icon = this.querySelector('i');

            fetch(`/student/wishlist/${courseId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    icon.classList.add('bi-heart-fill', 'text-danger');
                    icon.classList.remove('bi-heart');
                } else {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart');
                }
            });
        });
    });
</script>


@endsection
