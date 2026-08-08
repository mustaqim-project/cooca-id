@extends('layouts.admin')

@section('title', 'Product Categories — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Categories</span>
        </div>
        <h1 class="page-title">Product Categories</h1>
        <p class="page-subtitle">Group applications by Industry solutions (e.g. Retail POS, F&B, ERP, Accounting).</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
            <span>➕</span> Add Category
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.product-categories.index') }}" class="filter-bar" style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center;">
    <div class="filter-search" style="flex: 1; display: flex; align-items: center; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 6px; padding: 0 12px;">
        <span class="filter-search-icon" style="margin-right: 8px;">🔍</span>
        <input type="text" name="search" placeholder="Search categories by name or description..." value="{{ $filters['search'] ?? '' }}" style="border: none; background: transparent; padding: 10px 0; width: 100%; outline: none; color: var(--text);">
    </div>
    <select name="status" class="form-select" style="width: 160px; padding: 10px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text);">
        <option value="">All Statuses</option>
        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active Only</option>
        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
    </select>
    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Filter</button>
    @if(!empty($filters['search']) || !empty($filters['status']))
        <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline" style="padding: 10px 20px;">Reset</a>
    @endif
</form>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Products Count</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories ?? [] as $cat)
                        @php $cObj = is_array($cat) ? (object)$cat : $cat; @endphp
                        <tr>
                            <td class="font-bold text-base">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="avatar avatar-md" style="background: var(--bg-muted); color: var(--primary); font-size: 18px; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 6px;">
                                        @if($cObj->icon)
                                            @if(str_starts_with($cObj->icon, 'fa-') || str_starts_with($cObj->icon, 'fa-solid'))
                                                <i class="{{ $cObj->icon }}"></i>
                                            @else
                                                {{ $cObj->icon }}
                                            @endif
                                        @else
                                            🏷️
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.product-categories.show', $cObj->id) }}" class="text-primary font-bold" style="text-decoration: none;">
                                            {{ $cObj->name ?? 'Category' }}
                                        </a>
                                        <div class="text-xs text-muted" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $cObj->description ?? 'No description provided.' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><code>/{{ $cObj->slug ?? '' }}</code></td>
                            <td>
                                <span class="badge badge-purple" style="font-weight: bold;">
                                    {{ $cObj->products_count ?? 0 }} Products
                                </span>
                            </td>
                            <td class="font-semibold">{{ $cObj->sort_order ?? 0 }}</td>
                            <td>
                                @if($cObj->is_active ?? true)
                                    <span class="badge badge-success">ACTIVE</span>
                                @else
                                    <span class="badge badge-muted">INACTIVE</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions" style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.product-categories.show', $cObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ View</a>
                                    <a href="{{ route('admin.product-categories.edit', $cObj->id ?? 1) }}" class="btn btn-outline btn-sm">✏️ Edit</a>
                                    @if(($cObj->products_count ?? 0) === 0)
                                        <form action="{{ route('admin.product-categories.destroy', $cObj->id ?? 1) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: var(--danger); border-color: var(--danger); color: white;">🗑️ Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">No categories configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="card-footer" style="padding: 15px; border-top: 1px solid var(--border);">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection

