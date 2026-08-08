@extends('affiliator.layouts.app')

@section('title', 'Downlines Statistics')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.downlines.index') }}" class="crumb-link">Downlines</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Statistics</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Downlines Network Statistics</div>
            <div class="page-subtitle">Performance analytics of sub-affiliates in your multi-tier network.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.downlines.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Downlines
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-chart-line"
                    style="color:var(--primary);margin-right:8px;"></i>Network Analytics</div>
        </div>
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-sitemap" style="color:var(--text-faint);"></i></div>
                <div class="empty-state-title">Tier 2 Network Analytics</div>
                <div class="empty-state-text">Detailed breakdowns of multi-level commissions earned through your downline
                    sub-affiliates will appear here.</div>
            </div>
        </div>
    </div>
@endsection
