@extends('admin.layouts.app')

@section('title', 'Create Page')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 1000px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.cms.pages.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Create Custom Page</h2>
                <p class="text-secondary mb-0">Build a new static or dynamic page for your website.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.cms.pages.store') }}" method="POST" class="d-flex flex-column gap-4">
                @csrf

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title') }}" placeholder="Page Title" required>
                            <label for="title">Page Title <span class="text-danger">*</span></label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('slug') is-invalid @enderror"
                                id="slug" name="slug" value="{{ old('slug') }}" placeholder="url-slug" required>
                            <label for="slug">URL Slug <span class="text-danger">*</span></label>
                            <div class="form-text fs-7">e.g., terms-of-service, privacy-policy</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('template') is-invalid @enderror"
                                id="template" name="template" required>
                                <option value="default" {{ old('template') == 'default' ? 'selected' : '' }}>Default
                                    Template</option>
                                <option value="full-width" {{ old('template') == 'full-width' ? 'selected' : '' }}>Full
                                    Width Template</option>
                                <option value="landing" {{ old('template') == 'landing' ? 'selected' : '' }}>Landing Style
                                    Template</option>
                            </select>
                            <label for="template">Page Template</label>
                            @error('template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('is_published') is-invalid @enderror"
                                id="is_published" name="is_published" required>
                                <option value="1" {{ old('is_published', '1') == '1' ? 'selected' : '' }}>Published
                                </option>
                                <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>Draft</option>
                            </select>
                            <label for="is_published">Publish Status</label>
                            @error('is_published')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Basic Content Editor placeholder (Summernote / TinyMCE / Quill) -->
                    <div class="col-12">
                        <label class="text-secondary fs-7 mb-2 d-block">Page Content <span
                                class="text-danger">*</span></label>
                        <div class="border rounded-3 p-2 bg-light">
                            <!-- Toolbar mock -->
                            <div class="border-bottom pb-2 mb-2 d-flex gap-2 text-secondary">
                                <i class="bi bi-type-bold px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-type-italic px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-type-underline px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <div class="vr"></div>
                                <i class="bi bi-text-left px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-text-center px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-text-right px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <div class="vr"></div>
                                <i class="bi bi-link-45deg px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                <i class="bi bi-image px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                            </div>
                            <textarea class="form-control border-0 shadow-none bg-transparent @error('content') is-invalid @enderror" id="content"
                                name="content" placeholder="Write your page content here..." style="min-height: 400px; resize: vertical;" required>{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SEO Settings -->
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-search me-2 text-primary"></i> SEO Settings (Optional)</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                        id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                        placeholder="Meta Title">
                                    <label for="meta_title">Meta Title</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control rounded-3 shadow-none border bg-transparent" id="meta_description" name="meta_description"
                                        placeholder="Meta Description" style="height: 100px">{{ old('meta_description') }}</textarea>
                                    <label for="meta_description">Meta Description</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2 mt-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.cms.pages.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Create Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function() {
                    // Basic slugification if slug is empty or user is typing title for the first time
                    if (!slugInput.value || slugInput.dataset.autoSlug === 'true') {
                        let slug = this.value.toLowerCase()
                            .replace(/[^\w\s-]/g, '') // Remove non-word chars
                            .replace(/[\s_-]+/g, '-') // Swap spaces and underscores for hyphens
                            .replace(/^-+|-+$/g, ''); // Trim hyphens

                        slugInput.value = slug;
                        slugInput.dataset.autoSlug = 'true';
                    }
                });

                slugInput.addEventListener('input', function() {
                    this.dataset.autoSlug = 'false'; // User manually edited slug
                });
            }
        });
    </script>
@endsection
