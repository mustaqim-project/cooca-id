@extends('affiliator.layouts.app')

@section('title', 'Affiliator Dashboard')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Dashboard</h2>
                <p class="text-secondary mb-0">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Metrics Row -->
        <div class="row g-4 mb-2">
            <!-- Total Earnings -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill border border-success-subtle px-2 py-1"><i
                                    class="bi bi-arrow-up-short"></i> 12%</span>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Rp 1.2M</h3>
                            <p class="text-secondary fs-7 mb-0">Total Earnings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Referrals -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <span class="badge bg-primary-subtle text-primary rounded-pill border border-primary-subtle px-2 py-1"><i
                                    class="bi bi-arrow-up-short"></i> 5%</span>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">45</h3>
                            <p class="text-secondary fs-7 mb-0">Active Referrals</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Commissions -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Rp 450K</h3>
                            <p class="text-secondary fs-7 mb-0">Pending Commissions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Rate -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-graph-up fs-4"></i>
                            </div>
                            <span class="badge bg-info-subtle text-info rounded-pill border border-info-subtle px-2 py-1"><i
                                    class="bi bi-arrow-up-short"></i> 2.4%</span>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">8.5%</h3>
                            <p class="text-secondary fs-7 mb-0">Conversion Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <h5 class="fw-bold mt-2 mb-0">Recent Commissions</h5>
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Transaction ID</th>
                            <th class="py-3 px-3 border-0">Type</th>
                            <th class="py-3 px-3 border-0 text-end">Amount</th>
                            <th class="py-3 px-3 border-0 text-center">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($recentCommissions ?? [] as $commission)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-dark">
                                    {{ $commission->transaction->invoice_number ?? '-' }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 fs-7">
                                        Level {{ $commission->level ?? 1 }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 fw-bold text-success text-end">
                                    + Rp {{ number_format($commission->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @php
                                        $statusClass = match($commission->status ?? 'pending') {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ ucfirst($commission->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end text-secondary fs-7">
                                    {{ $commission->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Recent Commissions</h6>
                                    <p class="fs-7 mb-0">Your recent commission history will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
