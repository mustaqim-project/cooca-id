@extends('affiliator.layouts.app')

@section('title', 'Withdrawals')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Withdrawals</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Payout Requests & History</div>
            <div class="page-subtitle">Manage payout requests and track transfer statuses.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Request Withdrawal
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Requested Date</th>
                            <th>Bank / Account</th>
                            <th class="text-right">Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals ?? [] as $withdrawal)
                            <tr>
                                <td class="font-semibold text-sm">
                                    #WD-{{ $withdrawal->id }}
                                </td>
                                <td class="text-muted text-xs">
                                    {{ $withdrawal->created_at?->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <i class="fa-solid fa-building-columns" style="color:var(--primary);"></i>
                                        <div>
                                            <div class="font-semibold text-sm">
                                                {{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }}</div>
                                            <div class="text-xs text-muted">{{ $withdrawal->account_number }}
                                                ({{ $withdrawal->account_name }})</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right font-bold text-sm">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $st = strtolower($withdrawal->status ?? 'pending');
                                        $badgeClass = match ($st) {
                                            'completed', 'paid', 'approved' => 'badge-success',
                                            'pending' => 'badge-warning',
                                            'rejected', 'failed', 'cancelled' => 'badge-danger',
                                            default => 'badge-muted',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('affiliator.withdrawals.show', $withdrawal->id) }}"
                                        class="btn btn-outline btn-sm" style="padding:2px 8px;font-size:11px;">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-money-bill-transfer"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    No withdrawal requests recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($withdrawals ?? [], 'hasPages') && ($withdrawals ?? [])->hasPages())
                <div style="padding:16px;border-top:1px solid var(--border);">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
