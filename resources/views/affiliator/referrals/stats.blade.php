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
    $activeRef = $aff?->referrals()->whereHas('subscriptions', fn($q) => $q->where('status', 'active'))->count() ?? 0;
    $convRate = $totalRef > 0 ? round(($activeRef / $totalRef) * 100, 1) : 0;
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Referral Statistics & Analytics</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Performance metrics for your referral campaign.</p>
    </div>
    <a href="{{ route('affiliator.referrals.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Referrals
    </a>
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

<div class="portal-card">
    <div class="portal-card-header">
        <div class="portal-card-title">
            <i class="fa-solid fa-chart-column" style="color:var(--primary);"></i>
            Monthly Growth Breakdown
        </div>
    </div>
    <div class="portal-card-body text-center py-6">
        <i class="fa-solid fa-chart-area" style="font-size:48px;color:var(--text-faint);margin-bottom:12px;display:block;"></i>
        <h4 style="font-size:16px;font-weight:700;color:var(--text);">Advanced Analytics & Monthly Charts</h4>
        <p style="font-size:13px;color:var(--text-muted);max-width:480px;margin:6px auto 0;">Detailed traffic source tracking and chart visualization metrics will be continuously updated here.</p>
    </div>
</div>
@endsection
