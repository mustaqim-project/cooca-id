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
            <div class="stats-row" style="padding:10px 0;">
                <div>
                    <span class="text-xs text-muted block font-semibold uppercase">License Code</span>
                    <div class="text-xs text-muted">Kode identifikasi lisensi</div>
                </div>
                <div class="flex items-center gap-2">
                    <code style="font-size:13px;background:var(--bg);padding:6px 10px;border-radius:6px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--primary);">
                        {{ $license->license_code ?? $license['license_code'] }}
                    </code>
                    <button type="button" onclick="copyToClipboard('{{ $license->license_code ?? $license['license_code'] }}', 'License Code')" class="btn btn-ghost btn-sm" title="Copy License Code">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
            </div>
            <div class="stats-row" style="padding:10px 0;">
                <div>
                    <span class="text-xs text-muted block font-semibold uppercase">License Key (Token Code)</span>
                    <div class="text-xs text-muted">Kunci otentikasi API lisensi</div>
                </div>
                <div class="flex items-center gap-2">
                    <code style="font-size:13px;background:var(--bg);padding:6px 10px;border-radius:6px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--text);">
                        {{ $license->token_code ?? $license['token_code'] }}
                    </code>
                    <button type="button" onclick="copyToClipboard('{{ $license->token_code ?? $license['token_code'] }}', 'License Key')" class="btn btn-ghost btn-sm" title="Copy License Key">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
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
                <span class="text-sm font-bold">
                    @if($license->status === 'inactive')
                        <span class="text-muted">Belum Aktif</span>
                    @elseif($license->expires_at)
                        {{ $license->expires_at->format('d M Y') }}
                    @else
                        Lifetime
                    @endif
                </span>
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
