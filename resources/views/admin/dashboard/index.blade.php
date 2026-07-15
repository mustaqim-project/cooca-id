@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth('admin')->user()->name)

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <select class="form-select form-select-sm" id="dashboardPeriod" style="width:auto">
            <option value="7">Last 7 days</option>
            <option value="30" selected>Last 30 days</option>
            <option value="90">Last 90 days</option>
            <option value="365">Last 12 months</option>
        </select>
        <button class="btn-saas btn-saas-outline btn-sm" onclick="window.print()">
            <i class="bi bi-download me-1"></i>Export
        </button>
    </div>
@endsection

@section('content')

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background:rgba(99,102,241,.12);color:#6366f1">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div class="stat-card-body">
                    <p class="stat-card-label">Total Revenue</p>
                    <h3 class="stat-card-value">
                        Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
                    </h3>
                    <span class="stat-card-change positive">
                        <i class="bi bi-arrow-up-short"></i>
                        {{ $stats['revenue_growth'] ?? '0' }}% vs last month
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background:rgba(16,185,129,.12);color:#10b981">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div class="stat-card-body">
                    <p class="stat-card-label">Total Customers</p>
                    <h3 class="stat-card-value">
                        {{ number_format($stats['total_customers'] ?? 0) }}
                    </h3>
                    <span class="stat-card-change positive">
                        <i class="bi bi-arrow-up-short"></i>
                        {{ $stats['customers_growth'] ?? '0' }}% vs last month
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background:rgba(245,158,11,.12);color:#f59e0b">
                    <i class="bi bi-patch-check fs-4"></i>
                </div>
                <div class="stat-card-body">
                    <p class="stat-card-label">Active Subscriptions</p>
                    <h3 class="stat-card-value">
                        {{ number_format($stats['active_subscriptions'] ?? 0) }}
                    </h3>
                    <span class="stat-card-change neutral">
                        <i class="bi bi-dash"></i>
                        {{ $stats['subscriptions_growth'] ?? '0' }}% vs last month
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background:rgba(239,68,68,.12);color:#ef4444">
                    <i class="bi bi-headset fs-4"></i>
                </div>
                <div class="stat-card-body">
                    <p class="stat-card-label">Pending Tickets</p>
                    <h3 class="stat-card-value">
                        {{ number_format($stats['pending_tickets'] ?? 0) }}
                    </h3>
                    <span class="stat-card-change {{ ($stats['pending_tickets'] ?? 0) > 0 ? 'negative' : 'positive' }}">
                        <i class="bi bi-{{ ($stats['pending_tickets'] ?? 0) > 0 ? 'arrow-up-short' : 'check2' }}"></i>
                        {{ ($stats['pending_tickets'] ?? 0) > 0 ? 'Needs attention' : 'All resolved' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="card-saas h-100">
                <div class="card-saas-header d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-saas-title mb-0">Revenue Trend</h6>
                        <p class="text-muted-text small mb-0">Monthly revenue over time</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge-saas badge-saas-primary">MRR</span>
                    </div>
                </div>
                <div class="card-saas-body">
                    <div id="revenueChart" style="min-height:280px"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card-saas h-100">
                <div class="card-saas-header">
                    <h6 class="card-saas-title mb-0">Transaction Status</h6>
                    <p class="text-muted-text small mb-0">Distribution this month</p>
                </div>
                <div class="card-saas-body d-flex align-items-center justify-content-center">
                    <div id="statusChart" style="min-height:280px;width:100%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="card-saas">
        <div class="card-saas-header d-flex align-items-center justify-content-between">
            <div>
                <h6 class="card-saas-title mb-0">Recent Transactions</h6>
                <p class="text-muted-text small mb-0">Latest payment activity</p>
            </div>
            @if (isset($recentTransactions) && $recentTransactions->count())
                <a href="{{ route('admin.transactions.index') }}" class="btn-saas btn-saas-ghost btn-sm">
                    View all <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @endif
        </div>
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions ?? [] as $transaction)
                            <tr>
                                <td>
                                    <span class="fw-medium font-monospace small">
                                        #{{ $transaction->invoice_number ?? $transaction->id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="admin-avatar" style="width:32px;height:32px;font-size:.75rem">
                                            {{ strtoupper(substr($transaction->customer->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium small">{{ $transaction->customer->name ?? '-' }}</div>
                                            <div class="text-muted-text" style="font-size:.75rem">
                                                {{ $transaction->customer->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small text-secondary-text">
                                    {{ $transaction->product->name ?? '-' }}
                                </td>
                                <td class="fw-semibold small">
                                    Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'paid' => 'success',
                                            'success' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'expired' => 'secondary',
                                            'refund' => 'info',
                                        ];
                                        $st = strtolower($transaction->status ?? '');
                                        $badgeType = $statusMap[$st] ?? 'secondary';
                                    @endphp
                                    <span class="badge-saas badge-saas-{{ $badgeType }}">
                                        {{ ucfirst($st ?: 'unknown') }}
                                    </span>
                                </td>
                                <td class="text-muted-text small">
                                    {{ $transaction->created_at?->format('d M Y') ?? '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $transaction) }}"
                                        class="btn-saas btn-saas-ghost btn-sm py-0 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state py-5">
                                        <i class="bi bi-receipt empty-state-icon"></i>
                                        <h6 class="empty-state-title">No transactions yet</h6>
                                        <p class="empty-state-desc">Transactions will appear here once customers start
                                            purchasing.</p>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js"></script>
    <script>
        (function() {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#1e293b' : '#f1f5f9';
            const primaryColor = '#6366f1';

            // Revenue chart data from PHP
            const revenueLabels = @json($revenueChart['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
            const revenueData = @json($revenueChart['data'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);

            const revenueChart = new ApexCharts(document.getElementById('revenueChart'), {
                series: [{
                    name: 'Revenue',
                    data: revenueData
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif',
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.02,
                        stops: [0, 100]
                    }
                },
                colors: [primaryColor],
                xaxis: {
                    categories: revenueLabels,
                    labels: {
                        style: {
                            colors: textColor,
                            fontFamily: 'Inter, sans-serif'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor,
                            fontFamily: 'Inter, sans-serif'
                        },
                        formatter: v => 'Rp ' + (v >= 1000000 ? (v / 1000000).toFixed(1) + 'M' : v >= 1000 ? (
                            v / 1000).toFixed(0) + 'K' : v)
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                    }
                },
            });
            revenueChart.render();

            // Status donut chart
            const statusLabels = @json($statusChart['labels'] ?? ['Paid', 'Pending', 'Failed']);
            const statusData = @json($statusChart['data'] ?? [0, 0, 0]);
            const statusColors = ['#10b981', '#f59e0b', '#ef4444', '#6366f1', '#94a3b8'];

            const statusChart = new ApexCharts(document.getElementById('statusChart'), {
                series: statusData,
                labels: statusLabels,
                chart: {
                    type: 'donut',
                    height: 280,
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif',
                },
                colors: statusColors,
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: textColor
                    },
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: textColor,
                                    formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
            });
            statusChart.render();

            // Re-render on dark mode toggle
            document.getElementById('darkModeToggle')?.addEventListener('click', () => {
                setTimeout(() => {
                    const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    const tc = dark ? '#94a3b8' : '#64748b';
                    const gc = dark ? '#1e293b' : '#f1f5f9';
                    revenueChart.updateOptions({
                        xaxis: {
                            labels: {
                                style: {
                                    colors: tc
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: tc
                                }
                            }
                        },
                        grid: {
                            borderColor: gc
                        },
                        tooltip: {
                            theme: dark ? 'dark' : 'light'
                        }
                    });
                    statusChart.updateOptions({
                        legend: {
                            labels: {
                                colors: tc
                            }
                        },
                        tooltip: {
                            theme: dark ? 'dark' : 'light'
                        }
                    });
                }, 50);
            });
        })();
    </script>
@endpush
