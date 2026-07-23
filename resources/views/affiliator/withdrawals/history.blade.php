@extends('affiliator.layouts.app')

@section('title', 'Withdrawal History')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Withdrawal History</h2>
                    <p class="text-secondary mb-0">View all your past and pending payout requests.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-wallet2 me-1"></i> Request Payout
                </a>
            </div>
        </div>

        <!-- History Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="card-header bg-transparent border-bottom border-light p-4">
                <h5 class="fw-bold mb-0 text-dark">All Payouts</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Date Requested</th>
                            <th class="py-3 px-3 border-0">Amount</th>
                            <th class="py-3 px-3 border-0">Method</th>
                            <th class="py-3 px-3 border-0 text-center">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Details</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</div>
                                    @if($withdrawal->processed_at)
                                        <div class="text-secondary fs-7">Processed: {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 fw-bold text-dark">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium text-dark" style="font-size: 0.85rem;">{{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }}</div>
                                    @if($withdrawal->account_number)
                                        <div class="text-secondary fs-7">***{{ substr($withdrawal->account_number, -4) }}</div>
                                    @endif
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
                                    <a href="{{ route('affiliator.withdrawals.show', $withdrawal->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift text-primary fw-medium">
                                        View <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                                    <h6 class="fw-medium">No History Found</h6>
                                    <p class="fs-7 mb-0">You haven't made any withdrawal requests yet.</p>
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