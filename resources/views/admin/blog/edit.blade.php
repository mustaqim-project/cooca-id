@extends('layouts.admin')
@section('title', 'Edit Post')
@section('subtitle', 'Update article content')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.blog.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Posts
        </a>
    </div>

    <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" id="blogForm">
        @csrf
        @method('PUT')
        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title">Article Content</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label" for="title">Title <span class="text-danger">*</span></label>
                            <input class="form-saas-input @error('title') is-invalid @enderror" type="text"
                                name="title" id="title" value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="excerpt">Excerpt</label>
                            <textarea class="form-saas-textarea @error('excerpt') is-invalid @enderror" name="excerpt" id="excerpt"
                                rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group mb-0">
                            <label class="form-saas-label" for="content">Content <span class="text-danger">*</span></label>
                            <textarea class="form-saas-textarea @error('content') is-invalid @enderror" name="content" id="content" rows="16"
                                required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title">SEO & Metadata</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label" for="slug">Slug</label>
                            <input class="form-saas-input @error('slug') is-invalid @enderror" type="text" name="slug"
                                id="slug" value="{{ old('slug', $post->slug) }}">
                            @error('slug')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-saas-group">
                            <label class="form-saas-label" for="meta_title">Meta Title</label>
                            <input class="form-saas-input @error('meta_title') is-invalid @enderror" type="text"
                                name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}">
                            @error('meta_title')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-saas-group mb-0">
                            <label class="form-saas-label" for="meta_description">Meta Description</label>
                            <textarea class="form-saas-textarea @error('meta_description') is-invalid @enderror" name="meta_description"
                                id="meta_description" rows="3">{{ old('meta_description', $post->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Publish --}}
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title">Publish</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="d-flex flex-column gap-3">
                            <div>
                                <label class="form-saas-label mb-2">Status</label>
                                <div class="d-flex flex-column gap-2">
                                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.875rem">
                                        <input type="radio" name="is_published" value="0"
                                            {{ old('is_published', $post->is_published ? '1' : '0') == '0' ? 'checked' : '' }}>
                                        <span>Draft</span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.875rem">
                                        <input type="radio" name="is_published" value="1"
                                            {{ old('is_published', $post->is_published ? '1' : '0') == '1' ? 'checked' : '' }}>
                                        <span>Published</span>
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-2"
                                style="background:var(--surface-raised)">
                                <div>
                                    <div class="fw-semibold" style="font-size:.875rem">Featured</div>
                                    <div style="font-size:.8rem;color:var(--text-muted)">Show on homepage</div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                        value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-saas-footer">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-saas btn-saas-primary flex-fill">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.blog.show', $post) }}" class="btn-saas btn-saas-ghost">View</a>
                        </div>
                    </div>
                </div>

                {{-- Category & Tags --}}
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title">Category & Tags</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label" for="category">Category</label>
                            <select class="form-saas-select @error('category') is-invalid @enderror" name="category"
                                id="category">
                                <option value="">— Select category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}"
                                        {{ old('category', $post->category) == $cat ? 'selected' : '' }}>
                                        {{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-saas-group mb-0">
                            <label class="form-saas-label" for="tags">Tags</label>
                            @php
                                $currentTags = old(
                                    'tags',
                                    is_string($post->tags) ? json_decode($post->tags, true) : (array) $post->tags ?? [],
                                );
                            @endphp
                            <select class="form-saas-select @error('tags') is-invalid @enderror" name="tags[]"
                                id="tags" multiple size="5">
                                @foreach ($categories as $tag)
                                    <option value="{{ $tag }}"
                                        {{ in_array($tag, $currentTags) ? 'selected' : '' }}>{{ $tag }}</option>
                                @endforeach
                            </select>
                            <div class="form-saas-hint">Hold Ctrl/Cmd to select multiple</div>
                            @error('tags')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title">Featured Image</h5>
                    </div>
                    <div class="card-saas-body">
                        <div id="imagePreview" class="mb-3 {{ $post->featured_image ? '' : 'd-none' }}">
                            <img id="previewImg" src="{{ old('featured_image', $post->featured_image) }}" alt="Preview"
                                style="width:100%;border-radius:8px;object-fit:cover;max-height:160px">
                        </div>
                        <div class="form-saas-group mb-0">
                            <label class="form-saas-label" for="featured_image">Image URL</label>
                            <input class="form-saas-input @error('featured_image') is-invalid @enderror" type="url"
                                name="featured_image" id="featured_image"
                                value="{{ old('featured_image', $post->featured_image) }}" placeholder="https://...">
                            @error('featured_image')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('featured_image').addEventListener('input', function() {
            const url = this.value.trim();
            const preview = document.getElementById('imagePreview');
            const img = document.getElementById('previewImg');
            if (url) {
                img.src = url;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
    </script>
@endpush
