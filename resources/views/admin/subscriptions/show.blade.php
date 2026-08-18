@extends('layouts.admin')

@section('title', 'Subscription Details — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Subscription #SUB-{{ substr($subscription->id ?? '', 0, 8) }}</h1>
        <p class="page-subtitle">
            {{ optional($subscription->subscriptionPlan->product ?? null)->name ?? 'Product' }} —
            <span class="badge badge-purple">{{ $subscription->subscriptionPlan->name ?? 'Plan' }}</span>
            <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trial' ? 'warning' : 'danger') }}">{{ strtoupper($subscription->status ?? 'ACTIVE') }}</span>
        </p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-ghost">← Back</a>
    </div>
</div>

<div class="grid-31">
    <div class="flex-col gap-5">

        {{-- Subscription Core Info --}}
        <div class="card">
            <div class="card-header"><div class="card-title">📋 Subscription Info</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Customer</div>
                    <div class="font-bold text-sm">{{ optional($subscription->customer)->name ?? 'N/A' }}</div>
                    <div class="text-xs text-muted">{{ optional($subscription->customer)->email ?? '' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Product</div>
                    <div class="font-bold text-sm">{{ optional($subscription->subscriptionPlan->product ?? null)->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Plan</div>
                    <div class="font-semibold text-sm">{{ $subscription->subscriptionPlan->name ?? 'N/A' }} ({{ $subscription->subscriptionPlan->duration_months ?? 1 }} months)</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Billing Cycle Start</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->starts_at)->format('d M Y') ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Billing Cycle End</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->ends_at)->format('d M Y') ?? 'Infinite' }}</div>
                </div>
            </div>
        </div>

        {{-- Associated License Info --}}
        @if($subscription->license)
        <div class="card">
            <div class="card-header">
                <div class="card-title">🔑 Associated License</div>
                <span class="badge badge-{{ $subscription->license->status === 'active' ? 'success' : ($subscription->license->status === 'inactive' ? 'secondary' : 'danger') }}">
                    {{ strtoupper($subscription->license->status) }}
                </span>
            </div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">License Code</div>
                    <code class="font-bold text-xs" style="background: var(--bg-secondary); color: var(--primary); padding: 4px 8px; border-radius: var(--radius-sm); display: inline-block; margin-top: 2px;">
                        {{ $subscription->license->license_code }}
                    </code>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">License Key (Token Code)</div>
                    <code class="font-bold text-xs" style="background: var(--bg-secondary); color: var(--text); padding: 4px 8px; border-radius: var(--radius-sm); display: inline-block; margin-top: 2px;">
                        {{ $subscription->license->token_code }}
                    </code>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Registered Domain</div>
                    <div class="font-semibold text-sm">
                        @if($subscription->license->domain)
                            <a href="https://{{ $subscription->license->domain }}" target="_blank" style="color: var(--primary); text-decoration: none;">
                                {{ $subscription->license->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                            </a>
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">License Validity</div>
                    <div class="font-semibold text-sm">
                        {{ $subscription->license->starts_at?->format('d M Y') ?? '—' }} s/d {{ $subscription->license->expires_at?->format('d M Y') ?? 'Lifetime' }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Trial Info --}}
        @if($subscription->trial_ends_at)
        <div class="card">
            <div class="card-header"><div class="card-title">🧪 Trial Period</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Trial Ends At</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->trial_ends_at)->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>

    <div class="flex-col gap-5">

        {{-- Renewal & Grace --}}
        <div class="card">
            <div class="card-header"><div class="card-title">🔁 Renewal & Grace Period</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Auto-Renewal</div>
                    <div class="font-semibold text-sm">
                        @if($subscription->auto_renew)
                            <span class="badge badge-success">Enabled</span>
                        @else
                            <span class="badge badge-muted">Disabled</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Grace Period (Days)</div>
                    <div class="font-semibold text-sm">{{ $subscription->grace_period_days ?? 0 }} days</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Grace Ends At</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->grace_ends_at)->format('d M Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Next Billing Date</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->next_billing_date)->format('d M Y') ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Suspension Info --}}
        @if($subscription->status === 'suspended' || $subscription->suspended_at)
        <div class="card">
            <div class="card-header"><div class="card-title">⛔ Suspension Info</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Suspended At</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->suspended_at)->format('d M Y, H:i') ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Suspension Reason</div>
                    <div class="text-sm">{{ $subscription->suspension_reason ?? 'Not specified' }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Cancellation Info --}}
        @if($subscription->cancelled_at || $subscription->cancellation_reason)
        <div class="card">
            <div class="card-header"><div class="card-title">❌ Cancellation Info</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Cancelled At</div>
                    <div class="font-semibold text-sm">{{ optional($subscription->cancelled_at)->format('d M Y, H:i') ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Cancellation Reason</div>
                    <div class="text-sm">{{ $subscription->cancellation_reason ?? 'Not specified' }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
