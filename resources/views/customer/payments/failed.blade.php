@extends('customer.layouts.app')

@section('title', 'Payment Failed')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden text-center p-5">
                <div class="d-flex justify-content-center mb-4">
                    <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-x-lg" style="font-size: 3rem;"></i>
                    </div>
                </div>
                
                <h2 class="fw-bolder mb-3">Payment Failed</h2>
                <p class="text-secondary mb-5 fs-6">
                    Unfortunately, your payment could not be processed. This might be due to insufficient funds, an expired link, or network issues.
                </p>
                
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('customer.payments.index') }}" class="btn btn-primary rounded-pill px-4 py-2 hover-lift fw-medium">
                        View My Payments
                    </a>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-light border rounded-pill px-4 py-2 hover-lift text-secondary fw-medium">
                        Try Again
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection