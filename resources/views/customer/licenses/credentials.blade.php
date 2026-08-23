@extends('layouts.customer')
@section('title', 'License Credentials')
@section('breadcrumb')
    <a href="{{ route('customer.licenses.index') }}" class="crumb-link">Licenses</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Credentials</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-key" style="color:var(--primary);margin-right:10px;"></i>License Credentials</h1>
        <p class="page-subtitle">Keep these security keys confidential. Do not share with unauthorized parties.</p>
    </div>
    <a href="{{ route('customer.licenses.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back to Licenses
    </a>
</div>

<div class="card" style="max-width:640px;margin:0 auto;">
    <div class="card-header">
        <div class="card-title">🔐 Key & Token Information</div>
        <span class="badge badge-success">Active</span>
    </div>
    <div class="card-body">
        <div class="alert alert-warning mb-4">
            <i class="fa-solid fa-shield-halved"></i>
            Use these keys to authenticate your application instance against the COOCA.ID License Verification Server.
        </div>

        <div class="form-group">
            <label class="form-label font-semibold">License Code</label>
            <p class="text-xs text-muted mb-2">Kode lisensi utama untuk aktivasi instans ERP COOCA.</p>
            <div class="flex gap-2">
                <input type="text" class="form-input font-mono font-bold text-primary" readonly value="{{ $license->license_code ?? $license['license_code'] ?? '—' }}" id="licCodeInput">
                <button onclick="copyField('licCodeInput')" class="btn btn-primary">Copy Code</button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label font-semibold">License Key (Token Code)</label>
            <p class="text-xs text-muted mb-2">Kunci otentikasi dan token sinkronisasi lisensi dengan server COOCA.ID.</p>
            <div class="flex gap-2">
                <input type="text" class="form-input font-mono font-bold" readonly value="{{ $license->token_code ?? $license['token_code'] ?? '****************' }}" id="tokCodeInput">
                <button onclick="copyField('tokCodeInput')" class="btn btn-outline">Copy Key</button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label font-semibold">Registered Email</label>
            <p class="text-xs text-muted mb-2">Email akun pemilik lisensi pada portal COOCA.ID.</p>
            <div class="flex gap-2">
                <input type="text" class="form-input font-mono font-bold" readonly value="{{ $license->customer->email ?? $license['customer']['email'] ?? auth()->user()->email ?? '—' }}" id="emailInput">
                <button onclick="copyField('emailInput')" class="btn btn-outline">Copy Email</button>
            </div>
        </div>

        <div class="stats-row">
            <span class="text-sm text-muted">Assigned Domain</span>
            <span class="font-bold text-sm">{{ $license->domain ?? $license['domain'] ?? 'Not set (auto-binds on activation)' }}</span>
        </div>
        <div class="stats-row">
            <span class="text-sm text-muted">Product</span>
            <span class="font-bold text-sm">{{ $license->product->name ?? $license['product']['name'] ?? 'COOCA SaaS' }}</span>
        </div>
        <div class="stats-row">
            <span class="text-sm text-muted">Plan</span>
            <span class="font-bold text-sm">{{ $license->subscriptionPlan->name ?? $license['subscription_plan']['name'] ?? 'Standard' }}</span>
        </div>
    </div>
</div>

<script>
function copyField(id) {
    const el = document.getElementById(id);
    navigator.clipboard.writeText(el.value).then(() => {
        alert('Copied to clipboard!');
    });
}
</script>
@endsection
