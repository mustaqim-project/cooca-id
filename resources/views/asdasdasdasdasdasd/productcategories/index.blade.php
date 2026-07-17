@extends('layouts.admin')

@section('title', 'Product Categories')
@section('subtitle', 'Manage product groupings and taxonomies')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div class="input-group shadow-sm" style="max-width: 320px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="searchInput" placeholder="Search category...">
        </div>
        <div>
            <a href="{{ route('admin.product-categories.create') }}"
                class="btn btn-primary shadow-sm rounded-pill px-4 fw-medium">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card card-saas border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="categoriesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 60px;">Icon</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4 text-center">
                                    @if ($category->icon)
                                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 mx-auto d-flex align-items-center justify-content-center"
                                            style="width: 38px; height: 38px;">
                                            <i class="{{ $category->icon }} fs-5"></i>
                                        </div>
                                    @else
                                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 mx-auto d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $category->name }}</div>
                                    <div class="text-muted font-monospace fs-sm">{{ $category->slug }}</div>
                                    @if ($category->description)
                                        <div class="text-muted fs-sm mt-1">{{ Str::limit($category->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-medium">{{ $category->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if ($category->is_active)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-medium">Active</span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-medium">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-muted fw-medium">{{ $category->sort_order ?? 0 }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.product-categories.show', $category->id) }}"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="View">
                                            <i class="bi bi-eye text-muted"></i>
                                        </a>
                                        <a href="{{ route('admin.product-categories.edit', $category->id) }}"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Edit">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </a>
                                        @if (($category->products_count ?? 0) === 0)
                                            <form class="form-confirm-delete m-0"
                                                action="{{ route('admin.product-categories.destroy', $category->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;" title="Delete">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button"
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center opacity-50"
                                                style="width: 32px; height: 32px; cursor: not-allowed;"
                                                title="Cannot delete: has products">
                                                <i class="bi bi-trash3 text-muted"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-tags fs-1 text-muted opacity-50 d-block mb-3"></i>
                                        <h6 class="fw-semibold text-dark">No categories yet</h6>
                                        <p class="text-muted fs-sm mb-3">Categories help organize products cleanly.</p>
                                        <a href="{{ route('admin.product-categories.create') }}"
                                            class="btn btn-primary btn-sm rounded-pill px-4">Create First Category</a>
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
