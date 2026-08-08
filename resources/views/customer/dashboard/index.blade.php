@extends('layouts.customer')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="crumb-current">Dashboard</span>
@endsection

@section('content')
@php
    $customer     = auth('customer')->user();
    $companyName  = $customer->business_name ?? $customer->name;
    $greeting     = match(true) {
        now()->hour < 12 => 'Good morning',
        now()->hour < 17 => 'Good afternoon',
        default          => 'Good evening',
    };
    $hour = now()->hour;
    $greetEmoji = $hour < 12 ? '☀️' : ($hour < 17 ? '🌤️' : '🌙');

    // Handle variables safely whether passed as $stats or individual collections
    $activeLicensesCount  = $stats['activeLicenses'] ?? ($licenses ?? collect())->where('status', 'active')->count();
    $totalLicensesCount   = $stats['totalLicenses'] ?? ($licenses ?? collect())->count();
    $activeSubCount       = $stats['activeSubscriptions'] ?? ($subscriptions ?? collect())->where('status', 'active')->count();
    $totalSubCount        = $stats['totalSubscriptions'] ?? ($subscriptions ?? collect())->count();
    $pendingInvoicesCount = $stats['pendingInvoices'] ?? ($recentInvoices ?? collect())->whereIn('status', ['issued', 'overdue', 'pending'])->count();
    $totalSpentAmt        = $stats['totalSpent'] ?? ($recentInvoices ?? collect())->where('status', 'paid')->sum('amount');

    // Active subscription for hero
    $mainSub = ($subscriptions ?? collect())->where('status', 'active')->first();
    $planName = $mainSub?->subscriptionPlan?->name ?? 'Free';
    $nextBilling = $mainSub?->expires_at;

    $licList = $recentLicenses ?? $licenses ?? collect();
    $txList  = $recentTransactions ?? collect();
    $renewalList = $upcomingRenewals ?? collect();
@endphp

@if(!$customer->isCompanyProfileComplete())
<div class="alert alert-warning mb-4" style="display:flex;align-items:center;justify-content:between;gap:12px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);color:#b45309;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:10px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:18px;"></i>
        <span class="text-sm font-medium">Profil Perusahaan Anda belum lengkap! Silakan lengkapi informasi perusahaan Anda agar dapat berlangganan produk secara lancar.</span>
    </div>
    <a href="{{ route('customer.company-profile.edit') }}" class="btn btn-warning btn-sm" style="flex-shrink:0;">Lengkapi Sekarang</a>
</div>
@endif

{{-- ══════════════ HERO BANNER ══════════════ --}}
<div class="hero-banner">
    <div style="position:relative;z-index:1;">
        <div class="hero-greeting">{{ $greetEmoji }} {{ $greeting }}, {{ $companyName }}!</div>
        <div class="hero-sub">Welcome back to your COOCA.ID Customer Portal.</div>

        <div class="hero-meta">
            <div class="hero-chip">
                <div class="chip-label">Current Plan</div>
                <div class="chip-value">{{ $planName }}</div>
            </div>
            <div class="hero-chip">
                <div class="chip-label">Active Licenses</div>
                <div class="chip-value">{{ $activeLicensesCount }}</div>
            </div>
            @if($nextBilling)
            <div class="hero-chip">
                <div class="chip-label">Next Billing</div>
                <div class="chip-value">{{ $nextBilling->format('d M Y') }}</div>
            </div>
            @endif
            <div class="hero-chip">
                <div class="chip-label">Active Subs</div>
                <div class="chip-value">{{ $activeSubCount }}</div>
            </div>
        </div>
    </div>

    <div class="hero-actions">
        @if($mainSub?->license?->domain)
        <a href="https://{{ $mainSub->license->domain }}" target="_blank" class="btn btn-white">
            <i class="fa-solid fa-rocket"></i> Launch App
        </a>
        @endif
        <a href="{{ route('customer.subscriptions.create') }}" class="btn">
            <i class="fa-solid fa-plus"></i> Browse Plans
        </a>
        <a href="{{ route('customer.tickets.create') }}" class="btn">
            <i class="fa-solid fa-headset"></i> Support
        </a>
    </div>
</div>

{{-- ══════════════ KPI CARDS ══════════════ --}}
<div class="kpi-grid mb-6">
    <div class="kpi-card kpi-primary">
        <div class="kpi-icon primary"><i class="fa-solid fa-cube"></i></div>
        <div class="kpi-value">{{ $totalSubCount }}</div>
        <div class="kpi-label">Total Subscriptions</div>
        <div class="kpi-trend up"><i class="fa-solid fa-arrow-trend-up"></i> {{ $activeSubCount }} Active</div>
    </div>
    <div class="kpi-card kpi-success">
        <div class="kpi-icon success"><i class="fa-solid fa-key"></i></div>
        <div class="kpi-value">{{ $activeLicensesCount }}</div>
        <div class="kpi-label">Active Licenses</div>
        <div class="kpi-trend up"><i class="fa-solid fa-check-circle"></i> {{ $totalLicensesCount }} Total</div>
    </div>
    <div class="kpi-card {{ $pendingInvoicesCount > 0 ? 'kpi-warning' : 'kpi-success' }}">
        <div class="kpi-icon {{ $pendingInvoicesCount > 0 ? 'warning' : 'success' }}"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        <div class="kpi-value">{{ $pendingInvoicesCount }}</div>
        <div class="kpi-label">Pending Invoices</div>
        @if($pendingInvoicesCount > 0)
            <div class="kpi-trend down"><i class="fa-solid fa-triangle-exclamation"></i> Action required</div>
        @else
            <div class="kpi-trend up"><i class="fa-solid fa-check"></i> All settled</div>
        @endif
    </div>
    <div class="kpi-card kpi-accent">
        <div class="kpi-icon accent"><i class="fa-solid fa-wallet"></i></div>
        <div class="kpi-value" style="font-size:20px;">Rp {{ number_format($totalSpentAmt, 0, ',', '.') }}</div>
        <div class="kpi-label">Total Spent</div>
        <div class="kpi-trend" style="color:var(--text-muted);"><i class="fa-solid fa-receipt"></i> Total transactions</div>
    </div>
</div>

{{-- ══════════════ MAIN GRID ══════════════ --}}
<div class="grid-31" style="gap:24px;">
    {{-- LEFT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- SPENDING CHART --}}
        @if(isset($spendingChart) && count($spendingChart) > 0)
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--primary);margin-right:8px;"></i>Spending Overview</div>
                <span class="text-xs text-muted">Last 6 Months</span>
            </div>
            <div class="card-body">
                <canvas id="spendingChartCanvas" height="110"></canvas>
            </div>
        </div>
        @endif

        {{-- MY LICENSES --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-key" style="color:var(--primary);margin-right:8px;"></i>Active Licenses</div>
                <a href="{{ route('customer.licenses.index') }}" class="btn btn-ghost btn-sm">
                    View All <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>License Key</th>
                                <th>Product / Plan</th>
                                <th>Domain</th>
                                <th>Status</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($licList->take(5) as $license)
                            <tr>
                                <td>
                                    <code style="font-size:11px;background:var(--bg);padding:3px 6px;border-radius:4px;border:1px solid var(--border);">
                                        {{ substr($license->license_code, 0, 16) }}…
                                    </code>
                                </td>
                                <td>
                                    <div class="font-semibold text-sm">{{ $license->product?->name ?? $license->subscriptionPlan?->name ?? 'License' }}</div>
                                </td>
                                <td>
                                    @if($license->domain)
                                        <a href="https://{{ $license->domain }}" target="_blank" class="text-primary text-xs font-semibold">
                                            {{ $license->domain }}
                                        </a>
                                    @else
                                        <span class="text-muted text-xs">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($license->status === 'active') <span class="badge badge-success">Active</span>
                                    @elseif($license->status === 'expired') <span class="badge badge-danger">Expired</span>
                                    @else <span class="badge badge-muted">{{ ucfirst($license->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-xs text-muted">
                                    {{ $license->expires_at?->format('d M Y') ?? 'Lifetime' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:28px;">No active licenses found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RECENT TRANSACTIONS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-receipt" style="color:var(--success);margin-right:8px;"></i>Recent Transactions</div>
                <a href="{{ route('customer.payments.index') }}" class="btn btn-ghost btn-sm">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($txList->take(5) as $tx)
                            <tr>
                                <td class="font-semibold text-xs">{{ $tx->order_id ?? substr($tx->id, 0, 12) }}</td>
                                <td class="text-xs text-muted">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                <td class="font-bold text-sm">Rp {{ number_format($tx->gross_amount ?? $tx->amount, 0, ',', '.') }}</td>
                                <td class="text-xs text-muted">{{ strtoupper($tx->payment_type ?? 'Midtrans') }}</td>
                                <td>
                                    @if(in_array($tx->status, ['paid', 'settlement', 'success']))
                                        <span class="badge badge-success">Paid</span>
                                    @elseif(in_array($tx->status, ['pending']))
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($tx->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding:28px;">No transaction history.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div style="display:flex;flex-direction:column;gap:24px;">

        {{-- UPCOMING RENEWALS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--warning);margin-right:8px;"></i>Upcoming Renewals</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
                @forelse($renewalList as $renewal)
                <div style="border:1px solid var(--border);border-radius:var(--radius);padding:14px;">
                    <div class="flex justify-between items-start mb-1">
                        <div class="font-bold text-sm">{{ $renewal->subscriptionPlan?->name ?? 'Subscription' }}</div>
                        <span class="badge badge-warning">{{ $renewal->expires_at?->diffForHumans() }}</span>
                    </div>
                    <div class="text-xs text-muted mb-3">Renews on {{ $renewal->expires_at?->format('d M Y') }}</div>
                    <form method="POST" action="{{ route('customer.subscriptions.renew', $renewal->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm w-full" style="justify-content:center;">
                            <i class="fa-solid fa-rotate"></i> Renew Now
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center text-muted" style="padding:16px 0;font-size:13px;">
                    <i class="fa-solid fa-circle-check" style="color:var(--success);font-size:24px;margin-bottom:6px;display:block;"></i>
                    No upcoming renewals due soon.
                </div>
                @endforelse
            </div>
        </div>

        {{-- QUICK LINKS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">⚡ Quick Access</div>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <a href="{{ route('customer.products.index') }}" class="btn btn-outline btn-sm" style="justify-content:center;">
                    <i class="fa-solid fa-cube"></i> Products
                </a>
                <a href="{{ route('customer.subscriptions.index') }}" class="btn btn-outline btn-sm" style="justify-content:center;">
                    <i class="fa-solid fa-repeat"></i> Subscriptions
                </a>
                <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline btn-sm" style="justify-content:center;">
                    <i class="fa-solid fa-file-invoice"></i> Invoices
                </a>
                <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary btn-sm" style="justify-content:center;">
                    <i class="fa-solid fa-headset"></i> Support
                </a>
            </div>
        </div>

        {{-- NEED HELP --}}
        <div class="card" style="background:linear-gradient(135deg,var(--primary) 0%,#7C3AED 100%);border:none;color:#fff;">
            <div class="card-body">
                <div class="font-bold text-base mb-1">Need Assistance?</div>
                <div class="text-xs mb-4" style="opacity:.85;">Our support team is online and ready to assist you.</div>
                <a href="{{ route('customer.tickets.create') }}" class="btn btn-white btn-sm" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-headset"></i> Open Support Ticket
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@if(isset($spendingChart) && count($spendingChart) > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('spendingChartCanvas');
    if (!ctx) return;

    const chartData = @json($spendingChart);
    const labels = chartData.map(d => d.month);
    const amounts = chartData.map(d => d.amount);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Spending (Rp)',
                data: amounts,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4F46E5',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            return 'Rp ' + (val / 1000).toLocaleString('id-ID') + 'k';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush
