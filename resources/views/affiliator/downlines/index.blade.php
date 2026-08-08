@extends('affiliator.layouts.app')

@section('title', 'My Downlines')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Downlines</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">My Downlines Network</div>
            <div class="page-subtitle">Sub-affiliates registered under your tier 2 network.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.downlines.stats') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-chart-pie"></i> Statistics
            </a>
            <a href="{{ route('affiliator.downlines.tree') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-folder-tree"></i> View Tree
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Downline Partner</th>
                            <th>Email</th>
                            <th>Referrals Count</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($downlines ?? [] as $downline)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">
                                            {{ strtoupper(substr($downline->name ?? 'D', 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-sm">{{ $downline->name ?? 'Sub-Affiliate' }}</span>
                                    </div>
                                </td>
                                <td class="text-muted text-sm">
                                    {{ $downline->email ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <i class="fa-solid fa-users" style="font-size:10px;"></i>
                                        {{ $downline->referrals_count ?? $downline->referrals()->count() }} Customers
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">Active</span>
                                </td>
                                <td class="text-right text-muted text-xs">
                                    {{ isset($downline->created_at) ? \Carbon\Carbon::parse($downline->created_at)->format('d M Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-sitemap"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
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
