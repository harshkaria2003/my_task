@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-journal-plus me-3 fs-4"></i> Create New Course
                    </h4>
                </div>

                <div class="card-body p-5">
                    <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold fs-6">Course Title</label>
                            <input type="text" name="title" id="title" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="e.g. Mastering Laravel 10" 
                                   required
                                   autofocus>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold fs-6">Course Description</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                      placeholder="Write a brief overview of your course..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="price" class="form-label fw-semibold fs-6">Price (USD)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">$</span>
                                <input type="number" name="price" id="price" 
                                       class="form-control @error('price') is-invalid @enderror border-start-0" 
                                       step="0.01" min="0" 
                                       value="{{ old('price') }}" 
                                       placeholder="99.99" 
                                       required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


   <div class="mb-4">
    <label for="image" class="form-label fw-semibold fs-6">Course Image</label>
    <input type="file"
           class="form-control @error('image') is-invalid @enderror"
           id="image"
           name="image"
           accept="image/*">
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


<div class="mb-4" id="preview-container" style="display: none;">
    <img id="preview-image" src="#" style="max-height: 200px;" class="img-thumbnail">
</div>

<script>
    var input = document.getElementById('image');
    var previewContainer = document.getElementById('preview-container');
    var previewImage = document.getElementById('preview-image');

    input.addEventListener('change', function () {
        var file = input.files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function () {
                previewImage.src = reader.result;
                previewContainer.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            previewImage.src = '#';
            previewContainer.style.display = 'none';
        }
    });
</script>



                        <div class="d-flex justify-content-between">
                            <a href="{{ route('instructor.courses.index') }}" 
                               class="btn btn-outline-secondary btn-lg d-flex align-items-center gap-2 px-4">
                                <i class="bi bi-arrow-left-circle fs-5"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg d-flex align-items-center gap-2 px-4">
                                <i class="bi bi-plus-circle fs-5"></i> Create Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
