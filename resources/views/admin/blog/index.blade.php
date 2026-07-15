@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('subtitle', 'Manage articles and publications')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search posts...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.blog.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> New Post
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="blogTable">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Stats</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($post->featured_image)
                                            <img src="{{ $post->featured_image }}" alt="" width="48"
                                                height="36" style="object-fit:cover;border-radius:6px;flex-shrink:0;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="width:48px;height:36px;border-radius:6px;background:var(--surface-raised);flex-shrink:0">
                                                <i class="bi bi-image" style="color:var(--text-muted);font-size:.9rem"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold" style="font-size:.875rem;max-width:260px"
                                                class="text-truncate">
                                                {{ $post->title }}
                                            </div>
                                            @if ($post->is_featured)
                                                <span class="badge-saas badge-saas-warning mt-1">
                                                    <i class="bi bi-star-fill me-1"></i> Featured
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:.875rem">{{ $post->author->name ?? '—' }}</span>
                                </td>
                                <td>
                                    @if ($post->category)
                                        <span class="badge-saas badge-saas-info">{{ $post->category }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size:.8rem;color:var(--text-muted)">
                                        <i class="bi bi-eye me-1"></i>{{ number_format($post->views ?? 0) }}
                                        <span class="ms-2"><i
                                                class="bi bi-chat me-1"></i>{{ $post->comments_count ?? 0 }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($post->is_published)
                                        <span class="badge-saas badge-saas-success">Published</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Draft</span>
                                    @endif
                                </td>
                                <td style="font-size:.8rem;color:var(--text-muted);white-space:nowrap">
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <a href="{{ route('admin.blog.show', $post) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.blog.edit', $post) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST"
                                            class="form-confirm-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon"
                                                title="Delete" style="color:var(--danger)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-file-earmark-text"></i></div>
                                        <div class="empty-state-title">No posts yet</div>
                                        <div class="empty-state-description">Create your first blog post to get started.
                                        </div>
                                        <a href="{{ route('admin.blog.create') }}" class="btn-saas btn-saas-primary mt-3">
                                            <i class="bi bi-plus-lg me-1"></i> New Post
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
            <div class="card-saas-footer">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    @include('components.swal-alert')
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#blogTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
