@extends('layouts.customer')
@section('title', 'Trial Request Details')
@section('breadcrumb')
    <a href="{{ route('customer.trials.index') }}" class="crumb-link">Free Trials</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-flask" style="color:var(--accent);margin-right:10px;"></i>Trial Request Details</h1>
        <p class="page-subtitle">ID: {{ $trial->id }}</p>
    </div>
    <a href="{{ route('customer.trials.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid-31">
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Request Summary</div>
                @php
                    $labels = \App\Models\ErpRequest::getStatusLabels();
                @endphp
                <span class="badge badge-accent">{{ $labels[$trial->status] ?? ucfirst(str_replace('_', ' ', $trial->status)) }}</span>
            </div>
            <div class="card-body">
                <div class="stats-row">
                    <span class="text-sm text-muted">Requested Product</span>
                    <span class="font-bold text-sm">{{ $trial->product?->name ?? 'COOCA Module' }}</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Requested Subdomain</span>
                    <span class="font-bold text-sm">{{ $trial->requested_subdomain }}.cooca.id</span>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Submitted Date</span>
                    <span class="text-sm">{{ $trial->created_at->format('d M Y H:i') }}</span>
                </div>
                @if($trial->notes)
                <div class="mt-4">
                    <div class="text-xs font-bold text-muted mb-1">Notes:</div>
                    <div class="text-xs text-muted" style="background:var(--bg-secondary);padding:10px 14px;border-radius:var(--radius-sm);border:1px solid var(--border);">{{ $trial->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($trial->license)
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-key" style="color:var(--accent);margin-right:10px;"></i>Informasi Lisensi ERP
                </div>
            </div>
            <div class="card-body">
                <div class="stats-row" style="margin-bottom:12px;">
                    <span class="text-sm text-muted">License Code</span>
                    <code style="font-family:monospace;background:var(--bg);padding:4px 8px;border-radius:4px;word-break:break-all;font-size:12px;font-weight:bold;color:var(--text);display:block;margin-top:4px;">{{ $trial->license->license_code }}</code>
                </div>
                <div class="stats-row" style="margin-bottom:12px;">
                    <span class="text-sm text-muted">Token Code</span>
                    <code style="font-family:monospace;background:var(--bg);padding:4px 8px;border-radius:4px;word-break:break-all;font-size:12px;font-weight:bold;color:var(--text);display:block;margin-top:4px;">{{ $trial->license->token_code }}</code>
                </div>
                <div class="stats-row" style="margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
                    <span class="text-sm text-muted">Domain Terdaftar</span>
                    <span class="font-bold text-sm">
                        <a href="https://{{ $trial->license->domain }}" target="_blank" style="color:var(--accent);text-decoration:none;">
                            {{ $trial->license->domain }} <i class="fa-solid fa-up-right-from-square" style="font-size:10px;"></i>
                        </a>
                    </span>
                </div>
                <div class="stats-row" style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="text-sm text-muted">Masa Berlaku</span>
                    <span class="text-sm font-bold">{{ $trial->license->starts_at?->format('d M Y') ?? '—' }} s/d {{ $trial->license->expires_at?->format('d M Y') ?? '—' }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header"><div class="card-title">Next Steps</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('customer.subscriptions.create') }}" class="btn btn-primary w-full" style="justify-content:center;">
                    Upgrade to Paid Subscription
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
