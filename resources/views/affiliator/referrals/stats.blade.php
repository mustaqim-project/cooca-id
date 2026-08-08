@extends('affiliator.layouts.app')

@section('title', 'Referral Statistics')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.referrals.index') }}" class="crumb-link">My Referrals</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Statistics</span>
@endsection

@section('content')
    @php
        $aff = auth('affiliator')->user() ?? auth()->user();
        $totalRef = $aff?->referrals()->count() ?? 0;
        $activeRef =
            $aff?->referrals()->whereHas('subscriptions', fn($q) => $q->where('status', 'active'))->count() ?? 0;
        $convRate = $totalRef > 0 ? round(($activeRef / $totalRef) * 100, 1) : 0;
    @endphp

    <div class="page-header">
        <div>
            <div class="page-title">Referral Statistics & Analytics</div>
            <div class="page-subtitle">Performance metrics for your referral campaign.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.referrals.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Referrals
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="kpi-grid mb-6">
        <div class="kpi-card kpi-primary">
            <div class="kpi-icon primary"><i class="fa-solid fa-users"></i></div>
            <div class="kpi-value">{{ $totalRef }}</div>
            <div class="kpi-label">Total Referrals</div>
            <div class="kpi-trend up"><i class="fa-solid fa-user-plus"></i> Lifetime Registrations</div>
        </div>
        <div class="kpi-card kpi-success">
            <div class="kpi-icon success"><i class="fa-solid fa-user-check"></i></div>
            <div class="kpi-value">{{ $activeRef }}</div>
            <div class="kpi-label">Active Paying Referrals</div>
            <div class="kpi-trend up"><i class="fa-solid fa-circle-check"></i> Active Subscribers</div>
        </div>
        <div class="kpi-card kpi-accent">
            <div class="kpi-icon accent"><i class="fa-solid fa-chart-line"></i></div>
            <div class="kpi-value">{{ $convRate }}%</div>
            <div class="kpi-label">Conversion Rate</div>
            <div class="kpi-trend up"><i class="fa-solid fa-bolt"></i> Signup to Paid</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-chart-column"
                    style="color:var(--primary);margin-right:8px;"></i>Monthly Growth Breakdown</div>
        </div>
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-chart-area" style="color:var(--text-faint);"></i></div>
                <div class="empty-state-title">Advanced Analytics & Monthly Charts</div>
                <div class="empty-state-text">Detailed traffic source tracking and chart visualization metrics will be
                    continuously updated here.</div>
            </div>
        </div>
    </div>
@endsection
