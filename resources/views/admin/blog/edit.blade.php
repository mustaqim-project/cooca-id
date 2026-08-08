@extends('layouts.admin')

@section('title', 'Edit Article: ' . $post->title . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.blog.index') }}">Blog Articles</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">✏️ Edit Article</h1>
        <p class="page-subtitle">{{ $post->title }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">← Back to Articles</a>
        <a href="{{ route('admin.blog.show', $post) }}" class="btn btn-ghost">👁 Preview</a>
    </div>
</div>

<form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="grid-31">
    @csrf
    @method('PUT')

    {{-- ================================================================
         MAIN COLUMN
    ================================================================ --}}
    <div class="flex-col gap-5">

        {{-- ARTICLE EDITOR --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📝 Article Editor</div>
                @if($post->isPublished())
                    <span class="badge badge-success">Published</span>
                @else
                    <span class="badge badge-warning">Draft</span>
                @endif
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Article Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="postTitle" class="form-input"
                           required value="{{ old('title', $post->title) }}"
                           placeholder="e.g. 10 Strategi Efisiensi Stok Gudang untuk UMKM Ritel">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">URL Slug</label>
                        <input type="text" name="slug" id="postSlug" class="form-input"
                               value="{{ old('slug', $post->slug) }}">
                        <p class="form-hint">Changing slug will break existing links.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="blog_category_id" class="form-select">
                            <option value="">-- No Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('blog_category_id', $post->blog_category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Excerpt / Summary</label>
                    <textarea name="excerpt" id="postExcerpt" class="form-textarea" rows="3"
                              placeholder="Brief 2-3 sentence summary...">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Body Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" class="form-textarea tinymce" rows="20">{{ old('content', $post->content) }}</textarea>
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
                <span class="badge badge-success">Score: <span id="seoScoreLabel">{{ $post->seo_score }}%</span></span>
            </div>
            <div class="card-body">

                {{-- Live Google Snippet Preview --}}
                <div class="rounded-xl mb-6 p-4" style="background:var(--bg-secondary);border:1px solid var(--border);">
                    <div class="text-xs font-bold text-muted uppercase tracking-wide mb-3">📡 Live Google Snippet Preview</div>
                    <div style="font-family:Arial,sans-serif;max-width:600px;">
                        <div class="text-xs mb-1" style="color:#4d5156;">
                            https://cooca.id › blog › <span id="serpSlug">{{ $post->slug }}</span>
                        </div>
                        <div id="serpTitle" class="text-xl font-bold hover:underline cursor-pointer" style="color:#1a0dab;line-height:1.3;">
                            {{ old('meta_title', $post->meta_title ?? $post->title) }}
                        </div>
                        <div id="serpDesc" class="text-sm mt-1" style="color:#4d5156;line-height:1.4;">
                            {{ old('meta_description', $post->meta_description ?? $post->excerpt) }}
                        </div>
                    </div>
                </div>

                {{-- SEO Score Bar --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-muted">SEO Completion Score</span>
                        <span id="seoScorePct" class="text-sm font-bold" style="color:var(--accent);">{{ $post->seo_score }}%</span>
                    </div>
                    <div class="rounded-full overflow-hidden" style="height:8px;background:var(--border);">
                        <div id="seoScoreBar" class="rounded-full transition-all duration-500"
                             style="height:8px;width:{{ $post->seo_score }}%;background:{{ $post->seo_score >= 80 ? '#22c55e' : ($post->seo_score >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Focus Keyword</label>
                        <input type="text" name="focus_keyword" id="focusKeyword" class="form-input"
                               value="{{ old('focus_keyword', $post->focus_keyword) }}" placeholder="e.g. manajemen stok gudang">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="metaKeywords" class="form-input"
                               value="{{ old('meta_keywords', $post->meta_keywords) }}" placeholder="erp umkm, pos kasir, stok">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title <span class="text-muted">(50–60 chars)</span></label>
                    <input type="text" name="meta_title" id="metaTitle" class="form-input"
                           value="{{ old('meta_title', $post->meta_title) }}" placeholder="Judul SEO untuk Google">
                    <div class="flex justify-between mt-1">
                        <p class="form-hint">Fallback to article title if empty.</p>
                        <span id="metaTitleCount" class="text-xs text-muted">{{ strlen($post->meta_title ?? '') }} / 60</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description <span class="text-muted">(120–160 chars)</span></label>
                    <textarea name="meta_description" id="metaDescription" class="form-textarea" rows="2"
                              placeholder="Deskripsi untuk Google...">{{ old('meta_description', $post->meta_description) }}</textarea>
                    <div class="flex justify-between mt-1">
                        <p class="form-hint">Shown under title in search results.</p>
                        <span id="metaDescCount" class="text-xs text-muted">{{ strlen($post->meta_description ?? '') }} / 160</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Canonical URL</label>
                    <input type="url" name="canonical_url" id="canonicalUrl" class="form-input"
                           placeholder="https://cooca.id/blog/original-post"
                           value="{{ old('canonical_url', $post->canonical_url) }}">
                </div>

                {{-- Open Graph --}}
                <div class="mt-4 p-4 rounded-xl" style="border:1px dashed var(--border);background:var(--bg-secondary);">
                    <div class="font-bold text-sm mb-4">📣 Open Graph — Social Media Preview</div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">OG Title</label>
                            <input type="text" name="og_title" id="ogTitle" class="form-input"
                                   value="{{ old('og_title', $post->og_title) }}" placeholder="Social share title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">OG Description</label>
                            <input type="text" name="og_description" class="form-input"
                                   value="{{ old('og_description', $post->og_description) }}" placeholder="Social share snippet">
                        </div>
                    </div>

                    {{-- OG Image --}}
                    <div class="form-group">
                        <label class="form-label">OG Image <span class="text-muted">(1200×630 recommended)</span></label>
                        @if($post->og_image)
                            <div class="mb-2 rounded-xl overflow-hidden" style="max-height:120px;">
                                <img src="{{ Storage::url($post->og_image) }}" alt="{{ $post->og_image_alt }}"
                                     class="w-full object-cover" style="max-height:120px;">
                            </div>
                            <p class="form-hint mb-1">Current: <code>{{ basename($post->og_image) }}</code> — Upload new to replace.</p>
                        @endif
                        <input type="file" name="og_image" id="ogImageInput" class="form-input"
                               accept="image/*" onchange="previewOgImage(event)">
                        <div id="ogImagePreview" class="mt-2 hidden">
                            <img id="ogImgTag" src="" alt="OG Preview" class="rounded-lg" style="max-height:120px;object-fit:cover;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">OG Image Alt Text</label>
                        <input type="text" name="og_image_alt" class="form-input"
                               value="{{ old('og_image_alt', $post->og_image_alt) }}"
                               placeholder="Descriptive alt for social share image">
                    </div>
                </div>
            </div>
        </div>

        {{-- ANALYTICS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📊 Analytics Overview</div>
                <span class="badge badge-info">Live Data</span>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-3xl font-black" style="color:var(--accent);">{{ number_format($post->page_views) }}</div>
                        <div class="text-xs text-muted mt-1">Total Page Views</div>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-3xl font-black" style="color:var(--accent);">{{ number_format($post->unique_visitors) }}</div>
                        <div class="text-xs text-muted mt-1">Unique Visitors</div>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-3xl font-black" style="color:var(--accent);">{{ $post->avg_read_duration_seconds }}s</div>
                        <div class="text-xs text-muted mt-1">Avg. Read Duration</div>
                    </div>
                    <div class="rounded-xl p-4 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-3xl font-black" style="color:var(--accent);">{{ $post->bounce_rate }}%</div>
                        <div class="text-xs text-muted mt-1">Bounce Rate</div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-lg font-bold" style="color:var(--accent);">{{ $post->views_count }}</div>
                        <div class="text-xs text-muted">CMS Views</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-lg font-bold" style="color:var(--accent);">{{ $post->reading_time_minutes }} min</div>
                        <div class="text-xs text-muted">Read Time</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-lg font-bold" style="color:{{ $post->seo_score >= 80 ? '#22c55e' : ($post->seo_score >= 50 ? '#f59e0b' : '#ef4444') }};">
                            {{ $post->seo_score }}%
                        </div>
                        <div class="text-xs text-muted">SEO Score</div>
                    </div>
                </div>
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
                        <option value="1" {{ old('is_published', $post->is_published ? '1' : '0') == '1' ? 'selected' : '' }}>✅ Published</option>
                        <option value="0" {{ old('is_published', $post->is_published ? '1' : '0') == '0' ? 'selected' : '' }}>📝 Draft</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Scheduled Publish Date</label>
                    <input type="datetime-local" name="published_at" class="form-input"
                           value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="form-group">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} class="w-4 h-4">
                        <div>
                            <span class="font-bold block">⭐ Featured Article</span>
                            <span class="text-xs text-muted">Highlight on home & top of listings</span>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-2">💾 Save Changes</button>
            </div>
        </div>

        {{-- FEATURED IMAGE --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🖼️ Featured Image</div>
            </div>
            <div class="card-body">
                @if($post->featured_image)
                    <div class="mb-3 rounded-xl overflow-hidden" style="max-height:200px;">
                        <img src="{{ Storage::url($post->featured_image) }}"
                             alt="{{ $post->featured_image_alt }}"
                             class="w-full object-cover" style="max-height:200px;">
                    </div>
                    <p class="form-hint mb-2">Current: <code>{{ basename($post->featured_image) }}</code></p>
                @endif
                <div class="form-group">
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="featured_image" id="featuredImageInput" class="form-input"
                           accept="image/*" onchange="previewFeaturedImage(event)">
                    <p class="form-hint">Max 3MB. Saved as <code>slug.ext</code> — replaces current image.</p>
                </div>
                <div id="featuredPreview" class="hidden mt-2">
                    <img id="featuredImgTag" src="" alt="Preview"
                         class="w-full rounded-xl object-cover" style="max-height:180px;">
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Image Alt Text</label>
                    <input type="text" name="featured_image_alt" class="form-input"
                           value="{{ old('featured_image_alt', $post->featured_image_alt) }}"
                           placeholder="e.g. Ilustrasi manajemen stok UMKM">
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
                    <input type="text" id="tagInput" class="form-input" placeholder="Type a tag...">
                    <div id="tagContainer" class="flex flex-wrap gap-2 mt-2"></div>
                    <div id="tagHiddenInputs"></div>
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
                        <span class="font-semibold">{{ $post->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Last Updated</span>
                        <span class="font-semibold">{{ $post->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Author</span>
                        <span class="font-semibold">{{ $post->author?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Category</span>
                        <span class="font-semibold">{{ $post->blogCategory?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Read Time</span>
                        <span class="font-semibold">{{ $post->reading_time_minutes }} min</span>
                    </div>
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
    const seoScorePct = document.getElementById('seoScorePct');

    const seoFields = {
        metaTitle: { el: metaTitle, weight: 20 },
        metaDescription: { el: metaDescription, weight: 20 },
        focusKeyword: { el: document.getElementById('focusKeyword'), weight: 15 },
        ogTitle: { el: document.getElementById('ogTitle'), weight: 10 },
        canonicalUrl: { el: document.getElementById('canonicalUrl'), weight: 10 },
        metaKeywords: { el: document.getElementById('metaKeywords'), weight: 10 },
    };

    function updateSerp() {
        serpTitle.textContent = metaTitle.value.trim() || postTitle.value.trim() || 'Judul Artikel Blog - COOCA.ID';
        serpDesc.textContent  = metaDescription.value.trim() || postExcerpt?.value.trim() || 'Deskripsi meta artikel...';
        serpSlug.textContent  = postSlug.value.trim() || '{{ $post->slug }}';
    }

    function updateSeoScore() {
        let score = 0;
        for (const [key, cfg] of Object.entries(seoFields)) {
            if (cfg.el && cfg.el.value.trim()) score += cfg.weight;
        }
        // og_image (existing or new upload)
        const hasExistingOg = {{ $post->og_image ? 'true' : 'false' }};
        const ogFile = document.getElementById('ogImageInput');
        if (hasExistingOg || (ogFile && ogFile.files.length)) score += 15;
        score = Math.min(100, score);
        seoScoreBar.style.width = score + '%';
        seoScorePct.textContent = score + '%';
        const color = score >= 80 ? '#22c55e' : score >= 50 ? '#f59e0b' : '#ef4444';
        seoScoreBar.style.background = color;
        seoScorePct.style.color = color;
    }

    metaTitle.addEventListener('input', () => {
        updateSerp(); updateSeoScore();
        metaTitleCount.textContent = metaTitle.value.length + ' / 60';
        metaTitleCount.style.color = metaTitle.value.length > 60 ? '#ef4444' : 'var(--text-muted)';
    });
    metaDescription.addEventListener('input', () => {
        updateSerp(); updateSeoScore();
        metaDescCount.textContent = metaDescription.value.length + ' / 160';
        metaDescCount.style.color = metaDescription.value.length > 160 ? '#ef4444' : 'var(--text-muted)';
    });
    postTitle.addEventListener('input', updateSerp);
    postExcerpt?.addEventListener('input', updateSerp);
    postSlug.addEventListener('input', updateSerp);
    for (const cfg of Object.values(seoFields)) {
        cfg.el?.addEventListener('input', updateSeoScore);
    }
    document.getElementById('ogImageInput')?.addEventListener('change', updateSeoScore);

    updateSerp();
    updateSeoScore();

    // -------------------------------------------------------------------
    // Tags
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
        chip.className = 'badge badge-info flex items-center gap-1';
        chip.innerHTML = `${tag} <button type="button" onclick="removeTag(this,'${tag}')" style="background:none;border:none;cursor:pointer;font-size:11px;">✕</button>`;
        tagContainer.appendChild(chip);
        const hidden = document.createElement('input');
        hidden.type = 'hidden'; hidden.name = 'tags[]'; hidden.value = tag; hidden.id = 'tag-' + tag;
        tagHiddenInputs.appendChild(hidden);
    }

    window.removeTag = function(btn, tag) {
        existingTags.delete(tag);
        btn.closest('span').remove();
        const h = document.getElementById('tag-' + tag); if (h) h.remove();
    };

    tagInput?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(this.value.replace(',', ''));
            this.value = '';
        }
    });

    // Pre-fill existing tags
    @if($post->tags)
        @foreach($post->tags as $tag)
            addTag("{{ $tag }}");
        @endforeach
    @endif
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
