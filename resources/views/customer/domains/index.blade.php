@extends('customer.layouts.app')

@section('title', 'My Domains')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">My Domains</h2>
                <p class="text-secondary mb-0">Manage custom domains for your deployed products.</p>
            </div>
        </div>

        <!-- Domains Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Current Subdomain</th>
                            <th class="py-3 px-3 border-0">Custom Domain</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($tenants as $tenant)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-semibold">{{ $tenant->product->name ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $tenant->subdomain }}.cooca.id
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    @if($tenant->custom_domain)
                                        <span class="text-dark">{{ $tenant->custom_domain }}</span>
                                    @else
                                        <span class="text-secondary fst-italic">Not Set</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if ($tenant->status === 'active')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                            {{ ucfirst($tenant->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-2" data-bs-toggle="modal" data-bs-target="#editDomainModal-{{ $tenant->id }}">
                                        Edit Domain
                                    </button>
                                    @if ($tenant->custom_domain)
                                        <form action="{{ route('customer.domains.verify', $tenant) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 hover-lift">
                                                Verify
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Edit Domain Modal -->
                            <div class="modal fade" id="editDomainModal-{{ $tenant->id }}" tabindex="-1" aria-labelledby="editDomainModalLabel-{{ $tenant->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-sm rounded-4 glass">
                                        <div class="modal-header border-bottom border-light p-4">
                                            <h5 class="modal-title fw-bold" id="editDomainModalLabel-{{ $tenant->id }}">Edit Custom Domain</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('customer.domains.update', $tenant) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-secondary">Custom Domain</label>
                                                    <input type="text" name="custom_domain" value="{{ $tenant->custom_domain }}" placeholder="e.g. erp.mycompany.com" required class="form-control bg-light border-light text-secondary py-2">
                                                    <div class="form-text mt-2 text-secondary fs-7">
                                                        Please point your domain's CNAME record to <span class="fw-semibold">{{ $tenant->subdomain }}.cooca.id</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top border-light bg-transparent p-4">
                                                <button type="button" class="btn btn-light border rounded-pill px-4 hover-lift" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-globe fs-1"></i></div>
                                    <h6 class="fw-medium">No Custom Domains Found</h6>
                                    <p class="fs-7">You don't have any active products to manage domains for.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (method_exists($tenants, 'hasPages') && $tenants->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
