@extends('layouts.admin')

@section('title', 'Blog Articles CMS — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Blog Articles</span>
        </div>
        <h1 class="page-title">📰 Blog Articles & Content</h1>
        <p class="page-subtitle">Publish news, guides, ERP insights, and SEO-optimised business content.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline">📂 Categories</a>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">✍️ Write New Article</a>
    </div>
</div>

{{-- FILTERS --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.blog.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="form-group" style="flex:2;min-width:200px;">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-input" placeholder="Search title or content..."
                       value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="form-group" style="min-width:150px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="featured" {{ ($filters['status'] ?? '') === 'featured' ? 'selected' : '' }}>Featured</option>
                </select>
            </div>
            <div class="form-group" style="min-width:150px;">
                <label class="form-label">Category</label>
                <select name="blog_category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['blog_category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Category</th>
                        <th>SEO</th>
                        <th>Analytics</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            {{-- Article --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}"
                                             alt="{{ $post->featured_image_alt }}"
                                             class="rounded-lg object-cover flex-shrink-0"
                                             style="width:52px;height:52px;">
                                    @else
                                        <div class="rounded-lg flex items-center justify-center flex-shrink-0 text-2xl"
                                             style="width:52px;height:52px;background:var(--bg-secondary);border:1px solid var(--border);">
                                            📰
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold" style="max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $post->title }}
                                            @if($post->is_featured)
                                                <span class="badge badge-warning" style="font-size:10px;">⭐ Featured</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-muted">{{ $post->slug }}</div>
                                        <div class="text-xs text-muted">{{ $post->reading_time_minutes }} min read · {{ $post->author?->name ?? 'Editorial' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td>
                                @if($post->blogCategory)
                                    <span class="badge badge-purple">{{ $post->blogCategory->name }}</span>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>

                            {{-- SEO Score --}}
                            <td>
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-full overflow-hidden" style="width:60px;height:6px;background:var(--border);">
                                            <div class="rounded-full" style="height:6px;width:{{ $post->seo_score }}%;background:{{ $post->seo_score >= 80 ? '#22c55e' : ($post->seo_score >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                                        </div>
                                        <span class="text-xs font-bold" style="color:{{ $post->seo_score >= 80 ? '#22c55e' : ($post->seo_score >= 50 ? '#f59e0b' : '#ef4444') }};">{{ $post->seo_score }}%</span>
                                    </div>
                                    @if($post->focus_keyword)
                                        <div class="text-xs text-muted">🎯 {{ $post->focus_keyword }}</div>
                                    @endif
                                </div>
                            </td>

                            {{-- Analytics --}}
                            <td>
                                <div class="text-xs space-y-1">
                                    <div class="flex items-center gap-1">
                                        <span class="text-muted">👁</span>
                                        <span class="font-semibold">{{ number_format($post->page_views) }}</span>
                                        <span class="text-muted">views</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-muted">👥</span>
                                        <span class="font-semibold">{{ number_format($post->unique_visitors) }}</span>
                                        <span class="text-muted">visitors</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($post->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-muted">Draft</span>
                                @endif
                            </td>

                            {{-- Published Date --}}
                            <td class="text-xs text-muted">
                                {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }}
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.blog.show', $post->id) }}" class="btn btn-ghost btn-sm">👁</a>
                                    <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-ghost btn-sm">✏️</a>
                                    <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this article?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:60px 20px;">
                                <div class="text-5xl mb-3">📰</div>
                                <div class="font-bold mb-1">No Blog Articles Found</div>
                                <p class="text-sm text-muted mb-3">Start writing your first article to attract organic traffic.</p>
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">✍️ Write First Article</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($posts->hasPages())
        <div class="card-footer">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
