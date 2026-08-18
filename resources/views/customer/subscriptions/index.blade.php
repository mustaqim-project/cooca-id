@extends('layouts.customer')
@section('title', 'Subscriptions')
@section('breadcrumb')
    <span class="crumb-current">Subscriptions</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-repeat" style="color:var(--primary);margin-right:10px;"></i>Subscriptions</h1>
        <p class="page-subtitle">Manage your active plans, recurring billing, and renewal dates.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.subscriptions.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Subscription
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Plan & Product</th>
                        <th>License Code & Key</th>
                        <th>Billing Cycle</th>
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>Expiration Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                    @php
                        $plan = $sub->subscriptionPlan;
                        $lic  = $sub->license;
                    @endphp
                    <tr>
                        <td>
                            <div class="font-bold text-sm">{{ $plan?->name ?? 'Subscription' }}</div>
                            <div class="text-xs text-muted">{{ $plan?->product?->name ?? $lic?->product?->name ?? '' }}</div>
                        </td>
                        <td>
                            @if($lic)
                                <div style="display:flex;flex-direction:column;gap:4px;">
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-muted" style="min-width:32px;font-weight:600;">Code:</span>
                                        <code style="font-size:11px;background:var(--bg);padding:2px 6px;border-radius:4px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--primary);">
                                            {{ $lic->license_code }}
                                        </code>
                                        <button type="button" onclick="copyToClipboard('{{ $lic->license_code }}', 'License Code')" class="btn btn-ghost btn-xs" title="Copy License Code" style="padding:2px 5px;font-size:10px;">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-muted" style="min-width:32px;font-weight:600;">Key:</span>
                                        <code style="font-size:11px;background:var(--bg);padding:2px 6px;border-radius:4px;border:1px solid var(--border);font-family:monospace;font-weight:700;color:var(--text);">
                                            {{ $lic->token_code }}
                                        </code>
                                        <button type="button" onclick="copyToClipboard('{{ $lic->token_code }}', 'License Key')" class="btn btn-ghost btn-xs" title="Copy License Key" style="padding:2px 5px;font-size:10px;">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted text-xs">—</span>
                            @endif
                        </td>
                        <td><span class="badge badge-primary">{{ ucfirst($plan?->billing_cycle ?? 'monthly') }}</span></td>
                        <td class="font-bold text-sm">Rp {{ number_format($plan?->price ?? 0, 0, ',', '.') }}</td>
                        <td class="text-xs text-muted">{{ $sub->started_at?->format('d M Y') ?? '—' }}</td>
                        <td class="text-xs text-muted">
                            {{ $sub->expires_at?->format('d M Y') ?? '—' }}
                            @if($sub->expires_at && $sub->expires_at->isPast())
                                <span class="badge badge-danger" style="font-size:10px;">Expired</span>
                            @endif
                        </td>
                        <td>
                            @if($sub->status === 'active')   <span class="badge badge-success">Active</span>
                            @elseif($sub->status === 'trial')  <span class="badge badge-accent">Trial</span>
                            @elseif($sub->status === 'expired')<span class="badge badge-danger">Expired</span>
                            @else <span class="badge badge-muted">{{ ucfirst($sub->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('customer.subscriptions.show', $sub->id) }}" class="btn btn-ghost btn-sm">
                                    <i class="fa-solid fa-eye"></i> Details
                                </a>
                                @if($sub->status === 'active')
                                    <form method="POST" action="{{ route('customer.subscriptions.renew', $sub->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-rotate"></i> Renew
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">🔄</div>
                                <div class="empty-state-title">No Active Subscriptions</div>
                                <div class="empty-state-text">Choose a plan to activate COOCA.ID software modules.</div>
                                <a href="{{ route('customer.subscriptions.create') }}" class="btn btn-primary">Subscribe Now</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($subscriptions, 'hasPages') && $subscriptions->hasPages())
        <div class="card-footer">{{ $subscriptions->links() }}</div>
    @endif
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
