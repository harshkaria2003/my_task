@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-warning text-dark rounded-top-4">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-pencil-square me-3 fs-4"></i> Edit Course
                    </h4>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    
    {{-- Course Title --}}
    <div class="mb-4">
        <label for="title" class="form-label fw-semibold fs-6">Course Title</label>
        <input type="text" name="title" id="title"
               class="form-control form-control-lg @error('title') is-invalid @enderror"
               value="{{ old('title', $course->title) }}" required autofocus>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

  
    <div class="mb-4">
        <label for="description" class="form-label fw-semibold fs-6">Course Description</label>
        <textarea name="description" id="description" rows="6"
                  class="form-control form-control-lg @error('description') is-invalid @enderror" required>{{ old('description', $course->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="mb-4">
        <label for="price" class="form-label fw-semibold fs-6">Price (USD)</label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0">$</span>
            <input type="number" name="price" id="price"
                   class="form-control @error('price') is-invalid @enderror border-start-0"
                   step="0.01" min="0"
                   value="{{ old('price', $course->price) }}" required>
        </div>
        @error('price')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    
<div class="mb-4">
    <label class="form-label fw-semibold fs-6">Course Image</label><br>


    <img id="image-preview"
         src="{{ $course->image ? asset('storage/courses/' . $course->image) : '' }}"
         alt="Course Image"
         style="max-height: 200px;"
         class="img-thumbnail"
         @if(!$course->image) style="display:none;" @endif
    >
</div>


<div class="mb-5">
    <input type="file" name="image" id="image"
           class="form-control form-control-lg @error('image') is-invalid @enderror">
    @error('image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>


<script>
    document.getElementById('image').addEventListener('change', function (event) {
        const preview = document.getElementById('image-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; 
            };
            reader.readAsDataURL(file);
        }
    });
</script>




    {{-- Buttons --}}
    <div class="d-flex justify-content-between">
        <a href="{{ route('instructor.courses.index') }}"
           class="btn btn-outline-secondary btn-lg d-flex align-items-center gap-2 px-4">
            <i class="bi bi-arrow-left-circle fs-5"></i> Cancel
        </a>
        <button type="submit" class="btn btn-success btn-lg d-flex align-items-center gap-2 px-4">
            <i class="bi bi-save2 fs-5"></i> Update Course
        </button>
    </div>
</form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
