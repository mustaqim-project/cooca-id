@extends('layouts.admin')
@section('title', 'Products')
@section('subtitle', 'Manage your SaaS products')
@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.products.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
        </div>
    </div>
    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="productsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price From</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($product->thumbnail)
                                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                                                style="width:40px;height:40px;object-fit:cover;border-radius:8px;flex-shrink:0">
                                        @else
                                            <div
                                                style="width:40px;height:40px;border-radius:8px;background:var(--bg-tertiary);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                <i class="bi bi-box" style="color:var(--text-muted)"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                style="font-weight:500;color:var(--text-primary);text-decoration:none">{{ $product->name }}</a>
                                            @if ($product->slug)
                                                <div style="font-size:.75rem;color:var(--text-muted)">{{ $product->slug }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>
                                    @php $minPrice = $product->pricingPlans()->min('price'); @endphp
                                    {{ $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Free' }}
                                </td>
                                <td>
                                    @if ($product->is_active)
                                        <span class="badge-saas badge-saas-success">Active</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="form-confirm-delete">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-box"></i></div>
                                        <div class="empty-state-title">No products found</div>
                                        <div class="empty-state-description">Create your first product to start selling
                                        </div>
                                        <a href="{{ route('admin.products.create') }}"
                                            class="btn-saas btn-saas-primary mt-3">
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
        @if (isset($products) && method_exists($products, 'links'))
            <div class="card-saas-footer">{{ $products->links() }}</div>
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
