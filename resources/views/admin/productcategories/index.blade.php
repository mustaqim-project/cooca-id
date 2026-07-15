@extends('layouts.admin')

@section('title', 'Product Categories')
@section('subtitle', 'Manage product groupings and taxonomies')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search category...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.product-categories.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="categoriesTable">
                    <thead>
                        <tr>
                            <th style="width:48px">Icon</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="text-center">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }}" style="font-size:1.25rem;color:var(--primary)"></i>
                                    @else
                                        <div class="stat-card-icon blue"
                                            style="width:32px;height:32px;font-size:0.78rem;margin:0 auto">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $category->name }}</div>
                                    <div class="text-muted font-monospace" style="font-size:0.78rem">{{ $category->slug }}
                                    </div>
                                    @if ($category->description)
                                        <div class="text-muted" style="font-size:0.78rem">
                                            {{ Str::limit($category->description, 50) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-saas badge-saas-info">{{ $category->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if ($category->is_active)
                                        <span class="badge-saas badge-saas-success">Active</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:0.85rem">{{ $category->sort_order ?? 0 }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.product-categories.show', $category->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.product-categories.edit', $category->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if (($category->products_count ?? 0) === 0)
                                            <form class="form-confirm-delete"
                                                action="{{ route('admin.product-categories.destroy', $category->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm"
                                                    style="color:var(--danger)" title="Delete">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-saas btn-saas-ghost btn-saas-sm"
                                                style="opacity:0.3;cursor:not-allowed" title="Cannot delete: has products">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-tags"></i></div>
                                        <div class="empty-state-title">No categories yet</div>
                                        <div class="empty-state-description">
                                            <a href="{{ route('admin.product-categories.create') }}"
                                                style="color:var(--primary)">Create the first category</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#categoriesTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
