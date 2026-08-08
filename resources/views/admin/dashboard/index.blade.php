@extends('layouts.admin')

@section('title', 'Executive Dashboard — COOCA.ID Admin')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="#">Admin</a>
            <span>/</span>
            <span>Executive Dashboard</span>
        </div>
        <h1 class="page-title">Executive Dashboard</h1>
        <p class="page-subtitle">Real-time SaaS operational metrics, revenue analytics, and performance intelligence.</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-outline btn-sm">
            <span>📅</span> Last 30 Days
        </button>
        <button class="btn btn-primary btn-sm">
            <span>⚡</span> Generate Report
        </button>
    </div>
</div>

{{-- KPI GRID --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: #4F46E5; --kpi-color2: #06B6D4;">
        <div class="kpi-header">
            <span class="kpi-label">Total Revenue</span>
            <div class="kpi-icon" style="background: rgba(79,70,229,0.1); color: #4F46E5;">💰</div>
        </div>
        <div class="kpi-value">Rp {{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="kpi-trend {{ ($stats['revenueGrowth'] ?? 0) >= 0 ? 'up' : 'down' }}">
            <span>{{ ($stats['revenueGrowth'] ?? 0) >= 0 ? '↑' : '↓' }} {{ abs($stats['revenueGrowth'] ?? 0) }}%</span>
            <span class="trend-label">vs last month</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: #10B981; --kpi-color2: #34D399;">
        <div class="kpi-header">
            <span class="kpi-label">Monthly Revenue (MRR)</span>
            <div class="kpi-icon" style="background: rgba(16,185,129,0.1); color: #10B981;">📈</div>
        </div>
        <div class="kpi-value">Rp {{ number_format($stats['monthlyRevenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="kpi-trend up">
            <span>Today: Rp {{ number_format($stats['todayRevenue'] ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: #8B5CF6; --kpi-color2: #EC4899;">
        <div class="kpi-header">
            <span class="kpi-label">Active Customers</span>
            <div class="kpi-icon" style="background: rgba(139,92,246,0.1); color: #8B5CF6;">👥</div>
        </div>
        <div class="kpi-value">{{ number_format($stats['totalCustomers'] ?? 0) }}</div>
        <div class="kpi-trend {{ ($stats['customerGrowth'] ?? 0) >= 0 ? 'up' : 'down' }}">
            <span>+{{ $stats['newCustomersThisMonth'] ?? 0 }} new</span>
            <span class="trend-label">this month</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: #F59E0B; --kpi-color2: #F97316;">
        <div class="kpi-header">
            <span class="kpi-label">Active Subscriptions</span>
            <div class="kpi-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;">🔄</div>
        </div>
        <div class="kpi-value">{{ number_format($stats['activeSubscriptions'] ?? 0) }}</div>
        <div class="kpi-trend up">
            <span>{{ $stats['trialSubscriptions'] ?? 0 }} on trial</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: #06B6D4; --kpi-color2: #3B82F6;">
        <div class="kpi-header">
            <span class="kpi-label">Active Licenses</span>
            <div class="kpi-icon" style="background: rgba(6,182,212,0.1); color: #06B6D4;">🔑</div>
        </div>
        <div class="kpi-value">{{ number_format($stats['activeLicenses'] ?? 0) }}</div>
        <div class="kpi-trend down">
            <span>{{ $stats['expiredLicenses'] ?? 0 }} expired</span>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="grid-21 mb-6">
    {{-- Revenue Growth Chart --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Revenue Trajectory (12 Months)</div>
                <div class="card-subtitle">Gross subscription and license transaction volume</div>
            </div>
            <div class="badge badge-primary">Net Revenue</div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="revenueChart" height="240"></canvas>
            </div>
        </div>
    </div>

    {{-- Subscription Plan Distribution --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Plan Distribution</div>
                <div class="card-subtitle">Active subscriptions by tier</div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="planChart" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- TABLES ROW --}}
<div class="grid-21 mb-6">
    {{-- Recent Transactions --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Recent Transactions</div>
                <div class="card-subtitle">Latest payments processed across payment gateways</div>
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost btn-sm">View All →</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice / Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $tx)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $tx->reference ?? ('TX-'.$tx->id) }}</div>
                                    <div class="text-xs text-muted">{{ $tx->customer->name ?? $tx->customer->email ?? 'Guest' }}</div>
                                </td>
                                <td class="font-bold">Rp {{ number_format($tx->amount ?? $tx->net_amount ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if(($tx->status ?? '') === 'paid')
                                        <span class="badge badge-success">PAID</span>
                                    @elseif(($tx->status ?? '') === 'pending')
                                        <span class="badge badge-warning">PENDING</span>
                                    @else
                                        <span class="badge badge-danger">{{ strtoupper($tx->status ?? 'FAILED') }}</span>
                                    @endif
                                </td>
                                <td class="text-xs text-muted">{{ optional($tx->created_at)->format('d M H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding: 24px;">No transactions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Affiliate Withdrawals --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Withdrawal Requests</div>
                <div class="card-subtitle">Pending partner settlements</div>
            </div>
            <a href="{{ route('admin.settlements.index') }}" class="btn btn-ghost btn-sm">Manage →</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Partner</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentWithdrawals as $withdrawal)
                            <tr>
                                <td>
                                    <div class="font-semibold text-sm">{{ $withdrawal->affiliator->name ?? 'Partner' }}</div>
                                    <div class="text-xs text-muted">{{ $withdrawal->bank_name }}</div>
                                </td>
                                <td class="font-bold text-sm">Rp {{ number_format($withdrawal->amount ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($withdrawal->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($withdrawal->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="padding: 20px;">No withdrawal requests.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Line Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenueChartData);

    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.month),
            datasets: [{
                label: 'Revenue (IDR)',
                data: revenueData.map(d => d.revenue),
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#4F46E5',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: {
                    grid: { color: 'rgba(226, 232, 240, 0.5)' },
                    ticks: {
                        callback: function(val) {
                            return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                        }
                    }
                }
            }
        }
    });

    // Plan Donut Chart
    const planCtx = document.getElementById('planChart').getContext('2d');
    const planDist = @json($subscriptionPlanDist);

    new Chart(planCtx, {
        type: 'doughnut',
        data: {
            labels: planDist.length ? planDist.map(d => d.name) : ['Starter', 'Professional', 'Enterprise'],
            datasets: [{
                data: planDist.length ? planDist.map(d => d.count) : [45, 30, 25],
                backgroundColor: ['#4F46E5', '#06B6D4', '#10B981', '#F59E0B', '#8B5CF6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            },
            cutout: '70%'
        }
    });
});
</script>
@endpush
