@extends('layouts.admin')

@section('title', 'Product Catalog — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Products</span>
        </div>
        <h1 class="page-title">Products Management</h1>
        <p class="page-subtitle">Manage SaaS software products, modules, pricing tiers, and licensing specifications.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <span>➕</span> Add New Product
        </a>
    </div>
</div>

<div class="filter-bar">
    <div class="filter-search">
        <span class="filter-search-icon">🔍</span>
        <input type="text" placeholder="Filter by product name, slug, or tech stack...">
    </div>
    <select class="form-select" style="width: 180px;">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    <select class="form-select" style="width: 150px;">
        <option value="">All Statuses</option>
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product Details</th>
                        <th>Category</th>
                        <th>Base Price</th>
                        <th>Plans</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md" style="background: linear-gradient(135deg, #4F46E5, #06B6D4); font-size: 16px;">
                                        💻
                                    </div>
                                    <div>
                                        <div class="font-bold text-base">{{ $product->name }}</div>
                                        <div class="text-xs text-muted">{{ Str::limit($product->short_description ?? $product->description, 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-purple">{{ $product->category->name ?? 'General' }}</span>
                            </td>
                            <td class="font-bold text-primary">
                                Rp {{ number_format($product->base_price ?? $product->price ?? 0, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge badge-accent">{{ $product->subscription_plans_count ?? $product->subscriptionPlans->count() }} Tiers</span>
                            </td>
                            <td>
                                @if($product->is_active ?? true)
                                    <span class="badge badge-success"><span class="status-dot active"></span> Active</span>
                                @else
                                    <span class="badge badge-muted"><span class="status-dot inactive"></span> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-ghost btn-sm" title="Edit Product">✏️ Edit</a>
                                    <a href="{{ route('admin.products.plans.index', $product->id) }}" class="btn btn-outline btn-sm" title="Manage Plans">🏷️ Plans</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Delete">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                No products found. <a href="{{ route('admin.products.create') }}" class="text-primary font-semibold">Create one now</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($products, 'hasPages') && $products->hasPages())
        <div class="card-footer">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
