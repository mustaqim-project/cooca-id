@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Products</h2>
                <p class="text-secondary mb-0">Manage services, features, and subscription pricing.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-3 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create Product
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search products...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">#</th>
                            <th class="py-3 px-3 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Type</th>
                            <th class="py-3 px-3 border-0">Category</th>
                            <th class="py-3 px-3 border-0">Price From</th>
                            <th class="py-3 px-3 border-0">Plans</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($products as $product)
                            <tr>
                                <td class="py-3 px-4 text-secondary fs-7">{{ $loop->iteration }}</td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($product->thumbnail)
                                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                                                class="rounded-3 shadow-sm object-fit-cover flex-shrink-0"
                                                style="width:44px;height:44px;">
                                        @else
                                            <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:44px;height:44px;">
                                                <i class="bi bi-box-seam fs-5"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium">{{ $product->name }}</div>
                                            @if ($product->slug)
                                                <div class="text-secondary fs-7 font-monospace">{{ $product->slug }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">{{ $product->product_type_label }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-3 fw-medium">
                                    @php $minPrice = $product->subscriptionPlans->min('price'); @endphp
                                    {{ $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Free' }}
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $product->subscriptionPlans->count() }} Plans
                                </td>
                                <td class="py-3 px-3">
                                    @if ($product->is_active)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.products.show', $product->id) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.products.edit', $product->id) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this product?');">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-box-seam fs-1"></i></div>
                                    <h6 class="fw-medium">No Products Found</h6>
                                    <p class="fs-7">Start by creating your first product or service offering.</p>
                                    <a href="{{ route('admin.products.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3 mt-2">Create Product</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($products) && $products->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
