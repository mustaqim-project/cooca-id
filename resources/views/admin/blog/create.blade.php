@extends('admin.layouts.app')

@section('title', 'Create Blog Post')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 1000px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.blog.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Create Blog Post</h2>
                <p class="text-secondary mb-0">Write a new article for your audience.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data"
                class="d-flex flex-column gap-4">
                @csrf

                <div class="row g-4">
                    <div class="col-12 col-md-8">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title') }}" placeholder="Article Title"
                                onkeyup="generateSlug()" required>
                            <label for="title">Article Title <span class="text-danger">*</span></label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('category') is-invalid @enderror"
                                id="category" name="category" value="{{ old('category') }}" placeholder="Category"
                                required>
                            <label for="category">Category <span class="text-danger">*</span></label>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('slug') is-invalid @enderror"
                                id="slug" name="slug" value="{{ old('slug') }}" placeholder="url-slug" required>
                            <label for="slug">URL Slug <span class="text-danger">*</span></label>
                            <div class="form-text fs-7">Auto-generated from title, can be modified.</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('is_published') is-invalid @enderror"
                                id="is_published" name="is_published" required>
                                <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>Draft</option>
                            </select>
                            <label for="is_published">Publish Status</label>
                            @error('is_published')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div class="col-12">
                        <label class="text-secondary fs-7 mb-2 d-block">Cover Image</label>
                        <div
                            class="border rounded-4 p-4 text-center border-dashed position-relative hover-bg-light transition-all cursor-pointer bg-light-subtle">
                            <input type="file"
                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" id="image"
                                name="image" accept="image/*" onchange="previewImage(this)">
                            <div id="upload-prompt">
                                <i class="bi bi-image fs-1 text-secondary mb-2"></i>
                                <h6 class="fw-medium mb-1">Click to upload image</h6>
                                <p class="fs-7 text-secondary mb-0">PNG, JPG up to 2MB</p>
                            </div>
                            <img id="image-preview" src="#" alt="Preview" class="d-none img-fluid rounded-3"
                                style="max-height: 250px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Basic Content Editor placeholder -->
                    <div class="col-12">
                        <label class="text-secondary fs-7 mb-2 d-block">Article Content <span
                                class="text-danger">*</span></label>
                        <div class="border rounded-3 p-2 bg-light">
                            <!-- Toolbar mock -->
                            <div class="border-bottom pb-2 mb-2 d-flex flex-wrap gap-2 text-secondary">
                                <i class="bi bi-type-bold px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-type-italic px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-type-underline px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <div class="vr"></div>
                                <i class="bi bi-list-ul px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-list-ol px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <div class="vr"></div>
                                <i class="bi bi-link-45deg px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-image px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-camera-video px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <div class="vr"></div>
                                <i class="bi bi-code-slash px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-quote px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                            </div>
                            <textarea class="form-control border-0 shadow-none bg-transparent @error('content') is-invalid @enderror" id="content"
                                name="content" placeholder="Write your article content here..." style="min-height: 400px; resize: vertical;"
                                required>{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="border-light my-2 mt-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Publish Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generateSlug() {
            const title = document.getElementById('title').value;
            const slug = title.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        }

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const prompt = document.getElementById('upload-prompt');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    prompt.classList.add('d-none');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
                prompt.classList.remove('d-none');
            }
        }
    </script>
@endsection
