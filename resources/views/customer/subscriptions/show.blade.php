@extends('customer.layouts.app')

@section('title', 'Subscription Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.subscriptions.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Subscription Details</h2>
                    <p class="text-secondary mb-0">View and manage your subscription.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <div class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i> Subscription Information</h5>
                    
                    <div>
                        @if($subscription->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Active
                            </span>
                        @elseif($subscription->is_cancelled)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                <i class="bi bi-x-circle me-1"></i> Cancelled
                            </span>
                        @elseif($subscription->expires_at && $subscription->expires_at->isPast())
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                <i class="bi bi-exclamation-triangle me-1"></i> Expired
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
                                <i class="bi bi-hourglass-split me-1"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Product</div>
                            <div class="fw-semibold text-dark">{{ $subscription->plan->product->name ?? 'N/A' }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Plan</div>
                            <div class="text-dark">{{ $subscription->plan->name ?? 'N/A' }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Subscription ID</div>
                            <div class="text-dark font-monospace">{{ $subscription->id }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Started At</div>
                            <div class="text-dark">{{ $subscription->started_at ? $subscription->started_at->format('F d, Y - H:i') : '-' }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Expires At</div>
                            <div class="text-dark">
                                {{ $subscription->expires_at ? $subscription->expires_at->format('F d, Y - H:i') : 'Lifetime' }}
                                @if($subscription->expires_at && $subscription->is_active && $subscription->expires_at->isFuture())
                                    <span class="text-secondary fs-7 ms-2">
                                        ({{ $subscription->expires_at->diffForHumans() }})
                                    </span>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="card-footer bg-transparent border-top border-light p-4 d-flex justify-content-end gap-3">
                    @if($subscription->is_active)
                        <button type="button" onclick="renewSubscription('{{ $subscription->id }}')" class="btn btn-primary rounded-pill px-4 hover-lift">
                            <i class="bi bi-arrow-repeat me-1"></i> Renew
                        </button>
                        <button type="button" onclick="cancelSubscription('{{ $subscription->id }}')" class="btn btn-outline-danger rounded-pill px-4 hover-lift">
                            <i class="bi bi-x me-1"></i> Cancel Subscription
                        </button>
                    @else
                        <a href="{{ route('customer.products.show', $subscription->plan->product->slug ?? '') }}" class="btn btn-primary rounded-pill px-4 hover-lift">
                            <i class="bi bi-cart-plus me-1"></i> Subscribe Again
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
