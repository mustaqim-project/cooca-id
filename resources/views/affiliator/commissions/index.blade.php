@extends('affiliator.layouts.app')

@section('title', 'Commissions')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Commissions</h2>
                <p class="text-secondary mb-0">Track your earnings and commission history.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('affiliator.commissions.stats') }}" class="btn btn-light border rounded-pill px-3 hover-lift fw-medium">
                    <i class="bi bi-bar-chart me-1"></i> Statistics
                </a>
                <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-wallet2 me-1"></i> Request Withdrawal
                </a>
            </div>
        </div>

        <!-- Commissions Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search commissions...">
                </div>
                
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm rounded-pill border-light bg-light text-secondary" style="width: 120px;">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Transaction Ref</th>
                            <th class="py-3 px-3 border-0">Customer</th>
                            <th class="py-3 px-3 border-0">Type</th>
                            <th class="py-3 px-3 border-0 text-end">Amount</th>
                            <th class="py-3 px-3 border-0 text-center">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($commissions ?? [] as $commission)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-dark">
                                    <a href="{{ route('affiliator.commissions.show', $commission->id) }}" class="text-decoration-none hover-lift">
                                        {{ $commission->transaction->invoice_number ?? '-' }}
                                    </a>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($commission->transaction->customer->name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div class="fs-7 fw-medium text-dark">{{ $commission->transaction->customer->name ?? '-' }}</div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 fs-7">
                                        Level {{ $commission->level ?? 1 }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 fw-bold text-success text-end">
                                    + Rp {{ number_format($commission->amount ?? $commission->commission_amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @php
                                        $statusClass = match($commission->status ?? 'pending') {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ ucfirst($commission->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end text-secondary fs-7">
                                    {{ $commission->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-wallet2 fs-1"></i></div>
                                    <h6 class="fw-medium">No Commissions Found</h6>
                                    <p class="fs-7 mb-0">You don't have any commissions yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($commissions ?? [], 'hasPages') && ($commissions ?? [])->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $commissions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
