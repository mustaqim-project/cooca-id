@extends('layouts.admin')
@section('title', 'Products')
@section('subtitle', 'Manage your SaaS products')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div class="input-group shadow-sm" style="max-width: 320px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="searchInput" placeholder="Search products...">
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-medium">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
        </div>
    </div>

    <div class="card card-saas border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price From</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($product->thumbnail)
                                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                                                class="rounded-3 shadow-sm object-fit-cover flex-shrink-0"
                                                style="width:44px;height:44px;">
                                        @else
                                            <div class="bg-light text-muted rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:44px;height:44px;">
                                                <i class="bi bi-box fs-5"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                class="fw-semibold text-dark text-decoration-none d-block">{{ $product->name }}</a>
                                            @if ($product->slug)
                                                <span class="text-muted fs-sm font-monospace">{{ $product->slug }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span>
                                </td>
                                <td>
                                    @php $minPrice = $product->pricingPlans()->min('price'); @endphp
                                    <span
                                        class="fw-medium">{{ $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Free' }}</span>
                                </td>
                                <td>
                                    @if ($product->is_active)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-medium">Active</span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-medium">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="View">
                                            <i class="bi bi-eye text-muted"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Edit">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="form-confirm-delete m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="Delete">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-box fs-1 text-muted opacity-50 d-block mb-3"></i>
                                        <h6 class="fw-semibold text-dark">No products found</h6>
                                        <p class="text-muted fs-sm mb-3">Create your first product to start selling</p>
                                        <a href="{{ route('admin.products.create') }}"
                                            class="btn btn-primary btn-sm rounded-pill px-4">
                                            <i class="bi bi-plus-lg me-1"></i> Add Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($products) && method_exists($products, 'links') && $products->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#productsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
