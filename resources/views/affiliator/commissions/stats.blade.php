@extends('affiliator.layouts.app')

@section('title', 'Commission Statistics')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Commission Statistics</h2>
                    <p class="text-secondary mb-0">Analytics of your commission earnings.</p>
                </div>
            </div>
        </div>

        <!-- Metrics Row -->
        <div class="row g-4 mb-2">
            <!-- Total Earned -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-white h-100 hover-lift cursor-pointer" style="background: linear-gradient(135deg, #198754 0%, #157347 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <p class="text-white-50 fs-7 text-uppercase fw-semibold mb-1">Total Earned</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($total_commission ?? 0, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-wallet2 fs-4 text-white"></i>
                            </div>
                        </div>
                        <p class="text-white-50 fs-7 mb-0">All-time earnings from all referrals</p>
                    </div>
                </div>
            </div>

            <!-- Available to Withdraw -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-white h-100 hover-lift cursor-pointer" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <p class="text-white-50 fs-7 text-uppercase fw-semibold mb-1">Available to Withdraw</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($cleared_commission ?? 0, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-cash-coin fs-4 text-white"></i>
                            </div>
                        </div>
                        <p class="text-white-50 fs-7 mb-0">Cleared commissions ready for payout</p>
                    </div>
                </div>
            </div>

            <!-- Pending Clearance -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-white h-100 hover-lift cursor-pointer" style="background: linear-gradient(135deg, #fd7e14 0%, #e35d00 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <p class="text-white-50 fs-7 text-uppercase fw-semibold mb-1">Pending Clearance</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($pending_commission ?? 0, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-hourglass-split fs-4 text-white"></i>
                            </div>
                        </div>
                        <p class="text-white-50 fs-7 mb-0">Commissions waiting for clearance period</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0 text-dark">Earnings Breakdown by Product</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(isset($breakdown) && count($breakdown) > 0)
                            <div class="d-flex flex-column gap-4">
                                @foreach($breakdown as $item)
                                    <div>
                                        <div class="d-flex justify-content-between text-sm fw-medium mb-2">
                                            <span class="text-secondary">{{ $item->product_name ?? 'Unknown' }}</span>
                                            <span class="text-dark fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            @php 
                                                $percent = ($total_commission ?? 0) > 0 ? ($item->total / $total_commission) * 100 : 0; 
                                            @endphp
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-secondary">
                                <i class="bi bi-pie-chart fs-1 mb-3 d-block text-secondary opacity-50"></i>
                                <p class="mb-0 fs-7">No breakdown data available yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0 text-dark">Quick Actions</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ route('affiliator.withdrawals.create') }}" class="text-decoration-none p-3 rounded-4 border border-light bg-light hover-lift d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-bank fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-dark mb-1">Request Withdrawal</h6>
                                    <p class="text-secondary fs-7 mb-0">Transfer available balance to your bank</p>
                                </div>
                                <i class="bi bi-chevron-right text-secondary"></i>
                            </a>
                            
                            <a href="{{ route('affiliator.withdrawals.index') }}" class="text-decoration-none p-3 rounded-4 border border-light bg-light hover-lift d-flex align-items-center">
                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-dark mb-1">Withdrawal History</h6>
                                    <p class="text-secondary fs-7 mb-0">View your past payouts</p>
                                </div>
                                <i class="bi bi-chevron-right text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection