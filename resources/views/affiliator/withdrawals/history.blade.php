@extends('affiliator.layouts.app')

@section('title', 'Withdrawal History')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.withdrawals.index') }}" class="crumb-link">Withdrawals</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">History</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Withdrawal History</div>
            <div class="page-subtitle">Complete record of your payout requests and completions.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Request Payout
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date Requested</th>
                            <th>Amount</th>
                            <th>Method / Account</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td class="text-muted text-xs">
                                    <div class="font-semibold text-sm" style="color:var(--text);">
                                        {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</div>
                                    @if ($withdrawal->processed_at)
                                        <div style="font-size:11px;color:var(--text-faint);">Processed:
                                            {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="font-bold text-sm">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="font-semibold text-sm">
                                        {{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }}</div>
                                    @if ($withdrawal->account_number)
                                        <div class="text-xs text-muted">***{{ substr($withdrawal->account_number, -4) }}
                                        </div>
                                    @endif
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
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-clock-rotate-left"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    No withdrawal history found.
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
