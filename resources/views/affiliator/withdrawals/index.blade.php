@extends('affiliator.layouts.app')

@section('title', 'Withdrawals')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Withdrawals</h2>
                <p class="text-secondary mb-0">Manage your payout requests and history.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-wallet2 me-1"></i> Request Withdrawal
                </a>
            </div>
        </div>

        <!-- Withdrawals Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search withdrawals...">
                </div>
                
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm rounded-pill border-light bg-light text-secondary" style="width: 120px;">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Request ID</th>
                            <th class="py-3 px-3 border-0">Date</th>
                            <th class="py-3 px-3 border-0">Account</th>
                            <th class="py-3 px-3 border-0 text-end">Amount</th>
                            <th class="py-3 px-3 border-0 text-center">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($withdrawals ?? [] as $withdrawal)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-dark">
                                    #{{ $withdrawal->id }}
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $withdrawal->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-primary"><i class="bi bi-bank fs-5"></i></div>
                                        <div>
                                            <div class="fw-semibold text-dark" style="font-size: 0.8rem;">{{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }}</div>
                                            <div class="text-secondary fs-7">{{ $withdrawal->account_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 fw-bold text-dark text-end">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @php
                                        $statusClass = match($withdrawal->status) {
                                            'completed', 'paid', 'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected', 'failed', 'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('affiliator.withdrawals.show', $withdrawal->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift text-secondary">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Withdrawals Found</h6>
                                    <p class="fs-7 mb-0">You don't have any withdrawal requests yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($withdrawals ?? [], 'hasPages') && ($withdrawals ?? [])->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
