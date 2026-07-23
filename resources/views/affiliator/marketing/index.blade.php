@extends('affiliator.layouts.app')

@section('title', 'Marketing Materials')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Marketing Materials</h2>
                <p class="text-secondary mb-0">Products and links to promote.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('affiliator.marketing.banners') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-image me-1"></i> Banners
                </a>
            </div>
        </div>

        <!-- Marketing Table -->
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
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0" style="width: 80px;">ID</th>
                            <th class="py-3 px-3 border-0">Name / Title</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Referral Link</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($products ?? [] as $product)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-secondary">
                                    #{{ $product['id'] ?? $product->id ?? '-' }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-semibold text-dark">{{ $product['name'] ?? $product->name ?? 'Product' }}</div>
                                    <div class="text-secondary fs-7 text-truncate" style="max-width: 250px;">{{ $product['description'] ?? $product->description ?? 'Description' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        Active
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button onclick="navigator.clipboard.writeText('{{ $product['referral_link'] ?? '#' }}'); alert('Copied to clipboard!')" class="btn btn-sm btn-light border rounded-circle hover-lift text-secondary" title="Copy Link" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                        <a href="{{ $product['referral_link'] ?? '#' }}" target="_blank" class="btn btn-sm btn-primary rounded-circle hover-lift" title="Open Link" style="width: 32px; height: 32px; padding: 0; line-height: 30px;">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-megaphone fs-1"></i></div>
                                    <h6 class="fw-medium">No Marketing Materials Found</h6>
                                    <p class="fs-7 mb-0">There are currently no products available to promote.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($products ?? [], 'hasPages') && ($products ?? [])->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
