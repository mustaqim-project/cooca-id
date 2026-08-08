@extends('affiliator.layouts.app')

@section('title', 'Commission Details')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.commissions.index') }}" class="crumb-link">Commissions</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details</span>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-s btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
            <h2 style="font-size:20px;font-weight:800;color:var(--text);">Commission #{{ $commission->id }}</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Invoice Ref: {{ $commission->transaction?->invoice_number ?? $commission->invoice_number ?? '-' }}</p>
        </div>
    </div>
    <div>
        @php
            $st = $commission->status ?? 'pending';
            $badgeClass = match($st) {
                'paid', 'cleared' => 'status-paid',
                'pending'          => 'status-pending',
                'failed', 'rejected' => 'status-cancelled',
                default            => 'status-issued',
            };
        @endphp
        <span class="badge-status {{ $badgeClass }}" style="font-size:13px;padding:6px 14px;">
            {{ ucfirst($st) }}
        </span>
    </div>
</div>

<div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));gap:24px;">

    {{-- Left Card: Commission & Customer --}}
    <div class="portal-card" style="grid-column: span 2;">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-circle-info" style="color:var(--primary);"></i>
                Commission Overview
            </div>
        </div>
        <div class="portal-card-body">
            <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;" class="mb-4">
                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Commission Earned</div>
                    <div style="font-size:22px;font-weight:800;color:var(--success);margin-top:4px;">
                        + Rp {{ number_format($commission->commission_amount ?? $commission->amount ?? 0, 0, ',', '.') }}
                    </div>
                    <div style="font-size:11px;color:var(--text-faint);margin-top:2px;">Tier Level {{ $commission->level ?? 1 }}</div>
                </div>

                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Customer</div>
                    <div style="font-size:15px;font-weight:700;color:var(--text);margin-top:4px;">
                        {{ $commission->customer?->name ?? $commission->transaction?->customer?->name ?? 'Unknown Customer' }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                        {{ $commission->customer?->email ?? $commission->transaction?->customer?->email ?? 'N/A' }}
                    </div>
                </div>

                <div style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Created Date</div>
                    <div style="font-size:15px;font-weight:700;color:var(--text);margin-top:4px;">
                        {{ $commission->created_at?->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <div style="padding:14px;background:var(--primary-light);border-radius:var(--radius-sm);border:1px solid rgba(var(--primary-rgb),.2);font-size:13px;color:var(--primary);">
                <i class="fa-solid fa-circle-info" style="margin-right:6px;"></i>
                Commissions are held for a 14-day clearance verification period before transferring into your withdrawable wallet balance.
            </div>
        </div>
    </div>

    {{-- Right Card: Original Purchase --}}
    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-file-invoice" style="color:var(--accent);"></i>
                Purchase Summary
            </div>
        </div>
        <div class="portal-card-body">
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                <span style="font-size:13px;color:var(--text-muted);">Product</span>
                <span style="font-size:13px;font-weight:600;">{{ $commission->transaction?->product?->name ?? 'Subscription Order' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                <span style="font-size:13px;color:var(--text-muted);">Order Amount</span>
                <span style="font-size:13px;font-weight:600;">Rp {{ number_format($commission->transaction?->amount ?? 0, 0, ',', '.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;">
                <span style="font-size:13px;color:var(--text-muted);">Transaction Date</span>
                <span style="font-size:13px;font-weight:600;">{{ $commission->transaction?->created_at?->format('d M Y') ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
