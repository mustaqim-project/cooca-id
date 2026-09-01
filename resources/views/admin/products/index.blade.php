@extends('layouts.admin')

@section('title', 'SaaS Products Management — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Products</span>
        </div>
        <h1 class="page-title">SaaS & Software Products</h1>
        <p class="page-subtitle">Manage software catalogue, subscription pricing tiers, feature packages, and technology specifications.</p>
    </div>
    <div class="page-actions" style="display: flex; gap: 10px;">
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-tags mr-1"></i> Categories
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus mr-1"></i> Add Product
        </a>
    </div>
</div>

{{-- KPI / Quick Stats --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-cubes"></i>
        </div>
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Total Products</div>
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $stats['total'] ?? $products->total() }}</div>
        </div>
    </div>

    <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--success-soft); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Active Products</div>
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $stats['active'] ?? 0 }}</div>
        </div>
    </div>

    <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--warning-soft); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-star"></i>
        </div>
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Featured on Home</div>
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $stats['featured'] ?? 0 }}</div>
        </div>
    </div>

    <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Categories</div>
            <div style="font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2;">{{ $stats['categories'] ?? count($categories) }}</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.products.index') }}" class="filter-bar" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
    <div class="filter-search" style="flex: 1; min-width: 240px; display: flex; align-items: center; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 14px;">
        <i class="fa-solid fa-magnifying-glass text-muted mr-2" style="font-size: 14px;"></i>
        <input type="text" name="search" placeholder="Search product name, slug, or keywords..." value="{{ $filters['search'] ?? '' }}" style="border: none; background: transparent; padding: 10px 0; width: 100%; outline: none; color: var(--text); font-size: 14px;">
    </div>

    <select name="category_id" class="form-select" style="min-width: 170px; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 14px;">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>

    <select name="product_type" class="form-select" style="min-width: 160px; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 14px;">
        <option value="">All Types</option>
        @foreach(\App\Models\Product::TYPES as $typeKey => $typeLabel)
            <option value="{{ $typeKey }}" {{ ($filters['product_type'] ?? '') === $typeKey ? 'selected' : '' }}>{{ $typeLabel }}</option>
        @endforeach
    </select>

    <select name="is_active" class="form-select" style="min-width: 140px; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 14px;">
        <option value="">All Statuses</option>
        <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Inactive</option>
    </select>

    <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
        <i class="fa-solid fa-filter mr-1"></i> Filter
    </button>

    @if(!empty($filters['search']) || !empty($filters['category_id']) || !empty($filters['product_type']) || isset($filters['is_active']))
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 10px 16px;">
            <i class="fa-solid fa-rotate-left mr-1"></i> Reset
        </a>
    @endif
</form>

{{-- Data Table Card --}}
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="min-width: 280px;">Product Details</th>
                        <th>Category</th>
                        <th>Type & License</th>
                        <th>Pricing & Plans</th>
                        <th>Status</th>
                        <th style="text-align: right; min-width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $plansCount = $product->subscriptionPlans ? $product->subscriptionPlans->count() : 0;
                            $thumb = $product->thumbnail_url;
                        @endphp
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 14px;">
                                    <div style="width: 48px; height: 48px; border-radius: var(--radius-md); overflow: hidden; background: var(--bg-secondary); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @if($thumb)
                                            <img src="{{ $thumb }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @elseif($product->icon)
                                            @if(str_starts_with($product->icon, 'fa-') || str_starts_with($product->icon, 'fa-solid'))
                                                <i class="{{ $product->icon }}" style="font-size: 20px; color: var(--primary);"></i>
                                            @else
                                                <span style="font-size: 22px;">{{ $product->icon }}</span>
                                            @endif
                                        @else
                                            <i class="fa-solid fa-box" style="font-size: 20px; color: var(--text-muted);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="font-bold text-primary" style="text-decoration: none; font-size: 15px;">
                                                {{ $product->name }}
                                            </a>
                                            @if($product->version)
                                                <span style="font-size: 11px; padding: 2px 6px; border-radius: 4px; background: var(--bg-secondary); color: var(--text-muted); font-weight: 600;">v{{ $product->version }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-muted" style="margin-top: 3px; max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <code>/products/{{ $product->slug }}</code> &bull; {{ $product->short_description ?? 'No summary provided.' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($product->category)
                                    <a href="{{ route('admin.product-categories.show', $product->category->id) }}" style="text-decoration: none;">
                                        <span class="badge badge-purple" style="font-weight: 600;">
                                            <i class="fa-solid fa-tag mr-1"></i> {{ $product->category->name }}
                                        </span>
                                    </a>
                                @else
                                    <span class="badge badge-muted">Uncategorized</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span class="badge badge-accent" style="width: fit-content;">
                                        {{ $product->product_type_label }}
                                    </span>
                                    @if($product->license_type_label)
                                        <span class="text-xs text-muted" style="font-weight: 500;">
                                            <i class="fa-solid fa-key" style="font-size: 10px;"></i> {{ $product->license_type_label }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="font-bold text-primary" style="font-size: 14px;">
                                        @if(($product->base_price ?? 0) > 0)
                                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">Custom / Tiered</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.products.plans.index', $product->id) }}" class="text-xs" style="text-decoration: none; color: var(--accent); font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                                        <i class="fa-solid fa-layer-group"></i> {{ $plansCount }} Pricing {{ Str::plural('Tier', $plansCount) }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    @if($product->is_active)
                                        <span class="badge badge-success" style="width: fit-content;">
                                            <span class="status-dot active"></span> Active
                                        </span>
                                    @else
                                        <span class="badge badge-muted" style="width: fit-content;">
                                            <span class="status-dot inactive"></span> Inactive
                                        </span>
                                    @endif

                                    @if($product->is_featured)
                                        <span class="badge badge-warning" style="width: fit-content; font-size: 10px; padding: 2px 6px;">
                                            <i class="fa-solid fa-star"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="td-actions" style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-ghost btn-sm" title="View Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.plans.index', $product->id) }}" class="btn btn-ghost btn-sm" title="Pricing Plans" style="color: var(--accent);">
                                        <i class="fa-solid fa-tags"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline btn-sm" title="Edit Product">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete product \'{{ $product->name }}\'? This will archive its configuration.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Delete Product">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px 20px;">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; font-size: 28px; color: var(--text-muted);">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <div style="font-size: 16px; font-weight: 700; color: var(--text);">No Products Found</div>
                                    <p style="font-size: 13px; color: var(--text-muted); max-width: 360px; margin: 0;">
                                        @if(!empty($filters['search']) || !empty($filters['category_id']) || !empty($filters['product_type']) || isset($filters['is_active']))
                                            No software products match your current filter criteria. Try resetting filters.
                                        @else
                                            No software products have been configured yet. Start adding your first SaaS solution.
                                        @endif
                                    </p>
                                    <div style="margin-top: 8px;">
                                        @if(!empty($filters['search']) || !empty($filters['category_id']) || !empty($filters['product_type']) || isset($filters['is_active']))
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Clear Filters</a>
                                        @else
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fa-solid fa-plus mr-1"></i> Add First Product
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div class="text-xs text-muted">
                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </div>
                <div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
