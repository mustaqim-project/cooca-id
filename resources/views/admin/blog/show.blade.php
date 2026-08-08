@extends('layouts.admin')

@section('title', $post->title . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.blog.index') }}">Blog</a>
            <span>/</span>
            <span>Preview</span>
        </div>
        <h1 class="page-title">📰 {{ $post->title }}</h1>
        <p class="page-subtitle">Article detail, SEO scorecard, analytics counters & full preview.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">← Articles List</a>
        <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-primary">✏️ Edit Article</a>
    </div>
</div>

<div class="grid-31">
    {{-- Main Content Column --}}
    <div class="flex-col gap-5">
        {{-- Banner & Content Preview --}}
        <div class="card">
            @if($post->featured_image)
                <div class="overflow-hidden rounded-t-2xl">
                    <img src="{{ Storage::url($post->featured_image) }}"
                         alt="{{ $post->featured_image_alt }}"
                         class="w-full object-cover" style="max-height:350px;">
                </div>
                @if($post->featured_image_alt)
                    <div class="px-4 py-2 text-xs text-muted italic" style="background:var(--bg-secondary);border-bottom:1px solid var(--border);">
                        📷 Alt text: "{{ $post->featured_image_alt }}"
                    </div>
                @endif
            @endif

            <div class="card-body">
                <div class="flex items-center gap-3 mb-4">
                    @if($post->blogCategory)
                        <span class="badge badge-purple">📂 {{ $post->blogCategory->name }}</span>
                    @endif
                    @if($post->is_published)
                        <span class="badge badge-success">Published</span>
                    @else
                        <span class="badge badge-muted">Draft</span>
                    @endif
                    @if($post->is_featured)
                        <span class="badge badge-warning">⭐ Featured</span>
                    @endif
                    <span class="text-xs text-muted">⏱ {{ $post->reading_time_minutes }} min read</span>
                </div>

                <h1 class="text-3xl font-black mb-3" style="line-height:1.2;">{{ $post->title }}</h1>
                
                @if($post->excerpt)
                    <div class="p-4 rounded-xl mb-6 text-sm font-medium" style="background:var(--bg-secondary);border-left:4px solid var(--accent);color:var(--text-primary);">
                        {{ $post->excerpt }}
                    </div>
                @endif

                <div class="prose max-w-none mt-6" style="line-height:1.8;color:var(--text-primary);">
                    {!! $post->content !!}
                </div>
            </div>
        </div>

        {{-- SEO Scorecard & Preview --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🔍 SEO Scorecard & Meta Tags</div>
                <span class="badge badge-success">Score: {{ $post->seo_score }}%</span>
            </div>
            <div class="card-body">
                {{-- SERP Box --}}
                <div class="rounded-xl p-4 mb-6" style="background:var(--bg-secondary);border:1px solid var(--border);">
                    <div class="text-xs font-bold text-muted uppercase tracking-wide mb-2">Google Search Snippet</div>
                    <div style="font-family:Arial,sans-serif;">
                        <div class="text-xs text-muted mb-1">https://cooca.id › blog › {{ $post->slug }}</div>
                        <div class="text-xl font-bold" style="color:#1a0dab;">{{ $post->meta_title ?? $post->title }}</div>
                        <div class="text-sm mt-1" style="color:#4d5156;">{{ $post->meta_description ?? $post->excerpt }}</div>
                    </div>
                </div>

                <div class="grid-2 text-xs gap-4">
                    <div>
                        <span class="text-muted block font-semibold">Focus Keyword</span>
                        <span class="font-bold text-base" style="color:var(--accent);">{{ $post->focus_keyword ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-muted block font-semibold">Meta Keywords</span>
                        <span>{{ $post->meta_keywords ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-muted block font-semibold">Canonical URL</span>
                        <span>{{ $post->canonical_url ?? 'Default (self)' }}</span>
                    </div>
                    <div>
                        <span class="text-muted block font-semibold">Open Graph Image</span>
                        <span>{{ $post->og_image ? basename($post->og_image) : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Column --}}
    <div class="flex-col gap-5">
        {{-- Analytics Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">📊 Content Analytics</div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">{{ number_format($post->page_views) }}</div>
                        <div class="text-xs text-muted mt-1">Page Views</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">{{ number_format($post->unique_visitors) }}</div>
                        <div class="text-xs text-muted mt-1">Unique Visitors</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">{{ $post->avg_read_duration_seconds }}s</div>
                        <div class="text-xs text-muted mt-1">Avg Read Time</div>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:var(--bg-secondary);border:1px solid var(--border);">
                        <div class="text-2xl font-black" style="color:var(--accent);">{{ $post->bounce_rate }}%</div>
                        <div class="text-xs text-muted mt-1">Bounce Rate</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Author & Publishing Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">ℹ️ Information</div>
            </div>
            <div class="card-body">
                <div class="text-xs text-muted space-y-3">
                    <div class="flex justify-between">
                        <span>Author</span>
                        <span class="font-bold text-primary">{{ $post->author?->name ?? 'Editorial' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Category</span>
                        <span class="font-bold text-primary">{{ $post->blogCategory?->name ?? 'Uncategorized' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Slug</span>
                        <code class="text-xs">{{ $post->slug }}</code>
                    </div>
                    <div class="flex justify-between">
                        <span>Published Date</span>
                        <span class="font-semibold">{{ $post->published_at ? $post->published_at->format('d M Y, H:i') : 'Draft' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Created At</span>
                        <span class="font-semibold">{{ $post->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-primary w-full text-center">✏️ Edit Article</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
