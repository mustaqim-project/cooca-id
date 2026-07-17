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
        <!-- Revenue Card -->
        <div class="col-12 col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card card-saas border-0 shadow-sm h-100 relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <i class="bi bi-cash-stack fs-1 text-primary"></i>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                            <i class="bi bi-currency-dollar fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 text-muted fw-semibold">Total Revenue</h6>
                    </div>
                    <h3 class="fw-bold mb-2">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="mb-0 fs-sm text-success fw-medium">
                        <i class="bi bi-arrow-up-short"></i> {{ $stats['revenue_growth'] ?? '0' }}% <span
                            class="text-muted fw-normal">vs last month</span>
                    </p>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-12 col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
            <div class="card card-saas border-0 shadow-sm h-100 relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <i class="bi bi-people fs-1 text-success"></i>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 text-muted fw-semibold">Total Customers</h6>
                    </div>
                    <h3 class="fw-bold mb-2">{{ number_format($stats['total_customers'] ?? 0) }}</h3>
                    <p class="mb-0 fs-sm text-success fw-medium">
                        <i class="bi bi-arrow-up-short"></i> {{ $stats['customers_growth'] ?? '0' }}% <span
                            class="text-muted fw-normal">vs last month</span>
                    </p>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions Card -->
        <div class="col-12 col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
            <div class="card card-saas border-0 shadow-sm h-100 relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <i class="bi bi-shield-check fs-1 text-warning"></i>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                            <i class="bi bi-patch-check-fill fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 text-muted fw-semibold">Active Subscriptions</h6>
                    </div>
                    <h3 class="fw-bold mb-2">{{ number_format($stats['active_subscriptions'] ?? 0) }}</h3>
                    <p class="mb-0 fs-sm text-muted fw-medium">
                        <i class="bi bi-dash"></i> {{ $stats['subscriptions_growth'] ?? '0' }}% <span
                            class="text-muted fw-normal">vs last month</span>
                    </p>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 85%" aria-valuenow="85"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Pending Tickets Card -->
        <div class="col-12 col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="400">
            <div class="card card-saas border-0 shadow-sm h-100 relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <i class="bi bi-headset fs-1 text-danger"></i>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle p-2 me-3">
                            <i class="bi bi-ticket-detailed-fill fs-5"></i>
                        </div>
                        <h6 class="card-title mb-0 text-muted fw-semibold">Pending Tickets</h6>
                    </div>
                    <h3 class="fw-bold mb-2">{{ number_format($stats['pending_tickets'] ?? 0) }}</h3>
                    <p
                        class="mb-0 fs-sm {{ ($stats['pending_tickets'] ?? 0) > 0 ? 'text-danger' : 'text-success' }} fw-medium">
                        <i class="bi bi-{{ ($stats['pending_tickets'] ?? 0) > 0 ? 'arrow-up-short' : 'check2' }}"></i>
                        {{ ($stats['pending_tickets'] ?? 0) > 0 ? 'Needs attention' : 'All resolved' }}
                    </p>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-danger" role="progressbar"
                        style="width: {{ min(100, ($stats['pending_tickets'] ?? 0) * 10) }}%"
                        aria-valuenow="{{ $stats['pending_tickets'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-12 col-xl-8" data-aos="fade-up" data-aos-delay="500">
            <div class="card card-saas border-0 shadow-sm h-100">
                <div
                    class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-0 px-4">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Revenue Trend</h5>
                        <p class="text-muted fs-sm mb-0">Monthly revenue over time</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-medium">MRR</span>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div id="revenueChart" style="min-height:280px"></div>
                </div>
            </div>
        </div>

        <!-- Status Chart -->
        <div class="col-12 col-xl-4" data-aos="fade-up" data-aos-delay="600">
            <div class="card card-saas border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h5 class="card-title fw-bold mb-1">Transaction Status</h5>
                    <p class="text-muted fs-sm mb-0">Distribution this month</p>
                </div>
                <div class="card-body px-4 pb-4 pt-0 d-flex align-items-center justify-content-center">
                    <div id="statusChart" style="min-height:280px;width:100%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="card card-saas border-0 shadow-sm" data-aos="fade-up" data-aos-delay="700">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4">
            <div>
                <h5 class="card-title fw-bold mb-1">Recent Transactions</h5>
                <p class="text-muted fs-sm mb-0">Latest payment activity</p>
            </div>
            @if (isset($recentTransactions) && $recentTransactions->count())
                <a href="{{ route('admin.transactions.index') }}"
                    class="btn btn-light btn-sm rounded-pill px-3 fw-medium">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
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
                                    {{ $transaction->created_at ? $transaction->created_at->format('d M Y') : '-' }}
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
            const revenueLabels = {!! json_encode(
                $revenueChart['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ) !!};
            const revenueData = {!! json_encode($revenueChart['data'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};

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
            const statusLabels = {!! json_encode($statusChart['labels'] ?? ['Paid', 'Pending', 'Failed']) !!};
            const statusData = {!! json_encode($statusChart['data'] ?? [0, 0, 0]) !!};
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
