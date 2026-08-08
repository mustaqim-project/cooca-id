@extends('layouts.admin')

@section('title', 'Write New Blog Article — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.blog.index') }}">Blog Articles</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">✍️ Write New Article</h1>
        <p class="page-subtitle">Full-featured CMS — TinyMCE editor, image upload, SERP preview, Open Graph & analytics tracking.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">← Back to Articles</a>
    </div>
</div>

<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="grid-31">
    @csrf

    {{-- ================================================================
         MAIN COLUMN
    ================================================================ --}}
    <div class="flex-col gap-5">

        {{-- ARTICLE EDITOR --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📝 Article Editor</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Article Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="postTitle" class="form-input"
                           required value="{{ old('title') }}"
                           placeholder="e.g. 10 Strategi Efisiensi Stok Gudang untuk UMKM Ritel">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">URL Slug</label>
                        <input type="text" name="slug" id="postSlug" class="form-input"
                               placeholder="Auto-generated from title" value="{{ old('slug') }}">
                        <p class="form-hint">Leave blank to auto-generate from title</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="blog_category_id" class="form-select">
                            <option value="">-- No Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('blog_category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Excerpt / Summary</label>
                    <textarea name="excerpt" id="postExcerpt" class="form-textarea" rows="3"
                              placeholder="Brief 2-3 sentence summary used in listings & meta description...">{{ old('excerpt') }}</textarea>
                    <p class="form-hint">Max 500 characters. Shown in blog cards and used as meta fallback.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Body Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" class="form-textarea tinymce" rows="20">{{ old('content') }}</textarea>
                    @error('content')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ============================================================
             FULL SEO SUITE
        ============================================================ --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title flex items-center gap-2">🔍 SEO & Search Optimization</div>
                <span class="badge badge-success">SERP Preview Live</span>
            </div>
            <div class="card-body">

                {{-- Live Google Snippet Preview --}}
                <div class="rounded-xl mb-6 p-4" style="background:var(--bg-secondary);border:1px solid var(--border);">
                    <div class="text-xs font-bold text-muted uppercase tracking-wide mb-3">📡 Live Google Snippet Preview</div>
                    <div style="font-family:Arial,sans-serif;max-width:600px;">
                        <div class="text-xs mb-1" style="color:#202124;">
                            <span style="color:#4d5156;">https://cooca.id › blog › </span>
                            <span id="serpSlug" style="color:#4d5156;">artikel-slug</span>
                        </div>
                        <div id="serpTitle" class="text-xl font-bold hover:underline cursor-pointer" style="color:#1a0dab;line-height:1.3;">
                            {{ old('meta_title', old('title', 'Judul Artikel Blog - COOCA.ID')) }}
                        </div>
                        <div id="serpDesc" class="text-sm mt-1" style="color:#4d5156;line-height:1.4;">
                            {{ old('meta_description', old('excerpt', 'Deskripsi meta artikel blog ini akan muncul di pencarian Google.')) }}
                        </div>
                    </div>
                </div>

                {{-- SEO Score Bar --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-muted">SEO Completion Score</span>
                        <span id="seoScoreLabel" class="text-sm font-bold" style="color:var(--accent);">0%</span>
                    </div>
                    <div class="rounded-full overflow-hidden" style="height:8px;background:var(--border);">
                        <div id="seoScoreBar" class="rounded-full transition-all duration-500" style="height:8px;width:0%;background:var(--accent);"></div>
                    </div>
                    <p class="text-xs text-muted mt-1">Fill in meta title, description, focus keyword, OG image, OG title, canonical URL, and keywords to increase score.</p>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Focus Keyword</label>
                        <input type="text" name="focus_keyword" id="focusKeyword" class="form-input"
                               value="{{ old('focus_keyword') }}" placeholder="e.g. manajemen stok gudang">
                        <p class="form-hint">Primary keyword this article targets.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="metaKeywords" class="form-input"
                               value="{{ old('meta_keywords') }}" placeholder="erp umkm, pos kasir, manajemen stok">
                        <p class="form-hint">Comma-separated secondary keywords.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title <span class="text-muted">(50–60 chars recommended)</span></label>
                    <input type="text" name="meta_title" id="metaTitle" class="form-input"
                           value="{{ old('meta_title') }}" placeholder="Judul SEO khusus untuk pencarian Google">
                    <div class="flex justify-between mt-1">
                        <p class="form-hint">Fallback to article title if empty.</p>
                        <span id="metaTitleCount" class="text-xs text-muted">0 / 60</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description <span class="text-muted">(120–160 chars recommended)</span></label>
                    <textarea name="meta_description" id="metaDescription" class="form-textarea" rows="2"
                              placeholder="Ringkasan informatif artikel yang menarik klik dari Google...">{{ old('meta_description') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <p class="form-hint">Shown under title in search results.</p>
                        <span id="metaDescCount" class="text-xs text-muted">0 / 160</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Canonical URL <span class="text-muted">(Optional)</span></label>
                    <input type="url" name="canonical_url" id="canonicalUrl" class="form-input"
                           placeholder="https://cooca.id/blog/original-post" value="{{ old('canonical_url') }}">
                    <p class="form-hint">Use if this article has a duplicate on another URL.</p>
                </div>

                {{-- Open Graph --}}
                <div class="mt-4 p-4 rounded-xl" style="border:1px dashed var(--border);background:var(--bg-secondary);">
                    <div class="font-bold text-sm mb-4" style="color:var(--text-primary);">📣 Open Graph — Social Media Preview</div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">OG Title</label>
                            <input type="text" name="og_title" id="ogTitle" class="form-input"
                                   placeholder="Social share title" value="{{ old('og_title') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">OG Description</label>
                            <input type="text" name="og_description" class="form-input"
                                   placeholder="Social share snippet text" value="{{ old('og_description') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">OG Image Upload <span class="text-muted">(1200×630 recommended)</span></label>
                        <input type="file" name="og_image" id="ogImageInput" class="form-input" accept="image/*"
                               onchange="previewOgImage(event)">
                        <p class="form-hint">Max 3MB. Filename will be saved as <code>slug-og.ext</code></p>
                        <div id="ogImagePreview" class="mt-2 hidden">
                            <img id="ogImgTag" src="" alt="OG Preview" class="rounded-lg" style="max-height:160px;object-fit:cover;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">OG Image Alt Text</label>
                        <input type="text" name="og_image_alt" class="form-input"
                               placeholder="Descriptive alt for the social share image" value="{{ old('og_image_alt') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ANALYTICS TRACKING NOTES --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📊 Analytics Tracking</div>
                <span class="badge badge-info">Auto-tracked</span>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">0</div>
                        <div class="text-xs text-muted mt-1">Page Views</div>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">0</div>
                        <div class="text-xs text-muted mt-1">Unique Visitors</div>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">0s</div>
                        <div class="text-xs text-muted mt-1">Avg. Read Time</div>
                    </div>
                </div>
                <p class="text-xs text-muted mt-3">Analytics counters are updated automatically when visitors read this article. Connect GA4 in Settings → Integrations for enhanced tracking.</p>
            </div>
        </div>
    </div>

    {{-- ================================================================
         SIDEBAR
    ================================================================ --}}
    <div class="flex-col gap-5">

        {{-- PUBLISH OPTIONS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🚀 Publish Options</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_published" class="form-select">
                        <option value="1" {{ old('is_published', '1') == '1' ? 'selected' : '' }}>✅ Publish Immediately</option>
                        <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>📝 Save as Draft</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Scheduled Publish Date</label>
                    <input type="datetime-local" name="published_at" class="form-input" value="{{ old('published_at') }}">
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4">
                        <div>
                            <span class="font-bold block">⭐ Featured Article</span>
                            <span class="text-xs text-muted">Highlight on home & top of listings</span>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-2">🚀 Publish Article</button>
                <button type="submit" onclick="document.querySelector('[name=is_published]').value='0'" class="btn btn-outline w-full mt-2">
                    💾 Save as Draft
                </button>
            </div>
        </div>

        {{-- FEATURED IMAGE UPLOAD --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🖼️ Featured Image</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="featured_image" id="featuredImageInput" class="form-input"
                           accept="image/*" onchange="previewFeaturedImage(event)">
                    <p class="form-hint">Max 3MB. Saved as <code>slug.ext</code> in <code>blog/images/</code></p>
                </div>
                <div id="featuredPreview" class="hidden mt-2">
                    <img id="featuredImgTag" src="" alt="Featured Preview"
                         class="w-full rounded-xl object-cover" style="max-height:200px;">
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Image Alt Text <span class="text-red-400">*</span></label>
                    <input type="text" name="featured_image_alt" class="form-input"
                           placeholder="e.g. Ilustrasi manajemen stok gudang UMKM" value="{{ old('featured_image_alt') }}">
                    <p class="form-hint">Required for SEO & accessibility. Describe the image in detail.</p>
                </div>
            </div>
        </div>

        {{-- TAGS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🏷️ Tags</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tags (Press Enter or comma to add)</label>
                    <input type="text" id="tagInput" class="form-input" placeholder="Type a tag and press Enter...">
                    <div id="tagContainer" class="flex flex-wrap gap-2 mt-2"></div>
                    <div id="tagHiddenInputs"></div>
                    @if(old('tags'))
                        @foreach(old('tags') as $tag)
                            <input type="hidden" name="tags[]" value="{{ $tag }}">
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const postTitle = document.getElementById('postTitle');
    const postExcerpt = document.getElementById('postExcerpt');
    const postSlug = document.getElementById('postSlug');
    const metaTitle = document.getElementById('metaTitle');
    const metaDescription = document.getElementById('metaDescription');
    const metaTitleCount = document.getElementById('metaTitleCount');
    const metaDescCount = document.getElementById('metaDescCount');

    const serpTitle = document.getElementById('serpTitle');
    const serpDesc = document.getElementById('serpDesc');
    const serpSlug = document.getElementById('serpSlug');
    const seoScoreBar = document.getElementById('seoScoreBar');
    const seoScoreLabel = document.getElementById('seoScoreLabel');

    // SEO fields to score
    const seoFields = {
        metaTitle: { el: document.getElementById('metaTitle'), weight: 20 },
        metaDescription: { el: document.getElementById('metaDescription'), weight: 20 },
        focusKeyword: { el: document.getElementById('focusKeyword'), weight: 15 },
        ogTitle: { el: document.getElementById('ogTitle'), weight: 10 },
        canonicalUrl: { el: document.getElementById('canonicalUrl'), weight: 10 },
        metaKeywords: { el: document.getElementById('metaKeywords'), weight: 10 },
    };

    function slugify(str) {
        return str.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
    }

    function updateSerp() {
        serpTitle.textContent = metaTitle.value.trim() || postTitle.value.trim() || 'Judul Artikel Blog - COOCA.ID';
        serpDesc.textContent  = metaDescription.value.trim() || postExcerpt?.value.trim() || 'Deskripsi meta artikel blog ini akan muncul di pencarian Google.';
        const slug = postSlug.value.trim() || slugify(postTitle.value.trim()) || 'artikel-slug';
        serpSlug.textContent = slug;
    }

    function updateSeoScore() {
        let score = 0;
        for (const [key, cfg] of Object.entries(seoFields)) {
            if (cfg.el && cfg.el.value.trim()) score += cfg.weight;
        }
        // og_image gets 15 points — check file input
        const ogFile = document.getElementById('ogImageInput');
        if (ogFile && ogFile.files.length) score += 15;
        score = Math.min(100, score);
        seoScoreBar.style.width = score + '%';
        seoScoreLabel.textContent = score + '%';
        const color = score >= 80 ? '#22c55e' : score >= 50 ? '#f59e0b' : '#ef4444';
        seoScoreBar.style.background = color;
        seoScoreLabel.style.color = color;
    }

    function updateMetaTitleCount() {
        metaTitleCount.textContent = metaTitle.value.length + ' / 60';
        metaTitleCount.style.color = metaTitle.value.length > 60 ? '#ef4444' : 'var(--text-muted)';
    }

    function updateMetaDescCount() {
        metaDescCount.textContent = metaDescription.value.length + ' / 160';
        metaDescCount.style.color = metaDescription.value.length > 160 ? '#ef4444' : 'var(--text-muted)';
    }

    // Auto-slug from title
    postTitle.addEventListener('input', function() {
        if (!postSlug.dataset.manual) {
            postSlug.value = slugify(this.value);
        }
        updateSerp(); updateSeoScore();
    });

    postSlug.addEventListener('input', function() {
        this.dataset.manual = '1';
        updateSerp();
    });

    postExcerpt?.addEventListener('input', updateSerp);
    metaTitle.addEventListener('input', () => { updateSerp(); updateSeoScore(); updateMetaTitleCount(); });
    metaDescription.addEventListener('input', () => { updateSerp(); updateSeoScore(); updateMetaDescCount(); });

    for (const cfg of Object.values(seoFields)) {
        cfg.el?.addEventListener('input', updateSeoScore);
    }
    document.getElementById('ogImageInput')?.addEventListener('change', updateSeoScore);

    updateSerp();
    updateSeoScore();
    updateMetaTitleCount();
    updateMetaDescCount();

    // -------------------------------------------------------------------
    // Tag input
    // -------------------------------------------------------------------
    const tagInput = document.getElementById('tagInput');
    const tagContainer = document.getElementById('tagContainer');
    const tagHiddenInputs = document.getElementById('tagHiddenInputs');
    const existingTags = new Set();

    function addTag(value) {
        const tag = value.trim().toLowerCase();
        if (!tag || existingTags.has(tag)) return;
        existingTags.add(tag);

        const chip = document.createElement('span');
        chip.className = 'badge badge-info flex items-center gap-1 cursor-pointer';
        chip.innerHTML = `${tag} <button type="button" onclick="removeTag(this,'${tag}')" style="background:none;border:none;padding:0;cursor:pointer;font-size:12px;line-height:1;">✕</button>`;
        tagContainer.appendChild(chip);

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'tags[]';
        hidden.value = tag;
        hidden.id = 'tag-' + tag;
        tagHiddenInputs.appendChild(hidden);
    }

    window.removeTag = function(btn, tag) {
        existingTags.delete(tag);
        btn.closest('span').remove();
        const h = document.getElementById('tag-' + tag);
        if (h) h.remove();
    };

    tagInput?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(this.value.replace(',', ''));
            this.value = '';
        }
    });

    // Pre-fill old tags
    @if(old('tags'))
        @foreach(old('tags') as $tag)
            addTag("{{ $tag }}");
        @endforeach
    @endif
});

function previewFeaturedImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('featuredImgTag').src = e.target.result;
        document.getElementById('featuredPreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function previewOgImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('ogImgTag').src = e.target.result;
        document.getElementById('ogImagePreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
@endsection
