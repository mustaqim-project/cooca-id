@extends('layouts.customer')
@section('title', 'Subscription Details')
@section('breadcrumb')
    <a href="{{ route('customer.subscriptions.index') }}" class="crumb-link">Subscriptions</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-repeat" style="color:var(--primary);margin-right:10px;"></i>Subscription Details</h1>
        <p class="page-subtitle">ID: {{ $subscription->id }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.subscriptions.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
</div>

@php
    $plan = $subscription->subscriptionPlan;
    $lic  = $subscription->license;
    $prod = $lic?->product ?? $plan?->product;
@endphp

<div class="grid-31">
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- Overview Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Subscription Overview</div>
                @if($subscription->status === 'active') <span class="badge badge-success">Active</span>
                @elseif($subscription->status === 'expired') <span class="badge badge-danger">Expired</span>
                @else <span class="badge badge-muted">{{ ucfirst($subscription->status) }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="flex items-center gap-4 mb-4">
                    @if($prod?->logo)
                        <img src="{{ asset($prod->logo) }}" alt="{{ $prod->name }}" style="width:60px;height:60px;border-radius:12px;object-fit:contain;border:1px solid var(--border);padding:4px;">
                    @else
                        <div class="product-logo-placeholder" style="width:60px;height:60px;font-size:24px;border-radius:12px;">
                            {{ strtoupper(substr($prod?->name ?? 'P', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-xl">{{ $prod?->name ?? 'Product Subscription' }}</div>
                        <div class="text-sm text-muted">{{ $plan?->name ?? 'Subscription Plan' }}</div>
                    </div>
                </div>

                <div class="grid-3 mb-4">
                    <div style="background:var(--bg);padding:14px;border-radius:var(--radius);text-align:center;">
                        <div class="text-xs text-muted">Billing Cycle</div>
                        <div class="font-bold text-base mt-1">{{ ucfirst($plan?->billing_cycle ?? 'Monthly') }}</div>
                    </div>
                    <div style="background:var(--bg);padding:14px;border-radius:var(--radius);text-align:center;">
                        <div class="text-xs text-muted">Price</div>
                        <div class="font-bold text-base mt-1">Rp {{ number_format($plan?->price ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div style="background:var(--bg);padding:14px;border-radius:var(--radius);text-align:center;">
                        <div class="text-xs text-muted">Expires At</div>
                        <div class="font-bold text-base mt-1">{{ $subscription->expires_at?->format('d M Y') ?? 'Lifetime' }}</div>
                    </div>
                </div>

                @if($lic)
                <div class="divider"></div>
                <div class="font-bold text-sm mb-3 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-key" style="color:var(--primary);"></i> Informasi Lisensi & Akses ERP
                    </span>
                    <a href="{{ route('customer.licenses.credentials', $lic->id) }}" class="btn btn-ghost btn-xs" style="font-size:11px;">
                        <i class="fa-solid fa-shield-halved"></i> Full Credentials
                    </a>
                </div>

                <div class="stats-row" style="padding:10px 0;">
                    <div>
                        <span class="text-xs text-muted block font-semibold uppercase">License Code</span>
                        <div class="text-xs text-muted">Gunakan kode ini saat aktivasi instans ERP</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <code style="font-size:13px;background:var(--bg);padding:6px 10px;border-radius:6px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--primary);">
                            {{ $lic->license_code }}
                        </code>
                        <button type="button" onclick="copyToClipboard('{{ $lic->license_code }}', 'License Code')" class="btn btn-ghost btn-sm" title="Copy License Code">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="stats-row" style="padding:10px 0;">
                    <div>
                        <span class="text-xs text-muted block font-semibold uppercase">License Key (Token Code)</span>
                        <div class="text-xs text-muted">Kunci otentikasi lisensi & sinkronisasi API</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <code style="font-size:13px;background:var(--bg);padding:6px 10px;border-radius:6px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--text);">
                            {{ $lic->token_code }}
                        </code>
                        <button type="button" onclick="copyToClipboard('{{ $lic->token_code }}', 'License Key')" class="btn btn-ghost btn-sm" title="Copy License Key">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="stats-row" style="padding:10px 0;">
                    <span class="text-sm text-muted">Domain Terdaftar</span>
                    <span class="font-bold text-sm">
                        @if($lic->domain)
                            <a href="https://{{ $lic->domain }}" target="_blank" style="color:var(--primary);text-decoration:none;">
                                {{ $lic->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                            </a>
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </span>
                </div>

                <div class="stats-row" style="padding:10px 0;">
                    <span class="text-sm text-muted">Status Lisensi</span>
                    <span>
                        @if($lic->status === 'active') <span class="badge badge-success">Active</span>
                        @elseif($lic->status === 'inactive') <span class="badge badge-muted">Inactive</span>
                        @elseif($lic->status === 'expired') <span class="badge badge-danger">Expired</span>
                        @elseif($lic->status === 'revoked') <span class="badge badge-danger">Revoked</span>
                        @else <span class="badge badge-muted">{{ ucfirst($lic->status) }}</span>
                        @endif
                    </span>
                </div>

                <div class="stats-row" style="padding:10px 0;">
                    <span class="text-sm text-muted">Masa Berlaku Lisensi</span>
                    <span class="text-sm font-semibold">
                        @if($lic->status === 'inactive')
                            <span class="text-muted">Belum Aktif</span>
                        @elseif($lic->expires_at)
                            {{ $lic->starts_at?->format('d M Y') ?? '—' }} s/d {{ $lic->expires_at->format('d M Y') }}
                        @else
                            Lifetime
                        @endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Management Actions</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                @if($lic)
                    <a href="{{ route('customer.licenses.credentials', $lic->id) }}" class="btn btn-outline w-full" style="justify-content:center;">
                        <i class="fa-solid fa-shield-key"></i> View Credentials
                    </a>
                @endif
                @if($subscription->status === 'active')
                    <form method="POST" action="{{ route('customer.subscriptions.renew', $subscription->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full" style="justify-content:center;">
                            <i class="fa-solid fa-rotate"></i> Renew Subscription
                        </button>
                    </form>
                    <form method="POST" action="{{ route('customer.subscriptions.cancel', $subscription->id) }}" onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                        @csrf
                        <button type="submit" class="btn btn-danger-outline w-full" style="justify-content:center;">
                            <i class="fa-solid fa-ban"></i> Cancel Subscription
                        </button>
                    </form>
                @endif
                @if($lic?->domain)
                    <a href="https://{{ $lic->domain }}" target="_blank" class="btn btn-outline w-full" style="justify-content:center;">
                        <i class="fa-solid fa-rocket"></i> Launch Application
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, label) {
    if (!navigator.clipboard) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast(label + ' copied to clipboard!');
        return;
    }
    navigator.clipboard.writeText(text).then(() => {
        showToast(label + ' copied to clipboard!');
    });
}

function showToast(msg) {
    const el = document.createElement('div');
    el.className = 'toast-wrap';
    el.innerHTML = '<div class="toast toast-success"><span class="toast-icon"><i class="fa-solid fa-check" style="color:var(--success);"></i></span><div><div class="toast-title">Copied!</div><div class="toast-msg">' + msg + '</div></div></div>';
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
}
</script>
@endsection
