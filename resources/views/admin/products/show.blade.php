@extends('layouts.admin')

@section('title', 'Product: ' . $product->name)

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <a href="{{ route('admin.products.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
                <i class="bi bi-arrow-left me-1"></i> Products
            </a>
            <span class="text-muted mx-2">/</span>
            <span class="fw-semibold">{{ $product->name }}</span>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn-saas btn-saas-secondary btn-saas-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                class="form-confirm-delete d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-saas btn-saas-danger btn-saas-sm">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- General Info --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h6 class="card-saas-title mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>General Information
                    </h6>
                </div>
                <div class="card-saas-body">
                    <div class="mb-4">
                        <div class="form-saas-label">Product Name</div>
                        <div class="fw-semibold fs-5">{{ $product->name }}</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-saas-label">Category</div>
                        @if ($product->category)
                            <span class="badge-saas badge-saas-info">
                                <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>

                    <div>
                        <div class="form-saas-label">Description</div>
                        @if ($product->description)
                            <p class="mb-0" style="white-space: pre-wrap; line-height: 1.7;">{{ $product->description }}
                            </p>
                        @else
                            <span class="text-muted fst-italic">No description provided.</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Technical Integration --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h6 class="card-saas-title mb-0">
                        <i class="bi bi-code-slash me-2 text-primary"></i>Technical Integration
                    </h6>
                </div>
                <div class="card-saas-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-saas-label">Product ID (UUID)</div>
                            <code class="d-block p-2 rounded"
                                style="background:var(--surface-alt,#f5f5f5); font-size:.85rem; word-break:break-all;">{{ $product->id }}</code>
                        </div>
                        <div class="col-12">
                            <div class="form-saas-label">Webhook URL</div>
                            @if ($product->webhook_url)
                                <code class="d-block p-2 rounded"
                                    style="background:var(--surface-alt,#f5f5f5); font-size:.85rem; word-break:break-all;">{{ $product->webhook_url }}</code>
                            @else
                                <span class="text-muted fst-italic">Not configured</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <div class="form-saas-label">Demo URL</div>
                            @if ($product->demo_url)
                                <a href="{{ $product->demo_url }}" target="_blank" rel="noopener"
                                    class="d-inline-flex align-items-center gap-1" style="word-break:break-all;">
                                    {{ $product->demo_url }}
                                    <i class="bi bi-box-arrow-up-right" style="font-size:.75rem;"></i>
                                </a>
                            @else
                                <span class="text-muted fst-italic">Not configured</span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label">Created</div>
                            <span>{{ $product->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label">Last Updated</div>
                            <span>{{ $product->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Pricing Card --}}
            <div class="card-saas mb-4"
                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color:#fff; border:none;">
                <div class="card-saas-header" style="border-color:rgba(255,255,255,.15);">
                    <h6 class="card-saas-title mb-0" style="color:#fff;">
                        <i class="bi bi-currency-dollar me-2"></i>Pricing
                    </h6>
                </div>
                <div class="card-saas-body">
                    <div class="mb-3">
                        <div
                            style="font-size:.75rem; opacity:.8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:.25rem;">
                            Base Price</div>
                        <div style="font-size:2rem; font-weight:700; line-height:1.1;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div
                            style="font-size:.75rem; opacity:.8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:.4rem;">
                            Type</div>
                        @if ($product->type === 'subscription')
                            <span class="badge-saas badge-saas-info"
                                style="background:rgba(255,255,255,.2); color:#fff; border:1px solid rgba(255,255,255,.3);">
                                <i class="bi bi-arrow-repeat me-1"></i> Subscription
                            </span>
                        @elseif($product->type === 'one_time')
                            <span class="badge-saas badge-saas-success"
                                style="background:rgba(255,255,255,.2); color:#fff; border:1px solid rgba(255,255,255,.3);">
                                <i class="bi bi-check-circle me-1"></i> One Time
                            </span>
                        @else
                            <span class="badge-saas badge-saas-neutral"
                                style="background:rgba(255,255,255,.2); color:#fff; border:1px solid rgba(255,255,255,.3);">
                                {{ ucfirst($product->type ?? '—') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Product Performance --}}
            <div class="card-saas">
                <div class="card-saas-header">
                    <h6 class="card-saas-title mb-0">
                        <i class="bi bi-bar-chart-line me-2 text-primary"></i>Performance
                    </h6>
                </div>
                <div class="card-saas-body p-0">
                    <div style="border-bottom:1px solid var(--border); padding:1rem 1.25rem;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-card-icon green"
                                style="width:2.5rem; height:2.5rem; font-size:1rem; flex-shrink:0;">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div>
                                <div class="stat-card-label" style="font-size:.7rem;">Total Sales</div>
                                <div class="stat-card-value" style="font-size:1.25rem;">
                                    {{ $product->transactions ? $product->transactions->count() : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom:1px solid var(--border); padding:1rem 1.25rem;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-card-icon blue"
                                style="width:2.5rem; height:2.5rem; font-size:1rem; flex-shrink:0;">
                                <i class="bi bi-key"></i>
                            </div>
                            <div>
                                <div class="stat-card-label" style="font-size:.7rem;">Active Licenses</div>
                                <div class="stat-card-value" style="font-size:1.25rem;">
                                    {{ $product->licenses ? $product->licenses->where('status', 'active')->count() : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding:1rem 1.25rem;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-card-icon purple"
                                style="width:2.5rem; height:2.5rem; font-size:1rem; flex-shrink:0;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div>
                                <div class="stat-card-label" style="font-size:.7rem;">Total Revenue</div>
                                <div class="stat-card-value" style="font-size:1.25rem;">
                                    Rp
                                    {{ number_format(
                                        $product->transactions ? $product->transactions->where('status', 'paid')->sum('amount') : 0,
                                        0,
                                        ',',
                                        '.',
                                    ) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection
