@extends('layouts.customer')
@section('title', 'Free Trials')
@section('breadcrumb')
    <span class="crumb-current">Free Trials</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-flask" style="color:var(--accent);margin-right:10px;"></i>Free Trials</h1>
        <p class="page-subtitle">Test-drive COOCA.ID enterprise modules for 14 days free with no credit card required.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.trials.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Request New Trial
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product / Module</th>
                        <th>Trial Period</th>
                        <th>Status</th>
                        <th>Starts At</th>
                        <th>Expires At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trials ?? [] as $trial)
                    @php $daysLeft = $trial->trial_ends_at ? max(0, now()->diffInDays($trial->trial_ends_at, false)) : null; @endphp
                    <tr>
                        <td class="font-bold text-sm">{{ $trial->product?->name ?? 'COOCA Module' }}</td>
                        <td><span class="badge badge-accent">14-Day Free Trial</span></td>
                            @if($trial->status === 'active_trial')   <span class="badge badge-success">Active</span>
                            @elseif($trial->status === 'trial_expired')<span class="badge badge-danger">Expired</span>
                            @elseif(in_array($trial->status, ['waiting_setup', 'in_setup', 'domain_setup', 'testing']))<span class="badge badge-warning">Approved (Setup In Progress)</span>
                            @elseif($trial->status === 'waiting_approval')<span class="badge badge-info">Waiting Approval</span>
                            @else <span class="badge badge-muted">{{ ucwords(str_replace('_', ' ', $trial->status)) }}</span>
                            @endif
                        </td>
                        <td class="text-xs text-muted">{{ $trial->trial_starts_at?->format('d M Y') ?? '—' }}</td>
                        <td class="text-xs text-muted">
                            {{ $trial->trial_ends_at?->format('d M Y') ?? '—' }}
                            @if($daysLeft !== null && $trial->status === 'active_trial')
                                <span class="text-warning font-bold">({{ $daysLeft }}d left)</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('customer.subscriptions.create') }}" class="btn btn-primary btn-sm">
                                    Convert to Paid
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">🧪</div>
                                <div class="empty-state-title">No Active Trials</div>
                                <div class="empty-state-text">Experience full ERP capabilities risk-free for 14 days.</div>
                                <a href="{{ route('customer.trials.create') }}" class="btn btn-primary">Start 14-Day Trial</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
