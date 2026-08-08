@extends('layouts.admin')

@section('title', 'Category Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.product-categories.index') }}">Categories</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">{{ $category->name ?? 'Category Detail' }}</h1>
        <p class="page-subtitle">View info and products inside this category.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.product-categories.edit', $category->id) }}" class="btn btn-outline">✏️ Edit Category</a>
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-ghost">← Back</a>
    </div>
</div>

<div class="grid-31" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    
    {{-- Products inside Category --}}
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">💻 Products in this Category ({{ $category->products->count() }})</div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product Details</th>
                                <th>Base Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($category->products as $product)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="avatar avatar-md" style="background: linear-gradient(135deg, #4F46E5, #06B6D4); font-size: 16px; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 6px; color: white;">
                                                💻
                                            </div>
                                            <div>
                                                <div class="font-bold text-base">{{ $product->name }}</div>
                                                <div class="text-xs text-muted">{{ Str::limit($product->short_description ?? $product->description, 60) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-bold text-primary">
                                        Rp {{ number_format($product->base_price ?? $product->price ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($product->is_active ?? true)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-muted">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="td-actions" style="display: flex; gap: 8px;">
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-ghost btn-sm">👁️ View</a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline btn-sm">✏️ Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 40px;">No products found in this category.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Metadata Sidebar --}}
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">ℹ️ Category Metadata</div>
            </div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Icon / Emoji</div>
                    <div style="font-size: 24px; margin-top: 4px;">
                        @if($category->icon)
                            @if(str_starts_with($category->icon, 'fa-') || str_starts_with($category->icon, 'fa-solid'))
                                <i class="{{ $category->icon }}" style="color: var(--primary);"></i>
                            @else
                                {{ $category->icon }}
                            @endif
                        @else
                            🏷️
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Slug Path</div>
                    <div class="font-semibold text-sm"><code>/{{ $category->slug }}</code></div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Status</div>
                    <div style="margin-top: 4px;">
                        @if($category->is_active)
                            <span class="badge badge-success">ACTIVE</span>
                        @else
                            <span class="badge badge-muted">INACTIVE</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Sort Order</div>
                    <div class="font-semibold text-sm">{{ $category->sort_order ?? 0 }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Description</div>
                    <div class="text-sm" style="white-space: pre-line; line-height: 1.5; color: var(--text-muted);">
                        {{ $category->description ?? 'No description provided.' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Created Date</div>
                    <div class="font-semibold text-sm">{{ optional($category->created_at)->format('d M Y, H:i') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

