@extends('affiliator.layouts.app')

@section('title', 'Withdrawals')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Withdrawals</span>
@endsection

@section('content')
<div class="portal-card mb-6">
    <div class="portal-card-header" style="flex-wrap:wrap;gap:12px;">
        <div>
            <div class="portal-card-title">
                <i class="fa-solid fa-building-columns" style="color:var(--primary);"></i>
                Payout Requests & History
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Manage payout requests and track transfer statuses.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-p btn-sm">
                <i class="fa-solid fa-plus"></i> Request Withdrawal
            </a>
        </div>
    </div>

    <div class="portal-card-body p-0">
        <div class="table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th class="portal-th">Request ID</th>
                        <th class="portal-th">Requested Date</th>
                        <th class="portal-th">Bank / Account</th>
                        <th class="portal-th text-right">Amount</th>
                        <th class="portal-th text-center">Status</th>
                        <th class="portal-th text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals ?? [] as $withdrawal)
                        <tr>
                            <td class="portal-td font-medium">
                                #WD-{{ $withdrawal->id }}
                            </td>
                            <td class="portal-td text-muted" style="font-size:12px;">
                                {{ $withdrawal->created_at?->format('d M Y, H:i') }}
                            </td>
                            <td class="portal-td">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-building-columns" style="color:var(--primary);"></i>
                                    <div>
                                        <div style="font-weight:600;font-size:13px;">{{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }}</div>
                                        <div style="font-size:11px;color:var(--text-muted);">{{ $withdrawal->account_number }} ({{ $withdrawal->account_name }})</div>
                                    </div>
                                </div>
                            </td>
                            <td class="portal-td text-right font-bold" style="font-size:14px;">
                                Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                            </td>
                            <td class="portal-td text-center">
                                @php
                                    $st = strtolower($withdrawal->status ?? 'pending');
                                    $badgeClass = match($st) {
                                        'completed', 'paid', 'approved' => 'status-paid',
                                        'pending'                       => 'status-pending',
                                        'rejected', 'failed', 'cancelled' => 'status-cancelled',
                                        default                         => 'status-issued',
                                    };
                                @endphp
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ ucfirst($st) }}
                                </span>
                            </td>
                            <td class="portal-td text-center">
                                <a href="{{ route('affiliator.withdrawals.show', $withdrawal->id) }}" class="btn btn-s btn-sm" style="padding:2px 8px;font-size:11px;">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="portal-td text-center py-6 text-muted">
                                <i class="fa-solid fa-money-bill-transfer" style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
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
