@extends('affiliator.layouts.app')

@section('title', 'My Downlines')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Downlines</span>
@endsection

@section('content')
<div class="portal-card mb-6">
    <div class="portal-card-header" style="flex-wrap:wrap;gap:12px;">
        <div>
            <div class="portal-card-title">
                <i class="fa-solid fa-sitemap" style="color:var(--primary);"></i>
                My Downlines Network
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Sub-affiliates registered under your tier 2 network.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('affiliator.downlines.stats') }}" class="btn btn-s btn-sm">
                <i class="fa-solid fa-chart-pie"></i> Statistics
            </a>
            <a href="{{ route('affiliator.downlines.tree') }}" class="btn btn-p btn-sm">
                <i class="fa-solid fa-folder-tree"></i> View Tree
            </a>
        </div>
    </div>

    <div class="portal-card-body p-0">
        <div class="table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th class="portal-th">Downline Partner</th>
                        <th class="portal-th">Email</th>
                        <th class="portal-th">Referrals Count</th>
                        <th class="portal-th text-center">Status</th>
                        <th class="portal-th text-right">Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($downlines ?? [] as $downline)
                        <tr>
                            <td class="portal-td font-medium">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">
                                        {{ strtoupper(substr($downline->name ?? 'D', 0, 2)) }}
                                    </div>
                                    <span style="font-weight:600;">{{ $downline->name ?? 'Sub-Affiliate' }}</span>
                                </div>
                            </td>
                            <td class="portal-td text-muted">
                                {{ $downline->email ?? '-' }}
                            </td>
                            <td class="portal-td">
                                <span class="badge-status status-info">
                                    <i class="fa-solid fa-users" style="font-size:10px;"></i> {{ $downline->referrals_count ?? $downline->referrals()->count() }} Customers
                                </span>
                            </td>
                            <td class="portal-td text-center">
                                <span class="badge-status status-active">Active</span>
                            </td>
                            <td class="portal-td text-right text-muted" style="font-size:12px;">
                                {{ isset($downline->created_at) ? \Carbon\Carbon::parse($downline->created_at)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="portal-td text-center py-6 text-muted">
                                <i class="fa-solid fa-sitemap" style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                No downline partners in your network yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($downlines ?? [], 'hasPages') && ($downlines ?? [])->hasPages())
            <div style="padding:16px;border-top:1px solid var(--border);">
                {{ $downlines->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
