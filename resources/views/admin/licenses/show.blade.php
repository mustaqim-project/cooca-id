@extends('layouts.admin')

@section('title', 'License Key Details — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.licenses.index') }}">Licenses</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">License Details</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.licenses.index') }}" class="btn btn-ghost">← Back</a>
    </div>
</div>

<div class="grid-31">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🔑 License Information</div>
            <span class="badge badge-{{ $license->status === 'active' ? 'success' : 'danger' }}">{{ strtoupper($license->status ?? 'ACTIVE') }}</span>
        </div>
        <div class="card-body flex-col gap-3">
            <div>
                <div class="text-xs text-muted font-bold uppercase">License Code</div>
                <code class="text-lg font-bold text-primary" style="background: var(--bg); padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border); display: inline-block; margin-top: 4px;">
                    {{ $license->license_code ?? $license->license_key ?? 'LIC-XXXX' }}
                </code>
            </div>
            <div class="grid-2 gap-4">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Customer</div>
                    <div class="font-semibold text-sm">{{ $license->customer->name ?? 'N/A' }}</div>
                    <div class="text-xs text-muted">{{ $license->customer->email ?? '' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Product & Plan</div>
                    <div class="font-semibold text-sm">{{ $license->product->name ?? '—' }}</div>
                    <span class="badge badge-purple" style="margin-top: 4px;">{{ $license->subscriptionPlan->name ?? 'Standard' }}</span>
                </div>
            </div>
            <div class="grid-2 gap-4">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Bound Domain</div>
                    <div class="font-semibold text-sm">
                        @if($license->domain)
                            <a href="https://{{ $license->domain }}" target="_blank" class="text-primary font-semibold">
                                🌐 {{ $license->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                            </a>
                        @else
                            <span class="text-muted">Unbound</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">License Type</div>
                    <div class="font-semibold text-sm">
                        @if($license->is_trial)
                            <span class="badge badge-warning">TRIAL LICENSE</span>
                        @else
                            <span class="badge badge-success">PAID LICENSE</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="grid-2 gap-4">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Activated At</div>
                    <div class="font-semibold text-sm">{{ $license->activated_at ? $license->activated_at->format('d M Y, H:i') : '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Expires At</div>
                    <div class="font-bold text-sm">
                        @if($license->expires_at)
                            {{ $license->expires_at->format('d M Y, H:i') }}
                        @else
                            <span class="text-success">Lifetime (Never Expires)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header"><div class="card-title">Administrative Actions</div></div>
            <div class="card-body">
                @if($license->status === 'active')
                    <form action="{{ route('admin.licenses.revoke', $license->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke this license?');">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">Revocation Reason *</label>
                            <input type="text" name="reason" class="form-input" required placeholder="e.g. Refund issued / Non-payment">
                        </div>
                        <input type="hidden" name="category" value="administrative">
                        <button type="submit" class="btn btn-danger w-full">🚫 Revoke License</button>
                    </form>
                @else
                    <p class="text-muted text-sm">No actions available for revoked/expired licenses.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

