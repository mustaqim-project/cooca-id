@extends('affiliator.layouts.app')

@section('title', 'Commissions')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Commissions</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Commissions History</div>
            <div class="page-subtitle">Track your affiliate earnings and commission clearance statuses.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.commissions.stats') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-chart-pie"></i> Statistics
            </a>
            <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-building-columns"></i> Request Withdrawal
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice Ref</th>
                            <th>Customer</th>
                            <th>Tier Level</th>
                            <th class="text-right">Commission Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions ?? [] as $commission)
                            <tr>
                                <td>
                                    <a href="{{ route('affiliator.commissions.show', $commission->id) }}"
                                        class="text-primary font-semibold text-sm">
                                        {{ $commission->transaction->invoice_number ?? ($commission->invoice_number ?? '#COMM-' . $commission->id) }}
                                    </a>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                            {{ strtoupper(substr($commission->customer->name ?? ($commission->transaction?->customer?->name ?? 'C'), 0, 2)) }}
                                        </div>
                                        <span
                                            class="text-sm">{{ $commission->customer->name ?? ($commission->transaction?->customer?->name ?? 'Direct Customer') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary">Level {{ $commission->level ?? 1 }}</span>
                                </td>
                                <td class="text-right font-bold text-sm text-success">
                                    + Rp
                                    {{ number_format($commission->commission_amount ?? ($commission->amount ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $st = $commission->status ?? 'pending';
                                        $badgeClass = match ($st) {
                                            'paid', 'cleared' => 'badge-success',
                                            'pending' => 'badge-warning',
                                            'failed', 'rejected' => 'badge-danger',
                                            default => 'badge-muted',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="text-right text-muted text-xs">
                                    {{ $commission->created_at?->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('affiliator.commissions.show', $commission->id) }}"
                                        class="btn btn-outline btn-sm" style="padding:2px 8px;font-size:11px;">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-receipt"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
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
