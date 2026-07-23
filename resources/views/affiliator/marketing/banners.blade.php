@extends('affiliator.layouts.app')

@section('title', 'Marketing Banners')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.marketing.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Marketing Banners</h2>
                    <p class="text-secondary mb-0">Embeddable banners for your website or blog.</p>
                </div>
            </div>
        </div>

        <!-- Banners Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="card-header bg-transparent border-bottom border-light p-4">
                <h5 class="fw-bold mb-0 text-dark">Available Banners</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0" style="width: 80px;">ID</th>
                            <th class="py-3 px-3 border-0">Name / Size</th>
                            <th class="py-3 px-3 border-0 text-center">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($banners ?? [] as $banner)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-secondary">
                                    #{{ $banner['id'] }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-semibold text-dark">{{ $banner['name'] }}</div>
                                    <div class="text-secondary fs-7"><i class="bi bi-aspect-ratio me-1"></i> {{ $banner['size'] }}</div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        Ready
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <button onclick="navigator.clipboard.writeText('{{ addslashes($banner['html_code']) }}'); alert('Copied to clipboard!')" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift text-secondary">
                                        <i class="bi bi-code-slash me-1"></i> Copy HTML
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-images fs-1"></i></div>
                                    <h6 class="fw-medium">No Banners Found</h6>
                                    <p class="fs-7 mb-0">There are currently no banners available to embed.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($banners ?? [], 'hasPages') && ($banners ?? [])->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $banners->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
