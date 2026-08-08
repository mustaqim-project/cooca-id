@extends('layouts.admin')

@section('title', 'Edit Category: ' . $category->name . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.blog-categories.index') }}">Blog Categories</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">✏️ Edit Category</h1>
        <p class="page-subtitle">{{ $category->name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline">← Back</a>
    </div>
</div>

<form action="{{ route('admin.blog-categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="grid-31">
    @csrf
    @method('PUT')

    {{-- ================================================================
         MAIN COLUMN
    ================================================================ --}}
    <div class="flex-col gap-5">

        {{-- BASIC INFO --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📝 Category Details</div>
                @if($category->is_active)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-warning">Inactive</span>
                @endif
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="catName" class="form-input" required
                               value="{{ old('name', $category->name) }}">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL Slug</label>
                        <input type="text" name="slug" id="catSlug" class="form-input"
                               value="{{ old('slug', $category->slug) }}">
                        <p class="form-hint">⚠️ Changing slug breaks existing links.</p>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Icon (Emoji or CSS class)</label>
                        <input type="text" name="icon" class="form-input"
                               value="{{ old('icon', $category->icon) }}" placeholder="📦">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input"
                               value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea tinymce" rows="5">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="w-4 h-4">
                        <div>
                            <span class="font-bold">Active Category</span>
                            <span class="text-xs text-muted block">Inactive categories are hidden from public listing</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- SEO SUITE --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title flex items-center gap-2">🔍 Category SEO</div>
                <span class="badge badge-success">SERP Preview Live</span>
            </div>
            <div class="card-body">

                {{-- SERP Preview --}}
                <div class="rounded-xl mb-6 p-4" style="background:var(--bg-secondary);border:1px solid var(--border);">
                    <div class="text-xs font-bold text-muted uppercase tracking-wide mb-3">📡 Google Search Preview</div>
                    <div style="font-family:Arial,sans-serif;max-width:600px;">
                        <div class="text-xs mb-1" style="color:#4d5156;">
                            https://cooca.id › blog › category › <span id="serpCatSlug">{{ $category->slug }}</span>
                        </div>
                        <div id="serpCatTitle" class="text-xl font-bold" style="color:#1a0dab;line-height:1.3;">
                            {{ old('meta_title', $category->meta_title ?? $category->name . ' - Blog COOCA.ID') }}
                        </div>
                        <div id="serpCatDesc" class="text-sm mt-1" style="color:#4d5156;line-height:1.4;">
                            {{ old('meta_description', $category->meta_description ?? 'Kumpulan artikel ' . $category->name . ' di blog COOCA.ID.') }}
                        </div>
                    </div>
                </div>

                {{-- Analytics Row --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">{{ $category->posts_count ?? 0 }}</div>
                        <div class="text-xs text-muted mt-1">Total Posts</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">{{ number_format($category->total_post_views) }}</div>
                        <div class="text-xs text-muted mt-1">Total Views</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">
                            {{ $category->is_active ? '✅' : '⛔' }}
                        </div>
                        <div class="text-xs text-muted mt-1">Status</div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Focus Keyword</label>
                        <input type="text" name="focus_keyword" id="focusKeyword" class="form-input"
                               value="{{ old('focus_keyword', $category->focus_keyword) }}" placeholder="e.g. tips bisnis umkm">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-input"
                               value="{{ old('meta_keywords', $category->meta_keywords) }}" placeholder="umkm, bisnis, tips">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title <span class="text-muted">(50–60 chars)</span></label>
                    <input type="text" name="meta_title" id="catMetaTitle" class="form-input"
                           value="{{ old('meta_title', $category->meta_title) }}" placeholder="Judul SEO kategori">
                    <div class="flex justify-between mt-1">
                        <p class="form-hint">Fallback to category name if empty.</p>
                        <span id="catMetaTitleCount" class="text-xs text-muted">{{ strlen($category->meta_title ?? '') }} / 60</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description <span class="text-muted">(120–160 chars)</span></label>
                    <textarea name="meta_description" id="catMetaDesc" class="form-textarea" rows="2"
                              placeholder="Deskripsi kategori untuk Google...">{{ old('meta_description', $category->meta_description) }}</textarea>
                    <div class="flex justify-between mt-1">
                        <span></span>
                        <span id="catMetaDescCount" class="text-xs text-muted">{{ strlen($category->meta_description ?? '') }} / 160</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Canonical URL</label>
                    <input type="url" name="canonical_url" class="form-input"
                           value="{{ old('canonical_url', $category->canonical_url) }}"
                           placeholder="https://cooca.id/blog/category/nama">
                </div>

                {{-- OG Fields --}}
                <div class="mt-4 p-4 rounded-xl" style="border:1px dashed var(--border);background:var(--bg-secondary);">
                    <div class="font-bold text-sm mb-4">📣 Open Graph — Social Media</div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">OG Title</label>
                            <input type="text" name="og_title" class="form-input"
                                   value="{{ old('og_title', $category->og_title) }}" placeholder="Social media title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">OG Description</label>
                            <input type="text" name="og_description" class="form-input"
                                   value="{{ old('og_description', $category->og_description) }}" placeholder="Social media description">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">OG Image <span class="text-muted">(1200×630)</span></label>
                        @if($category->og_image)
                            <div class="mb-2 rounded-xl overflow-hidden" style="max-height:100px;">
                                <img src="{{ Storage::url($category->og_image) }}" alt="OG Image"
                                     class="w-full object-cover" style="max-height:100px;">
                            </div>
                            <p class="form-hint mb-1">Current: <code>{{ basename($category->og_image) }}</code></p>
                        @endif
                        <input type="file" name="og_image" class="form-input" accept="image/*">
                        <p class="form-hint">Upload to replace. Max 3MB.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         SIDEBAR
    ================================================================ --}}
    <div class="flex-col gap-5">

        <div class="card">
            <div class="card-header">
                <div class="card-title">💾 Save</div>
            </div>
            <div class="card-body">
                <button type="submit" class="btn btn-primary w-full">💾 Save Changes</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline w-full mt-2">Cancel</a>
                <hr class="my-4" style="border-color:var(--border);">
                <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST"
                      onsubmit="return confirm('Delete this category? Posts will be unlinked.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn w-full" style="background:#ef4444;color:#fff;">🗑 Delete Category</button>
                </form>
            </div>
        </div>

        {{-- COVER IMAGE --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🖼️ Cover Image</div>
            </div>
            <div class="card-body">
                @if($category->cover_image)
                    <div class="mb-3 rounded-xl overflow-hidden" style="max-height:200px;">
                        <img src="{{ Storage::url($category->cover_image) }}"
                             alt="{{ $category->cover_image_alt }}"
                             class="w-full object-cover" style="max-height:200px;">
                    </div>
                    <p class="form-hint mb-2">Current: <code>{{ basename($category->cover_image) }}</code></p>
                @endif
                <div class="form-group">
                    <label class="form-label">Upload New Cover</label>
                    <input type="file" name="cover_image" class="form-input" accept="image/*"
                           onchange="previewCoverImage(event)">
                    <p class="form-hint">Max 3MB. Saved as <code>slug.ext</code> — replaces current.</p>
                </div>
                <div id="coverPreview" class="hidden mt-2">
                    <img id="coverImgTag" src="" alt="Cover Preview"
                         class="w-full rounded-xl object-cover" style="max-height:180px;">
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Cover Image Alt Text</label>
                    <input type="text" name="cover_image_alt" class="form-input"
                           value="{{ old('cover_image_alt', $category->cover_image_alt) }}"
                           placeholder="e.g. Banner kategori tips UMKM">
                </div>
            </div>
        </div>

        {{-- METADATA --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">ℹ️ Metadata</div>
            </div>
            <div class="card-body">
                <div class="text-xs text-muted space-y-2">
                    <div class="flex justify-between">
                        <span>Created</span>
                        <span class="font-semibold">{{ $category->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Last Updated</span>
                        <span class="font-semibold">{{ $category->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sort Order</span>
                        <span class="font-semibold">{{ $category->sort_order }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const catName = document.getElementById('catName');
    const catSlug = document.getElementById('catSlug');
    const catMetaTitle = document.getElementById('catMetaTitle');
    const catMetaDesc = document.getElementById('catMetaDesc');

    function updateSerp() {
        document.getElementById('serpCatSlug').textContent = catSlug.value.trim() || '{{ $category->slug }}';
        document.getElementById('serpCatTitle').textContent = catMetaTitle.value.trim() || catName.value.trim() || '{{ $category->name }} - Blog COOCA.ID';
        document.getElementById('serpCatDesc').textContent = catMetaDesc.value.trim() || 'Kumpulan artikel {{ $category->name }} di blog COOCA.ID.';
    }

    catName.addEventListener('input', updateSerp);
    catSlug.addEventListener('input', updateSerp);
    catMetaTitle.addEventListener('input', function() {
        updateSerp();
        document.getElementById('catMetaTitleCount').textContent = this.value.length + ' / 60';
        this.style.borderColor = this.value.length > 60 ? '#ef4444' : '';
    });
    catMetaDesc.addEventListener('input', function() {
        updateSerp();
        document.getElementById('catMetaDescCount').textContent = this.value.length + ' / 160';
    });

    updateSerp();
});

function previewCoverImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('coverImgTag').src = e.target.result;
        document.getElementById('coverPreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
@endsection
