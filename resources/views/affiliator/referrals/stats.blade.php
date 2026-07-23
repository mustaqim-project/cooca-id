@extends('affiliator.layouts.app')

@section('title', 'Referral Statistics')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.referrals.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Referral Statistics</h2>
                    <p class="text-secondary mb-0">Analytics and performance of your referral campaigns.</p>
                </div>
            </div>
        </div>

        <!-- Metrics Row -->
        <div class="row g-4 mb-2">
            <!-- Total Referrals -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">{{ auth()->guard('affiliator')->user()->customers()->count() }}</h3>
                            <p class="text-secondary fs-7 mb-0">Total Referrals</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-cart-check fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">0</h3>
                            <p class="text-secondary fs-7 mb-0">Active Subscriptions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Earned -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Rp 0</h3>
                            <p class="text-secondary fs-7 mb-0">Total Earned</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Rate -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 hover-lift cursor-pointer">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">0%</h3>
                            <p class="text-secondary fs-7 mb-0">Conversion Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coming Soon -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="card-body py-5 text-center text-secondary">
                <div class="mb-3 d-inline-flex bg-light rounded-circle p-4">
                    <i class="bi bi-bar-chart-line fs-1 text-secondary"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark">Detailed Analytics Coming Soon</h5>
                <p class="mb-0 mx-auto" style="max-width: 500px;">
                    We are working on bringing you more detailed charts and historical data to help you track your referral performance over time.
                </p>
            </div>
        </div>
    </div>
@endsection