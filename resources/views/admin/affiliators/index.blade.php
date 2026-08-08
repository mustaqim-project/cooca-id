@extends('layouts.admin')

@section('title', 'Affiliate Partners — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Affiliators</span>
        </div>
        <h1 class="page-title">Affiliate Partners</h1>
        <p class="page-subtitle">Manage multi-tier affiliate marketers, referral codes, commission splits, and downlines.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.affiliators.create') }}" class="btn btn-primary">
            <span>🤝</span> Register Partner
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Partner Info</th>
                        <th>Referral Code</th>
                        <th>Commission Rate</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($affiliators as $aff)
                        @php $affObj = is_array($aff) ? (object)$aff : $aff; @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-base">{{ $affObj->name ?? 'Partner' }}</div>
                                <div class="text-xs text-muted">{{ $affObj->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <code class="badge badge-accent">{{ $affObj->referral_code ?? 'REF-XXXX' }}</code>
                            </td>
                            <td class="font-bold text-primary">
                                {{ $affObj->commission_rate ?? 25 }}%
                            </td>
                            <td class="font-bold text-success">
                                Rp {{ number_format($affObj->wallet_balance ?? 0, 0, ',', '.') }}
                            </td>
                            <td>
                                @if(($affObj->status ?? 'active') === 'active')
                                    <span class="badge badge-success">ACTIVE</span>
                                @else
                                    <span class="badge badge-danger">SUSPENDED</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.affiliators.show', $affObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ Detail</a>
                                    <a href="{{ route('admin.affiliators.edit', $affObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">No affiliators registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
