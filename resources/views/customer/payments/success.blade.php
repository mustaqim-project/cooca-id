@extends('customer.layouts.app')

@section('title', 'Payment Success')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden text-center p-5">
                <div class="d-flex justify-content-center mb-4">
                    <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                    </div>
                </div>
                
                <h2 class="fw-bolder mb-3">Payment Successful!</h2>
                <p class="text-secondary mb-5 fs-6">
                    Thank you for your purchase. Your payment has been processed successfully and your subscription is now active.
                </p>
                
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('customer.subscriptions.index') }}" class="btn btn-primary rounded-pill px-4 py-2 hover-lift fw-medium">
                        View Subscriptions
                    </a>
                    <a href="{{ route('customer.invoices.index') }}" class="btn btn-light border rounded-pill px-4 py-2 hover-lift text-secondary fw-medium">
                        View Invoices
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection