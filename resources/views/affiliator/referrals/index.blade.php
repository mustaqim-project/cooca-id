@extends('affiliator.layouts.app')

@section('title', 'My Referrals')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">My Referrals</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">My Referrals</div>
            <div class="page-subtitle">Track all users registered via your referral link.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.referrals.stats') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-chart-pie"></i> View Statistics
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">
                                            {{ strtoupper(substr($referral->name ?? 'C', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm">{{ $referral->name }}</div>
                                            @if ($referral->business_name)
                                                <div class="text-xs text-muted">{{ $referral->business_name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted text-sm">
                                    @php
                                        $emailParts = explode('@', $referral->email);
                                        $maskedEmail = substr($referral->email, 0, 3) . '***@' . ($emailParts[1] ?? '');
                                    @endphp
                                    {{ $maskedEmail }}
                                </td>
                                <td class="text-muted text-sm">
                                    {{ \Carbon\Carbon::parse($referral->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">
                                        <i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Registered
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-users-slash"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
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
