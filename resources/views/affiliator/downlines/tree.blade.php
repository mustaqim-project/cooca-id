@extends('affiliator.layouts.app')

@section('title', 'Downlines Tree')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.downlines.index') }}" class="crumb-link">Downlines</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Network Tree</span>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:var(--text);">Affiliate Network Tree</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Visual hierarchy of your direct and indirect referral network.</p>
    </div>
    <a href="{{ route('affiliator.downlines.index') }}" class="btn btn-s btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Downlines
    </a>
</div>

<div class="portal-card">
    <div class="portal-card-header">
        <div class="portal-card-title">
            <i class="fa-solid fa-folder-tree" style="color:var(--primary);"></i>
            Network Tree
        </div>
    </div>
    <div class="portal-card-body">
        @php
            $me = auth('affiliator')->user() ?? auth()->user();
            $dlines = $me?->downlines() ? $me->downlines()->withCount('referrals')->get() : collect();
        @endphp

        <div style="padding:16px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);max-width:600px;margin:0 auto;">
            <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface);border:1px solid var(--primary);border-radius:var(--radius-sm);">
                <div class="user-avatar" style="width:36px;height:36px;font-size:13px;">
                    {{ strtoupper(substr($me?->name ?? 'M', 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;">{{ $me?->name }} (You - Root)</div>
                    <div style="font-size:12px;color:var(--text-muted);">Referral Code: {{ $me?->referral_code }}</div>
                </div>
                <span class="badge-status status-paid" style="margin-left:auto;">Tier 1 Partner</span>
            </div>

            @if($dlines->count() > 0)
                <div style="margin-left:24px;padding-left:16px;border-left:2px dashed var(--primary);margin-top:16px;display:flex;flex-direction:column;gap:12px;">
                    @foreach($dlines as $dl)
                        <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);">
                            <i class="fa-solid fa-turn-up" style="transform:rotate(90deg);color:var(--text-faint);font-size:12px;"></i>
                            <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                {{ strtoupper(substr($dl->name, 0, 2)) }}
                            </div>
                            <div style="flex:1;">
                                <div style="font-size:13px;font-weight:600;">{{ $dl->name }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ $dl->referrals_count }} Customers</div>
                            </div>
                            <span class="badge-status status-info">Tier 2 Sub-Affiliate</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="margin-top:16px;text-align:center;color:var(--text-muted);font-size:13px;padding:16px;">
                    No sub-affiliates registered under your tier 2 network yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
