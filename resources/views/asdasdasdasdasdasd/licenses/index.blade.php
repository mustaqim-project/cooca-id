@extends('layouts.admin')

@section('title', 'Licenses')
@section('subtitle', 'Manage software licenses and domain authorizations')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:320px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search license, domain, customer...">
            </div>
        </div>
        <div class="page-toolbar-right">
            {{-- No create button: licenses are generated automatically --}}
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="licensesTable">
                    <thead>
                        <tr>
                            <th>License Key / Domain</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($licenses as $license)
                            <tr>
                                <td>
                                    <code class="text-sm"
                                        title="{{ $license->key }}">{{ substr($license->key, 0, 16) }}…</code>
                                    <div class="small text-muted mt-1">
                                        <i class="bi bi-globe2 me-1"></i>{{ $license->domain ?? 'Unconfigured' }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $license->customer_id ?? 0) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $license->customer->name ?? 'Unknown' }}
                                    </a>
                                    <div class="small text-muted">{{ $license->customer->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $license->product->name ?? 'Unknown Product' }}</span>
                                    @if ($license->subscription_id)
                                        <div class="small">
                                            <a href="{{ route('admin.subscriptions.show', $license->subscription_id) }}"
                                                class="text-decoration-none">
                                                Sub #{{ substr($license->subscription_id, 0, 8) }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeMap = [
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'revoked' => 'danger',
                                            'expired' => 'neutral',
                                        ];
                                        $badge = $badgeMap[$license->status] ?? 'neutral';
                                    @endphp
                                    <span
                                        class="badge-saas badge-saas-{{ $badge }}">{{ ucfirst($license->status) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($license->status === 'active')
                                            <form action="{{ route('admin.licenses.revoke', $license) }}" method="POST"
                                                class="form-confirm-submit">
                                                @csrf
                                                <button type="submit" class="btn-saas btn-saas-danger btn-saas-sm">
                                                    <i class="bi bi-x-lg me-1"></i>Revoke
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.licenses.activate', $license) }}" method="POST"
                                                class="form-confirm-submit">
                                                @csrf
                                                <button type="submit" class="btn-saas btn-saas-primary btn-saas-sm">
                                                    <i class="bi bi-check-lg me-1"></i>Activate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-key"></i></div>
                                        <div class="empty-state-title">No licenses found</div>
                                        <div class="empty-state-description">Licenses are generated automatically when
                                            subscriptions are activated.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#licensesTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
