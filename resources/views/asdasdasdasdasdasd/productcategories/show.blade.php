@extends('layouts.admin')

@section('title', 'Category Details')
@section('subtitle', 'View category info and associated products')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.product-categories.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Categories
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.product-categories.edit', $category->id) }}"
                class="btn-saas btn-saas-secondary btn-saas-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            @if (($category->products->count() ?? 0) === 0)
                <form class="form-confirm-delete" action="{{ route('admin.product-categories.destroy', $category->id) }}"
                    method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-saas btn-saas-danger btn-saas-sm">
                        <i class="bi bi-trash3 me-1"></i> Delete
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Category info sidebar --}}
        <div class="col-lg-4">
            <div class="card-saas mb-4">
                <div class="card-saas-body text-center" style="padding-top:2rem;padding-bottom:2rem">
                    @if ($category->icon)
                        <div class="stat-card-icon blue" style="width:64px;height:64px;font-size:1.5rem;margin:0 auto 1rem">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                    @else
                        <div class="stat-card-icon blue" style="width:64px;height:64px;font-size:1.5rem;margin:0 auto 1rem">
                            {{ strtoupper(substr($category->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">{{ $category->name }}</h5>
                    <div class="text-muted font-monospace mb-3" style="font-size:0.8rem">{{ $category->slug }}</div>
                    @if ($category->is_active)
                        <span class="badge-saas badge-saas-success">Active</span>
                    @else
                        <span class="badge-saas badge-saas-neutral">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="card-saas">
                <div class="card-saas-header"><span class="card-saas-title">Info</span></div>
                <div class="card-saas-body">
                    <table class="table table-sm table-borderless mb-0" style="font-size:0.88rem">
                        <tbody>
                            <tr>
                                <td class="text-muted">Description</td>
                                <td>{{ $category->description ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sort Order</td>
                                <td>{{ $category->sort_order ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Created</td>
                                <td>{{ $category->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Updated</td>
                                <td>{{ $category->updated_at->format('d M Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Products list --}}
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <span class="card-saas-title">Products in Category</span>
                    <span class="badge-saas badge-saas-info">{{ $category->products->count() ?? 0 }}</span>
                </div>
                <div class="card-saas-body p-0">
                    @if (isset($category->products) && $category->products->count() > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach ($category->products as $product)
                                <li class="d-flex align-items-center gap-3 px-4 py-3"
                                    style="border-bottom:1px solid var(--border)">
                                    <div class="stat-card-icon purple"
                                        style="width:36px;height:36px;font-size:0.85rem;flex-shrink:0">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-medium">{{ $product->name }}</div>
                                        <div class="text-muted" style="font-size:0.82rem">Rp
                                            {{ number_format($product->price ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                    @if ($product->is_active)
                                        <span class="badge-saas badge-saas-success">Active</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Inactive</span>
                                    @endif
                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="empty-state-title">No products in this category</div>
                            <div class="empty-state-description">
                                <a href="{{ route('admin.products.create') }}" style="color:var(--primary)">Add a
                                    product</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
