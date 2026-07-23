@extends('customer.layouts.app')

@section('title', 'Subscribe to Plan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="javascript:history.back()" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Subscribe to Plan</h2>
                    <p class="text-secondary mb-0">Complete your subscription purchase.</p>
                </div>
            </div>

            <form action="{{ route('customer.subscriptions.store') }}" method="POST" class="form-confirm-submit">
                @csrf
                <input type="hidden" name="subscription_plan_id" value="{{ request('plan_id') }}">

                <div class="row g-4">
                    <!-- Main Form -->
                    <div class="col-12 col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 glass h-100">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="mb-0 fw-semibold"><i class="bi bi-credit-card me-2"></i> Payment Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-info border-0 bg-info-subtle text-info-emphasis rounded-4 d-flex align-items-center mb-4" role="alert">
                                    <i class="bi bi-info-circle fs-4 me-3"></i>
                                    <div>
                                        You are about to subscribe. Please ensure the plan selected matches your needs.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Select Payment Method</label>
                                    <select name="payment_method" class="form-select rounded-3 bg-transparent py-2">
                                        <option value="bank_transfer">Bank Transfer (Virtual Account)</option>
                                        <option value="ewallet">E-Wallet (OVO, GoPay, Dana)</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>

                                @if(request('license_id'))
                                    <div class="mb-0">
                                        <input type="hidden" name="license_id" value="{{ request('license_id') }}">
                                        <p class="text-secondary fs-7 mb-0">
                                            <i class="bi bi-link-45deg me-1"></i> This subscription will be attached to your existing license.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar Actions -->
                    <div class="col-12 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 glass sticky-top" style="top: 2rem;">
                            <div class="card-body p-4">
                                <h5 class="fw-semibold mb-2">Checkout</h5>
                                <p class="text-secondary fs-7 mb-4">Review your selection and proceed to payment.</p>
                                
                                <div class="d-flex flex-column gap-3">
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 w-100 hover-lift fw-medium">
                                        <i class="bi bi-shield-check me-2"></i> Confirm & Pay
                                    </button>
                                    <a href="javascript:history.back()" class="btn btn-light border rounded-pill py-2 w-100 hover-lift text-secondary fw-medium">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
