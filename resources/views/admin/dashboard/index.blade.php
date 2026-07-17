@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Welcome Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Welcome back, Admin 👋</h2>
                <p class="text-secondary mb-0">Here's what's happening with Cooca ID today.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary">
                    <i class="bi bi-calendar3 me-2"></i> Last 30 Days
                </button>
                <button class="btn btn-primary rounded-pill px-3 hover-lift shadow-sm">
                    <i class="bi bi-download me-2"></i> Report
                </button>
            </div>
        </div>

        <!-- Bento Grid Stats -->
        <div class="row g-4">
            <!-- Stat 1 -->
            <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden position-relative glass">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i
                                    class="bi bi-arrow-up-short"></i> {{ $stats['revenueGrowth'] ?? 0 }}%</span>
                        </div>
                        <div class="text-secondary mb-1 fw-medium">Total Revenue</div>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') }}</h3>
                        <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                            {{ number_format($stats['todayRevenue'] ?? 0, 0, ',', '.') }} today</div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100"
                        style="height: 40px; background: linear-gradient(to top, rgba(var(--color-primary-rgb), 0.05), transparent);">
                    </div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden position-relative glass">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-arrow-repeat fs-4"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i
                                    class="bi bi-arrow-up-short"></i> {{ $stats['newSubscriptionsThisMonth'] ?? 0 }}
                                New</span>
                        </div>
                        <div class="text-secondary mb-1 fw-medium">Active Subscriptions</div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['activeSubscriptions'] ?? 0) }}</h3>
                        <div class="text-secondary mt-1" style="font-size: 0.75rem;">{{ $stats['totalSubscriptions'] ?? 0 }}
                            total · {{ $stats['expiredSubscriptions'] ?? 0 }} expired</div>
                    </div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden position-relative glass">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-hdd-network fs-4"></i>
                            </div>
                            <span
                                class="badge bg-{{ $stats['pendingTransactions'] > 0 ? 'danger' : 'success' }}-subtle text-{{ $stats['pendingTransactions'] > 0 ? 'danger' : 'success' }} rounded-pill px-2 py-1">{{ $stats['pendingTransactions'] ?? 0 }}
                                Pending</span>
                        </div>
                        <div class="text-secondary mb-1 fw-medium">Active Licenses</div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['activeLicenses'] ?? 0) }}</h3>
                        <div class="text-secondary mt-1" style="font-size: 0.75rem;">{{ $stats['totalLicenses'] ?? 0 }}
                            total · {{ $stats['expiredLicenses'] ?? 0 }} expired</div>
                    </div>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden position-relative glass">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i
                                    class="bi bi-arrow-up-short"></i> {{ $stats['customerGrowth'] ?? 0 }}%</span>
                        </div>
                        <div class="text-secondary mb-1 fw-medium">Total Customers</div>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['totalCustomers'] ?? 0) }}</h3>
                        <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                            {{ $stats['newCustomersThisMonth'] ?? 0 }} new this month</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <div class="col-12 col-xl-8" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm rounded-4 h-100 glass">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Revenue Overview</h5>
                        <button class="btn btn-sm btn-light rounded-pill"><i class="bi bi-three-dots"></i></button>
                    </div>
                    <div class="card-body px-4 pb-4 pt-0">
                        <div id="revenueChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card border-0 shadow-sm rounded-4 h-100 glass">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Products Traffic</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-0 d-flex justify-content-center align-items-center">
                        <div id="trafficChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lists Section -->
        <div class="row g-4 pb-4">
            <!-- Recent ERP Requests -->
            <div class="col-12 col-lg-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card border-0 shadow-sm rounded-4 h-100 glass overflow-hidden">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Recent ERP Requests</h5>
                        <a href="{{ route('admin.erp-requests.index') }}"
                            class="btn btn-sm btn-link text-decoration-none">View All</a>
                    </div>
                    <div class="list-group list-group-flush border-0">
                        @forelse($recentErpRequests ?? [] as $req)
                            @php
                                $statusMap = [
                                    'pending' => [
                                        'class' => 'warning',
                                        'text' => 'warning',
                                        'icon' => 'hourglass-split',
                                        'label' => 'Pending',
                                    ],
                                    'approved' => [
                                        'class' => 'info',
                                        'text' => 'info',
                                        'icon' => 'check2-circle',
                                        'label' => 'Approved',
                                    ],
                                    'rejected' => [
                                        'class' => 'danger',
                                        'text' => 'danger',
                                        'icon' => 'x-circle',
                                        'label' => 'Rejected',
                                    ],
                                    'waiting_setup' => [
                                        'class' => 'primary',
                                        'text' => 'primary',
                                        'icon' => 'gear',
                                        'label' => 'Waiting Setup',
                                    ],
                                    'in_setup' => [
                                        'class' => 'primary',
                                        'text' => 'primary',
                                        'icon' => 'gear',
                                        'label' => 'In Setup',
                                    ],
                                    'domain_setup' => [
                                        'class' => 'info',
                                        'text' => 'info',
                                        'icon' => 'globe',
                                        'label' => 'Domain Setup',
                                    ],
                                    'testing' => [
                                        'class' => 'warning',
                                        'text' => 'warning',
                                        'icon' => 'bug',
                                        'label' => 'Testing',
                                    ],
                                    'ready' => [
                                        'class' => 'success',
                                        'text' => 'success',
                                        'icon' => 'check2-all',
                                        'label' => 'Ready',
                                    ],
                                ];
                                $s = $statusMap[$req->status] ?? [
                                    'class' => 'secondary',
                                    'text' => 'secondary',
                                    'icon' => 'circle',
                                    'label' => ucfirst($req->status),
                                ];
                            @endphp
                            <div
                                class="list-group-item bg-transparent p-4 border-light d-flex align-items-center gap-3 hover-lift">
                                <div class="bg-{{ $s['class'] }}-subtle text-{{ $s['text'] }} rounded-circle p-2"
                                    style="width: 40px; height: 40px; display: grid; place-items: center;"><i
                                        class="bi bi-{{ $s['icon'] }}"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">
                                        {{ $req->customer?->name ?? 'Customer #' . $req->customer_id }}</div>
                                    <div class="text-secondary" style="font-size: 0.8rem;">
                                        {{ $req->notes ?? 'ERP Request' }}</div>
                                </div>
                                <span
                                    class="badge bg-{{ $s['class'] }} {{ $s['class'] === 'warning' || $s['class'] === 'light' ? 'text-dark' : '' }} rounded-pill">{{ $s['label'] }}</span>
                            </div>
                        @empty
                            <div class="list-group-item bg-transparent p-4 border-light text-center text-secondary">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                <span>No recent ERP requests</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- System Status/Timeline -->
            <div class="col-12 col-lg-6" data-aos="fade-up" data-aos-delay="700">
                <div class="card border-0 shadow-sm rounded-4 h-100 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="mb-0 fw-semibold">Recent Activity</h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse($recentTransactions ?? [] as $tx)
                            @php
                                $colorMap = [
                                    'paid' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    'refunded' => 'info',
                                ];
                                $iconMap = [
                                    'paid' => 'check2-all',
                                    'pending' => 'hourglass-split',
                                    'failed' => 'x-circle',
                                    'refunded' => 'arrow-counterclockwise',
                                ];
                                $c = $colorMap[$tx->status] ?? 'primary';
                                $i = $iconMap[$tx->status] ?? 'circle';
                            @endphp
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-light">
                                <div class="bg-{{ $c }}-subtle text-{{ $c }} rounded-circle p-2"
                                    style="width: 40px; height: 40px; display: grid; place-items: center; flex-shrink: 0;">
                                    <i class="bi bi-{{ $i }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">{{ $tx->customer?->name ?? 'Customer #' . $tx->customer_id }}
                                    </div>
                                    <div class="text-secondary" style="font-size: 0.8rem;">
                                        {{ ucfirst($tx->status) }} - Rp
                                        {{ number_format($tx->net_amount ?? ($tx->gross_amount ?? 0), 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    {{ $tx->created_at ? $tx->created_at->diffForHumans() : '' }}</div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-4">
                                <i class="bi bi-activity fs-2 d-block mb-2"></i>
                                <span>No recent activity</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--color-primary')
                .trim();
            const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--color-accent')
                .trim();
            const textColor = getComputedStyle(document.documentElement).getPropertyValue('--color-text-secondary')
                .trim();
            const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--color-border').trim();

            // Revenue Chart (Area) - Real data from controller
            const revenueMonths = @json(array_column($revenueChartData ?? [], 'month'));
            const revenueValues = @json(array_column($revenueChartData ?? [], 'revenue'));

            const revenueOptions = {
                series: [{
                    name: 'Revenue',
                    data: revenueValues
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent',
                    fontFamily: 'inherit',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                colors: [accentColor],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: revenueMonths,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        },
                        formatter: (value) => 'Rp ' + (value / 1000).toFixed(0) + 'k'
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                theme: {
                    mode: document.documentElement.getAttribute('data-theme') || 'light'
                }
            };

            const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            // Traffic Chart (Donut) - Real data from controller
            const txStatus = @json($transactionStatusBreakdown ?? []);
            const txLabels = Object.keys(txStatus).map(k => k.charAt(0).toUpperCase() + k.slice(1));
            const txValues = Object.values(txStatus);

            const trafficOptions = {
                series: txValues.length ? txValues : [1],
                chart: {
                    type: 'donut',
                    height: 300,
                    background: 'transparent',
                    fontFamily: 'inherit',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                labels: txLabels.length ? txLabels : ['No Data'],
                colors: ['#10b981', '#f59e0b', '#ef4444', '#6b7280'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%'
                        },
                        expandOnClick: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 0
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: textColor
                    }
                },
                theme: {
                    mode: document.documentElement.getAttribute('data-theme') || 'light'
                }
            };

            const trafficChart = new ApexCharts(document.querySelector("#trafficChart"), trafficOptions);
            trafficChart.render();

            // Listen for theme change to update charts
            document.getElementById('theme-toggle')?.addEventListener('click', () => {
                setTimeout(() => {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    revenueChart.updateOptions({
                        theme: {
                            mode: currentTheme
                        }
                    });
                    trafficChart.updateOptions({
                        theme: {
                            mode: currentTheme
                        }
                    });
                }, 100);
            });
        });
    </script>
@endpush
