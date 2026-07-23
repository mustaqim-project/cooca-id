@extends('affiliator.layouts.app')

@section('title', 'Commission Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.commissions.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Commission Details</h2>
                    <p class="text-secondary mb-0">Reference: {{ $commission->transaction->invoice_number ?? 'N/A' }}</p>
                </div>
            </div>
            <div>
                @php
                    $statusClass = match($commission->status ?? 'pending') {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-4 py-2 fs-6">
                    {{ ucfirst($commission->status ?? 'pending') }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0 text-dark">Transaction Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Customer Details -->
                            <div class="col-12 col-md-6">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Customer</p>
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border border-light">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                        style="width: 48px; height: 48px; font-size: 1.2rem;">
                                        {{ strtoupper(substr($commission->transaction->customer->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-5">{{ $commission->transaction->customer->name ?? 'Unknown Customer' }}</div>
                                        <div class="text-secondary fs-7">{{ $commission->transaction->customer->email ?? 'No email provided' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Commission Amount -->
                            <div class="col-12 col-md-6">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Commission Amount</p>
                                <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle h-100 d-flex flex-column justify-content-center">
                                    <h3 class="fw-bold text-success mb-0">+ Rp {{ number_format($commission->amount ?? $commission->commission_amount ?? 0, 0, ',', '.') }}</h3>
                                    <p class="text-success text-opacity-75 fs-7 mb-0 mt-1">Level {{ $commission->level ?? 1 }} Commission</p>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <hr class="border-light my-2">
                            </div>
                            
                            <!-- Dates & Details -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Created Date</p>
                                <p class="fw-medium text-dark mb-0">{{ $commission->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            
                            @if(($commission->status ?? 'pending') === 'pending')
                            <div class="col-12 col-sm-6 col-md-4">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Available to Withdraw</p>
                                <p class="fw-medium text-warning mb-0"><i class="bi bi-clock me-1"></i> {{ $commission->created_at->addDays(14)->format('d M Y') }}</p>
                            </div>
                            @endif
                            
                            @if($commission->cleared_at)
                            <div class="col-12 col-sm-6 col-md-4">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Cleared Date</p>
                                <p class="fw-medium text-success mb-0"><i class="bi bi-check-circle me-1"></i> {{ $commission->cleared_at->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0 text-dark">Original Purchase</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary fs-7 text-uppercase fw-semibold">Product</span>
                            <span class="fw-bold text-dark">{{ $commission->transaction->product->name ?? 'Unknown Product' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary fs-7 text-uppercase fw-semibold">Amount Paid</span>
                            <span class="fw-medium text-dark">Rp {{ number_format($commission->transaction->amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary fs-7 text-uppercase fw-semibold">Date</span>
                            <span class="fw-medium text-dark">{{ optional($commission->transaction->created_at)->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="mt-5 p-3 bg-light rounded-3 border border-light text-center">
                            <i class="bi bi-info-circle text-primary mb-2 fs-4"></i>
                            <p class="fs-7 text-secondary mb-0">Commissions are usually held for a 14-day clearance period before they become available for withdrawal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
