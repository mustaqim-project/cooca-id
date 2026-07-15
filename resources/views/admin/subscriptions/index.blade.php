@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('subtitle', 'Manage customer subscriptions')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:320px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search customer, product...">
            </div>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="subsTable">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Expires At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-card-icon blue"
                                            style="width:36px;height:36px;min-width:36px;font-size:.85rem">
                                            {{ strtoupper(substr($subscription->customer->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $subscription->customer_id ?? 0) }}"
                                                class="fw-semibold text-decoration-none">
                                                {{ $subscription->customer->name ?? 'Unknown Customer' }}
                                            </a>
                                            <div class="small text-muted">{{ $subscription->customer->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $subscription->product->name ?? 'Unknown Product' }}</span>
                                    <div class="small text-muted font-monospace">ID: {{ substr($subscription->id, 0, 8) }}…
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeMap = [
                                            'active' => 'success',
                                            'trial' => 'info',
                                            'expired' => 'danger',
                                            'cancelled' => 'neutral',
                                        ];
                                        $badge = $badgeMap[$subscription->status] ?? 'neutral';
                                    @endphp
                                    <span
                                        class="badge-saas badge-saas-{{ $badge }}">{{ ucfirst($subscription->status) }}</span>
                                </td>
                                <td>
                                    {{ $subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y') : 'Lifetime' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm">
                                        <i class="bi bi-eye me-1"></i>Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-card-checklist"></i></div>
                                        <div class="empty-state-title">No subscriptions found</div>
                                        <div class="empty-state-description">Subscriptions appear here when customers
                                            purchase a product.</div>
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
            document.querySelectorAll('#subsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
