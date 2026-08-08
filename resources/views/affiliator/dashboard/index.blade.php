@extends('affiliator.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="crumb-current">Dashboard</span>
@endsection

@section('content')
@php
    $affiliator   = auth('affiliator')->user() ?? auth()->user();
    $userName     = $affiliator?->name ?? 'Partner';
    $referralCode = $affiliator?->referral_code ?? '';
    $referralUrl  = url('/register?ref=' . $referralCode);

    $hour       = now()->hour;
    $greeting   = match(true) {
        $hour < 12 => 'Good morning',
        $hour < 17 => 'Good afternoon',
        default    => 'Good evening',
    };
    $greetEmoji = $hour < 12 ? '☀️' : ($hour < 17 ? '🌤️' : '🌙');

    $totalEarned        = $stats['totalEarned'] ?? 0;
    $availableBalance   = $stats['availableBalance'] ?? 0;
    $pendingCommissions = $stats['pendingCommissions'] ?? 0;
    $activeReferrals    = $stats['activeReferrals'] ?? 0;
    $totalReferrals     = $stats['totalReferrals'] ?? 0;
    $conversionRate     = $stats['conversionRate'] ?? 0;
@endphp

{{-- ══════════════ HERO BANNER ══════════════ --}}
<div class="hero-banner mb-6">
    <div style="position:relative;z-index:1;">
        <div class="hero-greeting">{{ $greetEmoji }} {{ $greeting }}, {{ $userName }}!</div>
        <div class="hero-sub">Welcome back to your COOCA.ID Affiliate Portal. Share your referral link & earn commissions.</div>

        <div class="hero-meta">
            <div class="hero-chip">
                <div class="chip-label">Available Balance</div>
                <div class="chip-value">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
            </div>
            <div class="hero-chip">
                <div class="chip-label">Total Earned</div>
                <div class="chip-value">Rp {{ number_format($totalEarned, 0, ',', '.') }}</div>
            </div>
            <div class="hero-chip">
                <div class="chip-label">Active Referrals</div>
                <div class="chip-value">{{ $activeReferrals }}</div>
            </div>
            <div class="hero-chip">
                <div class="chip-label">Conversion Rate</div>
                <div class="chip-value">{{ $conversionRate }}%</div>
            </div>
        </div>

        {{-- Referral Link Quick Copy Box --}}
        <div style="margin-top:20px;max-width:560px;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);border-radius:var(--radius);padding:10px 14px;display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-link" style="color:#fff;font-size:14px;"></i>
            <input type="text" readonly value="{{ $referralUrl }}" id="refLinkInput" style="border:none;background:none;outline:none;color:#fff;font-size:13px;font-weight:600;flex:1;">
            <button onclick="copyToClipboard('{{ $referralUrl }}', 'Referral Link')" class="btn btn-white btn-sm" style="padding:4px 12px;font-size:12px;">
                <i class="fa-solid fa-copy"></i> Copy Link
            </button>
        </div>
    </div>

    <div class="hero-actions">
        <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-white">
            <i class="fa-solid fa-building-columns"></i> Request Payout
        </a>
        <a href="{{ route('affiliator.marketing_materials.index') }}" class="btn">
            <i class="fa-solid fa-bullhorn"></i> Marketing Assets
        </a>
    </div>
</div>

{{-- ══════════════ KPI CARDS ══════════════ --}}
<div class="kpi-grid mb-6">
    <div class="kpi-card kpi-success">
        <div class="kpi-icon success"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="kpi-value">Rp {{ number_format($totalEarned, 0, ',', '.') }}</div>
        <div class="kpi-label">Total Earnings</div>
        <div class="kpi-trend up"><i class="fa-solid fa-arrow-trend-up"></i> Cleared & Paid</div>
    </div>

    <div class="kpi-card kpi-primary">
        <div class="kpi-icon primary"><i class="fa-solid fa-wallet"></i></div>
        <div class="kpi-value">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
        <div class="kpi-label">Available Balance</div>
        <div class="kpi-trend up"><i class="fa-solid fa-check-circle"></i> Ready to Withdraw</div>
    </div>

    <div class="kpi-card kpi-warning">
        <div class="kpi-icon warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="kpi-value">Rp {{ number_format($pendingCommissions, 0, ',', '.') }}</div>
        <div class="kpi-label">Pending Commissions</div>
        <div class="kpi-trend"><i class="fa-solid fa-spinner"></i> Awaiting Clearing</div>
    </div>

    <div class="kpi-card kpi-accent">
        <div class="kpi-icon accent"><i class="fa-solid fa-users"></i></div>
        <div class="kpi-value">{{ $totalReferrals }}</div>
        <div class="kpi-label">Total Referrals</div>
        <div class="kpi-trend up"><i class="fa-solid fa-user-check"></i> {{ $activeReferrals }} Active Customers</div>
    </div>
</div>

{{-- ══════════════ RECENT COMMISSIONS & DOWNLINES ══════════════ --}}
<div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));gap:24px;" class="mb-6">

    {{-- Recent Commissions Table --}}
    <div class="portal-card" style="grid-column: span 2;">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-wallet" style="color:var(--primary);"></i>
                Recent Commissions
            </div>
            <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-s btn-sm">View All</a>
        </div>
        <div class="portal-card-body p-0">
            <div class="table-wrap">
                <table class="portal-table">
                    <thead>
                        <tr>
                            <th class="portal-th">Invoice</th>
                            <th class="portal-th">Customer</th>
                            <th class="portal-th">Tier Level</th>
                            <th class="portal-th text-right">Commission Amount</th>
                            <th class="portal-th text-center">Status</th>
                            <th class="portal-th text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCommissions as $commission)
                            <tr>
                                <td class="portal-td font-medium">
                                    {{ $commission->transaction?->invoice_number ?? $commission->invoice_number ?? '#COMM-'.$commission->id }}
                                </td>
                                <td class="portal-td">
                                    {{ $commission->customer?->name ?? $commission->customer?->business_name ?? 'Direct Customer' }}
                                </td>
                                <td class="portal-td">
                                    <span class="badge-status status-info">
                                        Level {{ $commission->level ?? 1 }}
                                    </span>
                                </td>
                                <td class="portal-td text-right font-bold text-success">
                                    + Rp {{ number_format($commission->commission_amount ?? $commission->amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="portal-td text-center">
                                    @php
                                        $st = $commission->status ?? 'pending';
                                        $badgeClass = match($st) {
                                            'paid', 'cleared' => 'status-paid',
                                            'pending'          => 'status-pending',
                                            'rejected', 'failed' => 'status-cancelled',
                                            default            => 'status-issued',
                                        };
                                    @endphp
                                    <span class="badge-status {{ $badgeClass }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="portal-td text-right text-muted" style="font-size:12px;">
                                    {{ $commission->created_at?->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="portal-td text-center py-6 text-muted">
                                    <i class="fa-solid fa-inbox" style="font-size:24px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    No commissions recorded yet. Share your referral link to earn!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Downline Summary / Quick Links --}}
    <div class="portal-card">
        <div class="portal-card-header">
            <div class="portal-card-title">
                <i class="fa-solid fa-sitemap" style="color:var(--accent);"></i>
                Top Downlines
            </div>
            <a href="{{ route('affiliator.downlines.index') }}" class="btn btn-s btn-sm">Manage</a>
        </div>
        <div class="portal-card-body">
            @forelse($downlines as $downline)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="user-avatar" style="width:32px;height:32px;font-size:11px;">
                            {{ strtoupper(substr($downline->name, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;">{{ $downline->name }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $downline->referrals_count }} Direct Referrals</div>
                        </div>
                    </div>
                    <span class="badge-status status-active">Level 2</span>
                </div>
            @empty
                <div class="text-center py-4 text-muted" style="font-size:13px;">
                    No downlines registered under you yet.
                </div>
            @endforelse

            <div style="margin-top:16px;padding-top:12px;border-top:1px dashed var(--border);">
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Your Referral Code</div>
                <div style="display:flex;align-items:center;gap:8px;background:var(--bg);padding:8px 12px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <code style="font-size:14px;font-weight:700;color:var(--primary);flex:1;">{{ $referralCode }}</code>
                    <button onclick="copyToClipboard('{{ $referralCode }}', 'Referral Code')" class="btn btn-s btn-sm" style="padding:2px 8px;font-size:11px;">
                        Copy Code
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
