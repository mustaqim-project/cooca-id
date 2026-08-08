@extends('affiliator.layouts.app')

@section('title', 'Commission Statistics')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.commissions.index') }}" class="crumb-link">Commissions</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Statistics</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Commission Statistics</div>
            <div class="page-subtitle">Analytics and metrics of your earnings history.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Commissions
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="kpi-grid mb-6">
        <div class="kpi-card kpi-success">
            <div class="kpi-icon success"><i class="fa-solid fa-wallet"></i></div>
            <div class="kpi-value">Rp {{ number_format($total_commission ?? 0, 0, ',', '.') }}</div>
            <div class="kpi-label">Total Commission Earned</div>
            <div class="kpi-trend up"><i class="fa-solid fa-check-circle"></i> Lifetime Total</div>
        </div>

        <div class="kpi-card kpi-primary">
            <div class="kpi-icon primary"><i class="fa-solid fa-building-columns"></i></div>
            <div class="kpi-value">Rp {{ number_format($cleared_commission ?? 0, 0, ',', '.') }}</div>
            <div class="kpi-label">Available to Withdraw</div>
            <div class="kpi-trend up"><i class="fa-solid fa-bolt"></i> Cleared Balance</div>
        </div>

        <div class="kpi-card kpi-warning">
            <div class="kpi-icon warning"><i class="fa-solid fa-hourglass-start"></i></div>
            <div class="kpi-value">Rp {{ number_format($pending_commission ?? 0, 0, ',', '.') }}</div>
            <div class="kpi-label">Pending Clearance</div>
            <div class="kpi-trend"><i class="fa-solid fa-spinner"></i> Clearance Hold</div>
        </div>
    </div>

    <div class="grid-31" style="gap:24px;">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Earnings Breakdown by Product --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-chart-pie"
                            style="color:var(--primary);margin-right:8px;"></i>Earnings Breakdown by Product</div>
                </div>
                <div class="card-body">
                    @if (isset($breakdown) && count($breakdown) > 0)
                        <div style="display:flex;flex-direction:column;gap:16px;">
                            @foreach ($breakdown as $item)
                                <div>
                                    <div
                                        style="display:flex;justify-content:space-between;font-size:13px;font-weight:600;margin-bottom:6px;">
                                        <span>{{ $item->product_name ?? 'Product Purchase' }}</span>
                                        <span style="color:var(--primary);">Rp
                                            {{ number_format($item->total, 0, ',', '.') }}</span>
                                    </div>
                                    @php
                                        $percent =
                                            ($total_commission ?? 0) > 0 ? ($item->total / $total_commission) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width:{{ $percent }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="padding:32px 24px;">
                            <div class="empty-state-icon"><i class="fa-solid fa-chart-pie"
                                    style="color:var(--text-faint);"></i></div>
                            <div class="empty-state-text">No product breakdown data recorded yet.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-bolt"
                            style="color:var(--accent);margin-right:8px;"></i>Quick Actions</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
                    <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-outline"
                        style="justify-content:flex-start;padding:12px;width:100%;">
                        <div class="s-icon"
                            style="width:36px;height:36px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div style="flex:1;text-align:left;">
                            <div style="font-size:13px;font-weight:700;">Request Payout</div>
                            <div style="font-size:11px;color:var(--text-muted);">Transfer cleared balance to your bank
                                account</div>
                        </div>
                        <i class="fa-solid fa-chevron-right" style="font-size:11px;color:var(--text-faint);"></i>
                    </a>

                    <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-outline"
                        style="justify-content:flex-start;padding:12px;width:100%;">
                        <div class="s-icon"
                            style="width:36px;height:36px;background:rgba(6,182,212,.15);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-history"></i>
                        </div>
                        <div style="flex:1;text-align:left;">
                            <div style="font-size:13px;font-weight:700;">Payout History</div>
                            <div style="font-size:11px;color:var(--text-muted);">View previous withdrawal logs and statuses
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right" style="font-size:11px;color:var(--text-faint);"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
