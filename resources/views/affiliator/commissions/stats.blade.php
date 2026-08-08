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
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Commission Statistics</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Analytics and metrics of your earnings history.</p>
    </div>
    <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Commissions
    </a>
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

<div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));gap:24px;">
    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-chart-pie" style="color:var(--primary);"></i>
                Earnings Breakdown by Product
            </div>
        </div>
        <div class="portal-card-body">
            @if(isset($breakdown) && count($breakdown) > 0)
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($breakdown as $item)
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:600;margin-bottom:6px;">
                                <span>{{ $item->product_name ?? 'Product Purchase' }}</span>
                                <span style="color:var(--primary);">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                            </div>
                            @php
                                $percent = ($total_commission ?? 0) > 0 ? ($item->total / $total_commission) * 100 : 0;
                            @endphp
                            <div style="height:8px;background:var(--border);border-radius:4px;overflow:hidden;">
                                <div style="height:100%;background:var(--primary);width:{{ $percent }}%;border-radius:4px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-muted">
                    <i class="fa-solid fa-chart-pie" style="font-size:32px;color:var(--text-faint);margin-bottom:8px;display:block;"></i>
                    <p style="font-size:13px;margin:0;">No product breakdown data recorded yet.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-bolt" style="color:var(--accent);"></i>
                Quick Actions
            </div>
        </div>
        <div class="portal-card-body" style="display:flex;flex-direction:column;gap:12px;">
            <a href="{{ route('affiliator.withdrawals.create') }}" style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);text-decoration:none;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                <div class="s-icon" style="width:36px;height:36px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:700;color:var(--text);">Request Payout</div>
                    <div style="font-size:11px;color:var(--text-muted);">Transfer cleared balance to your bank account</div>
                </div>
                <i class="fa-solid fa-chevron-right" style="font-size:11px;color:var(--text-faint);"></i>
            </a>

            <a href="{{ route('affiliator.withdrawals.index') }}" style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);text-decoration:none;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                <div class="s-icon" style="width:36px;height:36px;background:rgba(6,182,212,.15);color:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-history"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:700;color:var(--text);">Payout History</div>
                    <div style="font-size:11px;color:var(--text-muted);">View previous withdrawal logs and statuses</div>
                </div>
                <i class="fa-solid fa-chevron-right" style="font-size:11px;color:var(--text-faint);"></i>
            </a>
        </div>
    </div>
</div>
@endsection
