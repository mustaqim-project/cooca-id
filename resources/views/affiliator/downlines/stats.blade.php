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
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Downlines Network Statistics</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Performance analytics of sub-affiliates in your multi-tier network.</p>
    </div>
    <a href="{{ route('affiliator.downlines.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Downlines
    </a>
</div>

<div class="portal-card">
    <div class="portal-card-header">
        <div class="portal-card-title">
            <i class="fa-solid fa-chart-line" style="color:var(--primary);"></i>
            Network Analytics
        </div>
    </div>
    <div class="portal-card-body text-center py-6">
        <i class="fa-solid fa-sitemap" style="font-size:48px;color:var(--text-faint);margin-bottom:12px;display:block;"></i>
        <h4 style="font-size:16px;font-weight:700;color:var(--text);">Tier 2 Network Analytics</h4>
        <p style="font-size:13px;color:var(--text-muted);max-width:480px;margin:6px auto 0;">Detailed breakdowns of multi-level commissions earned through your downline sub-affiliates will appear here.</p>
    </div>
</div>
@endsection
