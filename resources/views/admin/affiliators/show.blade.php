@extends('layouts.admin')

@section('title', 'Affiliate Partner Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.affiliators.index') }}">Affiliators</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">{{ $affiliator->name ?? 'Partner Profile' }}</h1>
        <p class="page-subtitle">Referral Code: <code class="text-primary font-bold">{{ $affiliator->referral_code ?? 'N/A' }}</code> — Commission Rate: {{ $affiliator->commission_rate ?? 25 }}%</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.affiliators.edit', $affiliator->id ?? 1) }}" class="btn btn-outline">✏️ Edit Partner</a>
        <a href="{{ route('admin.affiliators.index') }}" class="btn btn-ghost">← Back</a>
    </div>
</div>

<div class="grid-31">
    <div class="flex-col gap-5">

        {{-- Wallet & Bank Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">💰 Wallet & Bank Info</div>
            </div>
            <div class="card-body">
                <div class="text-xs text-muted font-bold uppercase">Current Balance</div>
                <div class="font-bold text-success text-2xl my-1">Rp {{ number_format($affiliator->balance ?? 0, 0, ',', '.') }}</div>
                <span class="badge {{ ($affiliator->status ?? 'active') === 'active' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($affiliator->status ?? 'ACTIVE') }}</span>

                <div class="section-divider mt-3"></div>

                <div class="flex-col gap-2 mt-3">
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Bank Name</div>
                        <div class="font-semibold text-sm">{{ $affiliator->bank_name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Account Number</div>
                        <div class="font-semibold text-sm">{{ $affiliator->bank_account ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Info Card --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">👤 Profile Info</div>
            </div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Email</div>
                    <div class="font-semibold text-sm">{{ $affiliator->email ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Phone</div>
                    <div class="font-semibold text-sm">{{ $affiliator->phone ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Upline Partner</div>
                    <div class="font-semibold text-sm">
                        @if(isset($affiliator->parent_affiliator_id) && $affiliator->parent_affiliator_id)
                            <a href="{{ route('admin.affiliators.show', $affiliator->parent_affiliator_id) }}" class="text-primary" style="text-decoration: underline;">View Upline</a>
                        @else
                            <span class="text-muted">Top-level Partner</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Registered</div>
                    <div class="font-semibold text-sm">{{ optional(isset($affiliator->created_at) ? \Carbon\Carbon::parse($affiliator->created_at) : null)?->format('d M Y') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Downlines Table --}}
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">🔗 Referred Downlines ({{ count($downlines ?? []) }})</div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Commission</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($downlines ?? [] as $downline)
                                <tr>
                                    <td class="font-bold">
                                        <a href="{{ route('admin.affiliators.show', $downline->id) }}" class="text-primary">{{ $downline->name ?? '' }}</a>
                                    </td>
                                    <td>{{ $downline->email ?? '' }}</td>
                                    <td>{{ $downline->commission_rate ?? 25 }}%</td>
                                    <td class="text-xs text-muted">{{ optional(isset($downline->created_at) ? \Carbon\Carbon::parse($downline->created_at) : null)?->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 24px;">No referred partners in network.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
