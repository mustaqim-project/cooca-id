@extends('admin.layouts.app')

@section('title', 'Blog Posts')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Blog Posts</h2>
                <p class="text-secondary mb-0">Create, publish, and manage blog articles and industry news.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create New Post
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search articles...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Article Title</th>
                            <th class="py-3 px-3 border-0">Category</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Published At</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($posts ?? [
                                (object)
    ['id' => 1, 'title' => 'Top 5 ERP Trends in 2026', 'slug' => 'top-5-erp-trends-in-2026', 'category' => 'Technology', 'is_published' => true, 'created_at' => now()->subDays(1)],
                                (object)['id' => 2, 'title' => 'How to Scale Your Business Operations', 'slug' => 'how-to-scale-your-business-operations', 'category' => 'Business', 'is_published' => true, 'created_at' => now()->subDays(3)],
                                (object)['id' => 3, 'title' => 'Understanding Financial Automation', 'slug' => 'understanding-financial-automation', 'category' => 'Finance', 'is_published' => false, 'created_at' => now()->subHours(10)]
                            ] as $post)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6">{{ $post->title }}</div>
                                    <div class="text-secondary fs-7 font-monospace">/{{ $post->slug }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-dark border px-3 py-1 rounded-pill">{{ $post->category ?? 'General' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($post->is_published ?? true)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Published</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ is_object($post->created_at) ? $post->created_at->format('d M Y') : 'Oct 15, 2026' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.blog.show', $post->id ?? 1) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.blog.edit', $post->id ?? 1) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.blog.destroy', $post->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                            class="bi bi-trash me-2"></i> Delete Post</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-journal-text fs-1"></i></div>
                                    <h6 class="fw-medium">No Blog Posts Created</h6>
                                    <p class="fs-7">Share updates and knowledge by writing your first article.</p>
                                    <a href="{{ route('admin.blog.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create Post</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($posts) && method_exists($posts, 'hasPages') && $posts->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
