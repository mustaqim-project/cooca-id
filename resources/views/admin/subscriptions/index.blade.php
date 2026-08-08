@extends('layouts.admin')

@section('title', 'Subscriptions — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Subscriptions</span>
        </div>
        <h1 class="page-title">Active Subscriptions</h1>
        <p class="page-subtitle">Track SaaS customer billing cycles, auto-renewals, plan tiers, and trial states.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subscription ID</th>
                        <th>Customer</th>
                        <th>Product & Plan</th>
                        <th>Cycle</th>
                        <th>Status</th>
                        <th>Renews / Expires At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                        <tr>
                            <td><code class="text-primary font-bold">SUB-{{ $sub->id }}</code></td>
                            <td>
                                <div class="font-semibold text-sm">{{ $sub->customer->name ?? 'Client' }}</div>
                                <div class="text-xs text-muted">{{ $sub->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-sm">{{ $sub->product->name ?? 'SaaS Product' }}</div>
                                <span class="badge badge-purple">{{ $sub->subscriptionPlan->name ?? 'Plan' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-muted">{{ $sub->subscriptionPlan->duration_months ?? 1 }} Months</span>
                            </td>
                            <td>
                                @if($sub->status === 'active')
                                    <span class="badge badge-success">ACTIVE</span>
                                @elseif($sub->status === 'trial')
                                    <span class="badge badge-warning">TRIAL</span>
                                @else
                                    <span class="badge badge-danger">{{ strtoupper($sub->status) }}</span>
                                @endif
                            </td>
                            <td class="text-xs text-muted">{{ optional($sub->ends_at)->format('d M Y') ?? 'Infinite' }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.subscriptions.show', $sub->id) }}" class="btn btn-ghost btn-sm">👁️ Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">No subscription records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($subscriptions, 'hasPages') && $subscriptions->hasPages())
        <div class="card-footer">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
@endsection
