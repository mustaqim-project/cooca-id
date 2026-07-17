@extends('admin.layouts.app')

@section('title', 'Blog Post Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.blog.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Post Details</h2>
                    <p class="text-secondary mb-0">Review content and metadata.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="#" target="_blank"
                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-primary">
                    <i class="bi bi-box-arrow-up-right me-2"></i> Live Preview
                </a>
                <a href="{{ route('admin.blog.edit', $post->id ?? 1) }}"
                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>
                <form action="{{ route('admin.blog.destroy', $post->id ?? 1) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center">
                    <div class="bg-light rounded-4 overflow-hidden mb-3 border shadow-sm" style="height: 160px;">
                        <img src="{{ $post->image ?? 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80' }}"
                            class="w-100 h-100" style="object-fit: cover;" alt="Cover">
                    </div>
                    <h4 class="fw-bold mb-1">{{ $post->title ?? 'Top 5 ERP Trends in 2026' }}</h4>
                    <p class="text-secondary mb-3 font-monospace fs-7">/{{ $post->slug ?? 'top-5-erp-trends-in-2026' }}</p>
                    <div>
                        @if ($post->is_published ?? true)
                            <span
                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Published</span>
                        @else
                            <span
                                class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle">Draft</span>
                        @endif
                        <span
                            class="badge bg-light text-dark rounded-pill px-3 py-2 border ms-1">{{ $post->category ?? 'Technology' }}</span>
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Author</span>
                        <span class="fw-medium fs-7">Admin</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span
                            class="fw-medium fs-7">{{ is_object($post->created_at ?? null) ? $post->created_at->format('M d, Y h:i A') : 'Oct 12, 2026 10:00 AM' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span
                            class="fw-medium fs-7">{{ is_object($post->updated_at ?? null) ? $post->updated_at->format('M d, Y h:i A') : 'Oct 15, 2026 14:30 PM' }}</span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2 text-primary"></i> Metrics</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-eye"></i></div>
                            <span class="text-secondary">Views</span>
                        </div>
                        <span class="fw-bold fs-5">1,204</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-share"></i></div>
                            <span class="text-secondary">Shares</span>
                        </div>
                        <span class="fw-bold fs-5">86</span>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Article Content</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-4 p-md-5">
                            <div class="border rounded-4 p-4 p-md-5 bg-white shadow-sm article-content"
                                style="min-height: 600px;">
                                <style>
                                    .article-content h2 {
                                        font-weight: bold;
                                        margin-bottom: 1rem;
                                        margin-top: 2rem;
                                        font-size: 1.75rem;
                                    }

                                    .article-content h2:first-child {
                                        margin-top: 0;
                                    }

                                    .article-content p {
                                        color: var(--color-text-primary);
                                        line-height: 1.8;
                                        margin-bottom: 1.5rem;
                                    }

                                    .article-content ul,
                                    .article-content ol {
                                        margin-bottom: 1.5rem;
                                        padding-left: 1.5rem;
                                        line-height: 1.8;
                                    }

                                    .article-content li {
                                        margin-bottom: 0.5rem;
                                    }
                                </style>

                                {!! $post->content ??
                                    "
                                                                                                <h2>Introduction</h2>
                                                                                                <p>Enterprise Resource Planning (ERP) is evolving faster than ever before. As businesses continue to scale globally and operations become increasingly complex, relying on outdated spreadsheet models and fragmented software solutions is no longer a viable strategy for sustained growth.</p>
                                
                                                                                                <p>In 2026, we are witnessing a massive paradigm shift in how organizations manage their core processes. From AI-driven insights to hyper-automation, modern ERP systems are transitioning from simple systems of record to proactive systems of intelligence.</p>
                                
                                                                                                <h2>1. Artificial Intelligence as Standard</h2>
                                                                                                <p>AI is no longer an expensive add-on. Modern ERPs integrate machine learning algorithms directly into core modules to predict inventory shortages, flag anomalous financial transactions, and automate routine data entry tasks.</p>
                                
                                                                                                <h2>2. Composable Architecture</h2>
                                                                                                <p>The era of rigid, monolithic ERP deployments is ending. Businesses now demand 'composable' systems where they can plug-and-play specific modules (finance, HR, supply chain) from different best-of-breed vendors using robust API integrations.</p>
                                                                                            " !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
