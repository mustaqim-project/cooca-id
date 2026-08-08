@extends('affiliator.layouts.app')

@section('title', 'Commissions')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Commissions</span>
@endsection

@section('content')
<div class="portal-card mb-6">
    <div class="portal-card-header" style="flex-wrap:wrap;gap:12px;">
        <div>
            <div class="portal-card-title">
                <i class="fa-solid fa-wallet" style="color:var(--primary);"></i>
                Commissions History
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Track your affiliate earnings and commission clearance statuses.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('affiliator.commissions.stats') }}" class="btn btn-s btn-sm">
                <i class="fa-solid fa-chart-pie"></i> Statistics
            </a>
            <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-p btn-sm">
                <i class="fa-solid fa-building-columns"></i> Request Withdrawal
            </a>
        </div>
    </div>

    <div class="portal-card-body p-0">
        <div class="table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th class="portal-th">Invoice Ref</th>
                        <th class="portal-th">Customer</th>
                        <th class="portal-th">Tier Level</th>
                        <th class="portal-th text-right">Commission Amount</th>
                        <th class="portal-th text-center">Status</th>
                        <th class="portal-th text-right">Date</th>
                        <th class="portal-th text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions ?? [] as $commission)
                        <tr>
                            <td class="portal-td font-medium">
                                <a href="{{ route('affiliator.commissions.show', $commission->id) }}" style="color:var(--primary);font-weight:600;">
                                    {{ $commission->transaction->invoice_number ?? $commission->invoice_number ?? '#COMM-'.$commission->id }}
                                </a>
                            </td>
                            <td class="portal-td">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                        {{ strtoupper(substr($commission->customer->name ?? $commission->transaction?->customer?->name ?? 'C', 0, 2)) }}
                                    </div>
                                    <span>{{ $commission->customer->name ?? $commission->transaction?->customer?->name ?? 'Direct Customer' }}</span>
                                </div>
                            </td>
                            <td class="portal-td">
                                <span class="badge-status status-info">
                                    Level {{ $commission->level ?? 1 }}
                                </span>
                            </td>
                            <td class="portal-td text-right font-bold text-success">
                                + Rp {{ number_format($commission->commission_amount ?? $commission->amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="portal-td text-center">
                                @php
                                    $st = $commission->status ?? 'pending';
                                    $badgeClass = match($st) {
                                        'paid', 'cleared' => 'status-paid',
                                        'pending'          => 'status-pending',
                                        'failed', 'rejected' => 'status-cancelled',
                                        default            => 'status-issued',
                                    };
                                @endphp
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ ucfirst($st) }}
                                </span>
                            </td>
                            <td class="portal-td text-right text-muted" style="font-size:12px;">
                                {{ $commission->created_at?->format('d M Y') }}
                            </td>
                            <td class="portal-td text-center">
                                <a href="{{ route('affiliator.commissions.show', $commission->id) }}" class="btn btn-s btn-sm" style="padding:2px 8px;font-size:11px;">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="portal-td text-center py-6 text-muted">
                                <i class="fa-solid fa-receipt" style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                No commission records found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($commissions ?? [], 'hasPages') && ($commissions ?? [])->hasPages())
            <div style="padding:16px;border-top:1px solid var(--border);">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
