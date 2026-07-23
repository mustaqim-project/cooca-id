@extends('customer.layouts.app')

@section('title', 'My Licenses')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">My Licenses</h2>
                <p class="text-secondary mb-0">Manage and view your product licenses.</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Licenses Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search licenses...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Product</th>
                            <th class="py-3 px-3 border-0">License Key</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Activated At</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($licenses as $license)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-key"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $license->product->name ?? 'Unknown Product' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-monospace bg-light rounded px-2 py-1 d-inline-block text-secondary fs-7 border border-light">
                                        {{ substr($license->license_key, 0, 8) }}...{{ substr($license->license_key, -4) }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusClass = match($license->status) {
                                            'active' => 'success',
                                            'suspended' => 'danger',
                                            'expired' => 'warning',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ ucfirst($license->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $license->activated_at ? \Carbon\Carbon::parse($license->activated_at)->format('M d, Y') : 'Not activated yet' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    @if($license->status == 'active')
                                        <a href="{{ route('customer.licenses.credentials', $license->id) }}"
                                            class="btn btn-sm btn-outline-success rounded-pill px-3 hover-lift me-2">
                                            <i class="bi bi-shield-lock me-1"></i> API Auth
                                        </a>
                                    @endif
                                    <a href="{{ route('customer.licenses.show', $license->id) }}"
                                        class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                                        Details <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Licenses Found</h6>
                                    <p class="fs-7">You don't have any licenses yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($licenses, 'hasPages') && $licenses->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $licenses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
