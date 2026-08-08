@extends('layouts.admin')

@section('title', 'New Blog Category — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.blog-categories.index') }}">Blog Categories</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">📂 New Blog Category</h1>
        <p class="page-subtitle">Organize blog content with rich categories — full SEO, cover images, and analytics.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline">← Back</a>
    </div>
</div>

<form action="{{ route('admin.blog-categories.store') }}" method="POST" enctype="multipart/form-data" class="grid-31">
    @csrf

    {{-- ================================================================
         MAIN COLUMN
    ================================================================ --}}
    <div class="flex-col gap-5">

        {{-- BASIC INFO --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📝 Category Details</div>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="catName" class="form-input" required
                               value="{{ old('name') }}" placeholder="e.g. Tips & Strategi UMKM">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL Slug</label>
                        <input type="text" name="slug" id="catSlug" class="form-input"
                               value="{{ old('slug') }}" placeholder="Auto-generated from name">
                        <p class="form-hint">Used in URLs: /blog/category/slug</p>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Icon (Emoji or CSS class)</label>
                        <input type="text" name="icon" class="form-input"
                               value="{{ old('icon') }}" placeholder="e.g. 📦 or fa-box">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input"
                               value="{{ old('sort_order', 0) }}" min="0">
                        <p class="form-hint">Lower = displayed first.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea tinymce" rows="5"
                              placeholder="Describe what articles in this category cover...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="w-4 h-4">
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
                            https://cooca.id › blog › category › <span id="serpCatSlug">nama-kategori</span>
                        </div>
                        <div id="serpCatTitle" class="text-xl font-bold" style="color:#1a0dab;line-height:1.3;">
                            {{ old('meta_title', 'Nama Kategori - Blog COOCA.ID') }}
                        </div>
                        <div id="serpCatDesc" class="text-sm mt-1" style="color:#4d5156;line-height:1.4;">
                            {{ old('meta_description', 'Deskripsi kategori ini akan muncul di pencarian Google.') }}
                        </div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Focus Keyword</label>
                        <input type="text" name="focus_keyword" id="focusKeyword" class="form-input"
                               value="{{ old('focus_keyword') }}" placeholder="e.g. tips bisnis umkm">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-input"
                               value="{{ old('meta_keywords') }}" placeholder="umkm, bisnis, tips">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title <span class="text-muted">(50–60 chars)</span></label>
                    <input type="text" name="meta_title" id="catMetaTitle" class="form-input"
                           value="{{ old('meta_title') }}" placeholder="Judul SEO kategori ini">
                    <div class="flex justify-between mt-1">
                        <p class="form-hint">Fallback to category name if empty.</p>
                        <span id="catMetaTitleCount" class="text-xs text-muted">0 / 60</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description <span class="text-muted">(120–160 chars)</span></label>
                    <textarea name="meta_description" id="catMetaDesc" class="form-textarea" rows="2"
                              placeholder="Deskripsi singkat kategori untuk hasil pencarian...">{{ old('meta_description') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <span></span>
                        <span id="catMetaDescCount" class="text-xs text-muted">0 / 160</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Canonical URL</label>
                    <input type="url" name="canonical_url" class="form-input"
                           placeholder="https://cooca.id/blog/category/nama" value="{{ old('canonical_url') }}">
                </div>

                {{-- OG Fields --}}
                <div class="mt-4 p-4 rounded-xl" style="border:1px dashed var(--border);background:var(--bg-secondary);">
                    <div class="font-bold text-sm mb-4">📣 Open Graph — Social Media</div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">OG Title</label>
                            <input type="text" name="og_title" class="form-input"
                                   value="{{ old('og_title') }}" placeholder="Social media title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">OG Description</label>
                            <input type="text" name="og_description" class="form-input"
                                   value="{{ old('og_description') }}" placeholder="Social media description">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">OG Image Upload <span class="text-muted">(1200×630)</span></label>
                        <input type="file" name="og_image" class="form-input" accept="image/*">
                        <p class="form-hint">Saved as <code>slug-og.ext</code> in <code>blog/categories/og/</code></p>
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
                <button type="submit" class="btn btn-primary w-full">✅ Create Category</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline w-full mt-2">Cancel</a>
            </div>
        </div>

        {{-- COVER IMAGE --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🖼️ Cover Image</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Upload Cover Image</label>
                    <input type="file" name="cover_image" class="form-input" accept="image/*"
                           onchange="previewCoverImage(event)">
                    <p class="form-hint">Max 3MB. Saved as <code>slug.ext</code></p>
                </div>
                <div id="coverPreview" class="hidden mt-2">
                    <img id="coverImgTag" src="" alt="Cover Preview"
                         class="w-full rounded-xl object-cover" style="max-height:180px;">
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Cover Image Alt Text</label>
                    <input type="text" name="cover_image_alt" class="form-input"
                           value="{{ old('cover_image_alt') }}" placeholder="e.g. Banner kategori tips UMKM">
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

    function slugify(s) {
        return s.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-');
    }

    function updateSerp() {
        const slug = catSlug.value.trim() || slugify(catName.value) || 'nama-kategori';
        document.getElementById('serpCatSlug').textContent = slug;
        document.getElementById('serpCatTitle').textContent = catMetaTitle.value.trim() || catName.value.trim() || 'Nama Kategori - Blog COOCA.ID';
        document.getElementById('serpCatDesc').textContent = catMetaDesc.value.trim() || 'Deskripsi kategori ini akan muncul di pencarian Google.';
    }

    catName.addEventListener('input', function() {
        if (!catSlug.dataset.manual) catSlug.value = slugify(this.value);
        updateSerp();
    });
    catSlug.addEventListener('input', function() { this.dataset.manual = '1'; updateSerp(); });
    catMetaTitle.addEventListener('input', function() {
        updateSerp();
        document.getElementById('catMetaTitleCount').textContent = this.value.length + ' / 60';
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
