@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('subtitle', 'Manage customer subscriptions')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div class="input-group shadow-sm" style="max-width: 320px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                placeholder="Search customer, product...">
        </div>
    </div>

    <div class="card card-saas border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="subsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Customer</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Expires At</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                            style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($subscription->customer->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $subscription->customer_id ?? 0) }}"
                                                class="fw-semibold text-dark text-decoration-none d-block">
                                                {{ $subscription->customer->name ?? 'Unknown Customer' }}
                                            </a>
                                            <span class="text-muted fs-sm">{{ $subscription->customer->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="fw-semibold text-dark d-block">{{ $subscription->product->name ?? 'Unknown Product' }}</span>
                                    <span class="text-muted fs-sm font-monospace">ID:
                                        {{ substr($subscription->id, 0, 8) }}…</span>
                                </td>
                                <td>
                                    @php
                                        $badgeMap = [
                                            'active' => ['success', 'Active'],
                                            'trial' => ['info', 'Trial'],
                                            'expired' => ['danger', 'Expired'],
                                            'cancelled' => ['secondary', 'Cancelled'],
                                        ];
                                        [$badgeClass, $badgeLabel] = $badgeMap[$subscription->status] ?? [
                                            'secondary',
                                            ucfirst($subscription->status),
                                        ];
                                    @endphp
                                    <span
                                        class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} px-3 py-2 rounded-pill fw-medium">{{ $badgeLabel }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i
                                            class="bi bi-clock me-1 opacity-50"></i>{{ $subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y') : 'Lifetime' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                        class="btn btn-light btn-sm rounded-pill px-3 fw-medium">
                                        <i class="bi bi-eye me-1 text-primary"></i>Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-card-checklist fs-1 text-muted opacity-50 d-block mb-3"></i>
                                        <h6 class="fw-semibold text-dark">No subscriptions found</h6>
                                        <p class="text-muted fs-sm mb-0">Subscriptions appear here when customers purchase a
                                            product.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($subscriptions) && method_exists($subscriptions, 'links') && $subscriptions->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#subsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
