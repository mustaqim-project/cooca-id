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
                        <th>Plan Name</th>
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
                    @php $plan = $sub->subscriptionPlan; @endphp
                    <tr>
                        <td class="font-bold text-sm">{{ $plan?->name ?? 'Subscription' }}</td>
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
                        <td colspan="7">
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
@endsection
