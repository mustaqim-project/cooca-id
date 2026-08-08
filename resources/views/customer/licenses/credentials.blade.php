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
            <label class="form-label">License Key</label>
            <div class="flex gap-2">
                <input type="text" class="form-input font-mono" readonly value="{{ $license->license_code ?? $license['license_code'] ?? '—' }}" id="licKeyInput">
                <button onclick="copyField('licKeyInput')" class="btn btn-primary">Copy</button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">API Token Code</label>
            <div class="flex gap-2">
                <input type="text" class="form-input font-mono" readonly value="{{ $license->token_code ?? $license['token_code'] ?? '****************' }}" id="tokCodeInput">
                <button onclick="copyField('tokCodeInput')" class="btn btn-outline">Copy</button>
            </div>
        </div>

        <div class="stats-row">
            <span class="text-sm text-muted">Assigned Domain</span>
            <span class="font-bold text-sm">{{ $license->domain ?? $license['domain'] ?? 'Not set' }}</span>
        </div>
        <div class="stats-row">
            <span class="text-sm text-muted">Product</span>
            <span class="font-bold text-sm">{{ $license->product->name ?? $license['product']['name'] ?? 'COOCA SaaS' }}</span>
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
