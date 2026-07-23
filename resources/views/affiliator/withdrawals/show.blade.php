@extends('affiliator.layouts.app')

@section('title', 'Withdrawal Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('affiliator.withdrawals.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Withdrawal Details</h2>
                    <p class="text-secondary mb-0">Reference: #{{ $withdrawal->id }}</p>
                </div>
            </div>
            <div>
                @php
                    $statusClass = match($withdrawal->status) {
                        'completed', 'paid', 'approved' => 'success',
                        'pending' => 'warning',
                        'rejected', 'failed', 'cancelled' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-4 py-2 fs-6">
                    {{ ucfirst($withdrawal->status) }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0 text-dark">Request Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Amount -->
                            <div class="col-12 col-md-6">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Amount Requested</p>
                                <div class="p-3 rounded-3 bg-light border border-light h-100 d-flex flex-column justify-content-center">
                                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                            
                            <!-- Destination Account -->
                            <div class="col-12 col-md-6">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Destination Account</p>
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border border-light h-100">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 48px; height: 48px;">
                                        <i class="bi bi-bank fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ strtoupper($withdrawal->withdrawal_method ?? 'BANK') }} - {{ $withdrawal->account_number }}</div>
                                        <div class="text-secondary fs-7">A/N: {{ $withdrawal->account_name }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <hr class="border-light my-2">
                            </div>
                            
                            <!-- Dates & Details -->
                            <div class="col-12 col-sm-6">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Date Requested</p>
                                <p class="fw-medium text-dark mb-0"><i class="bi bi-calendar3 me-1 text-secondary"></i> {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                            
                            @if($withdrawal->processed_at)
                            <div class="col-12 col-sm-6">
                                <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Date Processed</p>
                                <p class="fw-medium text-success mb-0"><i class="bi bi-check-circle me-1"></i> {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                            
                            @if($withdrawal->notes)
                            <div class="col-12 mt-4">
                                <div class="p-3 bg-light rounded-3 border border-light">
                                    <p class="text-secondary fs-7 mb-1 text-uppercase fw-semibold">Admin Notes</p>
                                    <p class="mb-0 text-dark">{{ $withdrawal->notes }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($withdrawal->status == 'rejected')
                            <div class="col-12 mt-4">
                                <div class="p-3 bg-danger-subtle rounded-3 border border-danger-subtle">
                                    <p class="text-danger fs-7 mb-1 text-uppercase fw-semibold">Rejection Reason</p>
                                    <p class="mb-0 text-dark fw-medium">{{ $withdrawal->reject_reason ?? 'No specific reason provided.' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0 text-dark">Status Updates</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="position-relative">
                            <!-- Timeline Line -->
                            <div class="position-absolute h-100 border-start border-2 border-primary" style="left: 12px; top: 12px; z-index: 0; opacity: 0.2;"></div>
                            
                            <!-- Timeline Items -->
                            <div class="d-flex gap-3 mb-4 position-relative z-1">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 26px; height: 26px; margin-top: 2px;">
                                    <i class="bi bi-check" style="font-size: 1rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">Request Submitted</h6>
                                    <p class="text-secondary fs-7 mb-0">{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            
                            @if(in_array($withdrawal->status, ['completed', 'paid', 'approved']))
                            <div class="d-flex gap-3 position-relative z-1">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 26px; height: 26px; margin-top: 2px;">
                                    <i class="bi bi-check-all" style="font-size: 1rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-success mb-1 fs-7">Payout Completed</h6>
                                    <p class="text-secondary fs-7 mb-0">Funds have been sent to your account.</p>
                                </div>
                            </div>
                            @elseif($withdrawal->status == 'rejected')
                            <div class="d-flex gap-3 position-relative z-1">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 26px; height: 26px; margin-top: 2px;">
                                    <i class="bi bi-x" style="font-size: 1rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-danger mb-1 fs-7">Request Rejected</h6>
                                    <p class="text-secondary fs-7 mb-0">Please check the rejection reason.</p>
                                </div>
                            </div>
                            @else
                            <div class="d-flex gap-3 position-relative z-1" style="opacity: 0.5;">
                                <div class="bg-light border border-2 border-secondary text-secondary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 26px; height: 26px; margin-top: 2px;">
                                    <i class="bi bi-hourglass" style="font-size: 0.8rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-secondary mb-1 fs-7">Processing Payout</h6>
                                    <p class="text-secondary fs-7 mb-0">Pending admin approval.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
