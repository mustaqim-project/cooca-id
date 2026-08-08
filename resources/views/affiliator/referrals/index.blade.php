@extends('affiliator.layouts.app')

@section('title', 'My Referrals')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">My Referrals</span>
@endsection

@section('content')
<div class="portal-card mb-6">
    <div class="portal-card-header" style="flex-wrap:wrap;gap:12px;">
        <div>
            <div class="portal-card-title">
                <i class="fa-solid fa-users" style="color:var(--primary);"></i>
                My Referrals
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Track all users registered via your referral link.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('affiliator.referrals.stats') }}" class="btn btn-s btn-sm">
                <i class="fa-solid fa-chart-pie"></i> View Statistics
            </a>
        </div>
    </div>

    <div class="portal-card-body p-0">
        <div class="table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th class="portal-th">Customer</th>
                        <th class="portal-th">Email</th>
                        <th class="portal-th">Registration Date</th>
                        <th class="portal-th text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <td class="portal-td font-medium">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">
                                        {{ strtoupper(substr($referral->name ?? 'C', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;">{{ $referral->name }}</div>
                                        @if($referral->business_name)
                                            <div style="font-size:11px;color:var(--text-muted);">{{ $referral->business_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="portal-td text-muted">
                                {{ substr($referral->email, 0, 3) }}***@{{ explode('@', $referral->email)[1] ?? '' }}
                            </td>
                            <td class="portal-td text-muted">
                                {{ \Carbon\Carbon::parse($referral->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="portal-td text-center">
                                <span class="badge-status status-active">
                                    <i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Registered
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="portal-td text-center py-6 text-muted">
                                <i class="fa-solid fa-users-slash" style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                No referrals registered yet. Share your referral link to earn!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($referrals, 'hasPages') && $referrals->hasPages())
            <div style="padding:16px;border-top:1px solid var(--border);">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
