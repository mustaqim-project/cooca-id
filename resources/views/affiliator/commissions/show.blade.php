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
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <div>
                <div class="page-title">Commission #{{ $commission->id }}</div>
                <div class="page-subtitle">Invoice Ref:
                    {{ $commission->transaction?->invoice_number ?? ($commission->invoice_number ?? '-') }}</div>
            </div>
        </div>
        <div class="page-actions">
            @php
                $st = $commission->status ?? 'pending';
                $badgeClass = match ($st) {
                    'paid', 'cleared' => 'badge-success',
                    'pending' => 'badge-warning',
                    'failed', 'rejected' => 'badge-danger',
                    default => 'badge-muted',
                };
            @endphp
            <span class="badge {{ $badgeClass }}" style="font-size:13px;padding:6px 14px;">
                {{ ucfirst($st) }}
            </span>
        </div>
    </div>

    <div class="grid-31" style="gap:24px;">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Commission Overview --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-circle-info"
                            style="color:var(--primary);margin-right:8px;"></i>Commission Overview</div>
                </div>
                <div class="card-body">
                    <div class="grid-3 mb-4" style="gap:16px;">
                        <div
                            style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">
                                Commission Earned</div>
                            <div style="font-size:22px;font-weight:800;color:var(--success);margin-top:4px;">
                                + Rp
                                {{ number_format($commission->commission_amount ?? ($commission->amount ?? 0), 0, ',', '.') }}
                            </div>
                            <div style="font-size:11px;color:var(--text-faint);margin-top:2px;">Tier Level
                                {{ $commission->level ?? 1 }}</div>
                        </div>

                        <div
                            style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">
                                Customer</div>
                            <div style="font-size:15px;font-weight:700;color:var(--text);margin-top:4px;">
                                {{ $commission->customer?->name ?? ($commission->transaction?->customer?->name ?? 'Unknown Customer') }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                                {{ $commission->customer?->email ?? ($commission->transaction?->customer?->email ?? 'N/A') }}
                            </div>
                        </div>

                        <div
                            style="background:var(--bg);padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);">
                                Created Date</div>
                            <div style="font-size:15px;font-weight:700;color:var(--text);margin-top:4px;">
                                {{ $commission->created_at?->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary" style="margin-bottom:0;">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Commissions are held for a 14-day clearance verification period before transferring into your
                            withdrawable wallet balance.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div style="display:flex;flex-direction:column;gap:24px;">

            {{-- Purchase Summary --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-file-invoice"
                            style="color:var(--accent);margin-right:8px;"></i>Purchase Summary</div>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="stats-row" style="padding:14px 24px;">
                        <span class="text-sm text-muted">Product</span>
                        <span
                            class="text-sm font-semibold">{{ $commission->transaction?->product?->name ?? 'Subscription Order' }}</span>
                    </div>
                    <div class="stats-row" style="padding:14px 24px;">
                        <span class="text-sm text-muted">Order Amount</span>
                        <span class="text-sm font-semibold">Rp
                            {{ number_format($commission->transaction?->amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="stats-row" style="padding:14px 24px;">
                        <span class="text-sm text-muted">Transaction Date</span>
                        <span
                            class="text-sm font-semibold">{{ $commission->transaction?->created_at?->format('d M Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
