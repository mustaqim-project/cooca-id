@extends('layouts.admin')
@section('title', 'View Post')
@section('subtitle', $post->title)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.blog.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Posts
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Featured Image --}}
            @if ($post->featured_image)
                <div class="mb-4" style="border-radius:12px;overflow:hidden;max-height:320px">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                        style="width:100%;object-fit:cover;max-height:320px">
                </div>
            @endif

            <div class="card-saas mb-4">
                <div class="card-saas-body">
                    <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:.75rem">{{ $post->title }}</h2>
                    <div class="d-flex align-items-center gap-3 mb-4" style="font-size:.85rem;color:var(--text-muted)">
                        <span><i class="bi bi-person me-1"></i>{{ $post->author->name ?? 'Unknown' }}</span>
                        <span><i
                                class="bi bi-calendar me-1"></i>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                        @if ($post->category)
                            <span class="badge-saas badge-saas-info">{{ $post->category }}</span>
                        @endif
                        @if ($post->is_featured)
                            <span class="badge-saas badge-saas-warning"><i class="bi bi-star-fill me-1"></i>Featured</span>
                        @endif
                    </div>

                    @if ($post->excerpt)
                        <div class="p-3 rounded-2 mb-4"
                            style="background:var(--surface-raised);border-left:3px solid var(--primary);font-style:italic;font-size:.925rem">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    <div style="line-height:1.8;font-size:.95rem">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Actions --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.blog.edit', $post) }}" class="btn-saas btn-saas-primary">
                        <i class="bi bi-pencil me-2"></i> Edit Post
                    </a>
                    <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="form-confirm-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-saas btn-saas-danger w-100">
                            <i class="bi bi-trash me-2"></i> Delete Post
                        </button>
                    </form>
                </div>
            </div>

            {{-- Publishing Status --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">Publishing Status</h5>
                </div>
                <div class="card-saas-body">
                    <div class="d-flex flex-column gap-3" style="font-size:.875rem">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:var(--text-muted)">Status</span>
                            @if ($post->is_published)
                                <span class="badge-saas badge-saas-success">Published</span>
                            @else
                                <span class="badge-saas badge-saas-neutral">Draft</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:var(--text-muted)">Featured</span>
                            @if ($post->is_featured)
                                <span class="badge-saas badge-saas-warning">Yes</span>
                            @else
                                <span style="color:var(--text-muted)">No</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted)">Author</span>
                            <span>{{ $post->author->name ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted)">Created</span>
                            <span>{{ $post->created_at->format('d M Y') }}</span>
                        </div>
                        @if ($post->published_at)
                            <div class="d-flex justify-content-between">
                                <span style="color:var(--text-muted)">Published</span>
                                <span>{{ $post->published_at->format('d M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">SEO & Metadata</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-3" style="font-size:.875rem">
                    <div>
                        <div style="color:var(--text-muted);margin-bottom:.25rem">Slug</div>
                        <code
                            style="font-size:.8rem;background:var(--surface-raised);padding:2px 6px;border-radius:4px">{{ $post->slug ?? '—' }}</code>
                    </div>
                    @if ($post->meta_title)
                        <div>
                            <div style="color:var(--text-muted);margin-bottom:.25rem">Meta Title</div>
                            <div>{{ $post->meta_title }}</div>
                        </div>
                    @endif
                    @if ($post->meta_description)
                        <div>
                            <div style="color:var(--text-muted);margin-bottom:.25rem">Meta Description</div>
                            <div style="font-size:.8rem">{{ $post->meta_description }}</div>
                        </div>
                    @endif
                    @if ($post->tags)
                        <div>
                            <div style="color:var(--text-muted);margin-bottom:.25rem">Tags</div>
                            <div class="d-flex flex-wrap gap-1">
                                @php $tags = is_string($post->tags) ? json_decode($post->tags, true) : (array)$post->tags; @endphp
                                @foreach ($tags ?? [] as $tag)
                                    <span class="badge-saas badge-saas-neutral">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Engagement --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">Engagement</h5>
                </div>
                <div class="card-saas-body">
                    <div class="row g-3">
                        <div class="col-6 text-center">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--primary)">
                                {{ number_format($post->views ?? 0) }}</div>
                            <div style="font-size:.8rem;color:var(--text-muted)">Views</div>
                        </div>
                        <div class="col-6 text-center">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--primary)">
                                {{ $post->comments_count ?? 0 }}</div>
                            <div style="font-size:.8rem;color:var(--text-muted)">Comments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection
