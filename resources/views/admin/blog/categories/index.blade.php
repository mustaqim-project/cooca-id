@extends('layouts.admin')

@section('title', 'Blog Categories CMS — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.blog.index') }}">Blog</a>
            <span>/</span>
            <span>Categories</span>
        </div>
        <h1 class="page-title">📂 Blog Categories CMS</h1>
        <p class="page-subtitle">Manage blog taxonomy — cover images, SEO metadata, ordering, and analytics.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
            ＋ New Category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Slug</th>
                        <th>Articles</th>
                        <th>Total Views</th>
                        <th>SEO Fields</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($cat->cover_image)
                                        <img src="{{ Storage::url($cat->cover_image) }}"
                                             alt="{{ $cat->cover_image_alt }}"
                                             class="rounded-lg object-cover flex-shrink-0"
                                             style="width:44px;height:44px;">
                                    @else
                                        <div class="rounded-lg flex items-center justify-center flex-shrink-0 text-xl"
                                             style="width:44px;height:44px;background:var(--bg-secondary);border:1px solid var(--border);">
                                            {{ $cat->icon ?? '📂' }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold">{{ $cat->name }}</div>
                                        <div class="text-xs text-muted">{{ Str::limit(strip_tags($cat->description ?? ''), 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-xs">/blog/{{ $cat->slug }}</code></td>
                            <td>
                                <span class="badge badge-purple">{{ $cat->posts_count }} Articles</span>
                            </td>
                            <td>
                                <span class="font-semibold">{{ number_format($cat->total_post_views) }}</span>
                            </td>
                            <td>
                                @php
                                    $seoFilled = collect([
                                        $cat->meta_title, $cat->meta_description, $cat->focus_keyword,
                                        $cat->og_image, $cat->og_title, $cat->canonical_url, $cat->meta_keywords,
                                    ])->filter()->count();
                                    $seoScore = round($seoFilled / 7 * 100);
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="rounded-full overflow-hidden" style="width:60px;height:6px;background:var(--border);">
                                        <div class="rounded-full" style="height:6px;width:{{ $seoScore }}%;background:{{ $seoScore >= 80 ? '#22c55e' : ($seoScore >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                                    </div>
                                    <span class="text-xs font-semibold">{{ $seoScore }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-muted">Inactive</span>
                                @endif
                            </td>
                            <td class="font-semibold text-center">{{ $cat->sort_order }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.blog-categories.edit', $cat->id) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                    <form action="{{ route('admin.blog-categories.destroy', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Delete category? Posts will be unlinked.')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding:60px 20px;">
                                <div class="text-4xl mb-3">📂</div>
                                <div class="font-bold mb-1">No Blog Categories Yet</div>
                                <a href="{{ route('admin.blog-categories.create') }}" class="text-primary font-bold">Create your first category</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
