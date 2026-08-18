@extends('layouts.customer')
@section('title', 'License Management')
@section('breadcrumb')
    <span class="crumb-current">Licenses</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-key" style="color:var(--primary);margin-right:10px;"></i>License Management</h1>
        <p class="page-subtitle">View, activate, and manage your product license keys.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>License Code & Key</th>
                        <th>Product</th>
                        <th>Plan</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licenses as $license)
                    <tr>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:4px;">
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-muted" style="min-width:32px;font-weight:600;">Code:</span>
                                    <code style="font-size:11px;background:var(--bg);padding:2px 6px;border-radius:4px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--primary);">
                                        {{ $license->license_code }}
                                    </code>
                                    <button type="button" onclick="copyToClipboard('{{ $license->license_code }}', 'License Code')" class="btn btn-ghost btn-xs" title="Copy License Code" style="padding:2px 5px;font-size:10px;">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-muted" style="min-width:32px;font-weight:600;">Key:</span>
                                    <code style="font-size:11px;background:var(--bg);padding:2px 6px;border-radius:4px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--text);">
                                        {{ $license->token_code }}
                                    </code>
                                    <button type="button" onclick="copyToClipboard('{{ $license->token_code }}', 'License Key')" class="btn btn-ghost btn-xs" title="Copy License Key" style="padding:2px 5px;font-size:10px;">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            @if($license->is_trial)
                                <span class="badge badge-purple" style="margin-top:4px;">Trial</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-semibold text-sm">{{ $license->product?->name ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $license->subscriptionPlan?->name ?? '—' }}</span>
                        </td>
                        <td>
                            @if($license->domain)
                                <a href="https://{{ $license->domain }}" target="_blank" class="text-primary text-sm font-semibold">
                                    {{ $license->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                                </a>
                            @else
                                <span class="text-muted text-sm">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            @if($license->status === 'active')    <span class="badge badge-success">Active</span>
                            @elseif($license->status === 'inactive') <span class="badge badge-muted">Inactive</span>
                            @elseif($license->status === 'expired')  <span class="badge badge-danger">Expired</span>
                            @elseif($license->status === 'revoked')  <span class="badge badge-danger">Revoked</span>
                            @endif
                        </td>
                        <td class="text-xs text-muted">
                            @if($license->status === 'inactive')
                                <span class="text-muted">Belum Aktif</span>
                            @elseif($license->expires_at)
                                {{ $license->expires_at->format('d M Y') }}
                                @if($license->expires_at->isPast())
                                    <span class="text-danger font-bold"> (expired)</span>
                                @elseif($license->expires_at->diffInDays() <= 30)
                                    <span class="text-warning font-bold"> ({{ $license->expires_at->diffInDays() }}d left)</span>
                                @endif
                            @else
                                Lifetime
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('customer.licenses.show', $license->id) }}" class="btn btn-ghost btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @if($license->status === 'inactive' && $license->subscription?->status === 'active')
                                    <a href="{{ route('customer.licenses.activate', $license->id) }}" class="btn btn-primary btn-sm">
                                        Activate
                                    </a>
                                @endif
                                @if($license->status === 'expired' || $license->status === 'revoked')
                                    <button onclick="showAppeal('{{ $license->id }}')" class="btn btn-outline btn-sm">
                                        Appeal
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">🔑</div>
                                <div class="empty-state-title">No Licenses Found</div>
                                <div class="empty-state-text">Your license keys will appear here after subscribing to a product.</div>
                                <a href="{{ route('customer.subscriptions.create') }}" class="btn btn-primary">Subscribe Now</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($licenses, 'hasPages') && $licenses->hasPages())
        <div class="card-footer">{{ $licenses->links() }}</div>
    @endif
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // simple feedback
        const el = document.createElement('div');
        el.className = 'toast-wrap';
        el.innerHTML = '<div class="toast toast-success"><span class="toast-icon"><i class="fa-solid fa-check" style="color:var(--success);"></i></span><div><div class="toast-title">Copied!</div><div class="toast-msg">License key copied to clipboard.</div></div></div>';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2500);
    });
}
</script>
@endsection
