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
                <div class="font-bold text-sm mb-2">Associated License & Access</div>
                <div class="stats-row">
                    <span class="text-sm text-muted">License Key</span>
                    <code style="font-size:12px;background:var(--bg);padding:4px 8px;border-radius:4px;border:1px solid var(--border);">
                        {{ $lic->license_code }}
                    </code>
                </div>
                <div class="stats-row">
                    <span class="text-sm text-muted">Domain</span>
                    <span class="font-bold text-sm">{{ $lic->domain ?? 'Not set' }}</span>
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
@endsection
