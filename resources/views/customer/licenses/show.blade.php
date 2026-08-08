@extends('layouts.customer')
@section('title', 'License Details')
@section('breadcrumb')
    <a href="{{ route('customer.licenses.index') }}" class="crumb-link">Licenses</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-key" style="color:var(--primary);margin-right:10px;"></i>License Details</h1>
        <p class="page-subtitle">ID: {{ $license->id ?? $license['id'] }}</p>
    </div>
    <a href="{{ route('customer.licenses.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid-31">
    <div class="card">
        <div class="card-header">
            <div class="card-title">License Summary</div>
            <span class="badge badge-success">{{ ucfirst($license->status ?? $license['status'] ?? 'active') }}</span>
        </div>
        <div class="card-body">
            <div class="stats-row">
                <span class="text-sm text-muted">License Code</span>
                <code style="font-size:13px;background:var(--bg);padding:4px 8px;border-radius:4px;border:1px solid var(--border);">
                    {{ $license->license_code ?? $license['license_code'] }}
                </code>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Product</span>
                <span class="font-bold text-sm">{{ $license->product->name ?? $license['product']['name'] ?? 'SaaS Module' }}</span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Domain</span>
                <span class="font-bold text-sm">{{ $license->domain ?? $license['domain'] ?? 'Unassigned' }}</span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Activated At</span>
                <span class="text-sm">{{ $license->activated_at ? $license->activated_at->format('d M Y') : 'Not activated' }}</span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Expires At</span>
                <span class="text-sm font-bold">{{ $license->expires_at ? $license->expires_at->format('d M Y') : 'Lifetime' }}</span>
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header"><div class="card-title">Actions</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('customer.licenses.credentials', $license->id ?? $license['id']) }}" class="btn btn-primary w-full" style="justify-content:center;">
                    <i class="fa-solid fa-shield-key"></i> View Credentials
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
