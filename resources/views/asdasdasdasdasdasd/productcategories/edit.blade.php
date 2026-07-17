@extends('layouts.admin')

@section('title', 'Edit Category')
@section('subtitle', 'Update product category details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.product-categories.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Categories
        </a>
    </div>

    <form class="form-confirm-submit" action="{{ route('admin.product-categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            {{-- Main --}}
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><span class="card-saas-title">Category Details</span></div>
                    <div class="card-saas-body">

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="name">Category Name <span
                                    class="text-danger">*</span></label>
                            <input class="form-saas-input @error('name') is-invalid @enderror" type="text" name="name"
                                id="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="slug">Slug</label>
                            <input class="form-saas-input @error('slug') is-invalid @enderror" type="text" name="slug"
                                id="slug" value="{{ old('slug', $category->slug) }}">
                            <div class="form-saas-hint">URL-friendly version of the name. Must be unique.</div>
                            @error('slug')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="description">Description</label>
                            <textarea class="form-saas-textarea @error('description') is-invalid @enderror" name="description" id="description"
                                rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="icon">Icon Class</label>
                                    <input class="form-saas-input @error('icon') is-invalid @enderror" type="text"
                                        name="icon" id="icon" value="{{ old('icon', $category->icon) }}"
                                        placeholder="e.g. bi bi-star">
                                    <div class="form-saas-hint">Bootstrap Icon class name.</div>
                                    @error('icon')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="sort_order">Sort Order</label>
                                    <input class="form-saas-input @error('sort_order') is-invalid @enderror" type="number"
                                        name="sort_order" id="sort_order"
                                        value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    <div class="form-saas-hint">Lower numbers appear first.</div>
                                    @error('sort_order')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card-saas">
                    <div class="card-saas-header"><span class="card-saas-title">Settings</span></div>
                    <div class="card-saas-body">
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_active">Active</label>
                            <div class="text-muted" style="font-size:0.82rem">Visible on the store.</div>
                        </div>

                        <hr style="border-color:var(--border)">

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.product-categories.show', $category->id) }}"
                                class="btn-saas btn-saas-ghost w-100">Cancel</a>
                        </div>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="card-saas mt-4" style="border-color:#fecaca">
                    <div class="card-saas-header" style="background:#fef2f2">
                        <span class="card-saas-title" style="color:#b91c1c">Danger Zone</span>
                    </div>
                    <div class="card-saas-body">
                        @if (($category->products_count ?? ($category->products->count() ?? 0)) === 0)
                            <form class="form-confirm-delete"
                                action="{{ route('admin.product-categories.destroy', $category->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-saas btn-saas-danger w-100">
                                    <i class="bi bi-trash3 me-1"></i> Delete Category
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn-saas btn-saas-danger w-100" disabled
                                title="Cannot delete: has products">
                                <i class="bi bi-trash3 me-1"></i> Delete Category
                            </button>
                            <div class="text-muted mt-2" style="font-size:0.8rem">Remove all products from this category
                                first.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
