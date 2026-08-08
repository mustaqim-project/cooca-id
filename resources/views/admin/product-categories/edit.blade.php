@extends('layouts.admin')

@section('title', 'Edit Category — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.product-categories.index') }}">Categories</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Product Category</h1>
        <p class="page-subtitle">Configure industry category grouping details.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.product-categories.show', $category->id) }}" class="btn btn-outline">← Back to Detail</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.product-categories.update', $category->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            
            <p class="text-xs text-muted font-bold uppercase mb-3" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Basic Settings</p>

            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Retail Point of Sale">
            </div>
            
            <div class="form-group">
                <label class="form-label">Slug <span class="text-muted" style="font-weight: normal;">(leave blank to auto-generate)</span></label>
                <input type="text" name="slug" class="form-input" placeholder="e.g. retail-pos" value="{{ old('slug', $category->slug ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Icon / Emoji</label>
                <input type="text" name="icon" class="form-input" placeholder="e.g. fa-solid fa-store or 🛒" value="{{ old('icon', $category->icon ?? '') }}">
                <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">Enter a FontAwesome class or a direct emoji.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="4" placeholder="Briefly describe the categories of products in this group...">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Ordering & Status</p>

            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
            </div>

            <div class="form-group" style="margin-top: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $category->is_active ? '1' : '0') === '1' ? 'checked' : '' }}>
                    <label for="is_active" style="cursor: pointer; font-size: 14px; color: var(--text);">Active category (visible in product catalogs)</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection

